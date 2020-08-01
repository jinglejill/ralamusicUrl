<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');
    

    $storeName = json_decode($json_str)->storeName;
    $sku = json_decode($json_str)->sku;
    $insert = json_decode($json_str)->insert;
    $lazadaProduct = json_decode($json_str)->lazadaProduct;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    
    
//    $storeName = json_decode($json_str,true)["storeName"];
//    $sku = json_decode($json_str,true)["sku"];
//    $insert = json_decode($json_str,true)["insert"];
//    $lazadaProduct = json_decode($json_str,true)["lazadaProduct"];
//    $modifiedUser = json_decode($json_str,true)["modifiedUser"];
 
    
    
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
//        $fromApp = true;
        $sql = "select * from lazadaProductTemp where SellerSku = '$sku'";
        $lazadaProductList = executeQueryArray($sql);
        writeToLog("lazada product list: ".json_encode($lazadaProductList));
        if(sizeof($lazadaProductList) == 0)
        {
            $lazadaProductApi = getLazadaProduct($sku);
            if($lazadaProductApi)
            {
                $lazadaProduct = (object)array();
                $lazadaProduct->PrimaryCategory = mysqli_real_escape_string($con,$lazadaProductApi->primary_category);
                $lazadaProduct->name = mysqli_real_escape_string($con,$lazadaProductApi->attributes->name);
                $lazadaProduct->name_en = mysqli_real_escape_string($con,$lazadaProductApi->attributes->name_en);
                $lazadaProduct->short_description = mysqli_real_escape_string($con,$lazadaProductApi->attributes->short_description);
                $lazadaProduct->short_description_en = mysqli_real_escape_string($con,$lazadaProductApi->attributes->description_en);
                $lazadaProduct->video = mysqli_real_escape_string($con,$lazadaProductApi->attributes->video);
                $lazadaProduct->brand = mysqli_real_escape_string($con,$lazadaProductApi->attributes->brand);
                $lazadaProduct->SellerSku = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->SellerSku);
                $lazadaProduct->quantity = $lazadaProductApi->skus[0]->quantity;
                $lazadaProduct->price = $lazadaProductApi->skus[0]->price;
                $lazadaProduct->special_price = $lazadaProductApi->skus[0]->special_price;
                $lazadaProduct->package_weight = $lazadaProductApi->skus[0]->package_weight;
                $lazadaProduct->package_length = $lazadaProductApi->skus[0]->package_length;
                $lazadaProduct->package_width = $lazadaProductApi->skus[0]->package_width;
                $lazadaProduct->package_height = $lazadaProductApi->skus[0]->package_height;
                $lazadaProduct->MainImage = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[0]);
                $lazadaProduct->Image2 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[1]);
                $lazadaProduct->Image3 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[2]);
                $lazadaProduct->Image4 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[3]);
                $lazadaProduct->Image5 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[4]);
                $lazadaProduct->Image6 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[5]);
                $lazadaProduct->Image7 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[6]);
                $lazadaProduct->Image8 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[7]);
            }
        }
        else
        {
            $lazadaProduct = $lazadaProductList[0];
        }
    }    
    writeToLog("source lazada:". json_encode($lazadaProduct));
    
    
//    if(sizeof($lazadaProductList) == 0)
    if(!$lazadaProduct)
    {
        if($insert)
        {
            $message = "เพิ่มสินค้าใน Shopee ไม่สำเร็จ";
        }
        else
        {
            $message = "แก้ไขสินค้าใน Shopee ไม่สำเร็จ";
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
    $productBrand = $product[0]->Brand;
    
    $sql = "select * from categoryMapping where lazadaCategoryID = '$primaryCategory'";
    $selectedRow = getSelectedRow($sql);
    $attributesProduct = array();//id, value(options)
    if(sizeof($selectedRow) > 0)
    {
        for($k=0; $k<sizeof($selectedRow); $k++)
        {
            $shopeeCategoryID = $selectedRow[$k]["ShopeeCategoryID"];
            $attributes = getShopeeAttributes(intval($shopeeCategoryID));

            
            for($i=0; $i<sizeof($attributes); $i++)//rala ส่วนมากมี 1 attribute_id
            {
                $attribute = $attributes[$i];
                
                $foundAttribute = false;
                for($j=0; $j<sizeof($attribute->options); $j++)
                {
                    $option = $attribute->options[$j];
                    if(stripos($option, $productBrand) !== false)
                    {
                        $foundAttribute = true;
                        $attributeValue = $option;
                        break;
                    }
                }
                
                if($foundAttribute)
                {
                    $attribute1 = array("attributes_id"=>$attribute->attribute_id,"value"=>$attributeValue);//search brand มาใส่ หากไม่เจอ ให้เลือก no brand
                    $attributesProduct[] = $attribute1;
                }
            }
            
            if($foundAttribute)
            {
                break;
            }
        }
        if(!$foundAttribute)
        {
            $attribute1 = array("attributes_id"=>$attribute->attribute_id,"value"=>$attribute->options[0]);//search brand มาใส่ หากไม่เจอ ให้เลือก no brand
            $attributesProduct[] = $attribute1;
        }
        
    }
    else
    {
        $shopeeCategoryID = $defaultCategoryID;
        
        
        $attribute1 = array("attributes_id"=>$defaultAttributeID,"value"=>$defaultAttributeValue);//search brand มาใส่ หากไม่เจอ ให้เลือก no brand
        $attributesProduct[] = $attribute1;
    }
    
    
    
    $categoryId = intval($shopeeCategoryID);
    $name = $product[0]->Name;//name
    $name = iconv_substr($name, 0, 120,'UTF-8'); //substr($name,0,120);
    $price = floatval($product[0]->Price);//skus[0]->price
    $stock = intval($product[0]->Quantity);//skus[0]->quantity
    $itemSku = $product[0]->Sku;//skus[0]->SellerSku
    $status = "NORMAL";//NORMAL, UNLIST
    $daysToShip = 2;
    $isPreOrder = false;
    $condition = "NEW";
    $sizeChart = "";
    
    
    //*****lazada data
//    if($fromApp)
//    {
//        $description = $lazadaProduct->attributes->short_description?$lazadaProduct->attributes->short_description:$lazadaProduct->attributes->name;//short_description parse html tag out (<ul>,<li>,\r,\t)
//        $description = str_replace('<li>','- ',$description);
//        $description = strip_tags($description);
//        $salePrice = floatval($lazadaProduct->skus[0]->special_price);
//        $weight = floatval($lazadaProduct->skus[0]->package_weight);//package_weight parseInt
//        $packageLength = intval($lazadaProduct->skus[0]->package_length);//package_length parseInt
//        $packageWidth = intval($lazadaProduct->skus[0]->package_width);//package_width parseInt
//        $packageHeight = intval($lazadaProduct->skus[0]->package_height);//package_height parseInt
//    }
//    else
    {
//        writeToLog("short_description:".$lazadaProduct["attributes"]["short_description"]);
        
//        $description = $lazadaProduct["short_description"]?$lazadaProduct["short_description"]:$lazadaProduct["name"];
//        $description = str_replace('<li>','- ',$description);
//        $description = strip_tags($description);
//        $salePrice = floatval($lazadaProduct["special_price"]);
//        $weight = floatval($lazadaProduct["package_weight"])>20?20:floatval($lazadaProduct["package_weight"]);
//        $packageLength = floatval($lazadaProduct["package_length"]);
//        $packageWidth = floatval($lazadaProduct["package_width"]);
//        $packageHeight = floatval($lazadaProduct["package_height"]);
        
        $description = $lazadaProduct->short_description?$lazadaProduct->short_description:$lazadaProduct->name;
        $description = strip_tags($description);
        
        
        //format description
        $string = $description;
        $pattern = '/(\t)/';
        $replacement = '- ';
        $description = preg_replace($pattern, $replacement, $string);

        
        $string = $description;
        $pattern = '/^(\r\n)/';
        $replacement = '';
        $description = preg_replace($pattern, $replacement, $string);
        
        
        $string = $description;
        $pattern = '/(\r\n)$/';
        $replacement = '';
        $description = preg_replace($pattern, $replacement, $string);
        
        
        
        
        $salePrice = floatval($lazadaProduct->special_price);
        $weight = floatval($lazadaProduct->package_weight)>20?20:floatval($lazadaProduct->package_weight);
        $packageLength = floatval($lazadaProduct->package_length);
        $packageWidth = floatval($lazadaProduct->package_width);
        $packageHeight = floatval($lazadaProduct->package_height);
        
//        $description = $lazadaProduct["attributes"]["short_description"]?$lazadaProduct["attributes"]["short_description"]:$lazadaProduct["attributes"]["name"];//short_description parse html tag out (<ul>,<li>,\r,\t)
//        $description = str_replace('<li>','- ',$description);
//        $description = strip_tags($description);
//        $salePrice = floatval($lazadaProduct["skus"][0]["special_price"]);
//        $weight = floatval($lazadaProduct["skus"][0]["package_weight"]);//package_weight parseInt
//        $packageLength = intval($lazadaProduct["skus"][0]["package_length"]);//package_length parseInt
//        $packageWidth = intval($lazadaProduct["skus"][0]["package_width"]);//package_width parseInt
//        $packageHeight = intval($lazadaProduct["skus"][0]["package_height"]);//package_height parseInt
    }
    //*****lazada data
    
    
    
    if($insert)
    {
        $images = array();//url skus[0]->Images
        
        if($product[0]->MainImage != "")
        {
            $image1 = array("url"=>$product[0]->MainImage);
            $images[] = $image1;
        }
        if($product[0]->Image2 != "")
        {
            $image2 = array("url"=>$product[0]->Image2);
            $images[] = $image2;
        }
        if($product[0]->Image3 != "")
        {
            $image3 = array("url"=>$product[0]->Image3);
            $images[] = $image3;
        }
        if($product[0]->Image4 != "")
        {
            $image4 = array("url"=>$product[0]->Image4);
            $images[] = $image4;
        }
        if($product[0]->Image5 != "")
        {
            $image5 = array("url"=>$product[0]->Image5);
            $images[] = $image5;
        }
        if($product[0]->Image6 != "")
        {
            $image6 = array("url"=>$product[0]->Image6);
            $images[] = $image6;
        }
        if($product[0]->Image7 != "")
        {
            $image7 = array("url"=>$product[0]->Image7);
            $images[] = $image7;
        }
        if($product[0]->Image8 != "")
        {
            $image8 = array("url"=>$product[0]->Image8);
            $images[] = $image8;
        }
    }
//    else
//    {
//        $images = array();//url skus[0]->Images
//        if($product[0]->MainImage != "")
//        {
//            $images[] = $product[0]->MainImage;
//        }
//        if($product[0]->Image2 != "")
//        {
//            $images[] = $product[0]->Image2;
//        }
//        if($product[0]->Image3 != "")
//        {
//            $images[] = $product[0]->Image3;
//        }
//        if($product[0]->Image4 != "")
//        {
//            $images[] = $product[0]->Image4;
//        }
//        if($product[0]->Image5 != "")
//        {
//            $images[] = $product[0]->Image5;
//        }
//        if($product[0]->Image6 != "")
//        {
//            $images[] = $product[0]->Image6;
//        }
//        if($product[0]->Image7 != "")
//        {
//            $images[] = $product[0]->Image7;
//        }
//        if($product[0]->Image8 != "")
//        {
//            $images[] = $product[0]->Image8;
//        }
//    }
    
    
    $logistics = array();//logisticId, enabled
    $logistic = array("logistic_id"=>70021,"enabled"=>true,"estimated_shipping_fee"=>39,"is_free"=>true,"logistic_name"=>"Kerry");
    $logistics[] = $logistic;
//    $variations = array();
    
    
    //param
    $date = new DateTime();
    $timestamp = $date->getTimestamp();
    
    
    $paramBody = array();
    $paramBody["partner_id"] = $partnerID;
    $paramBody["shopid"] = $shopID;
    $paramBody["timestamp"] = $timestamp;
    $paramBody["category_id"] = $categoryId;
    $paramBody["name"] = $name;
    $paramBody["description"] = $description;
    $paramBody["price"] = $salePrice;
    $paramBody["stock"] = $stock;
    $paramBody["item_sku"] = $itemSku;
    $paramBody["weight"] = $weight;
    if(floatval($lazadaProduct->package_length)+floatval($lazadaProduct->package_width)+floatval($lazadaProduct->package_height) <= 180)
    {
        $paramBody["package_length"] = $packageLength;
        $paramBody["package_width"] = $packageWidth;
        $paramBody["package_height"] = $packageHeight;
    }
    $paramBody["status"] = $status;
    $paramBody["days_to_ship"] = $daysToShip;
    $paramBody["is_pre_order"] = $isPreOrder;
    $paramBody["condition"] = $condition;
    $paramBody["size_chart"] = $sizeChart;
    $paramBody["images"] = $images;
    $paramBody["logistics"] = $logistics;
    $paramBody["attributes"] = $attributesProduct;
    
//    echo json_encode($paramBody);
//    exit();
//    $paramBody["variations"] = $variations;
    
    
    
    if($insert)
    {
        $result = insertShopeeProduct($paramBody);
//        $obj = json_decode($result);
        
        
        if($result->item_id)
        {
            $itemID = $result->item_id;
            
            //insert into shopeeProduct
            $sql = "insert into shopeeProduct (itemID,sku,modifiedUser) values('$itemID','$sku','$modifiedUser')";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                $message = "เพิ่ม Shopee sku ในแอปไม่สำเร็จ";
                sendNotiToAdmin($message);
                $ret["message"] = $message;
                mysqli_close($con);

                echo json_encode($ret);
                exit();
            }
        }
        else
        {
            //insert fail
            $message = "เพิ่มสินค้าใน Shopee ไม่สำเร็จ";
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
////        $obj = json_decode($result);
//
//        if($result->item_id)
//        {
//            //update success
//
//            $failData = array();
//            $result = updateShopeeImages($itemID,$images);
//            if(!$result->item_id && $result->msg != "No image updated. ")
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
//            $result = updateShopeeStock($itemID,$stock);
//            if(!$result->item)
//            {
//                $fail = 1;
//                $failData[] = "จำนวน";
//            }
//
//
//            //update fail
//            if(sizeof($failData)>0)
//            {
//                for($i=0; $i<sizeof($failData); $i++)
//                {
//                    if($i==0)
//                    {
//                        $failMessage = $failData[$i];
//                    }
//                    else
//                    {
//                        $failMessage .= ", " . $failData[$i];
//                    }
//                }
//
//                $message = "แก้ไข" . $failMessage . " ใน Shopee ไม่สำเร็จ";
//                sendNotiToAdmin($message);
//
//
//                $ret = array();
//                $ret["success"] = false;
//                $ret["message"] = $message;
//                mysqli_rollback($con);
//                mysqli_close($con);
//
//                echo json_encode($ret);
//                exit();
//            }
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
    

    //        1315 Stationery, Books & Music
    //        21712 Music
    //        24922 Music & Media
    //        24923 other music & media
    //        24932 other musical instruments
    //        24941 Music accessories
    //        23668 Music
    
?>


