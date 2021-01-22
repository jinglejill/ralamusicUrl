order: {"success":true,"Order":{"OrderNo":"201204PW6T4W1Y","OrderDate":"2020-12-04 20:21","Channel":2,"Items":[{"Sku":"BOSS-VE-500","Name":"BOSS\u00ae VE-500 \u0e40\u0e2d\u0e1f\u0e40\u0e1f\u0e04\u0e23\u0e49\u0e2d\u0e07 2in1 \u0e40\u0e1b\u0e47\u0e19\u0e17\u0e31\u0e49\u0e07\u0e40\u0e2d\u0e1f\u0e40\u0e1f\u0e04\u0e23\u0e49\u0e2d\u0e07\u0e41\u0e25\u0e30\u0e40\u0e2d\u0e1f\u0e40\u0e1f\u0e04\u0e01\u0e35\u0e15\u0e32\u0e23\u0e4c \u0e21\u0e35 Looper \u0e2b\u0e19\u0e49\u0e32\u0e08\u0e2d LCD + \u0e41\u0e16\u0e21\u0e1f\u0e23\u0e35\u0e2d\u0e41\u0e14\u0e1b\u0e40\u0e15\u0e2d\u0e23\u0e4c ** \u0e1b\u0e23\u0e30\u0e01\u0e31\u0e19 1 \u0e1b\u0e35 **","Quantity":1,"Image":"https:\/\/cf.shopee.co.th\/file\/5451179351aa50cd3fac92d6c52f6e7c","AccImage":[]}],"Images":[{"Id":1,"Image":"","Base64":"","Type":""},{"Id":2,"Image":"","Base64":"","Type":""},{"Id":3,"Image":"","Base64":"","Type":""},{"Id":4,"Image":"","Base64":"","Type":""},{"Id":5,"Image":"","Base64":"","Type":""},{"Id":6,"Image":"","Base64":"","Type":""},{"Id":7,"Image":"","Base64":"","Type":""},{"Id":8,"Image":"","Base64":"","Type":""}],"AccImages":[]}}
<?php
    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
    ini_set("memory_limit","50M");
    set_time_limit(3600);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    echo strlen("http://minimalist.co.th/saim/ralamusic/images/201215MT3B4J5R_202012251557_0.jpeg");
    exit();
    
    
    $orderSn = $_GET["orderNo"];
    $orderObj = getShopeeOrder($orderSn);
//    echo json_encode($orderObj);
    echo date("Y-m-d H:i", $orderObj->orders[0]->create_time);
    exit();
    $sql = "select * from deleted where deletedid = '1397'";
    $deleted = executeQueryArray($sql);
    $json = $deleted[0]->Json;
    $obj = json_decode($json);
    echo $obj[0]->Name;
    exit();
    
    
    
    $orderDeliveryGroupID = 412;
    //OrderDeliveryItem
    $sql = "select * from OrderDeliveryItem where orderDeliveryID in (select OrderDeliveryID from OrderDelivery where orderDeliveryGroupID = '$orderDeliveryGroupID')";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
//    echo $json;
   
    
    
//    echo $utf8encode;
//        echo $jsonEscape;
        $tableName = "OrderDeliveryItem";
        $ret = keepDeleteRecord($tableName,$json);


    exit();

    function keepDeleteRecord($tableName,$json)
    {
        global $con;
        global $modifiedUser;

        
        $json = str_replace("\u","\\u",$json);
        $json = mysqli_real_escape_string($con,$json);
        $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        return $ret == "";
    }
    
    
    
//
//    $sku = "K&M-11930-000-55";
//    $lazadaProduct = getLazadaProduct($sku);
//    echo json_encode($lazadaProduct);
//    exit();
//
    
    
    
//    $sql = "select * from lazadaProductTemp where status = 'active'";
//    $sql = "select * from (select @row:=@row+1 as Row, a.* from (select SellerSku from lazadaProductTemp where status = 'active')a,(select @row:=0)t)b where row=6440 or row = 6441";
    $sql = "select SellerSku from lazadaProductTemp where sellersku like '%&%'";
    $lazadaProductTempList = executeQueryArray($sql);
    
    for($i=0; $i<sizeof($lazadaProductTempList); $i++)
    {
        $lazadaProductApi = getLazadaProduct($lazadaProductTempList[$i]->SellerSku);
        $lazadaProduct = array();
        $skus = array();
        $sku = array();
//        $sku["SellerSku"] = str_replace("&","&amp;",$lazadaProductTempList[$i]->SellerSku);
        $sku["SellerSku"] = $lazadaProductTempList[$i]->SellerSku;
        $sku["SkuId"] = $lazadaProductApi->skus[0]->SkuId;
        $sku["special_from_date"] = "2021-01-12 00:00";
        $sku["special_to_date"] = "2033-01-12 00:00";
        $skus["Sku"] = $sku;
        $lazadaProduct["Skus"] = $skus;
        $lazadaProduct["ItemId"] = $lazadaProductApi->item_id;
        
        
//        $lazadaProductAddNode = array("Product"=>$lazadaProduct);
//        $xmlPayload = array2xml2(json_decode(json_encode($lazadaProductAddNode),true),false);
//        echo $xmlPayload;
//        exit();
        $ret = updateLazadaProduct($lazadaProduct);
        echo json_encode($ret);
        exit();
    }
    
    exit();
//    $lazadaProductAddNode = array("Product"=>$lazadaProduct);
//    $xmlPayload = array2xml(json_decode(json_encode($lazadaProductAddNode),true),false);
//    echo $xmlPayload;
//    exit();
//    $c = new LazopClient($url,$appKey,$appSecret);
//    $request = new LazopRequest('/product/update','POST');
//    $request->addApiParam('payload',$xmlPayload);
//    $resp = $c->execute($request, $accessToken);
//    echo $resp;
//    exit();
    
    function array2xml2($array, $xml = false)
    {
        if($xml === false)
        {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Request/>');
        }

        foreach($array as $key => $value){
            if(is_array($value)){
                array2xml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml->asXML();
    }

//    resizeImage("http://www.minimalist.co.th/saim/ralamusic/images/355160807486578_202101121433_0.jpeg");
//    exit();
//
//

//
//    $token = "65793e19bdfb17ce0d36ce741aec91ecd3b75052d8cf687facec7628681ebf08";
//    $noti = array();
//    $noti["title"] = "Order delivery recheck";
//    $noti["body"] = "#$orderDeliveryGroupID (qty. $orderCount) at $checkDate";
//    sendApplePushNotification($token,$noti);
//    exit();
//
//
//    $orderNo = $_GET["orderNo"];
//    $shopeeOrder = getShopeeOrder($orderNo);
//    echo json_encode($shopeeOrder);
//    exit();
//
//
//    $sql = "select * from deleted where deletedid = '1303'";
//    $deleted = executeQueryArray($sql);
//    $json = $deleted[0]->Json;
//    $orderDeliveryGroupList = json_decode($json);
//    $orderDeliveryGroup = $orderDeliveryGroupList[0];
//    $orderDeliveryGroupID = $orderDeliveryGroup->OrderDeliveryGroupID;
//    $checkDate = $orderDeliveryGroup->CheckDate;
//    $modifiedUser = $orderDeliveryGroup->ModifiedUser;
//    $modifiedDate = $orderDeliveryGroup->ModifiedDate;
//
//
//    $sql = "insert into orderDeliveryGroup (`OrderDeliveryGroupID`, `CheckDate`, `ModifiedUser`, `ModifiedDate`) values ('$orderDeliveryGroupID','$checkDate','$modifiedUser','$modifiedDate')";
////    echo "<br>";
////    echo $sql;
//    $ret = doQueryTask($con,$sql,$modifiedUser);
//    if($ret != "")
//    {
//        writeToLog("INSERT INTO orderDeliveryGroup fail [OrderDeliveryGroupID]:[$orderDeliveryGroupID]");
//        echo "<br>"."INSERT INTO orderDeliveryGroup fail [OrderDeliveryGroupID]:[$orderDeliveryGroupID]";
//    }
//
//
//
//    $sql = "select * from deleted where deletedid = '1304'";
//    $deleted = executeQueryArray($sql);
//    $json = $deleted[0]->Json;
//    $orderDeliveryList = json_decode($json);
//    for($i=0; $i<sizeof($orderDeliveryList); $i++)
//    {
//        $orderDelivery = $orderDeliveryList[$i];
//
//        $orderDeliveryID = $orderDelivery->OrderDeliveryID;
//        $orderDeliveryGroupID = $orderDelivery->OrderDeliveryGroupID;
//        $channel = $orderDelivery->Channel;
//        $orderNo = $orderDelivery->OrderNo;
//        $orderDate = $orderDelivery->OrderDate;
//        $image1 = $orderDelivery->Image1;
//        $image2 = $orderDelivery->Image2;
//        $image3 = $orderDelivery->Image3;
//        $image4 = $orderDelivery->Image4;
//        $image5 = $orderDelivery->Image5;
//        $image6 = $orderDelivery->Image6;
//        $image7 = $orderDelivery->Image7;
//        $image8 = $orderDelivery->Image8;
//
//        $modifiedUser = $orderDelivery->ModifiedUser;
//        $modifiedDate = $orderDelivery->ModifiedDate;
//
//
//        $sql = "insert into orderDelivery (`OrderDeliveryID`, `OrderDeliveryGroupID`, `Channel`, `OrderNo`, `OrderDate`, `Image1`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8`, `ModifiedUser`, `ModifiedDate`) values ('$orderDeliveryID','$orderDeliveryGroupID','$channel','$orderNo','$orderDate','$image1','$image2','$image3','$image4','$image5','$image6','$image7','$image8','$modifiedUser','$modifiedDate')";
////        echo "<br>";
////        echo $sql;
//        $ret = doQueryTask($con,$sql,$modifiedUser);
//        if($ret != "")
//        {
//            writeToLog("INSERT INTO orderDelivery fail [OrderDeliveryID]:[$orderDeliveryID]");
//            echo "<br>"."INSERT INTO orderDelivery fail [OrderDeliveryID]:[$orderDeliveryID]";
//        }
//    }
    
    $sql = "select * from orderDeliveryItem where orderDeliveryItemid = '371'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $tableName = "OrderDeliveryItem";
    $ret = keepDeleteRecord($tableName,$json);
//    echo $json;
    exit();
    
    $deleted = executeQueryArray($sql);
    $json = $deleted[0]->Json;

    $orderDeliveryItemList = json_decode($json);
    for($i=0; $i<sizeof($orderDeliveryItemList); $i++)
    {
        $orderDeliveryItem = $orderDeliveryItemList[$i];
        
        $orderDeliveryID = $orderDeliveryItem->OrderDeliveryID;
        $orderDeliveryItemID = $orderDeliveryItem->OrderDeliveryItemID;
        $sku = $orderDeliveryItem->Sku;
        $name = $orderDeliveryItem->Name;
        echo utf8_encode($name);
        exit();
        $quantity = $orderDeliveryItem->Quantity;
        $image = $orderDeliveryItem->Image;
        
        $modifiedUser = $orderDeliveryItem->ModifiedUser;
        $modifiedDate = $orderDeliveryItem->ModifiedDate;
        
        
        $sql = "insert into orderDeliveryItem (`OrderDeliveryItemID`, `OrderDeliveryID`, `Sku`, `Name`, `Quantity`, `Image`, `ModifiedUser`, `ModifiedDate`) values ('1000','$orderDeliveryID','$sku','$name','$quantity','$image','$modifiedUser','$modifiedDate')";
//        echo "<br>";
//        echo $sql;
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            writeToLog("INSERT INTO orderDeliveryItem fail [OrderDeliveryItemID]:[$orderDeliveryItemID]");
            echo "<br>"."INSERT INTO orderDeliveryItem fail [OrderDeliveryItemID]:[$orderDeliveryItemID]";
        }
    }
    
    exit();
    
    
    
    
//    $orderSn = $_GET["orderSn"];
//    $orderObj = getShopeeOrder($orderSn);
//    echo json_encode($orderObj);
//    exit();
//
//
//    $filename = $_GET["filename"];//"https://minimalist.co.th/saim/RALAMUSIC/Images/2012300SGJV45G_202012311030_0.jpeg";
//    $image = new Imagick($filename);
//    $orientation = $image->getImageOrientation();
//
    
//    if($orientation == 1)
//    {
//        $width = $image->getImageWidth();
//        $height = $image->getImageHeight();
//    }
//    else
//    {
//        $width = $image->getImageHeight();
//        $height = $image->getImageWidth();
//    }
//    echo json_encode(array($width,$height));
    
    echo $orientation;
//    echo "<br>";
//    echo $width;
//    echo "<br>";
//    echo $height;
//    echo $orientation;
    
    
//    list($width, $height) = getimagesize($filename);
//    echo $width > $height;
//    $image_info = getimagesize($filename);
//    echo getImageWidth($filename);//$image_info[0];
//    echo "<br>";
//    echo getImageHeight($filename);
//    echo json_encode($image_info);
    exit();
//    $storeName = "RALAMUSIC";
    
    
    $filename = "https://minimalist.co.th/saim/RALAMUSIC/Images/testImageOriginal.jpeg";
    resizeImage($filename);
    exit();
    
    
    echo json_encode(getPendingOrdersLazada());
    exit();
    
    
    $noti = array();
    $noti["title"] = "Order delivery recheck";
    $noti["body"] = "#$orderDeliveryGroupID (qty. $orderCount) at $checkDate";
    sendApplePushNotification('9785dce1a61aee542024dfc9cfdc64c458471de8ad358e3bc5f85eed81ae0a85',$noti);
    exit();
    
    
    
    $deliveryNo = '888854444635';
    echo substr($deliveryNo,4);
    exit();
    
    $orderId = '54451819';
    echo getWaybillNumberJd($orderId);
    exit();
    
    $orderId = '54436207';
    $c = getApiManager();
    $c->method = "jingdong.PlaceOrderServiceJsf.getPreWaybillCodeForOpenApi";
    $reqWaybillCodeDTO = array();
    $reqWaybillCodeDTO["orderId"] = $orderId;
    
    $data = array();
    $data["reqWaybillCodeDTO"] = $reqWaybillCodeDTO;
    
    
    $c->param_json = json_encode($data);
    $resp = $c->call();
    echo json_encode($resp);
    
    exit();
    
//    rename('./RALAMUSIC/Images/201204PW6T4W1Y_202012171405_0.jpeg', './RALAMUSIC/Deleted/201204PW6T4W1Y_202012171405_0.jpeg');
//    exit();
//    
//
    
//    echo json_encode(getPendingOrdersJd());
    
    echo json_encode(getOrderItemJD('54436207'));
    exit();
//    $orderNo = "347761126409631";
//    $lazadaOrder = getOrderLazada($orderNo);
//
////    echo json_encode($lazadaOrder);
////    exit();
//
//
//    $order = array();
//    $order["OrderNo"] = $orderNo;
//    $order["OrderDate"] = substr($lazadaOrder->created_at,0,16);//date("Y-m-d H:i", $orderObj->orders[0]->pay_time);
//
//    $items = array();
//    for($i=0; $i<sizeof($lazadaOrder->items); $i++)
//    {
//        $item = array();
//        $item["Sku"] = $lazadaOrder->items[$i]->sku;
//        $item["Name"] = $lazadaOrder->items[$i]->name;
//        $item["Quantity"] = 1;
//        $item["Image"] = $lazadaOrder->items[$i]->product_main_image;
//        $items[] = $item;
//    }
//    $order["Items"] = $items;
//    echo json_encode($order);
//    exit();
    
    
//    $obj = getDocument('shippingLabel','[345474641258769]');
//    $base64 = $obj->data->document->file;
//    $decoded = base64_decode($base64);
//    $file = 'invoice.html';
//    file_put_contents($file, $decoded);
    
    
    
    
//
//    if (file_exists($file)) {
//        header('Content-Description: File Transfer');
//        header('Content-Type: application/octet-stream');
//        header('Content-Disposition: attachment; filename="'.basename($file).'"');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
//        header('Content-Length: ' . filesize($file));
//        readfile($file);
//        exit;
//    }
//
//        exit();
    
    
    
    $obj = getDocument('shippingLabel','[347761126509631]');
//    echo json_encode($obj);
//    exit();
//    header("Content-type: text/html");
    $targetPath = "./test.html";
//    echo base64_decode($obj->data->document->file);
//    exit();
    $content= $obj->data->document->file;
    $file = fopen($targetPath, 'w');
    fwrite($file, base64_decode($content));
    fclose($file);
//    
////    echo file_get_contents( $obj->data->document->file);
    exit();
    
    
    // Create a stream
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>"Accept-language: en\r\n" .
                  "Cookie: foo=bar\r\n"
      )
    );

    $context = stream_context_create($opts);

    // Open the file using the HTTP headers set above
    $file = file_get_contents('http://www.example.com/', false, $context);
    
    
    
    
    $title = "test";
    $text = "tttt";
    $paramBody = array();
    $paramBody["alert"] = array(
                   'title' => $title
                   ,'body' => $text
                   );
    $paramBody["sound"] = "default";
    
    
    sendPushNotificationWithPath('7acdcef39bfce49b9d78aa8126532ceff91d8c1a438523b23974af08b41fc97a',$paramBody);
    exit();
    
    
    $sql = "SELECT Sku, Quantity FROM `mainproduct` WHERE `Sku` LIKE '%&%'";
    $productList = executeQueryArray($sql);
//    echo sizeof($productList);
//    exit();
    for($i=0; $i<sizeof($productList); $i++)
    {
        $sku = $productList[$i]->Sku;
        $quantity = $productList[$i]->Quantity;
        $ret = updateStockQuantityLazada($sku,$quantity);
        if($ret)
        {
            echo "<br>true";
        }
        else
        {
            echo "<br>false";
        }
    }
    exit();
    
    $sku = $_GET["sku"];
    $quantity = $_GET["quantity"];
    
//    $id = getItemIDAndSkuIDLazada($sku);
//    echo json_encode($id);
//    exit();
    
    
    $ret = updateStockQuantityLazadaTest($sku,$quantity);
    if($ret)
    {
        echo "true";
    }
    else
    {
        echo "false";
    }
    exit();
    
    $sku = $_GET["sku"];
    $quantity = 0;
    
    //update part
    $payLoad = file_get_contents('./lazadaUpdateQuantityTemplate3.php');
//    $payLoad = str_replace("#sku#",$sku,$payLoad);
//    $payLoad = str_replace("#quantity#",$quantity,$payLoad);
    
    
        
    $c = new LazopClient($url,$appKey,$appSecret);
    $request = new LazopRequest('/product/price_quantity/update','POST');
    $request->addApiParam('payload',$payLoad);
    $resp = $c->execute($request, $accessToken);
    writeToLog("update stock quantity lazada result:".$resp);
    $respObject = json_decode($resp);
    
    if($respObject->code == "0")
    {
        writeToLog("update quantity success, sku:" . $sku . ", quantity:" . $quantity);
        echo "update quantity success, sku:" . $sku . ", quantity:" . $quantity;
//        return true;
    }

    else
    {
        $lazadaMessage = $respObject->detail[0]->message;
        if(strpos($lazadaMessage, "Negative sellable stock over sale. Negative. Reserved stock ") !== false )
        {
            writeToLog("failed because of reserved stock");
            $lazadaMessage = str_replace('Negative sellable stock over sale. Negative. Reserved stock ','',$lazadaMessage);
            writeToLog("lazada message : " . $lazadaMessage);
            $dataList = explode(" ",$lazadaMessage);
            $reservedStock = $dataList[0];
            
            return updateStockQuantityLazada($sku,intval($reservedStock));
        }
        else
        {
            writeToLog("lazada update stock fail:".json_encode($respObject->detail));
            //notify
            $message = "lazada update stock fail:".json_encode($respObject->detail);
            sendNotiToAdmin($message);
            return false;
        }
    }
    exit();
    
    
    
    
    
    $itemID = 9802611119;
    echo json_encode(deleteItemShopee($itemID));
    exit();
    
//    $sku = $_GET['sku'];
//    echo json_encode(getJdProductSkuIds($sku));
//    exit();

    
//    $itemID = 6762728443;
//    $sku = "Yamaha-FG830-AB-S1";
    
    //shopee image update
    $start = $_GET["start"];
    $end = $_GET["end"];
    $sql = "select * from(select @row:=@row+1 as Row, a.* from (select shopeeProductID, Sku, ItemID from shopeeproduct where sku in (select sku from mainproducttemp) order by shopeeProductID)a, (select @row:=0 as row)t)b where row between $start and $end";
    $shopeeProductList = executeQueryArray($sql);
    
    for($k=0; $k<sizeof($shopeeProductList); $k++)
    {
        $shopeeProduct = $shopeeProductList[$k];
        $itemID = $shopeeProduct->ItemID;
        $sku = $shopeeProduct->Sku;
    
    
        $images = array();//url skus[0]->Images
        $sql = "select * from mainProductTemp where sku = '$sku'";
        $product = executeQueryArray($sql);
        if($product[0]->MainImage != "")
        {
            $images[] = $product[0]->MainImage;
        }
        if($product[0]->Image2 != "")
        {
            $images[] = $product[0]->Image2;
        }
        if($product[0]->Image3 != "")
        {
            $images[] = $product[0]->Image3;
        }
        if($product[0]->Image4 != "")
        {
            $images[] = $product[0]->Image4;
        }
        if($product[0]->Image5 != "")
        {
            $images[] = $product[0]->Image5;
        }
        if($product[0]->Image6 != "")
        {
            $images[] = $product[0]->Image6;
        }
        if($product[0]->Image7 != "")
        {
            $images[] = $product[0]->Image7;
        }
        if($product[0]->Image8 != "")
        {
            $images[] = $product[0]->Image8;
        }
        
        $ret = updateItemImageShopee(intval($itemID),$images);
        echo json_encode($ret);
//        if($ret->item_id <= 0)
//        {
//            echo "$sku<br>";
//        }
    }
    
//    echo json_encode(updateItemImageShopee($itemID,$images));
    exit();
    //shopee image update
    
    
    
    
//
//    $data = getJdProduct(2424432);
//    echo json_encode($data);
//    exit();
    
    
    
    
   
    
//    //update jd image
    $start = $_GET["start"];
    $end = $_GET["end"];
    
    
    $sql = "select * from(select @row:=@row+1 as Row, a.* from (select jdProductID, Sku, ProductId from jdproduct where sku in (select sku from mainproducttemp) order by jdProductID)a, (select @row:=0 as row)t)b where row between $start and $end";
    $jdProductList = executeQueryArray($sql);
    
    for($k=0; $k<sizeof($jdProductList); $k++)
    {
        $jdProduct = $jdProductList[$k];
        $productId = $jdProduct->ProductId;
        $sku = $jdProduct->Sku;
        
        
        $data = getJdProduct($productId);



        $imageList = array();
        $imageProduct = array();
        $imageSku = array();
        $sql = "select * from mainProductTemp where sku = '$sku'";
        $product = executeQueryArray($sql);
        if($product[0]->MainImage != "")
        {
            $index = 1;
            $tmpFileName = str_replace("/","-",$sku)."-".$index."." . getImageType($product[0]->MainImage);
            $jdImageUrl = JdImageUpload($product[0]->MainImage,$tmpFileName);
            echo $product[0]->MainImage . "<br>";

            //image product
            $image = array();
            $image["colorId"] = "0000000000";
            $image["imgUrl"] = $jdImageUrl;
            $image["index"] = 1;
            $imageProduct[] = $image;


            //image sku
            $image = array();
            $image["colorId"] = $sku;
            $image["imgUrl"] = $jdImageUrl;
            $image["index"] = $index;
            $imageSku[] = $image;
        }
        if($product[0]->Image2 != "")
        {
            $index = 2;
            $tmpFileName = str_replace("/","-",$sku)."-".$index."." . getImageType($product[0]->Image2);
            $jdImageUrl = JdImageUpload($product[0]->Image2,$tmpFileName);

            $image = array();
            $image["colorId"] = $sku;
            $image["imgUrl"] = $jdImageUrl;
            $image["index"] = $index;
            $imageSku[] = $image;
        }
        if($product[0]->Image3 != "")
        {
            $index = 3;
            $tmpFileName = str_replace("/","-",$sku)."-".$index."." . getImageType($product[0]->Image3);
            $jdImageUrl = JdImageUpload($product[0]->Image3,$tmpFileName);

            $image = array();
            $image["colorId"] = $sku;
            $image["imgUrl"] = $jdImageUrl;
            $image["index"] = $index;
            $imageSku[] = $image;
        }
        if($product[0]->Image4 != "")
        {
            $index = 4;
            $tmpFileName = str_replace("/","-",$sku)."-".$index."." . getImageType($product[0]->Image4);
            $jdImageUrl = JdImageUpload($product[0]->Image4,$tmpFileName);

            $image = array();
            $image["colorId"] = $sku;
            $image["imgUrl"] = $jdImageUrl;
            $image["index"] = $index;
            $imageSku[] = $image;
        }
        if($product[0]->Image5 != "")
        {
            $index = 5;
            $tmpFileName = str_replace("/","-",$sku)."-".$index."." . getImageType($product[0]->Image5);
            $jdImageUrl = JdImageUpload($product[0]->Image5,$tmpFileName);

            $image = array();
            $image["colorId"] = $sku;
            $image["imgUrl"] = $jdImageUrl;
            $image["index"] = $index;
            $imageSku[] = $image;
        }
        if($product[0]->Image6 != "")
        {
            $index = 6;
            $tmpFileName = str_replace("/","-",$sku)."-".$index."." . getImageType($product[0]->Image6);
            $jdImageUrl = JdImageUpload($product[0]->Image6,$tmpFileName);

            $image = array();
            $image["colorId"] = $sku;
            $image["imgUrl"] = $jdImageUrl;
            $image["index"] = $index;
            $imageSku[] = $image;
        }
        if($product[0]->Image7 != "")
        {
            $index = 7;
            $tmpFileName = str_replace("/","-",$sku)."-".$index."." . getImageType($product[0]->Image7);
            $jdImageUrl = JdImageUpload($product[0]->Image7,$tmpFileName);

            $image = array();
            $image["colorId"] = $sku;
            $image["imgUrl"] = $jdImageUrl;
            $image["index"] = $index;
            $imageSku[] = $image;
        }
        if($product[0]->Image8 != "")
        {
            $index = 8;
            $tmpFileName = str_replace("/","-",$sku)."-".$index."." . getImageType($product[0]->Image8);
            $jdImageUrl = JdImageUpload($product[0]->Image8,$tmpFileName);

            $image = array();
            $image["colorId"] = $sku;
            $image["imgUrl"] = $jdImageUrl;
            $image["index"] = $index;
            $imageSku[] = $image;
        }

        $imageList["0000000000"] = $imageProduct;
        $imageList[$sku] = $imageSku;

        $data->imageList = $imageList;


        
        //unset
        $skuList = $data->skuList;
        for($i=0; $i<sizeof($skuList); $i++)
        {
            $sku = $skuList[$i];
            $sku->outerId = str_replace('®','',$sku->upcCode);

            //unset sku****
            unset($sku->productCode);
            unset($sku->class);
            //unset sku****



            //unset saleAttrs****
            $saleAttrs = $sku->saleAttrs;
            for($j=0; $j<sizeof($saleAttrs); $j++)
            {
                $saleAttr = $saleAttrs[$j];
                $saleAttr->required = 1;
                unset($saleAttr->isEditting);
                unset($saleAttr->checked);
                unset($saleAttr->focus);
                unset($saleAttr->comAttId);
                unset($saleAttr->class);
            }
            //unset saleAttrs****
        }



    //    //unset****
        $data->appDescription = $data->appdis;
        $data->pcDescription = $data->dis;
        $data->locale = "en_US";
        unset($data->wareQD);
        unset($data->templateId);
        unset($data->dis);
        unset($data->applyId);
        unset($data->promiseId);
        unset($data->shelfLife);
        unset($data->class);
        unset($data->appdis);
        unset($data->afterSales);
        unset($data->unit);
        unset($data->descriptionEditType);
        unset($data->categoryStr);
        unset($data->countryOfOrigin);
        //unset

//        echo json_encode($data);
//        exit();
        
        $result = updateJdProduct($data);

        if($result["code"] == 200)
        {
            $productId = $result["productId"];
            $skuId = $result["skuId"];


            if($message)
            {
                $ret = array();
            //        $ret["success"] = false;
                $ret["sku"] = $sku->outerId;
                $ret["message"] = $message;
            //            mysqli_rollback($con);
                mysqli_close($con);

                echo json_encode($ret);
            }
            
//            exit();
        }
        else
        {
            //insert fail
            $message = "เพิ่มสินค้าใน JD ไม่สำเร็จ (" . $result["message"] . ")";
            sendNotiToAdmin($message);

            $ret = array();
            $ret["success"] = false;
            $ret["sku"] = $sku->outerId;
            $ret["message"] = $message;
//            mysqli_rollback($con);
            mysqli_close($con);

            echo json_encode($ret);
//            exit();
        }
    }
    
    mysqli_close($con);
    exit();
//    update jd image
    
    
    

    //item with variation
//    $skuWrongImage = array();
//    $sql = "select Sku from webskutodelete";
//    $skuList = executeQueryArray($sql);
//    for($i=0; $i<sizeof($skuList); $i++)//sizeof($mainProductList)
//    {
//        $sku = $skuList[$i]->Sku;
//        $lazadaProduct = getLazadaProduct($sku);
//        $itemID = $lazadaProduct->item_id;
//        $lazadaProductByItemID = getLazadaProductByItemID($itemID);
//        if(sizeof($lazadaProductByItemID->skus)>1)
//        {
//            $skuWrongImage[] = $sku;
//        }
//    }
//    echo json_encode($skuWrongImage);
//    exit();
    //item with variation
    
    
    
//    //display image
//    $id = $_GET["id"];
//    $sql = "select mainProduct.* from mainproduct where sku in (select sku from websku where webskuid = $id)";
//    $mainProductList = executeQueryArray($sql);
//    for($i=0; $i<1; $i++)//sizeof($mainProductList)
//    {
//        $mainProduct = $mainProductList[$i];
//        $mainImage = $mainProduct->MainImage;
//        $image2 = $mainProduct->Image2;
//        $image3 = $mainProduct->Image3;
//        $image4 = $mainProduct->Image4;
//        $image5 = $mainProduct->Image5;
//        $image6 = $mainProduct->Image6;
//        $image7 = $mainProduct->Image7;
//        $image8 = $mainProduct->Image8;
//    }
//
//    echo "<img src='$mainImage' width='12%'>...";
//    echo "<img src='$image2' width='12%'>...";
//    echo "<img src='$image3' width='12%'>...";
//    echo "<img src='$image4' width='12%'>...";
//    echo "<img src='$image5' width='12%'>...";
//    echo "<img src='$image6' width='12%'>...";
//    echo "<img src='$image7' width='12%'>...";
//    echo "<img src='$image8' width='12%'>...";
//
//    exit();
//    //display image
    
    
//    $skuList = ["Joyo-JPA-862","Studiomaster-CM50","Midiplus-Studio-M","KORG-LP-380-WH","Blackstar-HT-Club-40-MKII","KORG-LP-380-BK","Roland-JC-40","Roland-JC-22","Mantic-GT-1GC-R","Mantic GT-1DCE-SB-S1","Blackstar-HT-5RH-MKII-Valve-Head","Blackstar-Studio-10-6L6","Blackstar-Studio-10-EL34","Blackstar-HT-112OC-MKII-1x12-Cabinet","Paramount-MI-01-S1","Yamaha-ERG121U-Nux-Mighty-Lite-BT","Yamaha-Pacifica012-RD-Nux-Mighty-Lite-BT","Yamaha-Pacifica012-BL-Nux-Mighty-Lite-BT","Fender-Grace-Vanderwaal-Moonlight-Ukulele-F0971610102","SE-Electronics-X1-S","Fender-250XS","DB-Blake-Deluxe-VTFAC300-WD","DB-Blake-Deluxe-VTFAC300-BR","UDG-U96110BL-Ultimate-Laptop-Stand","Fender-Acoustic-200","Yamaha-FG820-NT","Daddario-Lock-Strap-50PLA07","NUX-LCDLB-Loop-Core-Deluxe-Bundle-Footswitch","NUX-BCDL-Boost-Core-Deluxe","Fantasia-F81BK-S2","Fantasia-F81BK","Fantasia-F101-BK-S2","Novation-Launchpad-Mini-MK3","Fantasia-F101-N-S1","Fantasia-F101-BK-S1","Fantasia-F101-BK","Kazuki-RLTL-CYW-Classic-Yellow","Yamaha-CGX102","Yamaha-AC1R-NT","Fender-Deluxe-MN-SSS-Blue-0147102327","Echoslap-VC201-EB","Roland-XPS-10","Clevan-D10SDB","Midiplus-X6-Mini","Yamaha-CGS102A","Yamaha-ERG121U","Roland-TD-17K-L","Daddario-EPS520","Lirevo-B20","Lirevo-B40","Lirevo-B10","Lirevo-B150","Lirevo-B80","XVIVE-U3","Remo-EN-1022-EB","Fantasia-QAG411MN","Yamaha-APX600-N-INT","Paramount-SOG-60C","Nux-WK-310-BK-S1","Paramount-DS41-1DVS","Yamaha-C80","Yamaha-CG102","Epiphone-Slash-AFD-Special-II","Martin-Lee-Z4116CE-S1","Yamaha-C70","Yamaha-C40","Carlsbro-CSD500-CEN15","Yamaha-CS40","Evans-EQPB1","Paramount-C5E","Kazuki-KZ409C-N-SET5","CARLSBRO-CSD130P","Fender-Newporter-Rustic-Copper-0970743096","Fender-Newporter-Candy-Apple-Red-0970743009","Fender-Newporter-Champagne-0970743044","Fender-Newporter-Jetty-Black-0970743006","Fender-CD140SCEN","Vox-Mini5-Rhythm-Classic","Echoslap-VC201-VL","Paramount-ED12BK","Dixon-PYH-C-BP","Hohner-Bravo-III-96RD","Squier-Affinity-Jazz-Bass-BK-SET01-0310760506","Kazuki-KZ39BK-S2","Kazuki-KZ39BK-S1","MK-2089","Paramount-CTS-S","Paramount-CTS-L","Yamaha-FS100CBK","Paramount-SMA007","Echoslap-VC201-VSB","Fantasia-C41RD-FAT200D","Paramount-PKS5","Paramount-PKS4","Carlsbro-DF1521","HUN-HIC-7AST-VL","HUN-HIC-7AST-RD","HUN-HIC-7AST-YL","Carlsbro-CSD120P","HUN-HIC-5AFG-YL","Arborea-DG22HR","Arborea-DG24","Arborea-DG20HR","Arborea-DG12RG","Carlsbro-CSD120-FCSS1","MEGA-GX60B","MEGA-GL20","MEGA-GL20B","MEGA-GX35R","Carlsbro-csd120","Belcat-SH-85","MUSEDO-T27","Kirlin-MW-472B-BK-6M","Mantic-OM-370-S2","Fantasia-F81GY-S2","Fantasia-F81GY","Fender\u00ae FA-125-N-S1","Fender-FA-125-N","Mantic-GT-10D-N-S2","Martin-Lee-AML-ML41C-SB-w\/VT348RD","Martin-Lee-AML-ML41C-N-w\/VT348RD","Pastel-K-154","Pastel-Siamkey61","Yamaha-THR30-II-Wireless","Yamaha-THR10-II-Wireless","Yamaha-FS830-DSR","Yamaha-FS830-NT","Blackstar-Sonnet-120-Black","Yamaha-Pacifica112J-Red-Metallic","Gibson-Logo-T-Shirt-Size-M","Gibson-Logo-T-Shirt-Size-S","Century-CE-A384-LH-SB-S1","Century-CE-A384-LH-BK-S1","Mantic-GT-1GCE-N-Full-Set","Mantic-GT-1GCE-N-S1","Mantic-GT-1DC-R","Mantic-AG-1CSB-S1","Kirlin-IM-202YSG-6M","Kirlin-IM-202RSG-6M","Jackson-JS22-DKA-M-RD","Fantasia-F80-BK-S5","Kazuki-BKZ-KLP-SB","Kazuki-BKZ-NMTL-NOR-w\/Kirlin-IM202RSG-3M","Fender-Fullerton-Tele-Uke-Black","VOX-VCC-Vintage-Coiled-Cable-SV","VOX-VCC-Vintage-Coiled-Cable-RD","VOX-VCC-Vintage-Coiled-Cable-BL","VOX-VCC-Vintage-Coiled-Cable-BK","Yamaha-Tour-Custom-TMP2F4-Licorice-Satin","Yamaha-Tour-Custom-TMP2F4-Chocolate-Satin","Yamaha-TMS1465-Licorice-Satin","Yamaha-TMS1465-Chocolate-Satin","Yamaha-TMS1465-Caramel-Satin","Yamaha-TMS1465-Candy-Apple-Satin","Yamaha-Tour-Custom-TMP2F4-Candy-Apple-Satin","Yamaha-TMS1455-Licorice-Satin","Yamaha-TMS1455-Chocolate-Satin","Yamaha-TMS1455-Caramel-Satin","Fantasia-F80-N-S5","Ernie-Ball-VPJR-Tuner-Pedal-SV-P06201","Ernie-Ball-VPJR-Tuner-Pedal-RD-P06202","Ernie-Ball-VPJR-Tuner-Pedal-BK-P06203","Squier-Bullet-Mustang-HH-SNG","Kazuki-KZ38C-WH-S1","Fender-Player-Strat-SSS-MN-Tidepool","Kazuki-KZ38C-WH","Fender-Player-Strat-HSS-Silver","Mantic GT-1D-R-S1","Lirevo-DPA-400-BK","Lirevo-DPA-800-BK","Fender-Deluxe-Nashville-Tele-Fiesta-Red-0147503340","Yamaha-Tour-Custom-TMP2F4-Caramel-Satin","Shure-SM57","Yamaha-THR10-II","Yamaha-Stage-Custom-Birch-SBP2F5-Cranberry-Red","Yamaha-Stage-Custom-Birch-SBP2F5-Honey-Amber","Yamaha-Stage-Custom-Birch-SBP2F5-Natural-Wood","Yamaha-Stage-Custom-Birch-SBP2F5-Pure-White","Schecter-C-1-SLS-Elite-Antique-Fade-Burst","Yamaha-FG830-AB-S1","Fender-Neck-Plate-0991445100","Paramount-JBB","Yamaha-TRBX305-MGR","Paramount-DR-003-BK","Yamaha-TRBX304-WH-White","Paramount-JBC","Roland-SPD-SX-SE","KRK-Rokit-RP8-G4-WH"];
//
//    for($i=0; $i<sizeof($skuList); $i++)
//    {
//        $sku = $skuList[$i];
//        $sql = "insert into webskutodelete (sku) values('$sku')";
//        $ret = doQueryTask($con,$sql,$modifiedUser);
//        if($ret != "")
//        {
//            writeToLog("INSERT INTO webskutodelete fail [sku]:[$sku]");
//            echo $sku . "<br>";
//        }
//    }
//    exit();
    
    
    
    //update mainproducttemp image
    $sql = "select Sku from websku where status = 1 or status = -1";
    $skuList = executeQueryArray($sql);
    for($i=0; $i<sizeof($skuList); $i++)
    {
        $mainImage = "";
        $image2 = "";
        $image3 = "";
        $image4 = "";
        $image5 = "";
        $image6 = "";
        $image7 = "";
        $image8 = "";
        $sku = $skuList[$i]->Sku;
        $lazadaProduct = getLazadaProduct($sku);
        for($j=0; $j < sizeof($lazadaProduct->skus[0]->Images); $j++)
        {
            if($j == 0)
            {
                $mainImage = $lazadaProduct->skus[0]->Images[$j];
            }
            else if($j == 1)
            {
                $image2 = $lazadaProduct->skus[0]->Images[$j];
            }
            else if($j == 2)
            {
                $image3 = $lazadaProduct->skus[0]->Images[$j];
            }
            else if($j == 3)
            {
                $image4 = $lazadaProduct->skus[0]->Images[$j];
            }
            else if($j == 4)
            {
                $image5 = $lazadaProduct->skus[0]->Images[$j];
            }
            else if($j == 5)
            {
                $image6 = $lazadaProduct->skus[0]->Images[$j];
            }
            else if($j == 6)
            {
                $image7 = $lazadaProduct->skus[0]->Images[$j];
            }
            else if($j == 7)
            {
                $image8 = $lazadaProduct->skus[0]->Images[$j];
            }
        }
        $sql = "update mainproducttemp set mainImage = '$mainImage', image2 = '$image2', image3 = '$image3', image4 = '$image4', image5 = '$image5', image6 = '$image6', image7 = '$image7', image8 = '$image8' where sku = '$sku'";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            writeToLog("update mainproduct fail [sku]:[$sku]");
//            echo $sku . "<br>";
        }
//        break; //test
    }
    exit();
    //update mainproducttemp image
    
    
//    $start = $_GET["start"];
//    $end = $_GET["end"];
//    $skuProblemList = array();
//    $sql = "select * from mainProduct where productid between $start and $end";
//    $mainProductList = executeQueryArray($sql);
//    for($i=0; $i<sizeof($mainProductList); $i++)
//    {
//        $sku = $mainProductList[$i]->Sku;
//        $mainImage = $mainProductList[$i]->MainImage;
//        $lazadaProduct = getLazadaProduct($sku);
//        if($lazadaProduct)
//        {
//            if($lazadaProduct->skus[0]->Images[0] != $mainImage)
//            {
//                $skuProblemList[] = $sku;
//            }
//        }
//
//    }
//    echo json_encode($skuProblemList);
//    exit();
    
    
    
    
//    $sku = "Century-DST-WH-S1";
//    $c = getApiManager();
//    $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
//    $c->param_json = '{"searchSkusByOuterIdParam":{"outerId":"' . $sku . '"}}';
//    $resp = $c->call();
//
//
//    echo $resp;
//    exit();
//    $productId= 13492975;
//    $skuId = 13492976;
//    $productStatus = 0;
//    $skuStatus = 0;
//    echo updateSkuStatus($productId,$productStatus,$skuId,$skuStatus);
//exit();
    
//    13493832,13493842
    
//    $skuId = 13492976;
//    echo deleteJdSku($skuId);
//    exit();
    
    
    $productId = 13492975;
    echo deleteJdProduct($productId);
    exit();
    
    
    
    
    
    $sql = "select Sku from mainproduct where productID >= 6456";
    $skuList = executeQueryArray($sql);
    echo json_encode($skuList);
    exit();
 
//
//    //***** delete product
//    DELETE from posttodelete;
//    insert into posttodelete (postid) SELECT ID FROM wp_posts product LEFT JOIN wp_postmeta as product_sku ON product.ID = product_sku.post_id WHERE post_type='product' and post_date>'2020-10-01 00:00:00' and product_sku.meta_key = '_sku' and product_sku.meta_value in (select sku from websku where webskuid between 1 and 100);
//
//
//    DELETE relations.*, taxes.*, terms.*
//    FROM wp_term_relationships AS relations
//    INNER JOIN wp_term_taxonomy AS taxes
//    ON relations.term_taxonomy_id=taxes.term_taxonomy_id
//    INNER JOIN wp_terms AS terms
//    ON taxes.term_id=terms.term_id
//    WHERE object_id  in (select postid from posttodelete);
//    DELETE FROM wp_postmeta WHERE post_id IN (select postid from posttodelete);
//    DELETE from wp_posts where id in (select postid from posttodelete);
//    //***** delete product
//
    
    
    
    
//    SELECT ID FROM wp_posts product LEFT JOIN wp_postmeta as product_sku ON product.ID = product_sku.post_id WHERE post_type='product' and post_date>'2020-10' and product_sku.meta_key = '_sku' and product_sku.meta_value in ('Tc-Electronic-Flashback-2-Delay')
    
    
 
    
    
//    SELECT count(*) FROM wp_posts WHERE post_type='product' and post_date>'2020-10'
//
//    SELECT count(*) FROM wp_posts product LEFT JOIN wp_postmeta as product_sku ON product.ID = product_sku.post_id WHERE post_type='product' and post_date>'2020-10' and product_sku.meta_key = '_sku' and product_sku.meta_value in (select sku from websku)
    
    
//    $filenameList = ["AKAI-Fire-Midi-Controller","AKAI-MPK-mini-MKII-BK","AKAI-MPK-mini-MKII","AROMA-AG10A-FCS","AROMA-AG10A","AROMA-AG10BK","AROMA-AGS01","AROMA-AL1","AROMA-AM706BK","AROMA-AMT500BK","AROMA-AMT560","AROMA-AT101","AROMA-AT200D-FBAT","AROMA-AT200DB","AROMA-AT200DE","AROMA-AUS02-VL","AROMA-AUS02","Alctron-CU28","Alctron-CU58-Pro","Alctron-CU58","Alctron-EPP005-X2","Alctron-EPP007-X2","Alctron-K5","Alctron-MA601","Alctron-MC001","Alctron-MS140","Alctron-MS180-5","Alctron-UM900","Alesis-ASP-2","Alesis-Command-Mesh-Kit-S1","Alesis-Nitro-Mesh-Kit","Alesis-Sample-Pad-Pro","Alesis-Strike-Kit-S1","Angel-AE-555-MRD-MF-VT","Angel-AG591-CE-NM","Arborea-AP-C18","Arborea-AP-CH18","Arborea-AP-H14","Arborea-AP-R20","Arborea-AP-S10","Arborea-ASB-5B","Arborea-ASB-7A","Arborea-B8-10","Arborea-B8-12","Arborea-B8-14H","Arborea-B8-16","Arborea-B8-16CH","Arborea-FH8","Arborea-FJB-450","Arborea-HB10","Arborea-HB12","Arborea-HB18","Arborea-HB20","Arborea-HR-SET14","Arborea-HR10","Arborea-HR12","Arborea-HR14H","Arborea-HR18","Arborea-HR8","Arborea-HRMG12","Arborea-HRMG14","Arborea-HRMG16","Arborea-HRMG18","Arborea-HRMG20","Arborea-KT22MR","Arborea-VK10","Arborea-VK14","Aroma-AG15A","Aroma-AH85","BJ2","BM-32KH-BL","BOSS-FV-50L","BOSS-GT1-FPSA230S","BOSS-Kanata-Artist","BOSS-MT-2W","BOSS-OD-1X","BOSS-OD-3","BOSS-PSA-230S","BOSS-RC-505","BOSS-VE-20-FAD","BOSS-VE-500","BOSS-VO-1","BOSS-WAZA-AIR","Baracuda-OM-300EQ-S1","Belcat-BS-06A-N-BK-wBelcat-BS-06A-M-BK-wBelcat-BS-06A-B-BK","Belcat-BT-101BK","Belcat-DST501-FAD","Belcat-IRIS2","Belcat-OVD502","Belcat-PST-10","Belcat-SH-85","Bespeco-KG10","Bespeco-Primo","Bespeco-SH12NE","Beyerdynamic-DT-770-Pro-250Ohm","Blackstar-FLY-3-Acoustic","Blackstar-FLY-3-Bass-Stereo-Pack","Blackstar-FLY-3-Black","Blackstar-FLY-3-PSU-1","Blackstar-FLY-3-Stereo-Pack-Black","Blackstar-FLY-3-Stereo-Pack-Vintage","Blackstar-FLY-3-Sugar-Skull-PSU-1","Blackstar-FLY-3-Sugar-Skull","Blackstar-FLY-3-Union-Flag-PSU-1","Blackstar-FLY-3-Union-Flag","Blackstar-FS-11","Blackstar-Fly-3-Bass-Mini-Amp","Blackstar-Fly-3-Bluetooth","Blackstar-HT-112OC-MKII-1x12-Cabinet","Blackstar-HT-5RH-MKII-Valve-Head","Blackstar-HT-Club-40-MKII","Blackstar-ID-Core-Stereo-100","Blackstar-ID-Core-Stereo-10","Blackstar-ID-Core-Stereo-150","Blackstar-ID-Core-Stereo-20","Blackstar-ID-Core-Stereo-40","Blackstar-LT-Echo-10","Blackstar-PSU-1","Blake-ABS-WC501-RD","Blake-Deluxe-BC501-BK_3.png","Blake-Deluxe-EC501-BK_1.png","Blake-Deluxe-EC501-CB_3.png","Blake-GCJ601","Blake-LSB100","Blue-Microphones-Yeti-Studio-Blackout","Bongo-Stand-BGS-10","Boss-AW-3","Boss-BC-2","Boss-EQ-200","Boss-FS-6","Boss-GT-1000","Boss-GT-100","Boss-GT-1B","Boss-KATANA-100-MkII","Boss-OS2","Boss-SY-1","Boss-TU-3W","Boss-VE-2","Brook-S25GDCGEQ","Brook-S25NACGEQ","Brook-S25NDCGEQ","Bullet-AC15C-BR","Bullet-AC15C-WH","Bullet-AC45R-BR","Bullet-BB20BK","Bullet-BT-150R-212-BK","Bullet-BT-150R-212-WH","Bullet-BT-150R-412-BK","Bullet-BT-150R-412-WH","Bullet-BT20-WH","Bullet-DA15T-BK","Bullet-DA15T-WH","Bullet-DA20T-BK-REVB","Bullet-DA20T-WH","Bullet-DA20T","CAD-Audio-AS16","CARLSBRO-CSD110P","CARLSBRO-CSD120-EDA30","CARLSBRO-CSD210-EDA30","CARLSBRO-CSD210P","CARLSBRO-CSD230-EDA30","CARLSBRO-CSD230P","CARLSBRO-CSD310P","CARLSBRO-CSD500P","CMC-CM-HH900","CMC-CM-Stand-107","CMC-CM-TP-913","CMC-CMBEAT02","CMC-CMDP602","CMC-CMSHK-101PA-BK","CMC-CMSHK-101PA-GR","CMC-CMSHK-101PA-PK","CMC-CMSHK-101PA-PR","CMC-CMSHK-101PA-RD","CMC-CMSHK-101PA-YL","CMC-CMSP400","CMC-CMSP600","CMC-DKEY1","CMC-LP236A","CMC-Prelude-Snare-Ultra-White","COCO-UK2142A-FTN20U","CPX500II-DRB-FBA","CPX500II-DRB","Calao-CL5089","Caline-C300","Caline-CP-17","Caline-CP-19","Caline-CP-22","Caline-CP-24","Caline-CP-25","Caline-CP-26","Caline-CP-30","Caline-CP-31","Caline-CP-40","Caline-CP33","Caline-S6","Caline-Scuru-S1-Mini-Bass-Amp","Carlsbro-BC334-2M","Carlsbro-BC351-2M","Carlsbro-BC356-6M","Carlsbro-BC748-3M","Carlsbro-BC748-6M","Carlsbro-BJJ003-3M","Carlsbro-BJJ004-6M","Carlsbro-BJJ012T-3M","Carlsbro-BJJ012T-6M","Carlsbro-BJJ024-6M","Carlsbro-BJJ031-3M","Carlsbro-BJJ032-3M","Carlsbro-BJJ032-6M","Carlsbro-BJJ033B-6M","Carlsbro-BJJ033T-3M","Carlsbro-BPA2","Carlsbro-BXJ007A-10M","Carlsbro-BXJ007A-5M","Carlsbro-BXJ013A-10M","Carlsbro-BXJ016A-10M","Carlsbro-BXJ016A-5M","Carlsbro-BXX001A-10M","Carlsbro-CC310","Carlsbro-CC320","Carlsbro-CC321","Carlsbro-CEN15","Carlsbro-CHR32","Carlsbro-CSD110-EDA50","Carlsbro-CSD110-FCSS1","Carlsbro-CSD110-FDCN2","Carlsbro-CSD120-DCN2-T1D","Carlsbro-CSD120-FCSS1","Carlsbro-CSD120-FDCN2","Carlsbro-CSD120-S1","Carlsbro-CSD130-CSS1","Carlsbro-CSD210-FCSS1","Carlsbro-CSD230-FDCN2","Carlsbro-CSD310","Carlsbro-CSD500-CEN15","Carlsbro-CSD500-FCSS1","Carlsbro-CSD500-FDCN2","Carlsbro-CSD500","Carlsbro-CSS2","Carlsbro-CY10S-wBC351-2M","Carlsbro-CY10S","Carlsbro-CY12D-ST","Carlsbro-CY12D","Carlsbro-DCN2","Carlsbro-DCN8","Carlsbro-DD130","Carlsbro-DF075","Carlsbro-DF1521-REVB","Carlsbro-DF1521","Carlsbro-DG077BK","Carlsbro-DG077YL","Carlsbro-DG089A","Carlsbro-DG096A-RD","Carlsbro-DG096B","Carlsbro-DH004","Carlsbro-DM7","Carlsbro-DRUM10D","Carlsbro-DRUM8B-ST-wBC344-2M","Carlsbro-DRUM8B-ST","Carlsbro-OVD29","Carlsbro-PS301SV","Carlsbro-SD207","Carlsbro-SF507","Carlsbro-SG103","Carlsbro-csd110","Carlsbro-csd120-eda50","Carlsbro-csd120","Carlsbro-csd130A","Carlsbro-csd130","Carlsbro-csd230-FCSS1","Carlsbro-csd230-eda50","Carlsbro-csd230","Casio-CDP-S100","Casio-CDP-S350","Casio-CT-X800-S1","Casio-CTK-1550-INT","Casio-CTK1550","Casio-PX160","Centent-TD-12S","Centent-TD-18C","Centent-XTT-16C","Centent-XTT-17CH","Centent-XTT-17C","Centent-XTT-17Z","Centent-XTT-18CH","Centent-XTT-18C","Centent-XTT-19C","Centent-XTT-8S","Century-CB-22-5-SB-S2","Century-CB-22-BK-S2","Century-CB-22-BU-S2","Century-CB-22-IV-S2","Century-CB-22-SB-S2","Century-CB-22-SC-S2","Century-CE-A38-BK-S2","Century-CE-A38-IV-S2","Century-CE-A38-LH-BK-S2","Century-CE-A38-LH-SB-S2","Century-CE-A38-MBL-S2","Century-CE-A38-SB-S2","Century-CE-A38-SC-S2","Century-CE-A384-BK-Metallic-Black-S2","Century-CE-A384-BN-Metallic-Brown-S2","Century-CE-A384-MRD-Metallic-Red-S2","Century-CE-A384-SB-Sunburst-S2","Century-CE-A384-SC-Cherry-Sunburst-S2","Century-CE-A384-SG-Gold-S2","Century-CEB10","CenturyCE-A384-LH-BK","CenturyCE-A384-LH-SB","Chaser-RZ77M-RD","Cherub-DT-10","Cherub-DT20","Cherub-WTB006","CherubWST-670","Clevan-C10","Clevan-CBJ5-30-MD40BJ","Clevan-CTD20-BK","Clevan-D22S-BK-S1","Clevan-D25S","Clevan-DC20E-12","Coco-TIM15CBR","Coco-TIM15GBR","Coco-TIM72CBR","Coco-TIM72GBR","Coco-UK2123A","Coco-UK2142A","Coco-UK241","Coco-UK242A","Coco-UK2613A","Coco-UK261","Coco-UK262A","Cuvave-Cube-Sugar","DATDR-1NGB545","DB-Blake-Deluxe-VTFAC300-BR.png","DB-Blake-Deluxe-VTFAC300-BR_1.png","DB-Blake-Deluxe-VTFAC300-BR_2.png","DB-Blake-Deluxe-VTFAC300-BR_3.png","DB-Blake-Deluxe-VTFAC300-BR_4.png","DB-Blake-Deluxe-VTFAC300-WD.png","DB-Blake-Deluxe-VTFAC300-WD_1.png","DB-Blake-Deluxe-VTFAC300-WD_2.png","DB-Blake-Deluxe-VTFAC300-WD_3.png","DB-Blake-Deluxe-VTFAC300-WD_4.png","DB-CBC-SET01","DB-CBC-SET03","DB-CW-01","DB-CW-01B","DR-Strings-B45-DATDR-1FB45","DR-Strings-BA-10-DATDR-3BA10","DR-Strings-FB40-DATDR-1FB40","DR-Strings-FB5-45-DATDR-1FB545","DR-Strings-MR-45-DATDR-1MR45","DR-Strings-MR5-45-DATDR-1MR545","DR-Strings-NEON-NBE-10","DR-Strings-NEON-NBE-9","DR-Strings-NEON-NGE-10","DR-Strings-NEON-NGE-9","DR-Strings-NEON-NMCE-9","DR-Strings-NGB-45-DATDR-1NBB45","DR-Strings-NGB5-40-DATDR-1NGB540","DR-Strings-NMCB-45-DATDR-1NMCB45","DR-Strings-NMCB6-30-DATDR-1NMCB630","DR-Strings-NOB-45-DATDR-1NOB45","DR-Strings-NOB5-40-DATDR-1NOB540","DR-Strings-NOB5-45-DATDR-1NOB545","DR-Strings-NPB-40-DATDR-1NPB40","DR-Strings-NPB5-45-DATDR-1NPB545","DR-Strings-NSA-DATDR-3NSA","DR-Strings-NUSAB-40-DATDR-1NUSAB40","DR-Strings-NWB-45-DATDR-1NWB45","DR-Strings-NYB-45-DATDR-1NYB45","DR-Strings-PB-40-DATDR-1PB40","DR-Strings-PB-45100-DATDR-1PB45100","DR-Strings-SIB5-45-DATDR-1SIB545","DR-Strings-UMCSC-DATDR-3UMCSC","DSTB-WY1002A-BL","DSTB-WY1002A-RD","DW-2002P","DW-3002PT","DW-5002","DW-9002P-P","DW-9002","Daddario-1CWH2-10B6","Daddario-4CSH4","Daddario-50BAL00","Daddario-50BAL01","Daddario-50BAL02","Daddario-50BAL03","Daddario-50BAL04","Daddario-50BAL06","Daddario-American-Stage-15FT-PW-AMSG-15","Daddario-American-Stage-PW-AMSG-20","Daddario-American-Stage-PW-AMSGRA-20","Daddario-Classic-PW-CGT-10","Daddario-Core-BL","Daddario-Core-BR","Daddario-Core-PK","Daddario-Core-RD","Daddario-Core-YL","Daddario-DP0002-Black","Daddario-ECB81-5","Daddario-EJ10","Daddario-EJ13","Daddario-EJ16","Daddario-EJ17","Daddario-EJ19","Daddario-EJ40","Daddario-EJ41","Daddario-EJ45","Daddario-EJ46LP","Daddario-EJ46TT","Daddario-EJ49","Daddario-EJ62","Daddario-EPS165-5","Daddario-EPS170-6","Daddario-EPS170","Daddario-EPS220","Daddario-EXL165","Daddario-EXL165TP","Daddario-EXL170-5","Daddario-EXL170","Daddario-EXL170TP","Daddario-EXL190","Daddario-EXL220-5","Daddario-EXL220","Daddario-EXL230","Daddario-EXP10","Daddario-EXP110","Daddario-EXP11","Daddario-EXP120","Daddario-EXP15","Daddario-EXP170-5","Daddario-EXP220","Daddario-EZ890-REVB","Daddario-EZ890","Daddario-EZ900","Daddario-EZ910","Daddario-EZ920","Daddario-EZ940","Daddario-Fret-Polishing-System","Daddario-Guitar-Care-Set","Daddario-Guitar-Rest-PW-GR-01","Daddario-Lemon-Oil-","Daddario-Lock-Strap-50PLA02","Daddario-Lock-Strap-50PLA04","Daddario-Lock-Strap-50PLA05","Daddario-XTE0942","Daddario-XTE0946","Daddario-XTE1046","Daddario-XTE1052","DaddarioEZ890-MD100BK-P039-POS-Pick","Danchoo-DFR-1-PK","Dandrea-Lemon-Oil-DCLDA-DAL2","Danmar-206CK-PK","Danmar-206CK-RD","Dr-Strings-NBA-11","Dr-Strings-NBA-12","Dr-Strings-NGA-12","Dr-Strings-NMCA-11","Dr-Strings-NOA-11","Dr-Strings-NOA-12","Dr-Strings-NRA-11","Dreammaker-BDM-IB6-BLS","Drum-Bag-FT9","Drum-Bag-RT9","Dunlop-Cloth-Blue","Dunlop-DAB1048","EBS-MicroBass-3","ESI-U22-XT-cosMik-Set","Echopack-ECP10-BK","Echopack-ECP10-BR","Echoslap-C16B","Echoslap-DECS-EGGWOOD","Echoslap-GFX-CD","Echoslap-GFX-LEO","Echoslap-GFX-LOVE","Echoslap-VC201-UK","Elixir-Nanoweb-Acoustic-Custom-Light","Elixir-Nanoweb-Bass-Light-5","Elixir-Nanoweb-Bass-Light-Medium-14077","Elixir-Nanoweb-Bass-Light","Elixir-Nanoweb-Electric-Super-Light","Epiphone-Bag-EPB20-VIP","Epiphone-DR100BK","Epiphone-DR100N","Epiphone-DR100SB","Epiphone-E-J200-Coupe-N","Epiphone-E-J200-Coupe-WR","Epiphone-EB-3-Cherry","Epiphone-EB-3-Ebony","Epiphone-EL-00-Pro-Vintage-Sunburst","Epiphone-EPB20E-VIP","Epiphone-El-Nino","Epiphone-Hummingbird-Pro-Cherry-Burst","Epiphone-LP-Special-VE-EB-ENSVEBVCH1-S1","Epiphone-LP-Special-VE-SB-ENSVVSVCH1","Epiphone-LP-Special-VE-WLV-ENSVWLVCH1-S1","Epiphone-LP-Special-VE-WLV-ENSVWLVCH1","Epiphone-LP-Standard-50s-Vintage-Sunburst","Epiphone-LP-Standard-60s-Bourbon-Burst","Epiphone-LP-Standard-60s-Ebony","Epiphone-LP-Studio-LT-CS","Epiphone-LP-Studio-LT-SB","Epiphone-LP-Studio-LT-WLV","Epiphone-Les-Paul-SL-EB-ENOLEBCH1","Epiphone-Les-Paul-SL-ENOLHSCH1","Epiphone-Les-Paul-SL-PBL-EB-ENOLPACH1","Epiphone-Les-Paul-SL-VSB-ENOLVSCH1","Epiphone-Les-Paul-SL-YS-ENOLSYCH1","Epiphone-LesPaul-Custom-Ebony","Epiphone-Lil-Tex","Epiphone-PRO-1-BK-EAPREBCH1-SET","Epiphone-PRO-1-BK-EAPREBCH1","Epiphone-PRO-1-BL-SET","Epiphone-PRO-1-BL","Epiphone-PRO-1-N-EAPRNACH1-SET","Epiphone-PRO-1-SB-EAPRVSCH1","Epiphone-Pro-1-Classic","Epiphone-SG-Special-VE-CS-EGSVHSVCH1","Ernie-Ball-Braided-Cable-Black-Neon-Orange-P06067","Ernie-Ball-Braided-Cable-Red-Blue-White-P06063","Ernie-Ball-Burly-Slinky-P02226","Ernie-Ball-Cable-10FT-BK-P06048","Ernie-Ball-Cable-10FT-WH-P06049","Ernie-Ball-Cable-20FT-BK-P06046","Ernie-Ball-Camouflage-Pick-Heavy","Ernie-Ball-Camouflage-Picks-S1","Ernie-Ball-Classic-Jacquard-P04097","Ernie-Ball-Classic-Jacquard-P04142","Ernie-Ball-Classic-Jacquard-P04162","Ernie-Ball-Classic-Jacquard-P04165","Ernie-Ball-Classic-Jacquard-P04167","Ernie-Ball-Classic-Jacquard-P04667","Ernie-Ball-Cobalt-Power-Slinky-P02720","Ernie-Ball-Cobalt-Regular-Slinky-P02721","Ernie-Ball-Coiled-Cable-Black","Ernie-Ball-Coiled-Cable-White","Ernie-Ball-Earthwood-12-String-Light-P02010","Ernie-Ball-Earthwood-Extra-Light-P02006","Ernie-Ball-Ernesto-Palla-Black-and-Silver-P02406","Ernie-Ball-Hybrid-Slinky-Bass-P02833","Ernie-Ball-Mega-Slinky-P02213","Ernie-Ball-Mono-Female-Jack-6","Ernie-Ball-Not-Even-Slinky-P02626","Ernie-Ball-P01009-X5","Ernie-Ball-P02021-Paradigm-Regular-Slinky","Ernie-Ball-P02023-Paradigm-Super-Slinky","Ernie-Ball-P02086-Paradigm-Bronze-80-20-Medium-Light","Ernie-Ball-P02088-Paradigm-Bronze-80-20-Light","Ernie-Ball-P02090-Paradigm-Bronze-80-20-Extra-Light","Ernie-Ball-P02211","Ernie-Ball-P02224","Ernie-Ball-P02230","Ernie-Ball-P02734","Ernie-Ball-P02736","Ernie-Ball-P02921","Ernie-Ball-Polypro-P04044-Rainbow","Ernie-Ball-Polypro-P04047-WR","Ernie-Ball-Polypro-P04048-GA","Ernie-Ball-Polypro-P04050-GE","Ernie-Ball-Polypro-P04052-BR","Ernie-Ball-Power-Slinky-2220","Ernie-Ball-Power-Slinky-RPS-P02242","Ernie-Ball-Prodigy-Black-set-6-P09342","Ernie-Ball-Prodigy-Large-Shield-white-P09338","Ernie-Ball-Prodigy-Sharp-Black-P09335","Ernie-Ball-Prodigy-Sharp-white-P09341","Ernie-Ball-Prodigy-Shield-Black-P09331","Ernie-Ball-Prodigy-Teardrop-black-P09330","Ernie-Ball-Prodigy-Teardrop-white-P09336","Ernie-Ball-Prodigy-white-set-6-P09343","Ernie-Ball-Prodigy-white-set-6-P09343_4.png","Ernie-Ball-Strap-Blocks-P04603","Ernie-Ball-Super-Glow-S1","Ernie-Ball-Super-Lock-BK-P04601","Ernie-Ball-Super-Slinky-2223","Ernie-Ball-Tele-Knobs-CR","Ernieball-Earthwood-Light-P02004","Evans-B14G2","Evans-B14HBG","Evans-B14UV1","Evans-BD22EMADON","Evans-BD22HBG-BK","Evans-BD22HR","Evans-EPP-EC2SHDD-R","Evans-EPP-HRUV1-R","Evans-EQPB2","Evans-ETP-G2CLR-R","Evans-ETP-G2CTD-R","Evans-TT14G2","F100BK-FBA-UP1","F100BL-FBA-UP1","F100SB-FBA-UP1","Fantasia-AW392N","Fantasia-AW410SB","Fantasia-C41BK-FAT200D","Fantasia-C41BK-FMB07","Fantasia-C41N-FMB07","Fantasia-C41N-S1","Fantasia-C41N","Fantasia-C41RD-FAT200D","Fantasia-C41RD-FMB07","Fantasia-C41RD","Fantasia-C41SB-FAT200D","Fantasia-C41SB-FMB07","Fantasia-DGM-10CBK","Fantasia-DGM10CBS-FBA","Fantasia-EA12EBK","Fantasia-EA12EN-FBA","Fantasia-EA12EN","Fantasia-EA12ESB-FBA","Fantasia-EA12ESB","Fantasia-EA12EWH-FBA","Fantasia-EA12EWH","Fantasia-EB16S-FAT101","Fantasia-EB16S","Fantasia-F100BK-FAT101-FPK","Fantasia-F100BK-FMB07-FCP2-FPK","Fantasia-F100BK-S1","Fantasia-F100BK","Fantasia-F100BL-FAT101-FPK","Fantasia-F100BL-FAT200D","Fantasia-F100BL-FBA01","Fantasia-F100BL-FMB07-FCP2-FPK","Fantasia-F100BL-S1","Fantasia-F100BL-S2","Fantasia-F100BL","Fantasia-F100N-AGS01","Fantasia-F100N-FBA01","Fantasia-F100N-FMB07-FCP2-FPK","Fantasia-F100N-S1","Fantasia-F100N-S2","Fantasia-F100N","Fantasia-F100SB-FAT101-FPK","Fantasia-F100SB-FMB07-FCP2-FPK","Fantasia-F101-BK-S1","Fantasia-F101-BK-S2","Fantasia-F101-BK","Fantasia-F101-BWN-S1","Fantasia-F101-BWN-S2","Fantasia-F101-BWN","Fantasia-F101-N-S1","Fantasia-F101-N-S2","Fantasia-F101-N","Fantasia-F200N","Fantasia-F80-BK-S1-REV","Fantasia-F80-BK-S1","Fantasia-F80-BK-S2","Fantasia-F80-BK-S3","Fantasia-F80-BK-S4","Fantasia-F80-BK","Fantasia-F80-BL-S1-REV","Fantasia-F80-BL-S1","Fantasia-F80-BL","Fantasia-QAG401GBK-S2","Fantasia-QAG401GN-S2","Fantasia-QAG411M-BK-S1","Fantasia-QAG411M-BK","Fantasia-QAG411M-N-S1","Fender-5-Way-Switch-0991367000","Fender-500K-Split-Shaft-Potentiometer-0990834000","Fender-50th-Woodstock-Picks","Fender-65-Twin-Reverb-Amp","Fender-70CL","Fender-7250-5M","Fender-7250ML","Fender-Acoustic-100","Fender-Acoustic-200","Fender-Aerodyne-II-Strat-HSS-Black","Fender-Aerodyne-II-Strat-HSS-Candy-Apple-Red","Fender-Affinity-Strat-HSS-WH-0370700505","Fender-Blues-Deluxe-40W-AMP","Fender-Blues-Deluxe-7-Pack-0990701049","Fender-Blues-Deluxe-KA","Fender-Blues-Deluxe-KC","Fender-Blues-Deluxe-KD","Fender-Blues-Deluxe-KE","Fender-Blues-Deluxe-KF","Fender-Blues-Deluxe-KG","Fender-Blues-Deville-Harmonica-Key-A","Fender-Blues-Deville-Harmonica-Key-C","Fender-Blues-Deville-Harmonica-Key-D","Fender-Blues-Deville-Harmonica-Key-G","Fender-CD140SCEN","Fender-CD140SCESB","Fender-CD60CEBK","Fender-CD60N","Fender-CD60S-BK-0961701006","Fender-CD60S-NT-0961701021","Fender-CD60S-NT-S1","Fender-CD60SCEBK","Fender-Electric-Bass-Gig-Bag-FB610","Fender-Electric-Guitar-Gig-Bag-FE1225","Fender-Electric-Guitar-Gig-Bag-FE610","Fender-FA-125-N","Fender-FA-125CE-N-S1","Fender-FA-125CE-N","Fender-FA100-REVB","Fender-FA100","Fender-FA1225","Fender-FA405","Fender-FA620","Fender-FAS-610","Fender-FC-100","Fender-FE620","Fender-FU610","Fender-GT-100","Fender-Guitar-Wall-Hanger-BK","Fender-Guitar-Wall-Hanger-SB","Fender-Hot-Rod-Harmonica-Key-A","Fender-Mahogany-Black-Top-Strat-HHH-Olympic-White","Fender-Malibu-California-Aqua-Splash-0970722008","Fender-Malibu-California-Jetty-Black-0970722006","Fender-Mustang-GT-200","Fender-Mustang-GTX50","Fender-Mustang-I-V2","Fender-Mustang-LT50","Fender-Newporter-Candy-Apple-Red-0970743009","Fender-Newporter-Champagne-0970743044","Fender-Newporter-Jetty-Black-0970743006","Fender-Performance-Patch-Cable-15CM-X2","Fender-Phoenix","Fender-Pickup-Screws-0994925000","Fender-Player-Jazz-Bass-V-PF-White-0149953515","Fender-Player-Jazzmaster-SB-0146903500","Fender-Redondo-Candy-Apple-Red-0970713509","Fender-Redondo-Jetty-Black-0970713506","Fender-Roadhouse-Strat-Classic-Copper-0147302384","Fender-Roadhouse-Strat-Olympic-White-0147302305","Fender-Roadhouse-Strat-Sunburst-0147300300","Fender-Rumble-100","Fender-Rumble-15","Fender-Rumble-40","Fender-Rumble-500","Fender-Rumble-Studio-40","Fender-Rumble25","Fender-Running-Logo-Strap","Fender-Sonoran-Candy-Apple-Red","Fender-Sonoran-Surf-Green","Fender-Speed-Slick-Cleaner-REVB","Fender-Speed-Slick-Cleaner","Fender-Standard-Frets-0991998000","Fender-Strap-Black-Poly-Lightning","Fender-Strap-Black-Poly-Red","Fender-Strap-Black-Poly-White","Fender-Strap-Black-Poly-Yellow","Fender-Strap-Blocks-0990819000","Fender-Strap-Locks-Black-0990690006","Fender-Strap-Locks-Chrome-0990690000","Fender-Strat-Accessory-Kit-Black","Fender-Strat-Accessory-Kit-Parchment","Fender-Strat-Knobs-AWH-0991369000","Fender-Strat-Knobs-BK-0991365000","Fender-Strat-Knobs-WH-0992035000","Fender-Stratacoustic-BK","Fender-T-Bucket-300CE-Moonlight-Burst","Fender-T-Bucket-300CESB-0968079021","Fender-T-Bucket-400CE","Fender-Turbo-Tune-String-Winder","Fender-Venice-Black","Fender-Venice-Natural","Fender-Zuma-Concert-Ukulele-Lake-Placid-Blue-S1","Fender-Zuma-Concert-Ukulele-Lake-Placid-Blue","Fender-Zuma-Concert-Ukulele-Natural","Fishma-PSY-401","Fishman-INK300","Fishman-ISYS-301-PSY-BAA-AAA","Fishman-OEM-PSY-201-PSY-FAA-QAA","Fishman-PSY-FAA-VAA-revb","Fishman-PSY-FAA-VAA","Fishman-PSY-GAA-QAA","Fitness-MB040L-1","Fitness-MC001","Fitness-MC760E","Fitness-MC760L","Fitness-MC760R","Fitness-MV012B","Fitness-MV012L","Fitness-MV012W","Fitness-MV013WB","FitnessDoubleBas","Flanger-FP-003-BK","Focusrite-Scarlett-18i8-3rd-Gen","Focusrite-Scarlett-2i2-3rd-Gen","Focusrite-Scarlett-2i2-Studio","Focusrite-Scarlett-Solo","G-Force-LMP-1412","GSMINI1FEQ","Gecko-K17BB","Gecko-K17BP","Gecko-K17K","Gecko-K17M","Gecko-MC-BL","Gibson-Les-Paul-Classic-2018-043-01286-EB","Gibson-Les-Paul-Classic-2018-043-01286-PB","Gibson-Les-Paul-Classic-Player-Plus-2018-043-01294-OB","Gibson-Les-Paul-Classic-T-2017-043-01280-1GT","Gibson-Les-Paul-Signature-Player-Plus-2018-043-01296-OB","Gibson-Les-Paul-Standard-50s-043-01406-HS","Gibson-Les-Paul-Standard-60s-043-01407-IT","Gibson-PRPG-030","Gibson-PRPR-015","Gibson-PRPR-025","Gibson-PRSK-010","Gibson-PRSK-020","Gibson-PRTK-010","Gibson-PRTK-020","Gibson-PRTK-030","Gibson-PRWA-010","Gibson-PRWA-020","Gibson-Regular-Style-Jet-Black-REVB","Gibson-SEG-700L","Gibson-Slingshot-Strap-Black","Gibson-Strap-Jet-Black","Gibson-The-Classic-Brown-Strap-043-9ASCL-BRN","Gibson-The-Montana-Strap-043-9ASAC-TAN","Gibson-Thunderbird-Bass-2019-043-01604-HC","Gibson-Tortoise-H-BR","Gibson-Tortoise-H-WH","Gibson-Ultra-Lights-Brite-Wires","Gibson-Woven-Gold-Logo","Golden-Cup-ATB001GR","Golden-Cup-ATB001YL","Golden-Cup-ATB002BL","Golden-Cup-ATB002RD","Golden-Cup-ATB002WH","Golden-Cup-JH016","Golden-Cup-JY1502-Tenor-Trombone","Golden-Cup-JYAS1102","Golden-Cup-JYCL1301","Gravity-GKSX2","Gretsch-G5022CBFE","Gretsch-G5022CE","Gretsch-G5022CWFE","Gretsch-G5024E","Gretsch-G5031FT","Gretsch-G9531","Guitar-Bag-DCGT-41-BK","Guitar-Bag-DCGT-41-CR","Guitar-Bag-DCGT-41-LB","Guitar-Bag-DCGT-41-OR","Guitar-Bag-DCGT-41-PK","Guitto-GGC-02-BK","Guitto-GGC-02-SV","Guitto-GGS-04","Guitto-GGS-07","Guitto-GPB-01B","GuittoGSS-01B","HUN-2200D","HUN-41E","HUN-HIC-2B-BK","HUN-HIC-5AFG-BLY","HUN-HIC-5AFG-RDY","HUN-HIC-5AFG-WH","HUN-HIC-5AFG-YL","HUN-HIC-5AST-BL","HUN-HIC-5AST-GR","HUN-HIC-5AST-VL","HUN-HIC-5AST-YL","HUN-HIC-7AST-BL","HUN-HIC-7AST-RD","HUN-HIC-7AST-VL","HUN-HIC-RK-BK","HUN-HIC-SF-N","Hohner-ACE48","Hohner-Amadeus","Hohner-Big-River-Harp-A","Hohner-Big-River-Harp-B","Hohner-Big-River-Harp-C","Hohner-Big-River-Harp-D","Hohner-Big-River-Harp-G","Hohner-Big-River-Harp-Set","Hohner-Blues-Bender-A","Hohner-Bravo-III-120RD","Hohner-Bravo-III-80BK","Hohner-Bravo-III-80RD","Hohner-Bravo-III-96BK","Hohner-Bravo-III-96RD","Hohner-CX12-Gold","Hohner-C","Hohner-Chromonica-48-Set","Hohner-Echo-Tremolo","Hohner-Golden-Melody-Key-A","Hohner-Golden-Melody-Key-Bb","Hohner-Happy-Color-Harp-Blue","Hohner-Hot-Metal-D","Hohner-Hot-Metal-E","Hohner-Hot-Metal-G","Hohner-KM1700","Hohner-Larry-Adler-48C","Hohner-Larry-Adler-64C","Hohner-MEISTERKLASSE","Hohner-MZ2010","Hohner-Marine-Band-1896-A","Hohner-Marine-Band-1896-B","Hohner-Marine-Band-1896-Bb","Hohner-Marine-Band-1896-C","Hohner-Marine-Band-1896-D","Hohner-Marine-Band-1896-E","Hohner-Marine-Band-1896-F","Hohner-Marine-Band-1896-G","Hohner-Meisterklasse-C","Hohner-Melody-Star","Hohner-Ocean-Star","Hohner-Ozzy-Osbourne","Hohner-Remaster-Vol-2-REVB","Hohner-Rocket-Amp-EN","Hohner-Rocket-Amp-KA","Hohner-Rocket-Amp-KE","Hohner-Rocket-Amp-KF","Hohner-Rocket-Amp-KG","Hohner-Rocket-Amp","Hun-3SD-S1","Hun-3SD-S3","Hun-3SD-S4","Hun-3SD-S5","Hun-3SD","Hun-40","Hun-41","IK-Multimedia-iRig-2","IK-Multimedia-iRig-HD-2","IK-Multimedia-iRig-Keys-2-Pro","IK-Multimedia-iRig-Keys-25-USB","IKMultimedia-iRig-Mic-Cast-2","Icon-MB-02","Icon-MB-06","Icon-PF-02","Icon-RF-01","JBP1601A-BL","JC10P","JOYO-ACE30-REVB","JOYO-GEMBOX-II","JOYO-JA03AC","JOYO-JA03LD","JOYO-JA03MT","JOYO-JA03TD","JOYO-JM-90","JOYO-JMD05","JOYO-JT01","JOYO-JW-01","JOYO-PC1","Jackson-Adrian-Smith-SDX-Snow-White-2913054576","Jackson-JS12-Dinky-RW-RD","Jackson-JS1X-RR-Minion-Neon-Yellow-2913334504","Jackson-JS2-Spectra-Metallic-Blue-2919004527","Jackson-JS22-7-DKA-HT-2910132568","Jackson-JS22-Dinky-RW-BK","Jackson-JS22-Dinky-RW-BL","Jackson-JS22-Dinky-RW-N","Jackson-JS22-Dinky-RW-WH","Jackson-JS3-Spectra-Gloss-Black-2919904503","Jackson-JS3-Spectra-Metallic-Red-2919904573","Jackson-JS32-7-DKA-HT-Snow-White-2910113576","Jackson-JS32-DKA-M-Black-2910238503-","Jackson-JS32-Dinky-DKA-Arch-Top-Black-Satin-2910248568","Jackson-JS32-Dinky-DKA-Arch-Top-Natural-Oil-2910138557","Jackson-JS32-Dinky-DKA-Arch-Top-Neon-Orange-2910148580","Jackson-JS32-Dinky-DKA-Arch-Top-Pavo-Purple-2910238552","Jackson-JS32-King-V-2910224577","Jackson-JS32T-BK-2910147586","JoJo-AW460BK","JoJo-AW460N","JoJo-AW460SB","JoJo-AW760BK","JoJo-AW760BL","JoJo-AW760N","Joyo-GEM-BOX-III","Joyo-Gembox-II-BC3283M","Joyo-I-Plug","Joyo-JBA100","Joyo-JBA35","Joyo-JPA-862","Junior-ST112T-N","KM-12289-000-00","KM-14365-001-55","KM-14640-000-55","KM-14910-000-01","KM-14930-011-55","KM-14940-000-55","KM-14941-000-55","KM-14950-000-01","KM-14990-000-55","KM-15010-011-55","KM-15040-000-55","KM-15140-000-01","KM-15590-000-55","KM-15910-000-55","KM-24030-500-55","KM-50500-000-55","KM-85070-500-55","KM-Alto-Sax-Stand-Selmer-14345-000-55","KM-Alto-Sax-Stand-Yamaha-14345-000-55","KM-Jazz-Sax-Stand-14330-000-55","KM-Jazz-Sax-Stand-14335-000-55","KNA-AP-1","KNA-UP-1","KNA-UP-2","KNA-VC-1","KNA-VV-2","KNA-VV-3","KORG-AW-LT100V","KORG-LP-380-WH","KORG-microKEY-Air-25","KORG-microKEY-Air-49","Kawai-KDP110-INT","Kawai-KDP110","Kazoo-USA-BLG","Kazoo-USA-ORG","Kazoo-USA-PKG","Kazoo-USA-RDV","Kazoo-USA-WH","Kazuk-BKZ-TLTW2","Kazuki-41DCE","Kazuki-41DCMG","Kazuki-41OME","Kazuki-AB10E-N","Kazuki-AKZ-ALLSOULOME","Kazuki-All-Soul-DC","Kazuki-All-Soul-D","Kazuki-All-Soul2-GA","Kazuki-AllSOUL-LMDGA","Kazuki-BA39","Kazuki-BKZ-RLST-BK-Black","Kazuki-BKZ-RLST-HRD-Holiday-Red","Kazuki-BKZ-RLST-RPK-Retro-Pink","Kazuki-BKZ-S4H-N","Kazuki-BKZ-SG41-RD","Kazuki-BKZ-ST01BK","Kazuki-BKZ-ST01SB-S1","Kazuki-BKZ-ST01SB","Kazuki-BKZ-ST01WH","Kazuki-BKZ-TLBG-N","Kazuki-BKZ02WH","Kazuki-CKZ-HS24","Kazuki-Chimes-DG-LCH25B","Kazuki-DB41BK-FBA41","Kazuki-DB41N","Kazuki-DB41SB-FBA41","Kazuki-DB41SB","Kazuki-DFKZ03","Kazuki-DGST-HIH","Kazuki-DLKZ41CE-BK-S1","Kazuki-DLKZ41CE-BK-S4","Kazuki-DLKZ41CE-N-S1","Kazuki-DLKZ41CE-N","Kazuki-DLKZ41CN","Kazuki-DST-ST1","Kazuki-DSTG-JP5","Kazuki-EOV41E-BK-S1","Kazuki-EOV41E-BK-S3","Kazuki-EOV41E-BK-S4","Kazuki-EOV41E-BK","Kazuki-EOV41E-BLS-S1","Kazuki-EOV41E-BLS-S3","Kazuki-EOV41E-BLS-S4","Kazuki-EOV41E-N-S1","Kazuki-EOV41E-N","Kazuki-EOV41E-SB-S3","Kazuki-EOV41E-SB","Kazuki-FD5","Kazuki-GOST-TC","Kazuki-Guitar-Bag-BA30","Kazuki-Harmonica-Key-D","Kazuki-Harmonica-Key-F","Kazuki-Harmonica-Key-G","Kazuki-KA-20","Kazuki-KFW39CEN","Kazuki-KJ200N","Kazuki-KNY40CN-S1","Kazuki-KNY41CN-S1","Kazuki-KOV381CEN","Kazuki-KOV381CN","Kazuki-KZ30BK-S1","Kazuki-KZ30N-S1","Kazuki-KZ30N","Kazuki-KZ389CN-FMB05","Kazuki-KZ389CN","Kazuki-KZ38BL-REVB","Kazuki-KZ38C-BK-S1","Kazuki-KZ38C-BK","Kazuki-KZ38C-CS","Kazuki-KZ38C-N","Kazuki-KZ38C-SB-S1","Kazuki-KZ38CS-FMB05-S2","Kazuki-KZ38N-S3","Kazuki-KZ38N","Kazuki-KZ38SB-FMB05-S2","Kazuki-KZ38SB-REVB","Kazuki-KZ38SB","Kazuki-KZ38WR-FMB05-S2","Kazuki-KZ38WR-S3","Kazuki-KZ38WR","Kazuki-KZ38YW-FMB05-S2","Kazuki-KZ38YW-S3","Kazuki-KZ38YW","Kazuki-KZ390BK","Kazuki-KZ390CBK-FBA39","Kazuki-KZ390CBK","Kazuki-KZ390CEBK-FBA39","Kazuki-KZ390CEN-FBA39","Kazuki-KZ390CN-FBA39","Kazuki-KZ39C-CS-S2","Kazuki-KZ39C-CS-S3","Kazuki-KZ39C-MG-S2","Kazuki-KZ39C-N-S2","Kazuki-KZ39C-N-S3","Kazuki-KZ39C-SB-S2","Kazuki-KZ39C-SB-S3","Kazuki-KZ39C-WR-S2","Kazuki-KZ39C-WR-S3","Kazuki-KZ39CEBK-FBA39","Kazuki-KZ39CEBK","Kazuki-KZ39CEN-FBA39","Kazuki-KZ39CEN","Kazuki-KZ39CESB","Kazuki-KZ408CWR-S1","Kazuki-KZ408CWR","Kazuki-KZ409C-BK-S1","Kazuki-KZ409C-BK-S3","Kazuki-KZ409C-BK-SET5","Kazuki-KZ409C-BK","Kazuki-KZ409C-N-S1","Kazuki-KZ409C-N-S3","Kazuki-KZ409C-N-SET5","Kazuki-KZ409C-N","Kazuki-KZ409C-SB-S1","Kazuki-KZ409C-SB-S3","Kazuki-KZ409C-SB-SET5","Kazuki-KZ409C-SB","Kazuki-KZ409C-WR-S1","Kazuki-KZ409C-WR","Kazuki-KZ409CE-BK","Kazuki-KZ409CE-N-Natural","Kazuki-KZ409CE-N-S1","Kazuki-KZ409CE-N","Kazuki-KZ409CE-SB-S1","Kazuki-KZ409CE-SB-Sunburst","Kazuki-KZ409CE-SB","Kazuki-KZ409CE-WR-S1","Kazuki-KZ409CE-WR-WineRed","Kazuki-KZ410BK-FBA41","Kazuki-KZ410BK","Kazuki-KZ410EBK-FBA41","Kazuki-KZ410EBK-S1","Kazuki-KZ410EBK","Kazuki-KZ410N-FBA41","Kazuki-KZ410N","Kazuki-KZ410SB-FBA41","Kazuki-KZ410SB","Kazuki-KZ41CCS-FBA","Kazuki-KZ41CCS-S1","Kazuki-KZ41CCS","Kazuki-KZ41CEBK-S1","Kazuki-KZ41CEBK","Kazuki-KZ41CEN-FBA41","Kazuki-KZ41CEN-S1","Kazuki-KZ41CEN","Kazuki-KZ41CESB-FBA41","Kazuki-KZ41CESB-S1","Kazuki-KZ41CESB","Kazuki-KZ41CEWR-FBA41","Kazuki-KZ41CEWR-S1","Kazuki-KZ41CEWR","Kazuki-KZ41CN-FBA-Brown","Kazuki-KZ41CN","Kazuki-KZ41CSB-FBA","Kazuki-KZ41CWR-FBA41","Kazuki-KZ41CWR-FBA","Kazuki-KZ41CWR","Kazuki-KZ68CN","Kazuki-KZ900C","Kazuki-KZ920C","Kazuki-SOV41E-BLS-S1","Kazuki-SOV41E-BLS-S3","Kazuki-SOV41E-BLS-S4","Kazuki-SOV41E-BLS","Kazuki-SOV41E-N-S1","Kazuki-SOV41E-N-S4","Kazuki-SOV41E-N","Kazuki-SOV41E-RDS-S3","Kazuki-SOV41E-SB","Kazuki-SX01ZCE-N-S1","Kazuki-SX01ZCE-N-S4","Kazuki-SX11ZCE-N-S1","Kazuki-SX11ZCE-N-S4","Kazuki-SX11ZCE-N","Kazuki-WB-DBR","Kazuki-WB-OME-BR","KazukiBKZ-KSG-BK","KazukiBKZ-KSG-RD","KazukiBKZ-KSG-WH","KazukiBKZ-VST-BK","KazukiBKZ-VST-LSB","KazukiBKZ-VST-RD","KazukiBKZ-VST-SB","KazukiDC089-KZBR","KazukiDC089-KZGR","Kenshin-27KGR","KickPort-DSKP2-SV","Kirin-I-242PRG-GR-3M","Kirlin-IM202RSG-3M","Kirlin-MW-470-BK-6M","Kirlin-MW-472B-BK-6M","Kirlin-MW-480-BKB-6M","Kirlin-Y-362PR-0.3M","Korg-B2-BK","Korg-B2SP-BK","Korg-B2SP-WH-INT","Korg-Electronic-Drum-Clip-Module-CH-01","Korg-Krome-EX-61","Korg-Kross-2-61-GO","Korg-Kross-2-61-GR","Korg-Kross-2-61-SP-GG","Korg-MiniPitch-Ukulele-Tuner-BeachWhite","Korg-MiniPitch-Ukulele-Tuner-SunsetOrange","Korg-PC-2-PHT-Orange","Korg-PC-300-BR","Korg-PC1-BL","Korg-PC1-OR","Korg-PC1-PK","Korg-PC1-RD","Korg-PC1-VL","Korg-PC1-YL","Laney-Nexus-SL","Line-6-FBV-Express-MkII","Line-6-HX-Effects","Line-6-Helix-Rack","Line-6-Pod-Go","Line-6-Powercab-112-Plus","Line-6-Powercab-112","Line-6-Powercab-212-Plus","Line-6-Relay-G10T","Line-6-Relay-G10","Line-6-Relay-G50","Line-6-Spider-V-120-MkII","Line-6-Spider-V-20-MkII","Line-6-Spider-V-20","Line-6-Spider-V-30-MkII","Line-6-Spider-V-60-MkII","Line6-HX-Stomp","Line6-Helix-Floor","MEGA-GL15","MEGA-GL20","MEGA-GL30R","MEGA-GX100B","MEGA-GX10","MEGA-GX15R","MEGA-GX35R","MEGA-GX60B","MEGA-GX60R","MEGA-LN-GX15R","MEGA-LN-GX35R","MEGA-T60R","MEGA-T64RS","MEGA-TB62RS-RD","MOOER-GE100-N","MOOER-PCZ","MOOER-POGO","MOSRITE-BRY","MOSRITE-GT-GRO","MOSRITE-GT-ORG","MOSRITE-USA-CRM","MOSRITE-USA","MUSEDO-T27","Mantic-AG-1CEL-S1","Mantic-AG-1CLH-Set-A","Mantic-AG-1CN-S1","Mantic-AG-1CN-S2","Mantic-AG370-N-S2","Mantic-AG370C-N-S1","Mantic-AM-1CBK","Mantic-AM-1CSB","Mantic-GT-10AC-N-S3","Mantic-GT-10DC-BK-S3","Mantic-GT-10DC-N-S3","Mantic-GT-10DC-SB-Full-Set","Mantic-GT-10DCE-BK","Mantic-GT-10DCE-N","Mantic-GT-10G-GR-S2","Mantic-GT-10G-N-S2","Mantic-GT-10GC-BK-S3","Mantic-GT-10GC-N-S3","Mantic-GT-1AC-N-Natural","Mantic-GT-1GC-GR-Full-Set","Mantic-GT-1GC-N-Full-Set","Mantic-GT-1GC-SB-Full-Set","Mantic-GT-1GC-SB","Mantic-GT-1GCE-GR-Full-Set","Mantic-GT-1GCE-GR-S1","Mantic-GT-1GCE-SB-Full-Set","Mantic-GT-1GCE-SB-S1","Mantic-LXA-1CBK","Mantic-LXA-1CN","Mantic-LXA-1CSB","Mantic-LXM-1CBK","Mantic-LXM-1CN","Mantic-MG-1C-BK-Set-A","Mantic-MG-1C-BK","Mantic-MG-1C-SB","ManticGT-1D-N-S1","ManticGT-1D-N-S2","ManticGT-1D-SB-S1","ManticGT-1DCE-BK-S1","ManticGT-1DCE-N-S1","ManticGT-1DCE-SB-S1","ManticLXA-1CEBK","ManticLXA-1CEN","Marina-C-150CR-20-RD","Marina-C101-20","Marina-C111-1-20FT-BK","Marina-C111-1-20FT-BL","Marina-C129-1-10","Marina-C129-1-20","Marina-C133-15","Marine-Band-Crossover-F","Marine-Band-Crossover-G","Marth-D400CBU","Marth-D400CGN","Marth-D41C","Marth-D90C","Martin-Lee-AML-ML41C-BK-wVT348RD-S2","Martin-Lee-AML-ML41C-N-wVT348RD-S1","Martin-Lee-AML-ML41C-N-wVT348RD-S2","Martin-Lee-AML-ML41C-N-wVT348RD","Martin-Lee-AML-ML41C-SB-wVT348RD-S1","Martin-Lee-AML-ML41C-SB-wVT348RD-S2","Martin-Lee-AML-ML41C-SB-wVT348RD","Martin-Lee-AMTL-M38B-BK-S1","Martin-Lee-AMTL-M38B-BK-S2","Martin-Lee-MD4145C-S2","Martin-Lee-ML408CE-BK-wVT348RD_6.png","Martin-Lee-ML408CE-N-wVT348RD-S1","Martin-Lee-ML408CE-SB-wVT348RD","Martin-Lee-ML41CE-SB-wVT348RD-S1","Martin-Lee-Z-4016C-S1","Martin-Lee-Z4012C","Medeli-DD315-S1","Medeli-Electric-Pedal","Meinl-CAJ100BU-M","Meinl-CAJ1CA-M","Meinl-CAJ3MB-M","Meinl-Cymbal-Cleaner-Care-Kit","Meinl-DGCY-MN-MCS-141620","Meinl-HCS141620","Meinl-MCCL-Cymbal-Cleaner","Melodian-ML32BL","Melodian-ML32PK","Midiplus-AKM320BT","Midiplus-AKM320","Midiplus-Easy-Piano","Mooer-GE200","Mooer-GE250","Mooer-GE300","Mooer-Red-Truck","Motion-E120-Super-Light-Set-009-042","Musedo-MC-1-BK","Musedo-MC-1-GD","Musedo-MC-1-GY","Musedo-MC-5","Musedo-T-11","Musedo-T2","NA210-FBH","NA212","NA310","NA312","NAE312T","NAE412T","NE-214","NE-314","NE212","NE311","NE312TM","NE312","NE410","NE412TM","NE412TR","NE413TM","NT600","NUX-Acoustic-30","NUX-MG-20","NUX-MG-300","NUX-MP1-Footswitch","NUX-MTCDL-Metal-Core-Deluxe","NUX-Mighty-Lite-BT","NUX-Mini-Studio-NSS-3","NUX-NAP5-Floor-Acoustic-Preamp","NUX-NDL-5","NUX-NMP-2-Dual-Footswitch","NUX-NPB-L-Pedalboard","NUX-NSS-5","NUX-OD3-Overdrive","NUX-Oceanic-NRV-2","NUX-PDI-1G-Guitar-Direct-Box","NUX-PG-2-Multi-Effects","NUX-PLS-4-Four-Channel-Line-Switcher","NUX-RTR-Roctary-Force-Guitar-Effect","NUX-Rivulet-NCH-2","NUX-Sculpture-NCP-2","NUX-TPCDL-Tape-Core-Deluxe","NUX-Tube-Man-MKII-NOD-2","NUXBrownieNDS-2","NUXHDPITCHNTU-2","NUXJTCNDL-2","Novation-Launchkey-25-MKII","Novation-Launchkey-37-MkIII","Novation-Launchkey-49-MKIII","Novation-Launchkey-61-MKII","Novation-Launchkey-Mini-MK3","Novation-Launchpad-Mini-MK3","Novation-Launchpad-Pro-MK3","Novation-Launchpad-X","Nux-Acoustic-G-EFXPA-2","Nux-B-5RC-Wireless-Guitar-System","Nux-MP-2-Mighty-Plug","Nux-Mighty-Air","Nux-NBP5-Melvin-Lee-Davis-Bass-Preamp","Nux-NDR-5","Nux-NMP-4","Nux-OTL-Octave-Loop","Nux-PA-50","Nux-PB-01BK","Nux-PB-01WH","Nux-Stageman","Nux-WK-310-BK-S1","Nux-WK-310-BK","Nux-WK-310-WH-S1","Nux-WK-400","Nux-WK-520","OEM-509A","OEM-BA20-VIP","OEM-M-M1-2IN1","OEM-MD40B","OEM-MS70B","OEM-MX30","OEM-Mic-Cover-MF-3BK","OEM-Mic-Cover-MF-3RD","OEM-NA210","OEM-NAE230","OEM-NE110","OEM-T20","On-Stage-DS7200B","On-Stage-DSB6700","On-Stage-GS7140","On-Stage-GS7655","On-Stage-RS7500-wMSA7500CB","OnStage-GCE6000T","Orange-Crush-12","Orange-Crush-20RT","Orange-Crush-35RT","Orange-Crush-Bass-25","Orange-FS-2","Pacifica212VFM-BK","Pacifica212VFM-CB","Paiste-Cleaner","Paramont-Pick-Mix-Set-A-BK","Paramont-Pick-Mix-Set-A-GD","Paramont-Pick-Mix-Set-A-GR","Paramont-Pick-Mix-Set-A-WH","Paramount-36A","Paramount-A2016-S1","Paramount-A2016-S2","Paramount-A2016","Paramount-AB80CEN-FMD49","Paramount-AB80CEN","Paramount-AB84CEN","Paramount-AB85CEN","Paramount-AB90CN","Paramount-AB90N-FTN49AB","Paramount-AB94CEN-S1","Paramount-AB94CEN","Paramount-AB95CEN","Paramount-AL016P","Paramount-ALP015P","Paramount-AMP1","Paramount-AOD-014A","Paramount-BB001CR","Paramount-BB002CR","Paramount-BB021CR","Paramount-BC01-Classical-Guitar-Bag","Paramount-BC393N-AT200D","Paramount-BC393N-BC01","Paramount-BC393NS-AT200D","Paramount-BC393NS","Paramount-BC500","Paramount-BL004CR","Paramount-BM32K","Paramount-BM37K-BK","Paramount-BM37K-BL","Paramount-BN007CR","Paramount-BOM403-S1","Paramount-BOM403-S2","Paramount-BOM403","Paramount-BOM403ET5-S1","Paramount-BOM403ET5","Paramount-BOM406ET5-S1","Paramount-BOM406ET5","Paramount-BOM407ET5-S1","Paramount-BOM407ET5","Paramount-BOM407N-S2","Paramount-BOM407","Paramount-BP001BK-x12","Paramount-BP001IV-x12","Paramount-BP004BK-x6","Paramount-BS006CR","Paramount-BY103","Paramount-BY105","Paramount-BY200","Paramount-BY203","Paramount-BY205","Paramount-C-10-Cowbell","Paramount-C-8-Cowbell","Paramount-C28-FBA","Paramount-C28","Paramount-C33CEQN-S1","Paramount-C33CEQN","Paramount-C33CEQSB-S1","Paramount-C33CSB","Paramount-C33N","Paramount-C33SB","Paramount-C42-N-S1","Paramount-C42-N-S2","Paramount-C5E-V2","Paramount-C836E-SB","Paramount-C836EBK-FMB05","Paramount-C836EN-FMB05","Paramount-C836ESB-FMB05","Paramount-C956N","Paramount-C98C-N","Paramount-CC450","Paramount-CD60CEM","Paramount-CD60CM-S1","Paramount-CE-50","Paramount-CL39-S1","Paramount-CL39","Paramount-CTS-S","Paramount-Car-Racer-Danchoo-KFQ-32K-BL-REVB","Paramount-DE049","Paramount-DH108R","Paramount-DHM-NUTB4B","Paramount-DHM-NUTB5B","Paramount-DK8","Paramount-DP100","Paramount-DS41-1DVS","Paramount-DSB-052","Paramount-DSB-070","Paramount-DSB-071","Paramount-EA18BEBK-FBA","Paramount-EA18BEBK","Paramount-EA18BEN","Paramount-EA18BESB-FBA","Paramount-EBG100BK-FMD40B","Paramount-EBG100BK","Paramount-EBG100N","Paramount-EBG100SB","Paramount-EBG400BK","Paramount-EBG405N","Paramount-EBG405SB","Paramount-EBG505N","Paramount-EC450","Paramount-EC450LP","Paramount-EC450Sg","Paramount-ED400","Paramount-ED95-S2","Paramount-ED95-S3","Paramount-ED95-S5","Paramount-ED95E-S3","Paramount-ED95E-S4","Paramount-ED95E","Paramount-EGT100NLH","Paramount-EGT100WHL-FBE01","Paramount-EGT100WHLH","Paramount-EGT200WH-S1","Paramount-EGT240BB","Paramount-F601SB-FBA01","Paramount-F601SB-S2","Paramount-F650-CEQN","Paramount-F650CN-S1","Paramount-F650CN","Paramount-F650N-AT-200D","Paramount-F650N-FBA","Paramount-F650NL","Paramount-F750CEQN-S1","Paramount-FH2-20","Paramount-G-03","Paramount-G3002","Paramount-G4N","Paramount-GS-Mini-7","Paramount-GSMINI1-S1","Paramount-GSMINI2FEQ","Paramount-GSMINI3-S1","Paramount-GSMINI3FEQ","Paramount-GSMINI5-S1","Paramount-GSMINI5","Paramount-GSMINI6-S1","Paramount-GSMINI6","Paramount-GX50CEQ-WH","Paramount-GX50CEQ","Paramount-H19","Paramount-H20","Paramount-H24","Paramount-H25","Paramount-H29","Paramount-H30","Paramount-H34","Paramount-H35","Paramount-H49","Paramount-H4S-REVB","Paramount-H4U","Paramount-H65","Paramount-HJ002-GD","Paramount-HS-015CR","Paramount-HS006BK","Paramount-J01CR","Paramount-J02BK","Paramount-J02CR","Paramount-J07CR","Paramount-J112CEN","Paramount-J112CN","Paramount-J44CR","Paramount-JB38EN","Paramount-JB38EVS","Paramount-JB49EN","Paramount-JG10GR","Paramount-JG28RD","Paramount-JG28YL","Paramount-KRV-11-WH","Paramount-KSP11GD","Paramount-KSP13BK","Paramount-KSP21GD","Paramount-KSP25WH","Paramount-KST42BK","Paramount-KSV41GD","Paramount-KSV42BK","Paramount-KTG25IV","Paramount-LSW-30","Paramount-M-M1","Paramount-MA001VS-FMD40MD","Paramount-MA001VS","Paramount-MA005VS","Paramount-MB25B","Paramount-MB25E","Paramount-MB36","Paramount-MD100BK","Paramount-MD100BR","Paramount-MD20U","Paramount-MD25TN","Paramount-MD40MD","Paramount-MD41CC","Paramount-MD668","Paramount-MDBG10","Paramount-MG-20","Paramount-MG-30","Paramount-MH8282","Paramount-MHS01","Paramount-MI-01-S1","Paramount-MI-01","Paramount-Melody-Danchoo-KFQ-32K-PK-REVB","Paramount-Melody-Danchoo-KFQ32K-PK","Paramount-NB001BK","Paramount-NB001CR","Paramount-NBK","Paramount-NK100R","Paramount-NK100RG","Paramount-NK200MG","Paramount-NK200M","Paramount-NS002BK","Paramount-NT-EG","Paramount-NT018BK","Paramount-PCM604","Paramount-PE100BK-S1","Paramount-PE100BK-S2","Paramount-PE100BK-S4","Paramount-PE100BK-S5","Paramount-PE100BK","Paramount-PE100BL-S1","Paramount-PE100BL-S2","Paramount-PE100BL-S4","Paramount-PE100BL-S5","Paramount-PE100BL","Paramount-PE100RD-S1","Paramount-PE100RD-S2","Paramount-PE100RD-S4","Paramount-PE100RD-S5","Paramount-PE100RD","Paramount-PE100SB-S1","Paramount-PE100SB-S2","Paramount-PE100SB-S4","Paramount-PE100SB-S5","Paramount-PE100SB","Paramount-PE100WH-S1","Paramount-PE100WH-S4","Paramount-PE100WH-S5","Paramount-PE102BK-S2","Paramount-PE102BK","Paramount-PE200WH-S3","Paramount-PKS8","Paramount-PL100-FBA01","Paramount-PL100","Paramount-PL300BK","Paramount-PL300BL","Paramount-PMM605","Paramount-PS-001","Paramount-PS-002","Paramount-PS012L","Paramount-PSL700B","Paramount-QAG402G-S1","Paramount-QAG412G-S1","Paramount-QAG412G-S2","Paramount-QAG501-N-S1","Paramount-QAG501-W-S1","Paramount-QAG501-W-S2","Paramount-QB-MB-15","Paramount-QB-MB11","Paramount-QB-MB12BK","Paramount-QZ04","Paramount-R206","Paramount-R208","Paramount-S450CE","Paramount-S450C","Paramount-SDG8283-FBA","Paramount-SH117R-MBK-S3","Paramount-SH117R-MBL-S3","Paramount-SH117R-MBL-S5","Paramount-SH117R-MRD-S5","Paramount-SH117R-MRD","Paramount-SH118R-MBK-S6","Paramount-SH118R-MBL-S6","Paramount-SH118R-MBL","Paramount-SH118R-MRD-S3","Paramount-SH118R-MRD-S6","Paramount-SH8R-MBK","Paramount-SP723CEQSB-FBA","Paramount-SPE2295-BK","Paramount-SPE2295-RD","Paramount-SPE2295-WH","Paramount-SQ-F","Paramount-SQ-FS","Paramount-SQ-JLEBK-S1","Paramount-SQ-JLEBK","Paramount-SQ-JLEN-S1","Paramount-SQ-JLESB-S1","Paramount-SQ-JLESB-S2","Paramount-SQ7","Paramount-T400","Paramount-TGS202","Paramount-TN20U","Paramount-TN25CM","Paramount-TN33BK","Paramount-TN33BR","Paramount-TN49AB","Paramount-TParamount-Thunder-HJ-12","Paramount-TS001CR","Paramount-Thunder-HJ-14","Paramount-Thunder-HJ-16","Paramount-Thunder-HJ-20","Paramount-Thunder-HJ-8","Paramount-Thunder-Ride18","Paramount-UB21-Soprano-Ukulele-Bag","Paramount-UB24-Concert-Ukulele-Bag","Paramount-UB26-Tenor-Ukulele-Bag","Paramount-W758","ParamountDHM-NUTCB-WH","ParamountKRV-10-BK","ParamountNT018-IV","ParamountNT021BK","ParamountQAG501E-N-S1","Pastel-DK360BK","Pastel-K-154","Pastel-P9BK","Pastel-P9WH","Pastel-Siamkey61","Pickguard-DPG-HB050","Pickguard-DPG-HB381","Pickguard-VT361-WH","Pirastro-Cello","Pirastro-Eudoxa","Pirastro-Evah-Pirazzi-Gold-Violin-415021","Pirastro-Evah-Pirazzi-Gold","Pirastro-Evah-Pirazzi-Violin-419021","PirastroPiranitoKolophon","Play-Drumboy-Drum-Carpet-RC02","Play-Drumboy-GPD-AT02-Danube","Play-Drumboy-GPD-DGS01-GN","Play-Drumboy-GPD-DGS01-PP","Play-Drumboy-GPD-DGS01-YW","Play-Drumboy-PB03-Blowout-Dazzle-Colour","Play-Drumboy-PB10-Red-Bird","Play-Drumboy-PB12-Spraying-Color","Player-Cymbal-Cleaner-CM250","Prima-P-103A16","Prima-P-200A16","Prima-P-280","Prima-P-360","Prima-P-480","Proline-PB100BK","Proline-PB100BL","Proline-PB105BL","Proline-PB105RD","Proline-PB200BK","Proline-PB200RD","Proline-PB200WH","Proline-PB205BK","Proline-PB205WH","Proline-PB90BK","Proline-PB90BL","Proline-PB90RD","Proline-PE1000-BL","Proline-PE1000-SV","Proline-PE1100-BK","Proline-PE1100-BL","Proline-PE1100-RD","Proline-PE1500-BK","Proline-PE1500-WH","Proline-PE2000-PP2000-BL","Proline-PE2000-PP2000-RD","Promark-Forward-7A-ActiveGrip","Promark-LA7AN","Promark-Rebound-5B-ActiveGrip","Promark-Rebound-7A-ActiveGrip","Promark-SD400","Promark-SRBLA","Promark-TX2BN","Promark-TX2BW","Promark-TX5AN","Promark-TX5BN","Promark-TX5BW","Promark-TX7AN","Promark-X5AXW","Puresound-CPS1424","RTOM-Moongel-Blue","RTOM-Moongel-Clear","Remo-CX-0114-10","Remo-EN-0114-BA","Remo-EN-0312-PS","Remo-EN-0313-BA","Remo-EN-0313-PS","Remo-EN-0314-BA","Remo-EN-0314-PS","Remo-EN-1218-CT","Remo-EN-1220-CT","Remo-EN-PS50-BSA","Remo-ES-0614-PS","Remo-ES-1622-PS","Remo-ET-7108-00","Remo-ET-7110-00","Remo-PR-1322-00","Remo-PS-0310-00","Remo-PS-0316-00","Remo-Practice-Pad-RT-0006-ST","Remo-Practice-Pad-RT-0008-00","Remo-Practice-Pad-RT-0008-58-C3T","Remo-Practice-Pad-RT-0008-ST","Remo-Practice-Pad-RT-0010-ST","Rock-RM1","RockaRhythm-FZG1065","RockaRhythm-FZGGP-10","RockaRhythm-FZGGP-8","RockaRhythm-G16-4","RockaRhythm-G204","RockaRhythm-HB8-10","RockaRhythm-HB8-7","RockaRhythm-RRES-10YL","RockaRhythm-YSH245","RockaRhythmSH8","RockaRhythmYX16-SH17-RD","RockaRhythmYX16-SH17-YL","Rockarhythm-G16-6","Rockarhythm-KSU-0-GD","Rockarhythm-KSU-0-GR","Rockarhythm-KSU-0-VL","Rockarhythm-KSU-BL","Rockarhythm-KSU-RD","Rockarhythm-MKSU","Roland-BTM-1","Roland-CB-88GP","Roland-FD-9","Roland-FP-10","Roland-Go-Keys-GO-61K","Roland-Go-Livecast","Roland-Octopad-SPD-30-V2-WH","Roland-RC-600","Roland-RD-88","Roland-Rubix-22","Roland-Rubix-24","Roland-SPD-30V2-PDS-10","Roland-TD-17K-L","Roland-TD-17KVX","Roland-TD-17KV","Roland-TD-1DMK","Roland-TD-1K","Roland-VDRUM-TD1KV","Roland-XPS-10","Roland-XPS30","RolandAX-Edge-BK","RolandAX-Edge-WH","RolandFA-06-BK","RolandJUPITER-Xm","Roli-Songmaker-Kit","Rowin-WS-20","SAMSON-C01","SE-Electronics-X1-S-Studio-Bundle","SHURE-PGA48","SHURE-PGA58LC","SHURE-SV100-2","SHURE-SV100","SQOE-SEIB500RD","SQOE-SEIB500SB","SQOE-SEST210BK-S2","SQOE-SETL300BK-FBC","SQOE-SETL300SB-FBC","SQOE-SQ1305RD","Sabinetek-Smartmike-BK","Sabinetek-Smartmike-Twin-Package-wTRSS-Cable","Sakura-BFG-4116CN-FAT200D","Sakura-BFG-4116CN","Samson-C01U-Pro","Samson-Expedition-XP300","Shure-SM58-LC","Shure-SM58S","Shure-SV100-MS70B","Shure-SV100-SD229-DE028","Sqoe-BS100NA","Sqoe-SELP100BK-REVB","Sqoe-SELP100BL-REVB","Sqoe-SELP100RD-REVB","Sqoe-SELP100SB-REVB","Sqoe-SEST200BK","Sqoe-SEST200SB","Sqoe-SETL500BK","Sqoe-SETL500N","Sqoe-SETL500RD","Sqoe-SQ1305BK-S1","Sqoe-SQ1305BL-S1","Sqoe-SQ1305SB-S1","Squier-Affinity-SPCL-2TS-0310603503-S1","Squier-Affinity-Tele-BK-0310202506","Squier-Affinity-Tele-GR-0310200592","Squier-Affinity-Tele-GR-S1-0310200592","Squier-Affinity-Tele-SV-S1-0310200581","Squier-Bullet-Strat-BK-0310001506-S1","Squier-Bullet-Strat-HSS-AWT-wLaney-Mini-Superg-wLaney-PSU12-S2","Squier-Bullet-Strat-HSS-AWT-wVox-Pathfinder10-S3","Squier-Bullet-Strat-HSS-BK-0310005506","Squier-Bullet-Strat-HSS-BK-S1-0310005506","Squier-Bullet-Strat-HSS-BK-wVox-Pathfinder10-S3","Squier-Bullet-Strat-HSS-WH-S1-0310005580","Squier-Bullet-Strat-SB-0310001532-S1","Squier-Bullet-Strat-SB-0310001532","Squier-Bullet-Strat-SSS-RD-SET01","Squier-Bullet-Strat-Sonic-Grey","Squier-Bullet-Strat-Trem-AWT-wLaney-Mini-Superg-wPSU12-S2","Squier-Bullet-Strat-Trem-AWT-wVox-Pathfinder10-S3","Squier-Bullet-Strat-Trem-BK-wLaney-Mini-Superg-wPSU12-S2","Squier-Bullet-Strat-Trem-BK-wVox-Pathfinder10-S3","Squier-Bullet-Strat-Trem-BSB-wLaney-Mini-Superg-wPSU12-S2","Squier-Bullet-Strat-Trem-BSB-wVox-Pathfinder10-S3","Squier-Bullet-Strat-Trem-Sonic-Grey-wLaney-Mini-Superg-wPSU12-S2","Squier-Bullet-Strat-Trem-Sonic-Grey-wVox-Pathfinder10-S3","Squier-Bullet-Strat-Trem-Tropical-Turquoise-wVox-Pathfinder10-S3","Squier-Bullet-Strat-Tropical-Turquoise","Squier-John-5-Tele-Gold-0371006580","Squier-Mini-Strat-BK-0310121506","Squier-Mini-Strat-BK-S1-0310121506","Squier-Mini-Strat-BLK-wLaney-Mini-Superg","Squier-Mini-Strat-PK-S1-0310121570","Squier-Mini-Strat-PK-wLaney-Mini-Superg","Squier-Mini-Strat-RD-S1-0310121558","Squier-Mini-Strat-TRD-wLaney-Mini-Superg","Squier-PNML-Baritone-Cabronita-Telecaster-0377030506","Squier-PNML-Offset-Tele-Natural","Squier-PNML-Offset-Tele-Surf-Green","Squier-PNML-Super-Sonic-0377015569-GRM","Squier-PNML-Super-Sonic-0377015583-IBM","Squier-PNML-Toronado-0377000502-Lake-Placid-Blue","Squier-PNML-Toronado-0377000506-Black","Squier-SFR-Affinity-Strat-LRL-Olympic-White","Squier-Vintage-Mod-Jazz-Bass-70s-WH","Squier-Vintage-Mod-Jazz-Bass-77s-BK-0307702506","Squier-Vintage-Mod-Jazz-Bass-77s-SB","Stable-CYS100","Stable-PD-1","Sterling-CT-30HSS-VC","Sterling-CT30SSS-DBL","Studiologic-Numa-Compact-2","Studiomaster-CM50","Studiomaster-CM51","Studiomaster-KM102","Super64-Gold","Sure-E12MM-BK","Switchcraft-280","TAMA-BCM40","TOMBO-Ultimo-C","Taiki-T-D210C","Taiki-T-D220-Set-A","Tascam-DR-05X","Tascam-TM-80-BK","Tascam-US-1x2","The-One-KBTO-TOM1BK","The-One-KBTO-TOM1WH","Tombo-Lee-Oskar-Key-A","Tomsline-AEG-3","Tyma-HDC-350S","Tyma-HG-350M","UDG-U9950-SV","Ukulele-Bag-DC-B212","Ukulele-Bag-DC-B244","Ukulele-Bag-DC-B2490","Ukulele-Bag-DC-B2491","Ukulele-Bag-DC-B264","Ukulele-Bag-DC074C","Ukulele-Bag-DC074S","Ukulele-Bag-DC077-BK","Ukulele-Bag-DC077-PK","Ukulele-Bag-DC085-UK21","Ukulele-Bag-DCPP-UB21","Ukulele-Bag-DCUK-SN23-BK","VOX-Amplug-IO","VOX-MINI3-G2-WH-REVB","VOX-Mini3-G2","Va-CG160CBK-S1","Vega-KB50","Vic-Firth-HHPBASS","Vic-Firth-HHPSL","Vic-Firth-HHPST","Vic-Firth-MB0H","Vic-Firth-MB1H","Vic-Firth-MB2H","Vic-Firth-MB3H","Vic-Firth-MB4H","Vic-Firth-MB5H","Vic-Firth-MS4","Vic-Firth-MT1A-S","Vic-Firth-MTS1SW","Vic-Firth-MTT","Vic-Firth-Nova-7A-RD","Vic-Firth-PAD12","Vic-Firth-PAD6D","Vic-Firth-SBBTS","Vic-Firth-SJQ","Vic-Firth-SMG","Vic-Firth-SRH2CO","Vic-Firth-SRH2","Vic-Firth-SRHN","Vic-Firth-SRHTSW","Vic-Firth-SRH","Vic-Firth-STATH","Vic-Firth-Stick-Caddy","Vic-Firth-VICKEY2","Vic-Firth-VICKEY3","Vic-Firth-VICKEY","VicFirth-5A-Hickory","VicFirth-5B-Hickory","VicFirth-5BB-Hickory","Vintage-V100-AFD-Paradise","Vox-AC2RV","Vox-Mini5-CL","Vox-VXII","Vox-amPlug2-Cab-and-Blues-S1","Vox-amPlug2-Cab-and-Classic-Rock-S1","Vox-amPlug2-Cab-and-Clean-S1","Vox-amPlug2-Cab-and-Lead-S1","Vox-amPlug2-Clean","Vox-amPlug2-Metal","Washburn-BWB-SB1PB-BK-01PG-RD","Washburn-BWB-SB1PB-BKPG-WH","Washburn-BWB-SB1PTS-SB","Wedgie-Cymbal-Washers-Kit-WCW001","Wedgie-WPH001-Guitar-Pick-Holder","Whimmory-YSL-S","Whimory-YSL-C","Whimory-YSL-W","Wilkinson-WOB51T","Wilkinson-WOCHB-N-CR-wWilkinson-WOCHB-B-CR","Wilkinson-WOCHB-N-CR","Wilkinson-WOCHB-N-GD-wWilkinson-WOCHB-B-GD","Wilkinson-WOCHB-N-GD","Wilkinson-WOF01","Wilkinson-WOGB1","Wilkinson-WOGB2","Wilkinson-WOHAS-B","Wilkinson-WOHHB-B-BK","Wilkinson-WOHHB-B-WH","Wilkinson-WOHHB-N-BK-wWilkinson-WOHHB-B-BK","Wilkinson-WOHHB-N-BK","Wilkinson-WOHHB-N-IV-wWilkinson-WOHHB-B-IV","Wilkinson-WOHHB-N-WH-wWilkinson-WOHHB-B-WH","Wilkinson-WOHZB-N-wWilkinson-WOHZB-B","Wilkinson-WOHZB-N","Wilkinson-WOT01","X100019100","X100920700","X3109007","X6419007","Xvive-U4","YS-MS-B4","YS-MS-V2","Yamaha-A1M-SB","Yamaha-A1R-NT","Yamaha-A1R-SB","Yamaha-A3M-SB","Yamaha-A3R-NT","Yamaha-A5R","Yamaha-AC1M-SB","Yamaha-AC1R-SB","Yamaha-AC3R-ARE-NT","Yamaha-AC3R-NT","Yamaha-AC5M","Yamaha-APX1000","Yamaha-APX1200II","Yamaha-APX600-N-INT","Yamaha-APX600-OVS-INT","Yamaha-APX600FM-Amber","Yamaha-APX600FM-Sunburst","Yamaha-Acoustic-Guitar-Bag-Deluxe-DDB","Yamaha-BB234-Rasberry-Red","Yamaha-BB234-Vintage-White","Yamaha-BB235-Rasberry-Red","Yamaha-BB235-Vintage-White","Yamaha-BB235-Yellow-Natural-Satin","Yamaha-BB434-Black","Yamaha-BB434-Sunburst","Yamaha-BB734A-Matte-Translucent-Black","Yamaha-BB735A-Dark-Coffee-Sunburst","Yamaha-BB735A-Matte-Translucent-Black","Yamaha-BBP34-Midnight-Blue","Yamaha-BBP35-Midnight-Blue","Yamaha-BBP35-Vintage-Sunburst","Yamaha-Bag-YB20-VIP","Yamaha-C40","Yamaha-C80","Yamaha-CG-TA","Yamaha-CG142C","Yamaha-CG142S","Yamaha-CG182C","Yamaha-CS40","Yamaha-CSF-TA","Yamaha-CSF1M-Tabacco-Brown-Sunburat","Yamaha-CX40","Yamaha-FC4A","Yamaha-FC7","Yamaha-FG-TA-BK","Yamaha-FG-TA-NT","Yamaha-FG5","Yamaha-FG800-Sandburst","Yamaha-FG800BLK-Black","Yamaha-FG800M-Matt-Natural","Yamaha-FG800NT-Natural","Yamaha-FG800TBS-Sunburst","Yamaha-FG820-12-S2","Yamaha-FG820-12","Yamaha-FG830-NT-S1","Yamaha-FP9C","Yamaha-FS-TA-NT","Yamaha-FS-TA-RR","Yamaha-FS-TA-SB","Yamaha-FS100CBK","Yamaha-FS800","Yamaha-FSX315CN-S1","Yamaha-FSX315CN","Yamaha-FSX315CSB","Yamaha-FSX3","Yamaha-GC32S_6.png","Yamaha-HW680W","Yamaha-HW780","Yamaha-HW880","Yamaha-JR2-NT","Yamaha-JR2-SB","Yamaha-LJ26","Yamaha-LJ36","Yamaha-LL-TA-NT","Yamaha-LL-TA-SB","Yamaha-LL26","Yamaha-LL36","Yamaha-LS-TA-SB","Yamaha-LS26","Yamaha-MODX6-BK","Yamaha-MODX7-BK","Yamaha-Montage-6-BK","Yamaha-Montage-7-BK","Yamaha-NTX5","Yamaha-P-125-WH","Yamaha-P115BK-LP5A-INT","Yamaha-P115BK-LP5A","Yamaha-P115BK","Yamaha-P115WH-LP5A-INT","Yamaha-P115WH-LP5A","Yamaha-P115WH","Yamaha-PAC212VQM-Caramel-Brown","Yamaha-PAC212VQM-Tobacco-Brown-Sunburst","Yamaha-PAC212VQM-Translucent-Black","Yamaha-PAC612VIIFM-Indigo-Blue","Yamaha-PAC612VIIFM-Root-Beer","Yamaha-PAC612VIIFM-Translucent-Black","Yamaha-PSR-E263-FST","Yamaha-PSR-E363","Yamaha-PSR-E463","Yamaha-PSR-F51-S1","Yamaha-PSR-S975","Yamaha-Pacifica012-BL-Black","Yamaha-Pacifica012-BL-Laney-Mini-Iron","Yamaha-Pacifica012-BL-Laney-Mini-Lion","Yamaha-Pacifica012-BL-Laney-Mini-Superg","Yamaha-Pacifica012-BL-Nux-Mighty-Lite-BT","Yamaha-Pacifica012-BL-amPlug2-Metal","Yamaha-Pacifica012-DBM-Blue","Yamaha-Pacifica012-DBM-amPlug2-Metal","Yamaha-Pacifica012-RD-Laney-Mini-Lion","Yamaha-Pacifica012-RD-Laney-Mini-Superg","Yamaha-Pacifica012-RD-Lirevo-Token-10","Yamaha-Pacifica012-RD-Nux-Mighty-Lite-BT","Yamaha-Pacifica012-WH-Laney-Mini-Iron-WH-Laney-Mini-Lion","Yamaha-Pacifica012-WH-Laney-Mini-Superg","Yamaha-Pacifica012-WH-White","Yamaha-Pacifica112J-Black","Yamaha-Pacifica112J-Yellow-Natural","Yamaha-Pacifica1611MS","Yamaha-RGX220DZ-Dark-Metallic-Grey","Yamaha-RGX220DZ-Metallic-Black","Yamaha-RGX220DZ-Metallic-Red","Yamaha-RGX420DZII-White","Yamaha-RS420-Fire-Red","Yamaha-RS420-Maya-Gold","Yamaha-RS502-Shop-Black","Yamaha-RS502T-Black","Yamaha-RS502T-Bowden-Green","Yamaha-RS620-Brick-Burst","Yamaha-RS620-Burnt-Charcoal","Yamaha-RS620-Snake-Eye-Green","Yamaha-RS720B-Ash-Grey","Yamaha-RS720B-Shop-Black","Yamaha-RS720B-Vintage-Japanese-Denim","Yamaha-RS820CR-Burst-Rat","Yamaha-RS820CR-Steel-Rust","Yamaha-RSP20CR-Brushed-Black","Yamaha-RSP20CR-Busty-Rat","Yamaha-Red-Label-FS3","Yamaha-Rydeen-RDP2F5-Black-Glitter","Yamaha-Rydeen-RDP2F5-Burgundy-Glitter","Yamaha-Rydeen-RDP2F5-HW680W-Black-Glitter","Yamaha-Rydeen-RDP2F5-HW680W-Burgundy-Glitter","Yamaha-Rydeen-RDP2F5-HW680W-Hot-Red","Yamaha-Rydeen-RDP2F5-HW680W-Mellow-Yellow","Yamaha-Rydeen-RDP2F5-HW680W-Sliver-Glitter","Yamaha-Rydeen-RDP2F5-Hot-Red","Yamaha-Rydeen-RDP2F5-Mellow-Yellow","Yamaha-Rydeen-RDP2F5-Sliver-Glitter","Yamaha-SA2200-Brown-Sunburst","Yamaha-SA2200-Viloin-Sunburst","Yamaha-SG1802-Black","Yamaha-SG1820-Black","Yamaha-SG1820A-Silver-Burst","Yamaha-SLG200N-CRD","Yamaha-SLG200N-NT","Yamaha-SLG200N-TBL","Yamaha-SLG200N-TBS","Yamaha-SLG200S-NT","Yamaha-SLG200S-TBL","Yamaha-TRBX504-Brick","Yamaha-TRBX504-Brown","YamahaGL-1","Zildjian-PZ4PK","Zildjian-Z7A","Zoom-G1X-Four"];
//    for($i=0; $i<sizeof($filenameList); $i++)
//    {
//        $sku = $filenameList[$i];
//        $sql = "insert into webskutodelete (sku) values('$sku')";
//        $ret = doQueryTask($con,$sql,$modifiedUser);
//        if($ret != "")
//        {
//            writeToLog("INSERT INTO webskutodelete fail [sku]:[$sku]");
//            echo $sql;
//            exit();
//        }
//    }
//    exit();
    
    
    
    
//    SELECT * FROM `lazadaproducttemp` WHERE `SellerSku` LIKE 'Century-DST-WH-S1' or `SellerSku` LIKE 'Century-DST-BK-S1'
    
//    $itemID = '69320327';
//    echo json_encode(getItemShopee($itemID));
//    exit();
    
//
//    $sql = "select * from shopeeproduct";
//    $shopeeProduct = executeQueryArray($sql);
//    for($i=0; $i<sizeof($shopeeProduct); $i++)
//    {
//        $itemID = $shopeeProduct[$i]->ItemID;
//        $item = getItemShopee($itemID);
//        if($item->name == 'ERNIE BALL® Peg Winder ที่หมุนหัวลูกบิดกีตาร์ (ที่หมุนสายกีตาร์ / String Winder)')
//        {
//            echo json_encode($item);
//            break;
//        }
//    }
//    exit();
////    $itemID = 6161751593;
//
//
//    echo json_encode(getItemShopee(1258352221));
//    exit();
//
    
//
//    list($width, $height) = getimagesize('https://th-live.slatic.net/p/bdf3889ba05b10ed5d55b93a05675a55.png');//1181
////    list($width, $height) = getimagesize('https://th-live.slatic.net/p/4942963de7e2e9cc7129563ebf40a885.jpg'); // 1900
//
//    echo "width:" . $width;
//    echo "<br>";
//    echo "height:" . $height;
//    exit();
    
    
    
    //DB-Blake-Deluxe-VTFAC300-BR
//    $ch = curl_init('https://th-live.slatic.net/p/bdf3889ba05b10ed5d55b93a05675a55.png'); //942998
////    $ch = curl_init('https://th-live.slatic.net/p/4942963de7e2e9cc7129563ebf40a885.jpg'); //865255
////
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//     curl_setopt($ch, CURLOPT_HEADER, TRUE);
//     curl_setopt($ch, CURLOPT_NOBODY, TRUE);
//
//     $data = curl_exec($ch);
//     $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
//
//     curl_close($ch);
//     echo $size;
//    exit();
    
    
//    $filename = "https://th-live.slatic.net/p/4942963de7e2e9cc7129563ebf40a885.jpg";
//    $image_info = getimagesize($filename);
////  $this->image_type = $image_info[2];
//    echo $image_info[2];
//exit();
    
    
//    $jdImageUrl = JdImageUpload('https://th-live.slatic.net/p/bdf3889ba05b10ed5d55b93a05675a55.png','testimage.png');
//    exit();
    
    
//    $currentFolder = getcwd();
//    copy($url, "./tmp/$tmpFileName");
//    $contents = $currentFolder."\\tmp\\testimage.png";
//    echo $contents;
//    $filename = $currentFolder."\\tmp\\testimageCopy.png";
//
//
//    $image = imagecreatefrompng($contents);
//    $ratio = 700 / imagesx($image);
//    $height = imagesy($image) * $ratio;
//    $new_image = imagecreatetruecolor($width, $height);
//    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
//    imagepng($image);
//
//    exit();
    
    

//    global $host;
//    global $contentType;
//    global $key;
//    global $partnerID;
//    global $shopID;
//
//
//    //create curl
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_POST, 1);
//    curl_setopt($ch, CURLOPT_FAILONERROR, true);
//
//
//    //find item_id and variation_id
//    $url = "https://partner.shopeemobile.com/api/v1/items/get";
//    curl_setopt($ch, CURLOPT_URL, $url);
//
//    $variations = array();
//    for($j=0; $j<100; $j++)//เผื่อไว้เป็น 60 ไม่อยากใส่ while(true)
//    {
//        //param
//        $pageEntries = 100;
//        $pageOffset = $j*$pageEntries;
//        $date = new DateTime();
//        $timestamp = $date->getTimestamp();//1586502149;
//
//
//        //payload
//        $paramBody = array();
//        $paramBody["pagination_offset"] = $pageOffset;
//        $paramBody["pagination_entries_per_page"] = $pageEntries;
//        $paramBody["partner_id"] = $partnerID;
//        $paramBody["shopid"] = $shopID;
//        $paramBody["timestamp"] = $timestamp;
//
//
//        $payload = json_encode($paramBody);
//        writeToLog("payload:" . $payload);
//
//
//        $contentLength = strlen($payload);
//        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
//
//
//        //header
//        $header = array();
//        $header[] = 'Host:' . $host;
//        $header[] = 'Content-Type:' . $contentType;
//        $header[] = 'Content-Length:' . $contentLength;
//        $header[] = 'Authorization:' . $authorization;
//        writeToLog("header:" . json_encode($header));
//
//
//        //set header and payload
//        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
//        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
//
//
//        //exec curl
//        $result = curl_exec($ch);
//        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        $curl_errno = curl_errno($ch);
//        if ($http_status==503)
//        {
//            writeToLog( "HTTP Status == 503");
//        }
//
//        if ($result === false)
//        {
//            writeToLog('Curl error: ' . curl_error($ch));
//        }
//
//
//        $obj = json_decode($result);
//
//        for($i=0; $i<sizeof($obj->items); $i++)
//        {
//            $item = $obj->items[$i];
//
//            if(sizeof($item->variations) > 0)
//            {
//                for($k=0; $k<sizeof($item->variations); $k++)
//                {
//                    $variation = $item->variations[$k];
////                    if($variation->variation_sku == $sku)
//                    {
//                        $variation = array();
//                        $variation["item_id"] = $item->item_id;
//                        $variation["item_sku"] = $item->item_sku;
//                        $variation["variation_id"] = $item->variation_id;
//                        $variation["variation_sku"] = $item->variation_sku;
//                        $variations[] = $variation;
////                            echo "<br>".$item->item_id.";".$item->item_sku.";".$variation->variation_id.";".$variation->variation_sku;
//                    }
//                }
//            }
//            else
//            {
////                if($item->item_sku == $sku)
//                {
//                    $itemID = $item->item_id;
//                    $itemSku = $item->item_sku;
//                    $sql = "INSERT INTO `shopeeproducttest`( `Sku`, `ItemID`, `ItemSku`, `ModifiedUser`) select '$itemSku','$itemID','$itemSku','bot'";
//                    $ret = doQueryTask($con,$sql,$modifiedUser);
//                    if($ret != "")
//                    {
//                        writeToLog("INSERT INTO shopeeproducttest fail [sku]:[$itemSku]");
//                    }
//                }
//            }
//        }
//
//        if(sizeof($obj->items) < 100)
//        {
//            return $variations;
////                break;
//        }
//    }
//
//
//
//    exit();
   
//    select sku, name, Description, 'in stock','new', concat(price,' THB'), concat('http://ralamusic.com/',sku,'/'),'http://ralamusic.com/wp-content/uploads/2020/11/Roland-XPS-10-SE-Red.jpg',Brand, concat(SpecialPrice,' THB') from lazadaproducttemp where ProductID = 6565
    
    
    
//    INSERT INTO `facebookproduct`(`id`, `title`, `description`, `availability`, `condition`, `price`, `link`, `image_link`, `brand`, `sale_price`, `sale_price_effective_date`) select sellersku, name, short_description, 'in stock','new', concat(price,' THB'), concat('http://ralamusic.com/',sellersku,'/'),'http://ralamusic.com/wp-content/uploads/2020/11/Roland-XPS-10-SE-Red.jpg',Brand, concat(Special_Price,' THB'),'2017-12-01T0:00-23:59/2030-12-31T0:00-23:59' from lazadaproducttemp where sellersku in ('Roland-XPS-10-SE-Red','Fantasia-F100-N-S4','Fantasia-F80-N-S5')
    
    
//    SELECT * FROM `product` left join productname on product.ProductCategory2 = productname.ProductCategory2 and product.ProductCategory1 = ProductName.ProductCategory1 and product.ProductName = ProductName.Code where ProductNameID=2043 and product.Status = 'S'
    
    
    
//    cp -v /Users/jinglejill/Desktop/reactApp/ralamusic3/ios/nativeModule/BRLMPrinterKitW.framework/BRLMPrinterKitW /Users/jinglejill/Desktop/backupBrotherFramework/BRLMPrinterKitW.framework/BRLMPrinterKitW
//=    lipo -remove i386 /Users/jinglejill/Desktop/reactApp/ralamusic3/ios/nativeModule/BRLMPrinterKitW.framework/BRLMPrinterKitW -o /Users/jinglejill/Desktop/reactApp/ralamusic3/ios/nativeModule/BRLMPrinterKitW.framework/BRLMPrinterKitW
//    lipo -remove x86_64 /Users/jinglejill/Desktop/reactApp/ralamusic3/ios/nativeModule/BRLMPrinterKitW.framework/BRLMPrinterKitW -o /Users/jinglejill/Desktop/reactApp/ralamusic3/ios/nativeModule/BRLMPrinterKitW.framework/BRLMPrinterKitW
//    
//    /Users/jinglejill/Desktop/reactApp/ralamusic3/ios/nativeModule
    
    
    
    
    
//    $sku = $_GET['sku'];
//
//    echo json_encode(getJdProductSkuIds($sku));
//    exit();
//
//
//
//    $productId = $_GET['productId'];
//    echo json_encode(getJdProduct($productId));
//    exit();
//
    
    
//    Martin-Lee-AMTL-M38B-N-S1
//    $sku = $_GET['sku'];
//    echo json_encode(getAllVariationsShopee($sku));
//    $itemID = $_GET['itemID'];
//    echo json_encode(getItemShopee($itemID));
//    exit();
//    UPDATE `lazadaproduct` SET `Name`='',`Description`='',`ShortDescription`='',`Quantity`=0,`Price`=0,`Video`='',`MainImage`='',`Image2`='',`Image3`='',`Image4`='',`Image5`='',`Image6`='',`Image7`='',`Image8`='',`PackageWeight`=0,`PackageWidth`=0,`PackageHeight`=0,`PackageLength`=0 WHERE 1
    
    
//    UPDATE `jdproduct` set `UpcCode`='',`OuterId`=''
//    UPDATE `shopeeproduct` set `ItemSku`='' WHERE 1
//    ALTER TABLE `mainproduct` ADD INDEX(`Sku`);
//    ALTER TABLE `lazadaproduct` ADD INDEX(`Sku`);
//    ALTER TABLE `shopeeproduct` ADD INDEX(`Sku`);
//    ALTER TABLE `jdproduct` ADD INDEX(`Sku`);
//
//    ALTER TABLE `categorymapping` ADD INDEX(`LazadaCategoryID`);
//    ALTER TABLE `categorymapping` ADD INDEX(`ShopeeCategoryID`);
//    ALTER TABLE `categorymappingjd` ADD INDEX(`LazadaCategoryID`);
//    ALTER TABLE `categorymappingjd` ADD INDEX(`JdCategoryID`);
//
//    ALTER TABLE `forgotpassword` ADD INDEX(`CodeReset`);
//    ALTER TABLE `forgotpassword` ADD INDEX(`Status`);
//    ALTER TABLE `jdorder` ADD INDEX(`OrderNo`);
//    ALTER TABLE `lazadaorder` ADD INDEX(`OrderNo`);
//    ALTER TABLE `rolemenu` ADD INDEX(`RoleID`);
//    ALTER TABLE `rolemenu` ADD INDEX(`MenuID`);
//    ALTER TABLE `shopeeorder` ADD INDEX(`OrderNo`);
//    ALTER TABLE `stocksharing` ADD INDEX(`StockSharingGroupID`);
//    ALTER TABLE `stocksharing` ADD INDEX(`Sku`);
//    ALTER TABLE `useraccount` ADD INDEX(`Username`);
//    ALTER TABLE `useraccount` ADD INDEX(`Email`);
//    ALTER TABLE `useraccount` ADD INDEX(`Active`);
//    ALTER TABLE `userrole` ADD INDEX(`UserAccountID`);
//    ALTER TABLE `userrole` ADD INDEX(`RoleID`);
//
    
    
    //SELECT date_format(receiptdate,'%Y'), count(*) FROM `receipt` left join receiptproductitem on receipt.ReceiptID = receiptproductitem.ReceiptID WHERE EventID in (248,266) group by date_format(receiptdate,'%Y')
    
//    $sql = "select Sku from stocksharing";
//    $selectedRow = getSelectedRow($sql);
//    for($i=0; $i<sizeof($selectedRow); $i++)
//    {
//        $sku = $selectedRow[$i]["Sku"];
//        $sql = "select * from shopeeorder where ShopeeOrder like '%$sku%' where shopeeOrderID betwenn 1128 and 1269";
//        $selectedRow2 = getSelectedRow($sql);
//
//        if(sizeof($selectedRow2)>0)
//        {
//            echo "<br>". $sku;
//        }
//    }
//
//    exit();
    
//    SELECT postcustomer.Telephone, sum(PayPrice) sales FROM `receipt`left join receiptproductitem on Receipt.ReceiptID = receiptproductitem.ReceiptID LEFT JOIN itemtrackingno on receiptproductitem.ReceiptProductItemID = itemtrackingno.ReceiptProductItemID LEFT JOIN postcustomer ON itemtrackingno.PostCustomerID = postcustomer.PostCustomerID GROUP by postcustomer.Telephone ORDER BY sales desc
  
    //update `categorymappingweb` LEFT JOIN mainproduct on categorymappingweb.Sku = mainproduct.Sku set categorymappingweb.LazadaCategoryID = mainproduct.PrimaryCategory WHERE CategoryMappingWebID >100 and CategoryMappingWebID <=500
    
//    $sku = "On-Stage-RS7500-w/MSA7500CB";
//    $url = "https://th-live.slatic.net/p/bb599ef2556c889c2f63aca0aacfabab.jpg";
//    $tmpFileName = str_replace("/","\/",$sku)."-".$index.".jpg";
//    $tmpFileName = $sku."-".$index.".jpg";
//    $currentFolder = getcwd();
//    copy($url, "./tmp/$tmpFileName");
//    $contents = $currentFolder."\\tmp\\$tmpFileName";
//    $c = getApiManagerBigData();
//    $c->method = "jingdong.common.image.UploadFile";
//    $c->param_json = "";
//    $c->param_file = $contents;
//    $resp = $c->call4BigData();
//
//    writeToLog("JdImageUpload result: " . $resp);
//    echo "JdImageUpload result: " . $resp;
//    $openapi_data = json_decode($resp)->openapi_data;
//    $JdUrl = json_decode($openapi_data)->data;
//    echo "<br>".$jdUrl;
////    return $JdUrl;
//    exit();
    
    
//    error_reporting(E_ALL);
//    ini_set('display_errors', 0);
    
    
//    $password = "Ralamusic12";
//    $password = hash('sha256', "$password$salt");
//    echo $password;
//    echo $_SERVER['HTTP_USER_AGENT'];
    
    
//    $sku = $_GET["sku"];
//
//
//    //get from db
//    $sql = "select * from lazadaproducttemp where sellersku = '$sku'";
//    $ret = executeQueryArray($sql);
//    echo $ret[0]->short_description;
//
//    echo "<br><br>";
//
//
//    //get from api
//    $ret = getLazadaProduct($sku);
//    echo $ret->attributes->short_description;
//
//
//
//    exit();
    
    
    
    
    //shopee getallsku and id
//    $variations = getAllSkuShopee();
    
    
    
////  shopee find itemID
//    $sku = $_GET["sku"];
//    $variations = getAllVariationsShopee($sku);
//    writeToLog("all variations:" . json_encode($variations));
//    if(sizeof($variations) > 0)
//    {
//        $variation = $variations[0];
//        $itemID = $variation["item_id"];
//        $variationID = $variation["variation_id"];
//        echo $itemID;
////        $quantity = getStockShopee($itemID,$variationID);
////        return $quantity;
//    }
//    exit();
    
    
//    //jd get product detail by productId
//    $productId = $_GET["productId"];
//    $c2 = getApiManager();
//    $c2->method = "com.productQueryApiService.queryProductById";
//    $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
//    $resp2 = $c2->call();
//    $openapi_data2 = json_decode($resp2)->openapi_data;
//    //    echo $openapi_data;
//    $data2 = json_decode($openapi_data2)->data;
////    echo $resp2;
//    echo json_encode($data2);
//    exit();
//
    
    
//    //jd search by sku
//    //get productid
//    $sku = $_GET["sku"];
//    $c = getApiManager();
//    $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
//    $param = array();
//    $outerId = array();
//    $outerId["outerId"] = $sku;
//    $param["searchSkusByOuterIdParam"] = $outerId;
////    echo json_encode($param);
////    exit();
//    $c->param_json = json_encode($param);
//    $resp = $c->call();
//
//    echo $resp;
//    writeToLog("get product jd skuId result:" . $resp);
//    $openapi_data = json_decode($resp)->openapi_data;
//    $objs = json_decode($openapi_data)->objs;
//
//    exit();
//
//
//
//
//
//    //prepare for delete selected sku in jd and shopee
//    $variations = getAllSkuShopee();
//
//    $sql = "SELECT Sku FROM `mainproducttest`";
//    $selectedRow = getSelectedRow($sql);
////    for($i=3; $i<sizeof($selectedRow); $i++)
//    for($i=20; $i<40; $i++)
//    {
//        $sku = $selectedRow[$i]["Sku"];
//        writeToLog("delete [i,sku]:[$i,$sku]");
//
//        //get productid
////        $sku = $_GET["sku"];
//        $c = getApiManager();
//        $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
//        $param = array();
//        $outerId = array();
//        $outerId["outerId"] = $sku;
//        $param["searchSkusByOuterIdParam"] = $outerId;
//    //    echo json_encode($param);
//    //    exit();
//        $c->param_json = json_encode($param);
//        $resp = $c->call();
//
//    //    echo $resp;
//        writeToLog("get product jd skuId result:" . $resp);
//        $openapi_data = json_decode($resp)->openapi_data;
//        $objs = json_decode($openapi_data)->objs;
//
//
//        $productSkuIds = array();
//        for($j=0; $j<sizeof($objs); $j++)
//        {
//            $productSkuId = array();
//            $productSkuId["productId"] = json_decode($objs[$j])->productId;
//            $productSkuId["skuId"] = json_decode($objs[$j])->skuId;
//            $productSkuIds[] = $productSkuId;
//
//
//            //jd delete by product id
//            $skuId = $productSkuId["skuId"];
//            $c = getApiManager();
//            $c->method = "com.jd.oversea.api.ProductUpdateApiService.deleteSku";
//            $c->param_json = '{"skuId":"' . $skuId . '","locale":"th"}';
//            $resp = $c->call();
//
//            $openapi_data = json_decode($resp)->openapi_data;
//            $code = json_decode($openapi_data)->code;
//            if($code != 200)
//            {
//                echo "[i,sku]:[$i,$sku] delete jd fail";
//            }
//            writeToLog("delete sku jd result:" . $resp);
//        }
//
//
//
//
//
//
//
//        //shopee delete product by id
//        for($j=0; $j<sizeof($variations); $j++)
//        {
//            $variation = $variations[$j];
//            if($variation["item_sku"] == $sku)
//            {
//                $itemID = $variation["item_id"];
//
//                //delete by id
//                $ret = deleteShopeeItemByItemID($itemID);
//                if(!$ret)
//                {
//                    echo "[i,sku]:[$i,$sku] delete shopee fail";
//                }
//
////                break;
//            }
//        }
//    }
//
//
//
////    echo json_encode($productSkuIds);
//
//    exit();
    
   
    
    
//    SELECT *  FROM `wp_kpcode_url_posts` WHERE `kpcode_post_name` LIKE 'slugtest';
//    DELETE from wp_kpcode_url_posts where id in (11901,11902);
//    insert INTO wp_kpcode_url_posts (kpcode_post_id,kpcode_post_name,kpcode_type) values(19995,'testja','posts');
    
//    $productId = 9074566;
//    $productStatus = 1;
//    $skuId = 9074567;
//    $skuStatus = 1;
//
//    $ret = updateSkuStatus($productId,$productStatus,$skuId,$skuStatus);
////    $ret = getJdProduct($productId);
//
//
//    echo json_encode($ret);
//    exit();
//    $sku = $_GET["sku"];
//    $variations = getAllVariationsShopee($sku);
//    echo json_encode($variations);
//    exit();
    
    
//    $orders = getPendingOrdersLazada();
//    echo json_encode($orders);
    
//    $orders = getPendingOrdersJd();
//    echo json_encode($orders);
//    exit();
    
    
//    $ret = getJdProduct(2428150);
//    echo json_encode($ret);
//    exit();
    
//    $c = getApiManager();
//    $c->method = "com.productQueryApiService.querycategory";
//    $param = array();
//    $param["categoryId"] = 1846;//$_GET["categoryId"];
//    $param["locale"] = "th_TH";
//    $c->param_json = json_encode($param);
//    $resp = $c->call();
//
//    $openapi_data = json_decode($resp)->openapi_data;
//    $data = json_decode($openapi_data)->data;
//
//    echo json_encode($data->brands);
//    exit();

//
//
//    $c = getApiManager();
//    $c->method = "jingdong.stock.batchQueryStock";
//    $c->param_json = '{"skuIds":[2428151]}';
//    $resp = $c->call();
//
//    echo $resp;
//    exit();
    
    
//    $c = getApiManager();
//    $c->method = "com.jd.th.pop.open.OrderInformationFacade.listOrder";
//    $data = array();
//    $data["locale"] = "th_TH";
//    $data["page"] = 1;
//    $data["pageSize"] = 100;
//    $data["orderStatusType"] = 2;
//
//    $param["param"] = $data;
//    $c->param_json = json_encode($param);
//    $resp = $c->call();
//
//    writeToLog("getPendingOrdersJd result:" . $resp);
//    $openapi_data = json_decode($resp)->openapi_data;
//    $data = json_decode($openapi_data)->data;
//
//    echo json_encode($data->itemList);
//
//    exit();
    
    
    
    
//    Echoslap-VC201-US 7844318159 9311081
//    Kirlin-Y-362PR-0.3M 6744318638 9311185
//    Presonus-Eris-HD9 5644314361 9311305
//    Echoslap-GFX-TG 6144314454 9311307
//    Echoslap-GFX-TB 5744318953 9311300
//
//    The-One-KBTO-TOM1BK 5444307843 9310803
//    The-One-KBTO-TOM1WH 5844306732 9310791
//    Echoslap-VC201-MEX 4844312820 9310824
//    Echoslap-VC201-UK 6544311303 9310813
    
    
//
    
    
//    $openapi_data = json_decode($resp)->openapi_data;
//    $objs = json_decode($openapi_data)->objs;
   
//    setConnectionValue("RALAMUSICWEB");
//    $dbName = "ralamusi_2018a";
//    $dbUser = "ralamusi_2018a";
//    $dbPassword = "rala*2018";
////    $host = "27.254.86.35";
//    $host = "ralamusic.com";
//    $con=mysqli_connect($host,$dbUser,$dbPassword,$dbName);
//    if($con)
//    {
//        echo "con is not null";
//    }
//    else
//    {
//        echo "con is null";
//    }
//    exit();
//    $timeZone = mysqli_query($con,"SET SESSION time_zone = '+07:00'");
//    mysqli_set_charset($con, "utf8");
//
//
////    setConnectionValue("MINIMALIST");
//    set_time_limit(720);
//    writeToLog("file: " . basename(__FILE__));
//    printAllPost();
//
//
//    $sku = $_GET["sku"];
//    $quantity = $_GET["quantity"];
//
////    $ret = updateStockWordPressSku($sku,$quantity);
////    echo $ret;
//    $ret = getStockWordPressSku($sku);
//    echo $ret;
//    exit();
    
    
    
//    //3531-1575336982582-0
//    $page = 1;//$_GET["page"];
//    $limit = 10;//$_GET["limit"];
//    $sql = "select ProductID from jdproduct where productID>($page-1)*$limit order by jdproductid limit $limit";
//    $selectedRow = getSelectedRow($sql);
//
//
//    for($i=0; $i<sizeof($selectedRow); $i++)
//    {
//        $productId = $selectedRow[$i]["ProductID"];
//
//
//        //***********
//        $c2 = getApiManager();
//        $c2->method = "com.productQueryApiService.queryProductById";
//        $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
//        $resp2 = $c2->call();
//        $openapi_data2 = json_decode($resp2)->openapi_data;
//        $data2 = json_decode($openapi_data2)->data;
//
//        echo json_encode($data2);
//        exit();
//
//        $brandId = $data2->brandId;
//        $categoryId = $data2->categoryId;
//
//
//        $sql = "update jdproduct set categoryID = '$categoryId', brandId = '$brandId', modifieduser = 'bot' where productID = '$productId'";
//        $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
//        if($ret != "")
//        {
//            $ret["message"] = "แก้ไข categoryId, brandId ไม่สำเร็จ (productId:$productId)";
//            mysqli_close($con);
//
//            echo json_encode($ret);
//            exit();
//        }
//    }
//    exit();

//    $mainProductQuantity = 3;
//    $sku = "Daddario-PWPS10-Pin-Black";
//
//
//    //update product in lazada
//        $ret = updateStockQuantityLazadaInApp($sku,$mainProductQuantity);
//        if(!$ret)
//        {
//    //        mysqli_close($con);
//            $failMarketplace[] = "Lazada";
//        }
//
//    //update product in jd
//        $ret = updateStockQuantityJdInApp($sku,$mainProductQuantity);
//        if(!$ret)
//        {
//    //        mysqli_close($con);
//            $failMarketplace[] = "JD";
//        }
//
//    exit();
    
//    Carlsbro-BJJ033B-3M   3961559462
    
//    $sku = $_GET["sku"];
//    echo json_encode(getVariationShopee($sku));
//    exit();
    
    
//    $itemID = 4561471172;
//    echo json_encode(getItemShopee($itemID));
//    exit();
    
//    $itemID = 4961734031;
//    echo json_encode(deleteItemShopee($itemID));
//    exit();
//
    
    $productId = 13492975;
    deleteJdProduct($productId);
    exit();
    
    
//    $sku = $_GET["sku"];
//    echo json_encode(getJdProductSkuIds($sku));
//    exit();
    
    
//    13351880,13351870
    
//    $productId = 13351880;
//    echo json_encode(getJdProduct($productId));
//    exit();
    
    echo json_encode(deleteJdSku(13351870));
    exit();
    
    echo json_encode(deleteJdProduct(13351880));
    exit();
    
    
    $sku = $_GET["sku"];
//    echo "<br>quantity lazada: ".getStockQuantityLazada($sku);
    echo "<br>quantity shopee: ".getStockQuantityShopee($sku);
//    echo "<br>quantity jd: ".getStockQuantityJd($sku);
    exit();



////    productid 3943894 audreyantiquegold40
//    $productSkuIds = getJdProductSkuIds("audreyantiquegold40");
//    echo json_encode($productSkuIds);
//    exit();

    
    //3257 salestate =8,
//    echo json_encode(getJdProduct(2424722));
//    exit();
//

//    $sku = "Ernie-Ball-Prodigy-Black-Mini";
//    $c = getApiManager();
//    $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
//    $c->param_json = '{"searchSkusByOuterIdParam":{"outerId":"' . $sku . '"}}';
//    $resp = $c->call();
//
//    writeToLog("has product search jd result:" . $resp);
//    $openapi_data = json_decode($resp)->openapi_data;
//    $objs = json_decode($openapi_data)->objs;
//    echo json_encode($objs);
//    exit();
//


//    $c = getApiManager();
//    $c->method = "jingdong.gms.ItemModelGlobalService.getItemModelById";
//    $c->param_json = '{"productId": 2426102,"searchCondition": {"containBaseInfo": true,"containImageList": true,"containImageListAll": true,"containProductProperty": true,"containSkuProperty": true,"source": "SELF"}}';
//    $resp = $c->call();
//    $openapi_data = json_decode($resp)->openapi_data;
//    echo $openapi_data;
//    exit();




//    2424722 color หาย appdis,dis หาย
//    2426102 color หาย  'Kazuki-KZ41C-BK', 'KZ41CBK', ''
//4943243 ปกติ อัพเดตได้แล้ว โดยการเอา image ออก แล้วใส่เข้าไปใหม่
    //Ernie-Ball-Prodigy-Large-Shield-Black-P09332 stock 6; 2424722 stock 1  , 4942920
    //product detail new
    $c = getApiManager();
    $c->method = "com.productQueryApiService.queryProductById";
    $c->param_json = '{"productId":"2424722","locale":"th_TH"}';
    $resp = $c->call();
    $openapi_data = json_decode($resp)->openapi_data;
    $data = json_decode($openapi_data)->data;
    echo json_encode($data);
    exit();
    
    
    $imageList = $data->imageList;
    //**** remove key class
    foreach ($imageList as $key => $value)
    {
        for($i=0; $i<sizeof($value); $i++)
        {
            $image = $value[$i];
            $image->colorId = str_replace('®','',$image->colorId);
            unset($image->class);
        }
    }

    
    
    $skuList = $data->skuList;
    for($i=0; $i<sizeof($skuList); $i++)
    {
        $sku = $skuList[$i];
//        $sku->skuStatus = 1;
        $sku->upcCode = str_replace('®','',$sku->upcCode);
        $sku->outerId = str_replace('®','',$sku->upcCode);
    //    $sku->stockNum = 1;
        
        
        //unset sku****
        unset($sku->productCode);
        unset($sku->class);
        //unset sku****
        
        
        
        //unset saleAttrs****
        $saleAttrs = $sku->saleAttrs;
        for($j=0; $j<sizeof($saleAttrs); $j++)
        {
            $saleAttr = $saleAttrs[$j];
            $saleAttr->required = 1;
            $saleAttr->localeName = str_replace('®','',$saleAttr->localeName);
            unset($saleAttr->isEditting);
            unset($saleAttr->checked);
            unset($saleAttr->focus);
            unset($saleAttr->comAttId);
            unset($saleAttr->class);
        }
        //unset saleAttrs****
    }
    
    
    
//    //unset****
    $data->appDescription = $data->appdis;
    $data->pcDescription = $data->dis;
    $data->locale = "th_TH";
    unset($data->wareQD);
    unset($data->templateId);
    unset($data->dis);
    unset($data->applyId);
    unset($data->promiseId);
    unset($data->shelfLife);
    unset($data->class);
    unset($data->appdis);
    unset($data->afterSales);
    unset($data->unit);
    unset($data->descriptionEditType);
    unset($data->categoryStr);
    unset($data->countryOfOrigin);
//    //unset****
    

//    echo json_encode($data);
//    exit();
//
    //update product new
    $c = getApiManager();
    $c->method = "com.productUpdateApiService.saveProduct";
    $param = array();
    $param["updateProductParam"] = $data;

    $c->param_json = json_encode($param);
    $resp = $c->call();

    echo $resp;
    exit();
//
    

    
    
    
    
    
    
    
//    //product list
//    $searchText = $_GET["searchText"];
//    $searchArray = explode(" ",$searchText);
//
////    $productFoundList = array();
////    $sql = "select * from lazadaProduct";
////    $selectedRow = getSelectedRow($sql);
////    for($k=0; $k<sizeof($selectedRow); $k++)
//    {
////        $sellerSku = $selectedRow[$k]["SellerSku"];
//        for($j=3; $j<=4; $j++)
//        {
//            $c = getApiManager();
//            $c->method = "com.productQueryApiService.queryProducts";
//            $c->param_json = '{"queryProductParam":{"saleState":"8","pageNum":"1000","page":"'.$j.'","locale":"th_TH"}}';
//            $resp = $c->call();
//        //    echo $resp;
//            $openapi_data = json_decode($resp)->openapi_data;
//        //    echo $openapi_data;
//            $data = json_decode($openapi_data)->data;
////            echo json_encode($data->datas);
////            exit();
//
//
//            $product = $data->datas[$i];
//
//            for($i=0; $i<sizeof($data->datas); $i++)
//            {
//                $product = $data->datas[$i];
//                $productName = $product->productName;
//                $productId = $product->productId;
//
//                echo $productId;
//
//                //***********
//                $c2 = getApiManager();
//                $c2->method = "com.productQueryApiService.queryProductById";
//                $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
//                $resp2 = $c2->call();
//                $openapi_data2 = json_decode($resp2)->openapi_data;
//                //    echo $openapi_data;
//                $data2 = json_decode($openapi_data2)->data;
//
////                echo json_encode($data2);
////                exit();
//                $skuList = $data2->skuList;
//
//                $sku = $skuList[0];
//                echo $productId.";".$sku->skuId.";".$sku->upcCode.";".$sku->outerId."<br>";
//                //***********
//
//
////                if($sku->upcCode != "")
////                {
////                    echo $productId.";".$sku->upcCode.";".$sku->outerId.";".$productName."<br>";
////                    exit();
////                }
////
////
////
////                $productFound = array();
////                for($k=0; $k<sizeof($searchArray); $k++)
////                {
////                    $search = $searchArray[$k];
////                    if(stripos($productName, $search) !== false)
////                    {
////                        $productFound["productName"] = $productName;
////                        $productFound["found"] = $productFound["found"]?$productFound["found"]+1:1;
////                        $productFound["productId"] = $product->productId;
////                        $productFound["mainSkuId"] = $product->mainSkuId;
////                    }
////                }
////                if($productFound["found"]>0)
////                {
////                    $productFoundList[] = $productFound;
////                }
//            }
//        }
//    }
    
//    usort($productFoundList, function($a, $b) {
//        return $b['found'] <=> $a['found'];
//    });
//
////    for($i=0; $i<sizeof($productFoundList); $i++)
//    for($i=0; $i<10; $i++)
//    {
//        $productFound = $productFoundList[$i];
//        echo $productFound["found"].":".$productFound["productId"].":".$productFound["mainSkuId"].":".$productFound["productName"]."<br>";
//    }

    
        
        
        
        
        
        
        
        
//
//    $sku = $_GET["sku"];
//    $variations = getAllVariationsShopee($sku);
//    echo json_encode($variations);
//    
//    
//    exit();
//    $orders = getShopeeOrders("READY_TO_SHIP");
//    echo json_encode($orders);
//    $k=1;
//    $products = getLazadaProducts();
//    for($i=0; $i<sizeof($products); $i++)
//    {
//        $product = $products[$i];
//        for($j=0; $j<sizeof($product->skus); $j++)
//        {
//            $sku = $product->skus[$j];
//            echo $k.";".$product->attributes->name.";"."".";".$sku->SellerSku.";".$sku->quantity.";".$sku->Images[0].";"."bot".";"."2020-05-25 18:00:00"."<br>";
//            $k++;
//        }
//
//    }
//    echo "quantity".getStockQuantityLazada("audreyantiquegold40");
//    echo updateStockJd("audreyantiquegold40",4);


//    $sku = $_GET["sku"];
//    $c = getApiManager();
//    $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
//    $c->param_json = '{"searchSkusByOuterIdParam":{"outerId":"' . $sku . '"}}';
//    $resp = $c->call();
//
//    $openapi_data = json_decode($resp)->openapi_data;
//    $objs = json_decode($openapi_data)->objs;
//    echo "<br>objs:".json_encode($objs);
//    exit();
//    echo "<br>skuId:".json_decode($objs[0])->skuId;
    
////    //update stock
//    $skuId = 3943885;
//    $quantity = 4;
//    $sid = 0;
//    writeToLog("skuID:".$skuId.", quantity:".$quantity. ", sid:".$sid);
//    $c = getApiManager();
//    $c->method = "jingdong.stock.updateSkuStock";
//    $c->param_json = '{"updateSkuStockParam":{"skuId":"' . $skuId . '","sid":"' . $sid . '","stockNum":"' . $quantity . '"}}';
//    $resp = $c->call();
//    writeToLog($resp);
//    $respObj = json_decode($resp);
//    $dataObj = json_decode($respObj->openapi_data);
//    $resultCode = $dataObj->resultCode;
//    echo $resp;
//    echo "<br>resultCode:".$resultCode;


    
//    $c = getApiManager();
//    $c->method = "jingdong.stock.batchQueryStock";
//    $c->method = "jingdong.stock.getStoreList";
//    $c->param_json = '{"skuIds":[3943876,3943877,3943878,3943879,3943884,3943885]}';//,3943896
//    $resp = $c->call();
//    echo $resp;
//    echo "<br>";
//    echo "<br>";
//    echo "data:" . json_decode($resp)->openapi_data;

?>


//<View style={[{flex:1}]}>
//                    <Image
//                      source={item.Image != ''?{uri: item.Image}:require('./../assets/images/noImage.jpg')}
//                      style={styles.image}
//                    />
//                    <View style={{height:padding.sm}}>
//                    </View>
//                  </View>



//{width:dimensions.fullWidth,height:item.Height/item.Width*dimensions.fullWidth}
