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
        $sql = "select * from mainProduct where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
//        writeToLog("get product:".json_encode($selectedRow));
        return $selectedRow[0];
    }
    
    
    $sql = "select * from mainproduct where sku = '$sku'";
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
    $sql = "update mainProduct set quantity = '$quantity' where sku in (select sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$sku')) or sku = '$sku'";
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
    $sql = "select * from (select Sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$sku') UNION select '$sku' as Sku)a order by Sku";
    $selectedRow = getSelectedRow($sql);
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $shareSku = $selectedRow[$i]["Sku"];
        
        //update product in lazada
        $ret = updateStockQuantityLazadaInApp($shareSku,$quantity);
        if(!$ret)
        {
            if(!in_array("Lazada", $failMarketplace))
            {
                $failMarketplace[] = "Lazada";
            }
        }
        
        
        //update product in shopee
        $ret = updateStockQuantityShopeeInApp($shareSku,$quantity);
        if(!$ret)
        {
            if(!in_array("Shopee", $failMarketplace))
            {
                $failMarketplace[] = "Shopee";
            }
        }
        
        
        //update product in jd
        $ret = updateStockQuantityJdInApp($shareSku,$quantity);
        if(!$ret)
        {
            if(!in_array("JD", $failMarketplace))
            {
                $failMarketplace[] = "JD";
            }            
        }
    }
    
    
    
    if(sizeof($failMarketplace)>0)
    {
        $success = false;
        $message = "Sku: $sku\r\nไม่สามารถแก้ไขจำนวนที่ $failMarketplace[0]";
        for($i=1; $i<sizeof($failMarketplace); $i++)
        {
            $message .= ", $failMarketplace[$i]";
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
    $sql = "select a.Sku, mainproduct.MainImage from (select Sku from stockSharing where StockSharingGroupID in (SELECT StockSharingGroupID FROM `stocksharing` WHERE sku = '$sku') UNION select '$sku' as Sku)a left join mainproduct on a.sku = mainproduct.Sku order by Sku";
//    $sql = "select stocksharing.Sku,mainproduct.MainImage from stocksharing left join mainproduct on stocksharing.sku = mainproduct.Sku where StockSharingGroupID in (SELECT StockSharingGroupID FROM `stocksharing` WHERE sku = '$sku') order by Sku";
    $stockSharingList = executeQueryArray($sql);
    
    
    
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>$success, "message"=>$message, "sku"=>$sku, "quantity"=>$asIsQuantity, "quantityIn"=>$quantityIn, "mainImage"=>$mainImage, "stockSharingList"=>$stockSharingList));
    exit();
?>
