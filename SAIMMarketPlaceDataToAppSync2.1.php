<?php
    include_once('dbConnect.php');
    
    set_time_limit(1800);
  
    
     
    $skus = $_POST["skus"];
    $storeName = $_POST["storeName"];
    
    
    if(!$storeName)
    {
        $storeName = "RALAMUSIC";
    }
    if(!$modifiedUser)
    {
        $modifiedUser = "bot";
    }
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    printAllPost();
    
    
    
    
    
    writeToLog("skus:".json_encode($skus));
    
    
    
    // Set autocommit to off
//    mysqli_autocommit($con,FALSE);
//    writeToLog("set auto commit to off");

    
    
    if($skus)
    {
        $skuList = array();
        for($i=0; $i<sizeof($skus); $i++)
        {
            $sku = (object)array();
            $sku->Sku = trim($skus[$i]);
            $skuList[] = $sku;
        }
    }
    else
    {
        $sql = "select SellerSku as Sku from lazadaproducttemp where status = 'active' and SellerSku not in (select sku from mainproduct) and sellerSku != 'Yamaha-PA150T'";
        $skuList = executeQueryArray($sql);
    }
    
    
    
    $insertSuccess = 0;
    $updateSuccess = 0;
    $insertFail = array();
    $updateFail = array();
    $insertFailLazada = array();
    $updateFailLazada = array();
    $insertFailShopee = array();
    $insertFailJd = array();
    $variations = getAllSkuShopee();
    writeToLog("sizeof skus:".sizeof($skuList));
    for($i=0; $i<sizeof($skuList); $i++)
    {
        $sku = $skuList[$i]->Sku;
        $sku = mysqli_real_escape_string($con,$sku);
        
        //main from lazada
        writeToLog("get product lazada sku:" . $sku);
        $lazadaProduct = null;
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

        
        if($lazadaProduct)
        {            
            $primaryCategory = $lazadaProduct->PrimaryCategory;
            $name = mysqli_real_escape_string($con,$lazadaProduct->name);
            $brand = mysqli_real_escape_string($con,$lazadaProduct->brand);
            $sellerSku = mysqli_real_escape_string($con,$lazadaProduct->SellerSku);
            $quantity = $lazadaProduct->quantity;
            $price = $lazadaProduct->price;
            $specialPrice = $lazadaProduct->special_price;
            $mainImage = mysqli_real_escape_string($con,$lazadaProduct->MainImage);
            $image2 = mysqli_real_escape_string($con,$lazadaProduct->Image2);
            $image3 = mysqli_real_escape_string($con,$lazadaProduct->Image3);
            $image4 = mysqli_real_escape_string($con,$lazadaProduct->Image4);
            $image5 = mysqli_real_escape_string($con,$lazadaProduct->Image5);
            $image6 = mysqli_real_escape_string($con,$lazadaProduct->Image6);
            $image7 = mysqli_real_escape_string($con,$lazadaProduct->Image7);
            $image8 = mysqli_real_escape_string($con,$lazadaProduct->Image8);
            $modifiedUser = "bot";
            
            
            //main product
            $ret = hasMainProduct($sku);
            if(!$ret)
            {
                //insert into main
                $sql = "insert into mainProduct (Name,PrimaryCategory,Brand,Sku,SpecialPrice,Price,MainImage,Image2,Image3,Image4,Image5,Image6,Image7,Image8,Quantity,ModifiedUser) values( '$name','$primaryCategory','$brand','$sellerSku','$specialPrice','$price','$mainImage','$image2','$image3','$image4','$image5','$image6','$image7','$image8','$quantity','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $insertFail[] = $sku;
                }
                else
                {
                    $insertSuccess++;
                }
            }
            else
            {
                //update main
                $sql = "update mainProduct set Name = '$name',PrimaryCategory='$primaryCategory', brand = '$brand', sku = '$sku', specialPrice = '$specialPrice', price = '$price', mainImage = '$mainImage', image2 = '$image2', image3 = '$image3', image4 = '$image4', image5 = '$image5', image6 = '$image6', image7 = '$image7', image8 = '$image8', quantity = '$quantity', modifiedUser = '$modifiedUser' where sku = '$sku'";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $updateFail[] = $sku;
                }
                else
                {
                    $updateSuccess++;
                }
            }
            
            
            //lazada
            $ret = hasLazadaProductInApp($sku);
            if(!$ret)
            {
                $sql = "insert into lazadaProduct (Sku,ModifiedUser) values( '$sellerSku','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $insertFailLazada[] = $sku;
                }
            }
//            else
//            {
//                $sql = "update lazadaProduct set Name = '$name', sku = '$sku', price = '$price', mainImage = '$mainImage', quantity = '$quantity', modifiedUser = '$modifiedUser' where sku = '$sku'";
//                $ret = doQueryTask($con,$sql,$modifiedUser);
//                if($ret != "")
//                {
//                    $updateFailLazada[] = $sku;
//                }
//            }
        }
        else
        {
            $message = "sync data, get lazada product fail [i,sku]: [$i,$sku]";
            sendNotiToAdmin($message);
            writeToLog($message);
            
            continue;
        }
        
        
        //shopee
        writeToLog("get product shopee sku:" . $sku);
        $ret = getShopeeProduct($variations,$sku);
        writeToLog("getShopeeProduct:".json_encode($ret));
        writeToLog("get product shopee sku ret:" . ($ret != null));
        if($ret)
        {
            $itemID = $ret["item_id"];
            $itemSku = mysqli_real_escape_string($con,$ret["item_sku"]);
            $variationID = $ret["variation_id"];
            $variationSku = mysqli_real_escape_string($con,$ret["variation_sku"]);
            $quantity = 0;
            $modifiedUser = "bot";


            $ret = hasShopeeProductInApp($sku);
            if(!$ret)
            {
                $sql = "insert into shopeeProduct (`Sku`, `ItemID`, `ItemSku`, `VariationID`, `VariationSku`, `ModifiedUser`) values( '$itemSku','$itemID','$itemSku','$variaionID','$variationSku','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $insertFailShopee[] = $sku;
                }
            }
        }
        else
        {
            //add product in shopee web
            //add shopee product in app
            $param = array();
            $param["storeName"] = $storeName;
            $param["sku"] = $sku;
            $param["insert"] = true;
            $param["lazadaProduct"] = $lazadaProduct;
            $param["modifiedUser"] = $modifiedUser;

            $result = insertShopeeProductCurl($param);
            if(!$result)
            {
                //insert fail
                $message = "add shopee product fail [i,sku]: [$i,$sku]";
                sendNotiToAdmin($message);
                writeToLog($message);
            }
        }


        //JD
        writeToLog("get product JD sku:" . $sku);
        $ret = getJdProductSkuIds($sku);
        writeToLog("get product JD sku ret:" . ($ret != null));
        if(sizeof($ret)>0)
        {
            //check if sizeof(skuList) > 0
            for($j=0; $j<sizeof($ret); $j++)
            {
                $productSkuId = $ret[0];
                $productId = $productSkuId["productId"];
                $data = getJdProductByProductId($productId);
                if(sizeof($data->skuList)>0)
                {
                    $hasJdSku = true;
                    break;
                }
            }
        }


        if($hasJdSku)
        {
            $productSkuId = $ret[0];
            $productId = $productSkuId["productId"];
            $skuId = $productSkuId["skuId"];

            $upcCode = "";
            $outId = "";
            $modifiedUser = "bot";


            $ret = hasJdProductInApp($sku);
            if(!$ret)
            {
                $sql = "insert into jdProduct (`Sku`, `ProductId`, `SkuId`, `ModifiedUser`) values('$sku','$productId','$skuId','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $insertFailJd[] = $sku;
                }
            }
        }
        else
        {
            //add product in jd web
            //add jd product in app
            $param = array();
            $param["storeName"] = $storeName;
            $param["sku"] = $sku;
            $param["insert"] = true;
            $param["lazadaProduct"] = $lazadaProduct;
            $param["modifiedUser"] = $modifiedUser;

            $result = insertJdProductCurl($param);
            if(!$result)
            {
                //insert fail
                $message = "add jd product fail [i,sku]: [$i,$sku]";
                sendNotiToAdmin($message);
                writeToLog($message);
            }
        }


        //web
        writeToLog("get product web sku:" . $sku);
        $ret = hasWebProduct($sku);
        writeToLog("get product web sku ret:" . $ret);

        if(!$ret)
        {
            //add product in web
            $param = array();
            $param["storeName"] = $storeName;
            $param["sku"] = $sku;
            $param["insert"] = true;
            $param["lazadaProduct"] = $lazadaProduct;
            $param["modifiedUser"] = $modifiedUser;

            $result = insertWebProductCurl($param);
            if(!$result)
            {
                //insert fail
                $message = "add web product fail [i,sku]: [$i,$sku]";
                sendNotiToAdmin($message);
                writeToLog($message);
            }
        }
        
        
        
//        if($i == 1)
//        {
//            break;//test
//        }
        if($i > 38)//ทีละ 40
        {
            break;
        }
    }
    
    //test
//    sleep(40);
    
    
    
    $sizeInsert = sizeof($skus)>20?20:sizeof($skus);//ทีละ 20
    if($sizeInsert == $insertSuccess+$updateSuccess)
    {
        $success = true;
        $message = "**สำเร็จ**";
        $message .= "\r\n\r\nเพิ่ม sku : " . $insertSuccess;
        $message .= "\r\nแก้ไข sku : " . $updateSuccess;
        if(sizeof($insertFailLazada)+sizeof($updateFailLazada)+sizeof($insertFailShopee)+sizeof($insertFailJd)==0)
        {
            $message .= "\r\nเพิ่ม sku สำหรับ marketplace สำเร็จ";
        }
        else
        {
            $message .= "\r\nเพิ่ม sku สำหรับ marketplace ไม่สำเร็จ";
        }
        writeToLog($message);
    }
    else if(sizeof($insertFail)+sizeof($updateFail)+sizeof($insertFailLazada)+sizeof($updateFailLazada)+sizeof($insertFailShopee)+sizeof($insertFailJd)==0)
    {
        $success = true;
        $message = "**ไม่สำเร็จ**";
        $message .= "\r\n\r\nเพิ่ม sku : " . $insertSuccess;
        $message .= "\r\nแก้ไข sku : " . $updateSuccess;
        if(sizeof($insertFailLazada)+sizeof($updateFailLazada)+sizeof($insertFailShopee)+sizeof($insertFailJd)==0)
        {
            $message .= "\r\nเพิ่ม sku สำหรับ marketplace สำเร็จ";
        }
        else
        {
            $message .= "\r\nเพิ่ม sku สำหรับ marketplace ไม่สำเร็จ";
        }
        writeToLog($message);
    }
    else
    {
        $success = false;
        $message = "**ไม่สำเร็จ**";
        $message .= "\r\n\r\nเพิ่ม sku สำเร็จ : " . $insertSuccess;
        $message .= "\r\nแก้ไข sku สำเร็จ : " . $updateSuccess;
        if(sizeof($insertFailLazada)+sizeof($updateFailLazada)+sizeof($insertFailShopee)+sizeof($insertFailJd)==0)
        {
            $message .= "\r\nเพิ่ม sku สำหรับ marketplace สำเร็จ";
        }
        else
        {
            $message .= "\r\nเพิ่ม sku สำหรับ marketplace ไม่สำเร็จ";
        }
        writeToLog("fetch data to app not success, " . $message);
        sendNotiToAdmin("fetch data to app not success, " . $message);
    }
    writeToLog("insert main fail:" . json_encode($insertFail));
    writeToLog("update main fail:" . json_encode($updateFail));
    writeToLog("insert lazada fail:" . json_encode($insertFailLazada));
    writeToLog("update lazada fail:" . json_encode($updateFailLazada));
    writeToLog("insert shopee fail:" . json_encode($insertFailShopee));
    writeToLog("insert JD fail:" . json_encode($insertFailJd));
    
    
    
    
    
//    mysqli_commit($con);
    mysqli_close($con);
    echo json_encode(array("success"=>$success,"message"=>$message),JSON_UNESCAPED_UNICODE);
    writeToLog(json_encode(array("success"=>$success,"message"=>$message), JSON_UNESCAPED_UNICODE));
    writeToLog("query commit, file: " . basename(__FILE__));
    exit();
    
?>
