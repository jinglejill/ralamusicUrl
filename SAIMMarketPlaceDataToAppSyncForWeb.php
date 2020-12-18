<?php
    include_once('dbConnect.php');
    
    set_time_limit(3600);
  
    
     
    $skus = $_POST["skus"];
    $storeName = $_POST["storeName"];
    $page = $_POST["page"];
    $perPage = $_POST["perPage"];
    
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
//        $sql = "select Sku from mainproduct where productID >= 6456";
//        $sql = "select * from (select @row:=@row+1 as Row, Sku from (select SellerSku as Sku from lazadaproducttemp where status = 'active' order by sellersku)a,(select @row:=0)t)b where row > ($page-1)*$perPage limit $perPage";
        $startRow = $_GET["startRow"];
        $endRow = $_GET["endRow"];
        $sql = "select Sku from websku where webskuid between $startRow and $endRow";
        $skuList = executeQueryArray($sql);
        
//        $sku = $_GET["sku"];
//        $skuList = array();
//        $skuList[] = (object)array("Sku"=>$sku);
    }
    
    $dupSku = array();
    $successSku = array();
    $failSku = array();
    $insertSuccess = 0;
    $updateSuccess = 0;
    $insertFail = array();
    $updateFail = array();
    $insertFailLazada = array();
    $updateFailLazada = array();
    $insertFailShopee = array();
    $insertFailJd = array();
//    $variations = getAllSkuShopee();
    writeToLog("sizeof skus:".sizeof($skuList));
    for($i=0; $i<sizeof($skuList); $i++)
    {
        $sku = $skuList[$i]->Sku;
        $sku = mysqli_real_escape_string($con,$sku);
        
        //main from lazada
        writeToLog("get product lazada sku:" . $sku);
        $lazadaProduct = null;
        $sql = "select * from lazadaProductTemp where SellerSku = '$sku'";
//        $lazadaProductList = executeQueryArray($sql);
//        writeToLog("lazada product list: ".json_encode($lazadaProductList));
//        if(sizeof($lazadaProductList) == 0)
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
//        else
//        {
//            $lazadaProduct = $lazadaProductList[0];
//        }

        
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
            
            
        }
        else
        {
            $message = "sync data, get lazada product fail [i,sku]: [$i,$sku]";
            sendNotiToAdmin($message);
            writeToLog($message);
            
            continue;
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
                $failSku[] = $i . ':' . $sku;
            }
            else
            {
                $successSku[] = $i . ':' . $sku;
            }
        }
        else
        {
            $dupSku[] = $i . ':' . $sku;
        }
        
        
//        if($i == 1)
//        {
//            break;//test
//        }
        if($i > 198)//ทีละ 20
        {
            break;
        }
//        sleep(3);
    }
    
    //test
//    sleep(40);
    
    
    
//    $sizeInsert = sizeof($skus)>20?20:sizeof($skus);//ทีละ 20
//    if($sizeInsert == $insertSuccess+$updateSuccess)
//    {
//        $success = true;
//        $message = "**สำเร็จ**";
//        $message .= "\r\n\r\nเพิ่ม sku : " . $insertSuccess;
//        $message .= "\r\nแก้ไข sku : " . $updateSuccess;
//        if(sizeof($insertFailLazada)+sizeof($updateFailLazada)+sizeof($insertFailShopee)+sizeof($insertFailJd)==0)
//        {
//            $message .= "\r\nเพิ่ม sku สำหรับ marketplace สำเร็จ";
//        }
//        else
//        {
//            $message .= "\r\nเพิ่ม sku สำหรับ marketplace ไม่สำเร็จ";
//        }
//        writeToLog($message);
//    }
//    else if(sizeof($insertFail)+sizeof($updateFail)+sizeof($insertFailLazada)+sizeof($updateFailLazada)+sizeof($insertFailShopee)+sizeof($insertFailJd)==0)
//    {
//        $success = true;
//        $message = "**ไม่สำเร็จ**";
//        $message .= "\r\n\r\nเพิ่ม sku : " . $insertSuccess;
//        $message .= "\r\nแก้ไข sku : " . $updateSuccess;
//        if(sizeof($insertFailLazada)+sizeof($updateFailLazada)+sizeof($insertFailShopee)+sizeof($insertFailJd)==0)
//        {
//            $message .= "\r\nเพิ่ม sku สำหรับ marketplace สำเร็จ";
//        }
//        else
//        {
//            $message .= "\r\nเพิ่ม sku สำหรับ marketplace ไม่สำเร็จ";
//        }
//        writeToLog($message);
//    }
//    else
//    {
//        $success = false;
//        $message = "**ไม่สำเร็จ**";
//        $message .= "\r\n\r\nเพิ่ม sku สำเร็จ : " . $insertSuccess;
//        $message .= "\r\nแก้ไข sku สำเร็จ : " . $updateSuccess;
//        if(sizeof($insertFailLazada)+sizeof($updateFailLazada)+sizeof($insertFailShopee)+sizeof($insertFailJd)==0)
//        {
//            $message .= "\r\nเพิ่ม sku สำหรับ marketplace สำเร็จ";
//        }
//        else
//        {
//            $message .= "\r\nเพิ่ม sku สำหรับ marketplace ไม่สำเร็จ";
//        }
//        writeToLog("fetch data to app not success, " . $message);
//        sendNotiToAdmin("fetch data to app not success, " . $message);
//    }
//    writeToLog("insert main fail:" . json_encode($insertFail));
//    writeToLog("update main fail:" . json_encode($updateFail));
//    writeToLog("insert lazada fail:" . json_encode($insertFailLazada));
//    writeToLog("update lazada fail:" . json_encode($updateFailLazada));
//    writeToLog("insert shopee fail:" . json_encode($insertFailShopee));
//    writeToLog("insert JD fail:" . json_encode($insertFailJd));
//
    
    
    
    
//    mysqli_commit($con);
    mysqli_close($con);
    echo json_encode(array("dupSku"=>$dupSku,"no of fail"=>sizeof($failSku),"failSku"=>$failSku, "successSku"=>$successSku),JSON_UNESCAPED_UNICODE);
    writeToLog(json_encode(array("success"=>$success,"message"=>$message), JSON_UNESCAPED_UNICODE));
    writeToLog("query commit, file: " . basename(__FILE__));
    exit();
    
?>
