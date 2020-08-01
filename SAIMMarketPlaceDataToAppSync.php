<?php

    include_once('dbConnect.php');
//    setConnectionValue("MINIMALIST");
    set_time_limit(1200);
  
  
    $json_str = file_get_contents('php://input');
    $skus = json_decode($json_str)->skus;
    $storeName = json_decode($json_str)->storeName;

    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $data["modifiedUser"]);
    writeToLog("post json: " . $json_str);
   
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");

    
    $insertSuccess = 0;
    $updateSuccess = 0;
    $insertFail = array();
    $updateFail = array();
    $insertFailLazada = array();
    $updateFailLazada = array();
    $insertFailShopee = array();
    $insertFailJd = array();
    $variations = getAllSkuShopee();
    for($i=0; $i<sizeof($skus); $i++)
    {
        $sku = $skus[$i];
        
        
        //main from lazada
        writeToLog("get product lazada sku:" . $sku);
        $ret = getLazadaProduct($sku);
        writeToLog("get product lazada sku ret:" . ($ret != null));
        if($ret)
        {
            $name = mysqli_real_escape_string($con,$ret->attributes->name);
            $primaryCategory = mysqli_real_escape_string($con,$ret->primary_category);
            $brand = mysqli_real_escape_string($con,$ret->attributes->brand);
            $sellerSku = mysqli_real_escape_string($con,$ret->skus[0]->SellerSku);
            $quantity = $ret->skus[0]->quantity;
            $specialPrice = $ret->skus[0]->special_price;
            $price = $ret->skus[0]->price;
            $mainImage = mysqli_real_escape_string($con,$ret->skus[0]->Images[0]);
            $image2 = mysqli_real_escape_string($con,$ret->skus[0]->Images[1]);
            $image3 = mysqli_real_escape_string($con,$ret->skus[0]->Images[2]);
            $image4 = mysqli_real_escape_string($con,$ret->skus[0]->Images[3]);
            $image5 = mysqli_real_escape_string($con,$ret->skus[0]->Images[4]);
            $image6 = mysqli_real_escape_string($con,$ret->skus[0]->Images[5]);
            $image7 = mysqli_real_escape_string($con,$ret->skus[0]->Images[6]);
            $image8 = mysqli_real_escape_string($con,$ret->skus[0]->Images[7]);
            $modifiedUser = "bot";
            
            
            //main product
            $ret = hasMainProduct($sku);
            if(!$ret)
            {
                //insert into main
                $sql = "insert into mainProduct (Name,PrimaryCategory,Brand,Sku,SpecialPrice,Price,MainImage,Image2,Image3,Image4,Image5,Image6,Image7,Image8,Quantity,ModifiedUser) values( '$name','$primaryCategory','$brand','$sellerSku','$specialPrice','$price','$mainImage','$image2','$image3','$image4','$image5','$image6','$image7','$image8','$quantity','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
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
                $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
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
                $sql = "insert into lazadaProduct (Name,Sku,Price,MainImage,Quantity,ModifiedUser) values( '$name','$sellerSku','$price','$mainImage','$quantity','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                if($ret != "")
                {
                    $insertFailLazada[] = $sku;
                }
            }
            else
            {
                $sql = "update lazadaProduct set Name = '$name', sku = '$sku', price = '$price', mainImage = '$mainImage', quantity = '$quantity', modifiedUser = '$modifiedUser' where sku = '$sku'";
                $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                if($ret != "")
                {
                    $updateFailLazada[] = $sku;
                }
            }
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
                $sql = "insert into shopeeProduct (`Sku`, `ItemID`, `ItemSku`, `VariationID`, `VariationSku`, `Quantity`, `ModifiedUser`) values( '$itemSku','$itemID','$itemSku','$variaionID','$variationSku','$quantity','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                if($ret != "")
                {
                    $insertFailShopee[] = $sku;
                }
            }
        }
        
        
        //JD
        writeToLog("get product JD sku:" . $sku);
        $ret = getJdProductSkuIds($sku);
        writeToLog("get product JD sku ret:" . ($ret != null));
        if(sizeof($ret)>0)
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
                $sql = "insert into jdProduct (`Sku`, `ProductId`, `SkuId`, `UpcCode`, `OuterId`, `ModifiedUser`) values('$sku','$productId','$skuId','$upcCode','$outerId','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                if($ret != "")
                {
                    $insertFailJd[] = $sku;
                }
            }
        }
    }
    
    if(sizeof($skus) == $insertSuccess+$updateSuccess)
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
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    echo json_encode(array("success"=>$success,"message"=>$message));
    writeToLog("query commit, file: " . basename(__FILE__));
    exit();
    
?>
