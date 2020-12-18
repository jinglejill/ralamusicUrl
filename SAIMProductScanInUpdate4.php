<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str)->storeName;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    $sku = json_decode($json_str)->sku;
    $quantity = json_decode($json_str)->quantity;
    $quantityIn = $quantity;
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    function getProductQuantity($sku)
    {
        $product = getProduct($sku);
        return $product["Quantity"];
    }
    
    function getProductImage($sku)
    {
        $product = getProduct($sku);
        return $product["MainImage"];
    }
    
    function getProduct($sku)
    {
        global $con;
        
        $sku = mysqli_real_escape_string($con,$sku);
        $sql = "select MainImage, Quantity from mainProduct where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
//        writeToLog("get product:".json_encode($selectedRow));
        return $selectedRow[0];
    }
    
    $escapeSku = mysqli_real_escape_string($con,$sku);
    $sql = "select Sku, Quantity from mainproduct where sku = '$escapeSku'";
    $selectedRow = getSelectedRow($sql);
    if(!sizeof($selectedRow)>0)
    {
        $ret = array();
        $ret["success"] = false;
        $ret["quantity"] = getProductQuantity($sku);
        $ret["sku"] = $sku;
        $ret["message"] = "Sku: $sku\r\nไม่พบสินค้านี้";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $mainProductQuantity = $selectedRow[0]["Quantity"];
    $quantity += $mainProductQuantity;
    
    
    //update product in Main
    $sql = "update mainProduct set quantity = '$quantity' where sku in (select sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$escapeSku')) or sku = '$escapeSku'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["quantity"] = getProductQuantity($sku);
        $ret["sku"] = $sku;
        $ret["message"] = "Sku: $sku\r\nแก้ไขจำนวนไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    //update marketplace
    $failMarketplace = array();
    
    
    //update every sku that share the same pool of stock
    $sql = "select * from (select Sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$escapeSku') UNION select '$escapeSku' as Sku)a order by Sku";
    $selectedRow = getSelectedRow($sql);
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $shareSku = $selectedRow[$i]["Sku"];
        $escapeShareSku = mysqli_real_escape_string($con,$shareSku);
        $sql = "select * from mapsku where sku = '$escapeShareSku'";
        $mapSku = executeQueryArray($sql);
        $lazadaSku = $mapSku[0]->LazadaSku;
        $shopeeSku = $mapSku[0]->ShopeeSku;
        $jdSku = $mapSku[0]->JdSku;
        $webSku = $mapSku[0]->WebSku;
                
        $escapeLazadaSku = mysqli_real_escape_string($con,$lazadaSku);
        $escapeShopeeSku = mysqli_real_escape_string($con,$shopeeSku);
        $escapeJdSku = mysqli_real_escape_string($con,$jdSku);
        $escapeWebSku = mysqli_real_escape_string($con,$webSku);
                
        
        //update product in lazada
        $ret = updateStockQuantityLazadaInApp($lazadaSku,$quantity);
        if(!$ret)
        {
            //check the reason if it's because of sku_not_found
            $lazadaProductApi = getLazadaProductApi($lazadaSku);
            if($lazadaProductApi->data == null && $lazadaProductApi->code != "InternalError")
//            if(!$lazadaProduct)
            {
                //delete
                $sql = "select * from lazadaProduct where sku = '$escapeLazadaSku'";
                $dataList = executeQueryArray($sql);
                if(sizeof($dataList)>0)
                {
                    $json = json_encode($dataList);
                    $tableName = "LazadaProduct";
                    $ret = keepDeleteRecord($tableName,$json);
                    if($ret)
                    {
                        $sql = "delete from lazadaProduct where sku = '$escapeLazadaSku'";
                        $ret = doQueryTask($con,$sql,$modifiedUser);
                    }
                }
                
                if(!in_array("Lazada", $failMarketplace) && $shareSku == $sku)
                {
                    $failMarketplace[] = array("Marketplace"=>"Lazada","Message"=>"SKU_NOT_FOUND");
                }
                else if(!in_array("Lazada", $failMarketplace))
                {
                    $failMarketplace[] = array("Marketplace"=>"Lazada","Message"=>"");
                }
            }
            else
            {
                if(!in_array("Lazada", $failMarketplace))
                {
                    $failMarketplace[] = array("Marketplace"=>"Lazada","Message"=>"");
                }
            }
        }
        
        
        //update product in shopee
        $ret = updateStockQuantityShopeeInApp($shopeeSku,$quantity);
        if(!$ret)
        {
            //check the reason if it's because of sku_not_found
            if(!$variations)
            {
                $variations = getAllSkuShopee();
            }
            $shopeeProducts = getShopeeProducts($variations,$shopeeSku);
            $shopeeProducts = getNormalOrUnListProducts($shopeeProducts);
            if(sizeof($shopeeProducts) == 0)
            {
                //delete
                $sql = "select * from shopeeProduct where sku = '$escapeShopeeSku'";
                $dataList = executeQueryArray($sql);
                if(sizeof($dataList)>0)
                {
                    $json = json_encode($dataList);
                    $tableName = "ShopeeProduct";
                    $ret = keepDeleteRecord($tableName,$json);
                    if($ret)
                    {
                        $sql = "delete from shopeeProduct where sku = '$escapeShopeeSku'";
                        $ret = doQueryTask($con,$sql,$modifiedUser);
                    }
                }
                
                if(!in_array("Shopee", $failMarketplace) && $shareSku == $sku)
                {
                    $failMarketplace[] = array("Marketplace"=>"Shopee","Message"=>"SKU_NOT_FOUND");
                }
                else if(!in_array("Shopee", $failMarketplace))
                {
                    $failMarketplace[] = array("Marketplace"=>"Shopee","Message"=>"");
                }
            }
            else
            {
                //กรณีมี แต่ไม่ตรงใน db ของ app
                
                $sql = "select ItemID, ShopeeProductID from shopeeProduct where sku = '$escapeShopeeSku'";
                $shopeeProductsInApp = executeQueryArray($sql);
                for($j=0; $j<sizeof($shopeeProductsInApp); $j++)
                {
                    $shopeeProductInApp = $shopeeProductsInApp[$j];
                    $shopeeItem = getItemShopee($shopeeProductInApp->ItemID);
                    
                    if(!$shopeeItem)
                    {
                        $shopeeProductID = $shopeeProductInApp->ShopeeProductID;
                        
                        
                        //delete
                        $sql = "select * from shopeeProduct where shopeeProductID = '$shopeeProductID'";
                        $dataList = executeQueryArray($sql);
                        if(sizeof($dataList)>0)
                        {
                            $json = json_encode($dataList);
                            $tableName = "ShopeeProduct";
                            $ret = keepDeleteRecord($tableName,$json);
                            if($ret)
                            {
                                $sql = "delete from shopeeProduct where shopeeProductID = '$shopeeProductID'";
                                $ret = doQueryTask($con,$sql,$modifiedUser);
                            }
                        }
                    }
                }
                
                for($j=0; $j<sizeof($shopeeProducts); $j++)
                {
                    //insert variation
                    $shopeeProduct = $shopeeProducts[$j];
                    $itemSku = $shopeeProduct->item_sku;
                    $escapeItemSku = mysqli_real_escape_string($con,$itemSku);
                    $itemID = $shopeeProduct->item_id;
                    $variationID = $shopeeProduct->variation_id;
                    $sql = "insert into shopeeProduct (`Sku`, `ItemID`, `VariationID`, `ModifiedUser`) values( '$escapeItemSku','$itemID','$variaionID','$modifiedUser')";
                    $ret = doQueryTask($con,$sql,$modifiedUser);
                   
                    
                    //update quantity
                    $ret = updateStockQuantityShopeeInApp($shopeeSku,$quantity);
                    if(!$ret)
                    {
                        if(!in_array("Shopee", $failMarketplace))
                        {
                            $failMarketplace[] = array("Marketplace"=>"Shopee","Message"=>"");
                        }
                    }
                }
                
            }
        }
        
        //update product in jd
        $ret = updateStockQuantityJdInApp($jdSku,$quantity);
        if(!$ret)
        {
            //check the reason if it's because of sku_not_found
            $jdProducts = getNormalOrUnListProductsJD($jdSku);
            if(sizeof($jdProducts) == 0)
            {
                //delete
                $sql = "select * from jdProduct where sku = '$escapeJdSku'";
                $dataList = executeQueryArray($sql);
                if(sizeof($dataList)>0)
                {
                    $json = json_encode($dataList);
                    $tableName = "JdProduct";
                    $ret = keepDeleteRecord($tableName,$json);
                    if($ret)
                    {
                        $sql = "delete from jdProduct where sku = '$escapeJdSku'";
                        $ret = doQueryTask($con,$sql,$modifiedUser);
                    }
                }
                
                if(!in_array("JD", $failMarketplace) && $shareSku == $sku)
                {
                    $failMarketplace[] = array("Marketplace"=>"JD","Message"=>"SKU_NOT_FOUND");
                }
                else if(!in_array("JD", $failMarketplace))
                {
                    $failMarketplace[] = array("Marketplace"=>"JD","Message"=>"");
                }
            }
            else
            {
                //กรณีมี แต่ไม่ตรงใน db ของ app
                
                $sql = "select JdProductID, ProductId, SkuId from jdProduct where sku = '$escapeJdSku'";
                $jdProductsInApp = executeQueryArray($sql);
                for($j=0; $j<sizeof($jdProductsInApp); $j++)
                {
                    $jdProductInApp = $jdProductsInApp[$j];
                    $productId = $jdProductInApp->ProductId;
                    $skuId = $jdProductInApp->SkuId;
                    
                    $hasJdProduct = hasItemJd($skuId,$skuId);
                    if(!$hasJdProduct)
                    {
                        $jdProductID = $jdProductInApp->JdProductID;
                        
                        
                        //delete
                        $sql = "select * from jdProduct where jdProductID = '$jdProductID'";
                        $dataList = executeQueryArray($sql);
                        if(sizeof($dataList)>0)
                        {
                            $json = json_encode($dataList);
                            $tableName = "JdProduct";
                            $ret = keepDeleteRecord($tableName,$json);
                            if($ret)
                            {
                                $sql = "delete from jdProduct where jdProductID = '$jdProductID'";
                                $ret = doQueryTask($con,$sql,$modifiedUser);
                            }
                        }
                    }
                }
                
                for($j=0; $j<sizeof($jdProducts); $j++)
                {
                    //insert variation
                    $jdProduct = $jdProducts[$j];
                    $itemSku = $shareSku;
                    $escapeItemSku = mysqli_real_escape_string($con,$itemSku);
                    $productId = $jdProduct->productId;
                    $skuId = $jdProduct->skuId;
                    $sql = "insert into jdProduct (`Sku`, `ProductId`, `SkuId`, `ModifiedUser`) values('$escapeItemSku','$productId','$skuId','$modifiedUser')";
                    $ret = doQueryTask($con,$sql,$modifiedUser);
                   
                    
                    //update quantity
                    $ret = updateStockQuantityJdInApp($jdSku,$quantity);
                    if(!$ret)
                    {
                        if(!in_array("JD", $failMarketplace))
                        {
                            $failMarketplace[] = array("Marketplace"=>"JD","Message"=>"");
                        }
                    }
                }
                
            }
        }
    }
    
    
    writeToLog("failMarketplace: ".json_encode($failMarketplace));
    if(sizeof($failMarketplace)>0)
    {
        $success = false;
        $reason = $failMarketplace[0]["Message"] == ""?"":" (ไม่พบ Sku)";
        $message = "Sku: $sku\r\nไม่สามารถแก้ไขจำนวนที่ " . $failMarketplace[0]["Marketplace"] . $reason;
        for($i=1; $i<sizeof($failMarketplace); $i++)
        {
            $reason = $failMarketplace[$i]["Message"] == ""?"":" (ไม่พบ Sku)";
            $message .= ", " . $failMarketplace[$i]["Marketplace"] . $reason;
        }
    }
    else
    {
        $success = true;
        $message = "";
    }
    mysqli_commit($con);
    
    
    
    $asIsQuantity = getProductQuantity($sku);
    $mainImage = getProductImage($sku);
    
    
    $sql = "select a.Sku, mainproduct.MainImage from (select Sku from stockSharing where StockSharingGroupID in (SELECT StockSharingGroupID FROM `stocksharing` WHERE sku = '$escapeSku') UNION select '$escapeSku' as Sku)a left join mainproduct on a.sku = mainproduct.Sku order by Sku";
    $stockSharingList = executeQueryArray($sql);
    
    
    //update data in case of sku_not_found
    $lazadaExist = 0;
    $shopeeExist = 0;
    $jdExist = 0;
    if($message != "")
    {
        for($i=0; $i<sizeof($stockSharingList); $i++)
        {
            $stockSharing = $stockSharingList[$i];
            $eachSku = $stockSharing->Sku;
            $eachSku = mysqli_real_escape_string($con,$eachSku);
            $sql = "select * from mapsku where sku = '$eachSku'";
            $mapSku = executeQueryArray($sql);
            $lazadaSku = $mapSku[0]->LazadaSku;
            $shopeeSku = $mapSku[0]->ShopeeSku;
            $jdSku = $mapSku[0]->JdSku;
            $webSku = $mapSku[0]->WebSku;
            
            //hasLazadaProduct
            $hasProduct = hasLazadaProductInApp($lazadaSku);
            $stockSharing->LazadaExist = $hasProduct?1:0;
            
            
            //hasShopeeProduct
            $hasProduct = hasShopeeProductInApp($shopeeSku);
            $stockSharing->ShopeeExist = $hasProduct?1:0;


            //hasJdProduct
            $hasProduct = hasJdProductInApp($jdSku);
            $stockSharing->JdExist = $hasProduct?1:0;
            
            
            //hasWebProduct
            $hasProduct = hasWebProduct($webSku);
            $product->WebExist = $hasProduct?1:0;
        }
    }
    
    
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>$success, "message"=>$message, "sku"=>$sku, "quantity"=>$asIsQuantity, "quantityIn"=>$quantityIn, "mainImage"=>$mainImage,  "stockSharingList"=>$stockSharingList));
    exit();
    
    
    function keepDeleteRecord($tableName,$json)
    {
        global $con;
        global $modifiedUser;
        
        $json = mysqli_real_escape_string($con,$json);
        $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        return $ret == "";
    }
?>
