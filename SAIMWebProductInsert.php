<?php
    include_once('dbConnect.php');
    set_time_limit(300);
    
    $json_str = file_get_contents('php://input');

    
    
    $storeName = json_decode($json_str)->storeName;
    $sku = json_decode($json_str)->sku;
    $insert = json_decode($json_str)->insert;
    $lazadaProduct = json_decode($json_str)->lazadaProduct;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    
    $sku = mysqli_real_escape_string($con,$sku);
    
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
    
  
    if(!$lazadaProduct)
    {
        $sql = "select * from lazadaProductTemp where SellerSku = '$sku'";
        $lazadaProductList = executeQueryArray($sql);
        writeToLog("lazada product list: ".json_encode($lazadaProductList));
        if(sizeof($lazadaProductList) == 0)
        {
            $lazadaProductApi = getLazadaProduct($sku);
            if($lazadaProductApi)
            {
                $lazadaProduct = (object)array();
                $lazadaProduct->PrimaryCategory = $lazadaProductApi->primary_category;
                $lazadaProduct->name = $lazadaProductApi->attributes->name;
                $lazadaProduct->name_en = $lazadaProductApi->attributes->name_en;
                $lazadaProduct->short_description = $lazadaProductApi->attributes->short_description;
                $lazadaProduct->short_description_en = $lazadaProductApi->attributes->description_en;
                $lazadaProduct->video = $lazadaProductApi->attributes->video;
                $lazadaProduct->brand = $lazadaProductApi->attributes->brand;
                $lazadaProduct->SellerSku = $lazadaProductApi->skus[0]->SellerSku;
                $lazadaProduct->quantity = $lazadaProductApi->skus[0]->quantity;
                $lazadaProduct->price = $lazadaProductApi->skus[0]->price;
                $lazadaProduct->special_price = $lazadaProductApi->skus[0]->special_price;
                $lazadaProduct->package_weight = $lazadaProductApi->skus[0]->package_weight;
                $lazadaProduct->package_length = $lazadaProductApi->skus[0]->package_length;
                $lazadaProduct->package_width = $lazadaProductApi->skus[0]->package_width;
                $lazadaProduct->package_height = $lazadaProductApi->skus[0]->package_height;
                $lazadaProduct->MainImage = $lazadaProductApi->skus[0]->Images[0];
                $lazadaProduct->Image2 = $lazadaProductApi->skus[0]->Images[1];
                $lazadaProduct->Image3 = $lazadaProductApi->skus[0]->Images[2];
                $lazadaProduct->Image4 = $lazadaProductApi->skus[0]->Images[3];
                $lazadaProduct->Image5 = $lazadaProductApi->skus[0]->Images[4];
                $lazadaProduct->Image6 = $lazadaProductApi->skus[0]->Images[5];
                $lazadaProduct->Image7 = $lazadaProductApi->skus[0]->Images[6];
                $lazadaProduct->Image8 = $lazadaProductApi->skus[0]->Images[7];
            }
        }
        else
        {
            $lazadaProduct = $lazadaProductList[0];
        }
    }
    writeToLog("source lazada:". json_encode($lazadaProduct, JSON_UNESCAPED_UNICODE));
    
    
    if(!$lazadaProduct)
    {
        if($insert)
        {
            $message = "เพิ่มสินค้าใน Web ไม่สำเร็จ";
        }
        else
        {
            $message = "แก้ไขสินค้าใน Web ไม่สำเร็จ";
        }
        sendNotiToAdmin($message);
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_close($con);

        echo json_encode($ret);
        exit();
    }
    
    $sql = "select * from mainproduct where sku = '$sku'";
    $product = executeQueryArray($sql);
    $primaryCategory = $product[0]->PrimaryCategory;

    
    $webPrimaryCategory = "";
    $webCategoryNameList = array();
    $sql = "select * from categoryMappingWeb where lazadaCategoryID = '$primaryCategory'";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) > 0)
    {
        for($i=0; $i<sizeof($selectedRow); $i++)
        {
            $webCategoryNameList[] = $selectedRow[$i]["WebCategoryName"];
            if($webCategoryNameList[$i]->IsWebPrimaryCategory)
            {
                $primaryCategory = $webCategoryNameList[$i]->WebCategoryName;
            }
        }
    }
    $product[0]->WebCategoryNameList = $webCategoryNameList;
    
    
    if($insert)
    {
        $param = array();
        unset($product[0]->ShortDescription);

        {
            $lazadaProduct->short_description = str_replace("\n","",$lazadaProduct->short_description);
        }
        $param["lazadaProduct"] = $lazadaProduct;
        $param["product"] = $product;
        $result = insertWebProduct($param);

        
        if(!$result)
        {
            //insert fail
            $message = "เพิ่มสินค้าในเว็บไม่สำเร็จ";
            sendNotiToAdmin($message);
            
            $ret = array();
            $ret["success"] = false;
            $ret["message"] = $message;
            mysqli_rollback($con);
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
    }
//    else
//    {
//        //update*********************************************************
//        //updateItem,
//        //updateItemImg,updatePrice,updateStock
//
//        $sql = "select * from shopeeProduct where sku = '$sku'";
//        $shopeeProduct = executeQueryArray($sql);
//        $itemID = $shopeeProduct[0]->ItemID;
//
//        $shopeeItem = getItemShopee($itemID);
//
//
//
//        $status = $shopeeItem->status;//"UNLIST";//NORMAL, UNLIST
//        $daysToShip = $shopeeItem->days_to_ship;
//        $isPreOrder = $shopeeItem->is_pre_order;
//        $condition = $shopeeItem->condition;
//        $sizeChart = $shopeeItem->size_chart;
//        $logistics = $shopeeItem->logistics;
//
//
//        $paramBody["item_id"] = intval($itemID);
//        unset($paramBody["price"]);
//        unset($paramBody["stock"]);
//        unset($paramBody["images"]);
//
//
//        $result = updateShopeeProduct($paramBody);
//        $obj = json_decode($result);
//
//        if($obj->item_id)
//        {
//            //update success
//
//            $failData = array();
//            $result = updateShopeeImages($itemID,$images);
//            if(!$result->item)
//            {
//                $fail = 1;
//                $failData[] = "รูปภาพ";
//            }
//
//            $result = updateShopeePrice($itemID,$price);
//            if(!$result->item)
//            {
//                $fail = 1;
//                $failData[] = "ราคา";
//            }
//
//            $result = updateShopeeStock($itemID,$quantity);
//            if(!$result->item)
//            {
//                $fail = 1;
//                $failData[] = "จำนวน";
//            }
//
//
//            //update fail
//            for($i=0; $i<sizeof($failData); $i++)
//            {
//                if($i==0)
//                {
//                    $failMessage = $failData[$i];
//                }
//                else
//                {
//                    $failMessage .= ", " . $failData[$i];
//                }
//            }
//
//            $message = "แก้ไข" . $failMessage . " ใน Shopee ไม่สำเร็จ";
//            sendNotiToAdmin($message);
//
//
//            $ret = array();
//            $ret["success"] = false;
//            $ret["message"] = $message;
//            mysqli_rollback($con);
//            mysqli_close($con);
//
//            echo json_encode($ret);
//            exit();
//        }
//        else
//        {
//            //update fail
//            $message = "แก้ไขสินค้าใน Shopee ไม่สำเร็จ";
//            sendNotiToAdmin($message);
//
//
//            $ret = array();
//            $ret["success"] = false;
//            $ret["message"] = $message;
//            mysqli_rollback($con);
//            mysqli_close($con);
//
//            echo json_encode($ret);
//            exit();
//        }
//    }
        
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true));
    exit();
    
    
?>


