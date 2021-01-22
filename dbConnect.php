<?php
    include "./lazada/LazopSdk.php";
    include "./jdOpenApi.php";
    
    //conection variable
    $con;
    $modifiedUser="bot";
    $globalDBName="SAIM";
    $dbUser = "FFD";
    $appImageUrl = "https://minimalist.co.th/saim";
    $appUrl = "https://minimalist.co.th/saim";
    
    //firebase
    $fcmServerKey = "AAAA0giYusQ:APA91bF_25L92doKo8UJB9qphUAEql8ZuSnDN7dYUdABK8vCnEBOoS8bBCUrYK3O4IYPj52uhXC_ZK0Ek5doR1c5nafP51ixNP23zVV59vEyvlo7491O9DdevylqnXSFp7Rr74wv9yu9";
    
    //line
    $lineNotifyToken = "Iw9r67OTA4B7ZuOqlv0lEiwTipakqPKvaNwqGabZZ2X";
    $lineAdminToken = "nYfj6oMyVaJDSg8QQzGivPXDJMPzXwMI837Egr2gZED";
    
    //wordPress
    $wordPressDB = "mini_2020";
    $testWordPressDB = "test_mini";
    $pointPerBaht = 40;
    $minimumPointSpend = 4000;
    $earnedCheckPoints = "PPRP";
    $redeemCheckPoints = "RP";
    $earnedFactor = 0.1;
    $expirePeriod = 2;
    
    
    //lazada variable minimalist
    $url = "https://api.lazada.co.th/rest";
    $appKey = "117625";
    $appSecret = "qYcpF3J7HWqIeGQMnDGq14XRrEMGxHUS";
    $accessToken = "50000800201yc121a32b9dTobjvNDQEJnOXupEf6IUHKPhxqG8hhv8pzuRX6Bd6e";//miniTokenStart: 16-08-2020 02:04
    $refreshToken = "50001800101cz148ac247GwTpigsuDyiLkP3mUggbGYFHxftSEcigu8ssiU8VGrw";//expire in 15544206 ประมาณปลายตุลา 2020

    
    
//    //lazada variable ralamusic
//    $url = "https://api.lazada.co.th/rest";
//    $appKey = "119433";
//    $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
//    $accessToken = "50000800941srTr7jlPaRTABafvGJra0wDBhET8MyXcneJhakI08S1f443451ibq";//ralaTokenStart: 10-12-2020 11:00
//    $refreshToken = "50001800132cSOwTpBCUzHRjJ9pZOrah6GYiLTeTvGWj12d1cfe9bp4PyLkeT59x";//expire ประมาณ16 feb 2021

    
    //shopee variable minimalist
    $host = "partner.shopeemobile.com";
    $contentType = "application/json";
    $key = "42995589cb4a87c62e3f2b239c43647053736610c1fcae8c9373395d4877a745";
    $partnerID = 845302;
    $shopID = 88091;
    $addressID = 25425270;
    
    
    
//    //shopee variable ralamusic
//    $host = "partner.shopeemobile.com";
//    $contentType = "application/json";
//    $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
//    $partnerID = 845652;
//    $shopID = 1396523;
//    $addressID = 25425270;
    $defaultCategoryID = 24941;
    $defaultAttributeID = 23277;
    $defaultAttributeValue = "ไม่มียี่ห้อ(No Brand)";
    $defaultJdCategoryID = 1853;
    $defaultJdBrandID = 8238;
    
    
    //jd variable minimalist
    $appKeyJd = "222ffa678534712a816efc3535049e41";
    $appSecretJd = "25b12ddf8b66efe51ff35d38460b2bd1";
    $accessTokenJd = "35f6a652e822627337db132c85e5b5c5";
    $serverUrl = "https://open.jd.co.th/api";
    $serverUrlBigData = "https://open.jd.co.th/api_bigdata";
    
    
//    //jd variable ralamusic
//    $appKeyJd = "4f167657105d3afd732653da83fb49a5";
//    $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
//    $accessTokenJd = "64514eb4d73290d0c9974b648058adec";
//    $serverUrl = "http://open.jd.co.th/api";
//
    
    $salt = "FvTivqTqZXsgLLx1v3P8TGRyVHaSOB1pvfm02wvGadj7RLHV8GrfxaZ84oGA8RsKdNRpxdAojXYg9iAj";
    
//    function deleteOrderDeliveryGroup($param)
//    {
//        global $contentType;
//        
//        
//        //create curl
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POST, 1);
////        curl_setopt($ch, CURLOPT_FAILONERROR, true);
//        
//        
//        //url
//        $url = $appUrl . "/SAIMOrderDeliveryGroupDelete.php";
//        curl_setopt($ch, CURLOPT_URL, $url);
//        
//        
//        //payload
//        $payload = json_encode($param,JSON_UNESCAPED_UNICODE);
//        writeToLog("payload:" . $payload);
//        
//        
//        //header
//        $header = array();
//        $header[] = 'Content-Type:' . $contentType;
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
//            writeToLog( "HTTP Status == 503)");
//        }
//          
//        if ($result === false)
//        {
//            print_r('Curl error: ' . curl_error($ch));
//            writeToLog( "Curl Errno returned $curl_errno");
//        }
//        
//        
//        writeToLog("web product insert result:" . $result);
//        $obj = json_decode($result);
//        
//        
//        return $obj;
//    }
    
    function getMainImageBySku($sku)
    {
        global $con;
        $sku = mysqli_real_escape_string($con,$sku);
        
        
        $sql = "select MainImage from mainProduct where sku = '$sku'";
        $mainProductList = executeQueryArray($sql);
        return $mainProductList[0]->MainImage;
    }
    
    function editStockSharingList($param)
    {
        global $contentType;
        global $appUrl;
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
//        $url = "http://www.ralamusic.com/SAIM/SAIMHasWebProductGet.php";
        $url = $appUrl . "/SAIMStockSharingInsertList.php";
        writeToLog("test url:"+$url);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //payload
        $payload = json_encode($param);
        writeToLog("payload:" . $payload);
        
        
        //header
        $header = array();
        $header[] = 'Content-Type:' . $contentType;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("editStockSharingList result:" . $result);
        $obj = json_decode($result);
        
        
        return $obj->success;
    }
    
    function getImageType($filename)
    {
        $image_info = getimagesize($filename);
        if($image_info[2] == 1)
        {
            return "gif";
        }
        else if($image_info[2] == 2)
        {
            return "jpg";
        }
        else if($image_info[2] == 3)
        {
            return "png";
        }
        return "jpg";
    }
    
    function getImageWidth($filename)
    {
        $image_info = getimagesize($filename);
        return $image_info[0];
    }
    
    function getImageHeight($filename)
    {
        $image_info = getimagesize($filename);
        return $image_info[1];
    }
    
    function resizeImage($filename)
    {
        global $globalDBName;
        
        $parts = explode("/",$filename);
        $imageName = $parts[sizeof($parts)-1];
        
        
        //size
        list($width, $height) = getimagesize($filename);
        writeToLog("(width,height):(".$width.",".$height.")");
        $percent = 1;
        if($width > $height)
        {
            if($height >= 2532)
            {
                $percent = 2532.0/$width;
            }
        }
        else
        {
            if($width >= 2532)
            {
                $percent = 2532.0/$height;
            }
        }
        
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        writeToLog("(newwidth,newheight):(".$newwidth.",".$newheight.")");
        
        
        // Load
        $newImage = imagecreatetruecolor($newwidth, $newheight);
        $imageType = getImageType($filename);
        $newFileName = '.\\'.$globalDBName.'\\Images\\'.$imageName;
        
        if($imageType == 'png')
        {
            // integer representation of the color black (rgb: 0,0,0)
            $background = imagecolorallocate($newImage , 0, 0, 0);
            // removing the black from the placeholder
            imagecolortransparent($newImage, $background);

            // turning off alpha blending (to ensure alpha channel information
            // is preserved, rather than removed (blending with the rest of the
            // image in the form of black))
            imagealphablending($newImage, false);

            // turning on alpha channel information saving (to ensure the full range
            // of transparency is preserved)
            imagesavealpha($newImage, true);
        }
        
        if($imageType == "png")
        {
            $source = imagecreatefrompng($filename);
            // Resize
            imagecopyresized($newImage, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            // Output
            imagepng($newImage,$newFileName);
        }
        else if($imageType == "jpg")
        {
            $source = imagecreatefromjpeg($filename);
            // Resize
            imagecopyresized($newImage, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            // Output
            imagejpeg($newImage,$newFileName);
        }
        else if($imageType == "gif")
        {
            $source = imagecreatefromgif($filename);
            // Resize
            imagecopyresized($newImage, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            // Output
            imagegif($newImage,$newFileName);
        }
    }
    
    function cors()
    {
        writeToLog("cors");
        header('Content-Type: application/json');
//        header('Access-Control-Allow-Origin: *');
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN']))
        {
            writeToLog("HTTP_ORIGIN:" . isset($_SERVER['HTTP_ORIGIN']));
            writeToLog("HTTP_ORIGIN:" . $_SERVER['HTTP_ORIGIN']);
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            writeToLog("REQUEST_METHOD:" . isset($_SERVER['REQUEST_METHOD']));
            writeToLog("REQUEST_METHOD:" . $_SERVER['REQUEST_METHOD']);
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            {
                writeToLog("HTTP_ACCESS_CONTROL_REQUEST_METHOD:" . isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']));
                writeToLog("HTTP_ACCESS_CONTROL_REQUEST_METHOD:" . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']);
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }
            

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            {
                writeToLog("HTTP_ACCESS_CONTROL_REQUEST_HEADERS:" . isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']));
                writeToLog("HTTP_ACCESS_CONTROL_REQUEST_HEADERS:" . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
            

            exit(0);
        }

//    echo "You have CORS!";
    }
    
    function getApiManager()
    {
        global $appKeyJd;
        global $appSecretJd;
        global $accessTokenJd;
        global $serverUrl;
        
        $c = new ApiManager();
        
        $c->appKey = $appKeyJd;
        $c->appSecret = $appSecretJd;
        $c->accessToken = $accessTokenJd;
        $c->serverUrl = $serverUrl;
        
        return $c;
    }
    
    function getApiManagerBigData()
    {
        global $appKeyJd;
        global $appSecretJd;
        global $accessTokenJd;
        global $serverUrlBigData;
        
        $c = new ApiManager();
        
        $c->appKey = $appKeyJd;
        $c->appSecret = $appSecretJd;
        $c->accessToken = $accessTokenJd;
        $c->serverUrl = $serverUrlBigData;
        
        return $c;
    }
        
    function JdImageUpload($url,$tmpFileName)
    {
        $currentFolder = getcwd();
        copy($url, "./tmp/$tmpFileName");
        $contents = $currentFolder."\\tmp\\$tmpFileName";
        $c = getApiManagerBigData();
        $c->method = "jingdong.common.image.UploadFile";
        $c->param_json = "";
        $c->param_file = $contents;
        $resp = $c->call4BigData();
        
        writeToLog("JdImageUpload result: " . $resp);
        $openapi_data = json_decode($resp)->openapi_data;
        $JdUrl = json_decode($openapi_data)->data;
        return $JdUrl;
    }
    
    function getJdProductByProductId($productId)
    {
        $c = getApiManager();
        $c->method = "com.productQueryApiService.queryProductById";
        $c->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
        $resp = $c->call();
        $openapi_data = json_decode($resp)->openapi_data;
        $data = json_decode($openapi_data)->data;
    //    echo $resp;
//        echo json_encode($data);
        writeToLog("getJdProductByProductId result:" . $resp);
        return $data;
    }
    
    function updateSkuStatus($productId,$productStatus,$skuId,$skuStatus)
    {
        $c = getApiManager();
        $c->method = "jingdong.gms.ItemModelGlobalService.updateItemModelSaleStates";
        $param = array();
        
        $skuStatussSet = array();
        $skuStatuss = array();
        $skuStatuss["skuId"] = $skuId;
        $skuStatuss["skuStatus"] = $skuStatus;
        $skuStatuss["yn"] = 1;
        $skuStatussSet[] = $skuStatuss;
        
        
        $itemStatus = array();
        $itemStatus["ip"] = "127.0.0.1";
        $itemStatus["source"] = "SELF";
        $itemStatus["opName"] = "erp";
        $itemStatus["productId"] = $productId;
        $itemStatus["productStatus"] = $productStatus;
        $itemStatus["yn"] = 1;
        $itemStatus["skuStatussSet"] = $skuStatussSet;
        
                
        $param["itemStatus"] = $itemStatus;
        $c->param_json = json_encode($param);
        $resp = $c->call();
        writeToLog("updateSkuStatus result:" . $resp);
        
        $openapi_data = json_decode($resp)->openapi_data;
        $success = json_decode($openapi_data)->success;
        return $success;
    }
    
    function getJdBrands($categoryId)
    {
        $c = getApiManager();
        $c->method = "com.productQueryApiService.querycategory";
        $param = array();
        $param["categoryId"] = $categoryId;
        $param["locale"] = "th_TH";
        $c->param_json = json_encode($param);
        $resp = $c->call();

        $openapi_data = json_decode($resp)->openapi_data;
        $data = json_decode($openapi_data)->data;
        
        return $data->brands;
    }
    
    function getPendingOrdersJd()
    {
        $orders = array();
        for($i=0; $i<4; $i++)//set max 4 times
        {
            $c = getApiManager();
            $c->method = "com.jd.th.pop.open.OrderInformationFacade.listOrder";
            $data = array();
            $data["locale"] = "th_TH";
            $data["page"] = $i;
            $data["pageSize"] = 100;
            $data["orderStatusType"] = 2;
            
            $param["param"] = $data;
            $c->param_json = json_encode($param);
            $resp = $c->call();
            
            writeToLog("getPendingOrdersJd result:" . $resp);
            $openapi_data = json_decode($resp)->openapi_data;
            $data = json_decode($openapi_data)->data;
            
            $orders = array_merge($orders,$data->itemList);
            if(sizeof($data->itemList) < 100)
            {
                break;
            }
        }
       
        return $orders;
    }
    
    function getOrderItemJD($orderId)
    {
        $c = getApiManager();
        $c->method = "jingdong.PopAdminExport.queryOrderDetail";
        $data = array();
        $data["orderId"] = $orderId;
        
        
        $c->param_json = json_encode($data);
        $resp = $c->call();
        writeToLog("getOrderItemJD:" . $resp);
        
        
        $openapi_data = json_decode($resp)->openapi_data;
        $orderInfo = json_decode($openapi_data)->orderInfoVO;
        
        return $orderInfo;
    }
    
    function getJdSkuById($id)
    {
        $sql = "select Sku from jdProduct where skuId = '$id'";
        $jdProductList  = executeQueryArray($sql);
        return $jdProductList[0]->Sku;
    }
    
    function getOrderIdByWaybillNumber($waybillNumber)
    {
        $sql = "select OrderNo from jdOrder where waybillNumber = '$waybillNumber'";
        $jdOrderList  = executeQueryArray($sql);
        return $jdOrderList[0]->OrderNo;
    }
    
    function getWaybillNumberJd($orderId)
    {
        $c = getApiManager();
        $c->method = "jingdong.PlaceOrderServiceJsf.getPreWaybillCodeForOpenApi";
        $reqWaybillCodeDTO = array();
        $reqWaybillCodeDTO["orderId"] = $orderId;
        
        $data = array();
        $data["reqWaybillCodeDTO"] = $reqWaybillCodeDTO;
        
        writeToLog("getWaybillNumberJd data:".$data);
        $c->param_json = json_encode($data);
        $resp = $c->call();
        writeToLog("getWaybillNumberJd:" . $resp);
        
        
        $openapi_data = json_decode($resp)->openapi_data;
        $data = json_decode($openapi_data)->data;
        
        return $data->waybillCode;
    }
    
    function insertJdProductCurl($param)
    {
        global $contentType;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "http://www.minimalist.co.th/saim/SAIMJdProductInsert.php";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //payload
        $payload = json_encode($param,JSON_UNESCAPED_UNICODE);
        writeToLog("payload:" . $payload);
        
        
        //header
        $header = array();
        $header[] = 'Content-Type:' . $contentType;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("jd product insert result:" . $result);
        $obj = json_decode($result);
        
        
        return $obj->success;
    }
    
    function insertJdProduct($data)
    {
        writeToLog(json_encode($data));
        
        $c = getApiManager();
        $c->method = "com.productUpdateApiService.saveProduct";
        $param = array();
        $param["updateProductParam"] = $data;
        $c->param_json = json_encode($param);
        $resp = $c->call();
        
        writeToLog("insertJdProduct result:" . $resp);
        $openapi_data = json_decode($resp)->openapi_data;
        $code = json_decode($openapi_data)->code;
        $message = json_decode($openapi_data)->message;
        $productId = json_decode($openapi_data)->data;
        
        if($code == 200)
        {
            $ret = getJdProduct($productId);
            $skuId = $ret->skuList[0]->skuId;
            $retUpdate = updateSkuStatus($productId,1,$skuId,1);
            
            return array("code"=>$code,"message"=>$message,"productId"=>$productId,"skuId"=>$skuId);
        }
        else
        {
            return array("code"=>$code,"message"=>$message);
        }
        
        return array("code"=>'',"message"=>'');
    }
    
    function updateJdProduct($data)
    {
        writeToLog(json_encode($data));
        
        $c = getApiManager();
        $c->method = "com.productUpdateApiService.saveProduct";
        $param = array();
        $param["updateProductParam"] = $data;
        $c->param_json = json_encode($param);
        $resp = $c->call();
        
        writeToLog("insertJdProduct result:" . $resp);
        $openapi_data = json_decode($resp)->openapi_data;
        $code = json_decode($openapi_data)->code;
        $message = json_decode($openapi_data)->message;
        $productId = json_decode($openapi_data)->data;
        
        if($code == 200)
        {
            $ret = getJdProduct($productId);
            $skuId = $ret->skuList[0]->skuId;
//            $retUpdate = updateSkuStatus($productId,1,$skuId,1);
            
            return array("code"=>$code,"message"=>$message,"productId"=>$productId,"skuId"=>$skuId);
        }
        else
        {
            return array("code"=>$code,"message"=>$message);
        }
        
        return array("code"=>'',"message"=>'');
    }
    
    function hasJdProduct($sku)
    {
        $c = getApiManager();
        $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
        $c->param_json = '{"searchSkusByOuterIdParam":{"outerId":"' . $sku . '"}}';
        $resp = $c->call();
        
        writeToLog("has product search jd result:" . $resp);
        $openapi_data = json_decode($resp)->openapi_data;
        $objs = json_decode($openapi_data)->objs;
        return sizeof($objs)>0;
    }
    
    function deleteJdProduct($productId)
    {
        $c = getApiManager();
        $c->method = "com.jd.oversea.api.ProductUpdateApiService";
        $c->param_json = '{"productId":"' . $productId . '","locale":"en"}';
        $resp = $c->call();
        
        writeToLog("deleteJdProduct result:" . $resp);
//        $openapi_data = json_decode($resp)->openapi_data;
//        $objs = json_decode($openapi_data)->objs;
        return $resp;
    }
    
    function deleteJdSku($skuId)
    {
        $c = getApiManager();
        $c->method = "com.jd.oversea.api.ProductUpdateApiService.deleteSku";
        $c->param_json = '{"skuId":"' . $skuId . '","locale":"en"}';
        $resp = $c->call();
        
        writeToLog("deleteJdSku result:" . $resp);
//        $openapi_data = json_decode($resp)->openapi_data;
//        $objs = json_decode($openapi_data)->objs;
        return $resp;
    }
    
    function getNormalOrUnListProductsJD($sku)
    {
        $skuVariations = getJdProductSkuIds($sku);
        
        $jdProducts = array();
        if($skuVariations)
        {
            for($i=0; $i<sizeof($skuVariations); $i++)
            {
                $variation = $skuVariations[$i];
                $productId = $variation->productId;
                $skuId = $variation->skuId;
                
                $jdProduct = getJdProduct($productId);
                for($j=0; $j<sizeof($jdProduct->skuList); $j++)
                {
                    $jdSku = $jdProduct->skuList[$j];
                    if($jdSku->outerId == $sku && $jdSku->skuStatus != 10)
                    {
                        $jdProducts[] = $skuVariations[$i];
                        break;
                    }
                }
            }
        }
        
        if(sizeof($jdProducts)>0)
        {
            return $jdProducts;
        }
        return null;
        
    }
    
    function hasItemJd($productId,$skuId)
    {
        $jdProduct = getJdProduct($productId);
        for($j=0; $j<sizeof($jdProduct->skuList); $j++)
        {
            $jdSku = $jdProduct->skuList[$j];
            if($jdSku->outerId == $sku && $jdSku->skuStatus != 10)
            {
                return true;
            }
        }
        return false;
    }
    
    function getJdProductSkuIds($sku)
    {
        writeToLog("sku:".$sku);
        $c = getApiManager();
        $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
        $c->param_json = '{"searchSkusByOuterIdParam":{"outerId":"' . $sku . '"}}';
        $resp = $c->call();
        
        writeToLog("get product jd skuId result:" . $resp);
        $openapi_data = json_decode($resp)->openapi_data;
        $objs = json_decode($openapi_data)->objs;
         
        
        $productSkuIds = array();
        for($i=0; $i<sizeof($objs); $i++)
        {
            $productSkuId = array();
            $productSkuId["productId"] = json_decode($objs[$i])->productId;
            $productSkuId["skuId"] = json_decode($objs[$i])->skuId;
            $productSkuIds[] = $productSkuId;
        }
        return $productSkuIds;
    }
    
    function getJdProductSkuIdsInApp($sku)
    {
        global $con;
        
        $sku = mysqli_real_escape_string($con,$sku);
        $sql = "select * from jdProduct where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        $productSkuIds = array();
        for($i=0; $i<sizeof($selectedRow); $i++)
        {
            $jdProduct = $selectedRow[$i];
            $productSkuId = array();
            $productSkuId["productId"] = $jdProduct["ProductId"];
            $productSkuId["skuId"] = $jdProduct["SkuId"];
            $productSkuIds[] = $productSkuId;
        }
        return $productSkuIds;
    }
    
    function updateStockQuantityJd($sku,$quantity)
    {
        $productSkuIds = getJdProductSkuIdsInApp($sku);
        for($i=0; $i<sizeof($productSkuIds); $i++)
        {
            $skuId = $productSkuIds[$i]["skuId"];
            $ret = updateStockJd($skuId,$quantity,0);
            if(!$ret)
            {
                //roll back updated
                for($j=0; $j<$i; $j++)
                {
                    $skuId = $productSkuIds[$j]["skuId"];
                    updateStockJd($skuId,$quantity,0);
                }
                return false;
            }
        }
        return true;
    }
    
    function updateStockQuantityJdInApp($sku,$quantity)
    {
        $productSkuIds = getJdProductSkuIdsInApp($sku);
        for($i=0; $i<sizeof($productSkuIds); $i++)
        {
            $skuId = $productSkuIds[$i]["skuId"];
            $ret = updateStockJd($skuId,$quantity,0);
            if(!$ret)
            {
                //roll back updated
                for($j=0; $j<$i; $j++)
                {
                    $skuId = $productSkuIds[$j]["skuId"];
                    updateStockJd($skuId,$quantity,0);
                }
                return false;
            }
        }
        return true;
    }
    
    function updateStockJd($skuId,$quantity,$sid)
    {
        writeToLog("skuID:".$skuId.", quantity:".$quantity. ", sid:".$sid);
        $c = getApiManager();
        $c->method = "jingdong.stock.updateSkuStock";
        $c->param_json = '{"updateSkuStockParam":{"skuId":"' . $skuId . '","sid":"' . $sid . '","stockNum":"' . $quantity . '"}}';
        $resp = $c->call();
        writeToLog("update stock jd result:".$resp);
                
        $openapi_data = json_decode($resp)->openapi_data;
        $resultCode = json_decode($openapi_data)->resultCode;
        
        return $resultCode == 1?true:false;
    }
    
    function getStockQuantityJd($sku)
    {
        $productSkuIds = getJdProductSkuIds($sku);
        if(sizeof($productSkuIds)>0)
        {
            $productId = $productSkuIds[0]["productId"];
            $skuId = $productSkuIds[0]["skuId"];
            
            writeToLog("get quantity productId: ". $productId . ", skuId: ". $skuId);
            $product = getJdProduct($productId);
            $skuList = $product->skuList;
            for($i=0; $i<sizeof($skuList); $i++)
            {
                $skuItem = $skuList[$i];
                if($skuItem->outerId == $sku)
                {
                    return $skuItem->stockNum;
                }
            }
        }
        return 0;
    }
    
    function getJdProduct($productId)
    {
        $c = getApiManager();
        $c->method = "com.productQueryApiService.queryProductById";
        $c->param_json = '{"productId":"' . $productId . '","locale":"en_US"}';
        $resp = $c->call();
        writeToLog("get jd product result:".$resp);
        
        $openapi_data = json_decode($resp)->openapi_data;
        $data = json_decode($openapi_data)->data;
        
        return $data;
    }
    
    function hasLazadaProductInRala($sku)
    {
        $sql = "select Sku from lazadaProduct where SellerSku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        return sizeof($selectedRow)>0;
    }
    
    function hasShopeeProductInRala($sku)
    {
        $sql = "select Sku from shopeeProduct where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        return sizeof($selectedRow)>0;
    }
    
    function hasJdProductInRala($sku)
    {
        $sql = "select Sku from jdProduct where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        return sizeof($selectedRow)>0;
    }
    
    function hasMainProduct($sku)
    {
        global $con;
        
        $sku = mysqli_real_escape_string($con,$sku);
        $sql = "select Sku from mainProduct where Sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        return sizeof($selectedRow)>0;
    }

    function hasLazadaProductInApp($sku)
    {
        global $con;
        
        $sku = mysqli_real_escape_string($con,$sku);
        $sql = "select Sku from lazadaProduct where Sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        return sizeof($selectedRow)>0;
    }
    
    function hasShopeeProductInApp($sku)
    {
        global $con;
        
        $sku = mysqli_real_escape_string($con,$sku);
        $sql = "select Sku from shopeeProduct where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        return sizeof($selectedRow)>0;
    }
    
    function hasJdProductInApp($sku)
    {
        global $con;
        
        $sku = mysqli_real_escape_string($con,$sku);
        $sql = "select Sku from jdProduct where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        return sizeof($selectedRow)>0;
    }
    
    function lazadaOrderExist($orderNo)
    {
        $sql = "select Sku from lazadaOrder where orderNo = '$orderNo'";
        $lazadaOrderList = executeQueryArray($sql);
        
        return sizeof($lazadaOrderList);
    }
    
    function shopeeOrderExist($orderSn)
    {
        $sql = "select ShopeeOrderID from shopeeOrder where orderNo = '$orderSn'";
        $shopeeOrderList = executeQueryArray($sql);
        
        return sizeof($shopeeOrderList);
    }
        
    function insertShopeeProductCurl($param)
    {
        global $contentType;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "http://www.minimalist.co.th/saim/SAIMShopeeProductInsert2.php";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //payload
        $payload = json_encode($param,JSON_UNESCAPED_UNICODE);
        writeToLog("payload:" . $payload);
        
        
        //header
        $header = array();
        $header[] = 'Content-Type:' . $contentType;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("shopee product insert result:" . $result);
        $obj = json_decode($result);
        
        
        return $obj->success;
    }
    
    function deleteShopeeItemByItemID($itemID)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);


        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/delete";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();


        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["item_id"] = intval($itemID);
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);


        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));


        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }

        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        writeToLog("delete shopee by ItemID result: " . $result);
        
        $resultObj = json_decode($result);
        return $resultObj->item_id > 0;
    }
    
    function updateShopeeStock($itemID,$stock)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);


        //url
        $url = "https://partner.shopeemobile.com/api/v1/items/update_stock";
        curl_setopt($ch, CURLOPT_URL, $url);


        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();


        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["item_id"] = intval($itemID);
        $paramBody["stock"] = intval($stock);
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);


        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);


        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));


        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

    

        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }

        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        writeToLog("update shopee stock result: " . $result);
        return json_decode($result);
    }
    
    function updateShopeePrice($itemID,$price)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);


        //url
        $url = "https://partner.shopeemobile.com/api/v1/items/update_price";
        curl_setopt($ch, CURLOPT_URL, $url);


        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();


        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["item_id"] = intval($itemID);
        $paramBody["price"] = floatval($price);
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);


        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);


        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));


        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

    

        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }

        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        writeToLog("update shopee price result: " . $result);
        return json_decode($result);
    }
    
    function updateShopeeImages($itemID,$images)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);


        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/img/update";
        curl_setopt($ch, CURLOPT_URL, $url);


        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();


        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["item_id"] = intval($itemID);
        $paramBody["images"] = $images;
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);


        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);


        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));


        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

    

        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }

        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        writeToLog("update shopee images result: " . $result);
        return json_decode($result);
    }
    
    function insertShopeeProduct($paramBody)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);


        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/add";
        curl_setopt($ch, CURLOPT_URL, $url);


//        //param
//        $date = new DateTime();
//        $timestamp = $date->getTimestamp();


        //payload
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);


        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);


        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));


        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

    

        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }

        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        writeToLog("add shopee item result: " . $result);
        return json_decode($result);
    }
    
    function updateItemImageShopee($itemID, $images)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);


        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/img/update";
        curl_setopt($ch, CURLOPT_URL, $url);


        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
        
        
        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["item_id"] = $itemID;
        $paramBody["images"] = $images;
        


        //payload
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);


        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);


        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));


        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

    

        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }

        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        writeToLog("add shopee item result: " . $result);
        return json_decode($result);
    }
    
    function updateShopeeProduct($paramBody)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/update";
        curl_setopt($ch, CURLOPT_URL, $url);


//        //param
//        $date = new DateTime();
//        $timestamp = $date->getTimestamp();


        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);


        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);


        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));


        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );


        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }

        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }

        writeToLog("update shopee item result: " . $result);
        return json_decode($result);
    }
    
    function getShopeeAttributes($categoryID)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        $url = "https://partner.shopeemobile.com/api/v1/item/attributes/get";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
        
        
        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["category_id"] = $categoryID;
        
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
//        writeToLog("get shopee attributes: " . ($result != null));
//        writeToLog("get shopee attributes result: " . ($result));
        return json_decode($result)->attributes;
    }
    
    function setPickupTime($orderSn,$pickupTimeID)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        global $addressID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/logistics/init";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
        $pickup = array();
        $pickup["address_id"] = $addressID;
        $pickup["pickup_time_id"] = $pickupTimeID;
        

        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["ordersn"] = $orderSn;
        $paramBody["pickup"] = $pickup;
        
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("time slot:" . $result);
//        $obj = json_decode($result);
    }
    
    function getShopeePickupTimeID($orderSn)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        global $addressID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/logistics/timeslot/get";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
      
        

        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["ordersn"] = $orderSn;
        $paramBody["address_id"] = $addressID;
        
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("time slot:" . $result);
        $obj = json_decode($result);
        if($obj->msg)
        {
            sendNotiToAdmin("cannot get shopee time slot: ". $obj->msg);
        }
        for($i=0; $i<sizeof($obj->pickup_time); $i++)
        {
            $pickupTimeID = $obj->pickup_time[$i]->pickup_time_id;
            $weekday = date('N', $pickupTimeID);
            if($weekday != 1)//mon no pickup
            {
                break;
            }
        }
        return $pickupTimeID;
    }
    
    function saveAndGetPostCustomerID($name,$fullAddress,$zipCode,$phone)
    {
        global $con;

        $sql = "select * from postCustomer where firstName like '%$name%' and street1 like '%$fullAddress%' and postCode = '$zipCode' and telephone = '$phone'";
        mysqli_set_charset($con, "tis-620");
        $selectedRow = getSelectedRow($sql);
//        echo "size of postcustomer:" . sizeof($selectedRow) . "<br>";
        if(sizeof($selectedRow) > 0)
        {
            $postCustomerID = $selectedRow[0]["PostCustomerID"];
        }
        else
        {
            $telephone = $phone;
            $sql = "select * from postCustomer where telephone = '$telephone'";
            $selectedRow = getSelectedRow($sql);
            if(sizeof($selectedRow) == 0)
            {
                $sql = "INSERT INTO `customer`(`ModifiedDate`) VALUES (now())";
                $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                if($ret != "")
                {
                    mysqli_rollback($con);
            //        putAlertToDevice($_POST["modifiedUser"]);
                    echo json_encode($ret);
                    exit();
                }
                $customerID = mysqli_insert_id($con);
            }
            else
            {
                $customerID = $selectedRow[0]["CustomerID"];
            }
            
            $firstName = $name;
            $street1 = $fullAddress;
            $postcode = $zipCode;
            $telephone = $phone;
            //query statement
            $sql = "INSERT INTO PostCustomer(CustomerID, FirstName, Street1, Postcode, Country, Telephone, LineID, FacebookID, EmailAddress, TaxCustomerName, TaxCustomerAddress, TaxNo, Other) VALUES ('$customerID', '$firstName', '$street1', '$postcode', '$country', '$telephone', '$lineID', '$facebookID', '$emailAddress', '$taxCustomerName', '$taxCustomerAddress', '$taxNo', '$other')";
            $ret = doQueryTask2($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_rollback($con);
        //        putAlertToDevice($_POST["modifiedUser"]);
                echo json_encode($ret);
                exit();
            }
            
            //insert ผ่าน
            $newID = mysqli_insert_id($con);
            $postCustomerID = $newID;
        }
        return $postCustomerID;
    }
    
    function getPreOrderProductList($searchSku,$quantity)
    {
        $sql = "select * from product left join productName on product.productCategory2 = productName.ProductCategory2 and product.productCategory1 = productName.ProductCategory1 and product.productName = productName.code left join color on product.color = color.code left join productSize on product.size = productSize.code where replace(concat(productName.name,color.name,case when productSize.sizeLabel = '-' then '' else productSize.sizeLabel end),' ','') = '$searchSku' and status = 'I' and eventID not in (332) order by eventID";
        $preOrderProductListTemp = executeQueryArray($sql);
        $preOrderProductList = array();
        for($i=0; $i<sizeof($preOrderProductListTemp) && $i<$quantity; $i++)
        {
            $preOrderProductList[] = $preOrderProductListTemp[$i];
        }
        return $preOrderProductList;
    }
    
    function createAppOrder($param)
    {
        global $contentType;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "http://www.jinglejill.com/saim/SAIMReceiptAndProductBuyInsert9.php";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //payload
        $payload = json_encode($param);
        writeToLog("payload:" . $payload);
        
        
        //header
        $header = array();
        $header[] = 'Content-Type:' . $contentType;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        $obj = json_decode($result);
        writeToLog("create app result:" . $result);
        
        if($obj->status != 1)
        {
            $message = "create app order fail, channel: " . $param["Channel"] . ", order no.:" . $param["OrderNo"];
            sendNotiToAdmin($message);
        }
        return $obj->status;
    }
    
    function getShopeeOrders($status)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/orders/get";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
        
        

        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["order_status"] = $status;
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("orders: " . $result);
        $obj = json_decode($result);
        
        
        return $obj->orders;
    }

    function getShopeeOrder($orderSn)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/orders/detail";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
        $orderSnList = array();
        $orderSnList[] = $orderSn;
        

        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["ordersn_list"] = $orderSnList;
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("order:" . $result);
        $obj = json_decode($result);
        
        
        
        return $obj;
    }
    
    function getStockQuantityShopee($sku)
    {
        $variations = getAllVariationsShopee($sku);
        writeToLog("all variations:" . json_encode($variations));
        if(sizeof($variations) > 0)
        {
            $variation = $variations[0];
            $itemID = $variation["item_id"];
            $variationID = $variation["variation_id"];
            $quantity = getStockShopee($itemID,$variationID);
            return $quantity;
        }
        
        return 0;
    }
    
    function getVariationShopee($sku)
    {
        $variations = getAllVariationsShopee($sku);
        if(sizeof($variations) > 0)
        {
            $variation = $variations[0];
            
            return $variation;
        }
        return null;
    }
    
    //in case of multiple link of the same sku
    function updateStockToAllSkuInOrder2($orderObj)
    {
        global $globalDBName;
        
        $fail = 0;
        $updateItems = array();
        $updateVariations = array();
        $items = $orderObj->orders[0]->items;
        for($i=0; $i<sizeof($items); $i++)
        {
            $item = $items[$i];
            $itemID = $item->item_id;
            $variationID = $item->variation_id;
            $itemSku = $item->item_sku;
            $variationSku = $item->variation_sku;
            if(strpos($itemSku, "middleton") !== false && strlen($itemSku) > 9)
            {
                continue;
            }
            
            //for both case with/without variation
            writeToLog("itemID:" . $itemID . ", variationID:".$variationID);
            $quantity = getStockShopee($itemID,$variationID);
            writeToLog("quantity:" . $quantity);
            if($variationID != "")
            {
                writeToLog("variationSku:" . $variationSku);
                $searchSku = $variationSku;
            }
            else
            {
                writeToLog("itemSku:" . $itemSku);
                $searchSku = $itemSku;
            }
            
            if($searchSku == "")
            {
                return;
            }
            
//            if($globalDBName == "RALAMUSIC" || $globalDBName == "RALAMUSICTEST")
//            {
//                $variations = getAllVariationsShopeeInApp($searchSku);
//            }
//            else
//            {
//                $variations = getAllVariationsShopee($searchSku);
//            }
            
            $sql = "select * from (select Sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$searchSku') UNION select '$searchSku' as Sku)a order by Sku";
            $selectedRow = getSelectedRow($sql);
            for($j=0; $j<sizeof($selectedRow); $j++)
            {
                $searchSku = $selectedRow[$j]["Sku"];
                
                
                $variations = getAllVariationsShopeeInApp($searchSku);
                
                
                
                writeToLog("all variations:" . json_encode($variations));
                if(sizeof($variations) > 5)//เพื่อกันไม่ให้ update stock กรณีค้นหา variations ผิด และออกมามากเกิน
                {
                    return;
                }
                for($k=0; $k<sizeof($variations); $k++)
                {
                    $variation = $variations[$k];
                    $stock = 0;
                    $stock += $quantity;
                    
                    if($variation["item_id"]==$itemID && $variation["variation_id"]==$variationID)
                    {
                        continue;
                    }
                    
                    //update stock
                    $updateVariation = array();
                    $updateVariation["item_id"] = $variation["item_id"];
                    $updateVariation["variation_id"] = $variation["variation_id"];
                    $updateVariation["stock"] = $stock;
                    
                    if($variation["variation_id"] > 0)
                    {
                        $updateVariations[] = $updateVariation;
                    }
                    else
                    {
                        $updateItems[] = $updateVariation;
                    }
                }
            }
            
        }
        
        writeToLog( "update items:" . json_encode($updateItems));
        writeToLog( "update variations:" . json_encode($updateVariations));
        if(sizeof($updateItems)>0)
        {
            $ret = updateStockBatchShopee($updateItems,"items");
            if(!$ret)
            {
                $fail++;
            }
        }
        if(sizeof($updateVariations)>0)
        {
            $ret = updateStockBatchShopee($updateVariations,"vars");
            if(!$ret)
            {
                $fail++;
            }
        }
        
        return $fail == 0;
    }
    
    function updateStockToAllSkuInOrder($orderObj)
    {
        global $globalDBName;
        
        $fail = 0;
        $updateItems = array();
        $updateVariations = array();
        $items = $orderObj->orders[0]->items;
        for($i=0; $i<sizeof($items); $i++)
        {
            $item = $items[$i];
            $itemID = $item->item_id;
            $variationID = $item->variation_id;
            $itemSku = $item->item_sku;
            $variationSku = $item->variation_sku;
            if(strpos($itemSku, "middleton") !== false && strlen($itemSku) > 9)
            {
                continue;
            }
            
            //for both case with/without variation
            writeToLog("itemID:" . $itemID . ", variationID:".$variationID);
            $quantity = getStockShopee($itemID,$variationID);
            writeToLog("quantity:" . $quantity);
            if($variationID != "")
            {
                writeToLog("variationSku:" . $variationSku);
                $searchSku = $variationSku;
            }
            else
            {
                writeToLog("itemSku:" . $itemSku);
                $searchSku = $itemSku;
            }
            
            if($searchSku == "")
            {
                return;
            }
            
            if($globalDBName == "RALAMUSIC" || $globalDBName == "RALAMUSICTEST")
            {
                $variations = getAllVariationsShopeeInApp($searchSku);
            }
            else
            {
                $variations = getAllVariationsShopee($searchSku);
            }
            
            writeToLog("all variations:" . json_encode($variations));
            if(sizeof($variations) > 5)//เพื่อกันไม่ให้ update stock กรณีค้นหา variations ผิด และออกมามากเกิน
            {
                return;
            }
            for($j=0; $j<sizeof($variations); $j++)
            {
                $variation = $variations[$j];
                $stock = 0;
                $stock += $quantity;
                
                if($variation["item_id"]==$itemID && $variation["variation_id"]==$variationID)
                {
                    continue;
                }
                
                //update stock
                $updateVariation = array();
                $updateVariation["item_id"] = $variation["item_id"];
                $updateVariation["variation_id"] = $variation["variation_id"];
                $updateVariation["stock"] = $stock;
                
                if($variation["variation_id"] > 0)
                {
                    $updateVariations[] = $updateVariation;
                }
                else
                {
                    $updateItems[] = $updateVariation;
                }
            }
        }
        
        writeToLog( "update items:" . json_encode($updateItems));
        writeToLog( "update variations:" . json_encode($updateVariations));
        if(sizeof($updateItems)>0)
        {
            $ret = updateStockBatchShopee($updateItems,"items");
            if(!$ret)
            {
                $fail++;
            }
        }
        if(sizeof($updateVariations)>0)
        {
            $ret = updateStockBatchShopee($updateVariations,"vars");
            if(!$ret)
            {
                $fail++;
            }
        }
        
        return $fail == 0;
    }
    
    function getStockShopee($itemID,$variationID)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/get";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();

        
        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["item_id"] = intval($itemID);
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        

        $obj = json_decode($result);
        if(!$obj->item)
        {
            writeToLog("cannot get quantity shopee (itemID,variationID): (" . $itemID.",".$variationID.")" );
        }
        if(sizeof($obj->item->variations) > 0)
        {
            for($i=0; $i<sizeof($obj->item->variations); $i++)
            {
                $variation = $obj->item->variations[$i];
                if($variation->variation_id == $variationID)
                {
                    curl_close($ch);
                    return $variation->stock;
                }
            }
        }
        else
        {
            curl_close($ch);
            return $obj->item->stock;
        }
        
        
        curl_close($ch);
        return 0;
    }
    
    function getItemShopee($itemID)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/get";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();

        
        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["item_id"] = intval($itemID);
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        

        $obj = json_decode($result);
        if(!$obj->item)
        {
            writeToLog("cannot get item shopee itemID:" . $itemID );
        }
        
        curl_close($ch);
        return $obj->item;
    }
    
    function deleteItemShopee($itemID)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/delete";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();

        
        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["item_id"] = intval($itemID);
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        

        $obj = json_decode($result);
        
        
        curl_close($ch);
        return $obj;
    }
    
    function deleteVariationShopee($itemID,$variationID)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/delete_variation";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();

        
        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["item_id"] = intval($itemID);
        $paramBody["variation_id"] = intval($variationID);
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        

        $obj = json_decode($result);
        
        
        curl_close($ch);
        return $obj;
    }
    
    function getAllSkuShopee()
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //find item_id and variation_id
        $url = "https://partner.shopeemobile.com/api/v1/items/get";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $variations = array();
        for($j=0; $j<60; $j++)//เผื่อไว้เป็น 60 ไม่อยากใส่ while(true)
        {
            //param
            $pageEntries = 100;
            $pageOffset = $j*$pageEntries;
            $date = new DateTime();
            $timestamp = $date->getTimestamp();//1586502149;
            
            
            //payload
            $paramBody = array();
            $paramBody["pagination_offset"] = $pageOffset;
            $paramBody["pagination_entries_per_page"] = $pageEntries;
            $paramBody["partner_id"] = $partnerID;
            $paramBody["shopid"] = $shopID;
            $paramBody["timestamp"] = $timestamp;
            
            
            $payload = json_encode($paramBody);
            writeToLog("payload:" . $payload);
            
            
            $contentLength = strlen($payload);
            $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
            
            
            //header
            $header = array();
            $header[] = 'Host:' . $host;
            $header[] = 'Content-Type:' . $contentType;
            $header[] = 'Content-Length:' . $contentLength;
            $header[] = 'Authorization:' . $authorization;
            writeToLog("header:" . json_encode($header));
            
            
            //set header and payload
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            
            
            //exec curl
            $result = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_errno = curl_errno($ch);
            if ($http_status==503)
            {
                writeToLog( "HTTP Status == 503");
            }
              
            if ($result === false)
            {
                writeToLog('Curl error: ' . curl_error($ch));
            }
            

            $obj = json_decode($result);
            
            for($i=0; $i<sizeof($obj->items); $i++)
            {
                $item = $obj->items[$i];

                if(sizeof($item->variations) > 0)
                {
                    for($k=0; $k<sizeof($item->variations); $k++)
                    {
                        $variation = $item->variations[$k];
    //                    if($variation->variation_sku == $sku)
                        {
                            $variation = array();
                            $variation["item_id"] = $item->item_id;
                            $variation["item_sku"] = $item->item_sku;
                            $variation["variation_id"] = $item->variation_id;
                            $variation["variation_sku"] = $item->variation_sku;
                            $variations[] = $variation;
//                            echo "<br>".$item->item_id.";".$item->item_sku.";".$variation->variation_id.";".$variation->variation_sku;
                        }
                    }
                }
                else
                {
    //                if($item->item_sku == $sku)
                    {
                        $variation = array();
                        $variation["item_id"] = $item->item_id;
                        $variation["item_sku"] = $item->item_sku;
                        $variation["variation_id"] = 0;
                        $variation["variation_sku"] = "";
                        $variations[] = $variation;
//                        echo "<br>".$item->item_id.";".$item->item_sku.";"."0".";"."";
                    }
                }
            }
            
            if(sizeof($obj->items) < 100)
            {
                return $variations;
//                break;
            }
        }
    }
    
    function getAllVariationsShopee($sku)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //find item_id and variation_id
        $url = "https://partner.shopeemobile.com/api/v1/items/get";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $variations = array();
        for($j=0; $j<60; $j++)//เผื่อไว้เป็น 60 ไม่อยากใส่ while(true)
        {
            //param
            $pageEntries = 100;
            $pageOffset = $j*$pageEntries;
            $date = new DateTime();
            $timestamp = $date->getTimestamp();//1586502149;
            
            
            //payload
            $paramBody = array();
            $paramBody["pagination_offset"] = $pageOffset;
            $paramBody["pagination_entries_per_page"] = $pageEntries;
            $paramBody["partner_id"] = $partnerID;
            $paramBody["shopid"] = $shopID;
            $paramBody["timestamp"] = $timestamp;
            
            
            $payload = json_encode($paramBody);
            writeToLog("payload:" . $payload);
            
            
            $contentLength = strlen($payload);
            $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
            
            
            //header
            $header = array();
            $header[] = 'Host:' . $host;
            $header[] = 'Content-Type:' . $contentType;
            $header[] = 'Content-Length:' . $contentLength;
            $header[] = 'Authorization:' . $authorization;
            writeToLog("header:" . json_encode($header));
            
            
            //set header and payload
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            
            
            //exec curl
            $result = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_errno = curl_errno($ch);
            if ($http_status==503)
            {
                writeToLog( "HTTP Status == 503");
            }
              
            if ($result === false)
            {
                writeToLog('Curl error: ' . curl_error($ch));
            }
            

            $obj = json_decode($result);
            
            
            for($i=0; $i<sizeof($obj->items); $i++)
            {
                $item = $obj->items[$i];
                if($item->item_sku == "middleton")
                {
                    continue;
                }
                if(sizeof($item->variations) > 0)
                {
                    for($k=0; $k<sizeof($item->variations); $k++)
                    {
                        $variation = $item->variations[$k];
                        if($variation->variation_sku == $sku)
                        {
                            $foundVariation = array();
                            $foundVariation["item_id"] = $item->item_id;
                            $foundVariation["variation_id"] = $variation->variation_id;
                            $variations[] = $foundVariation;
                        }
                    }
                }
                else
                {
                    if($item->item_sku == $sku)
                    {
                        $foundVariation = array();
                        $foundVariation["item_id"] = $item->item_id;
                        $foundVariation["variation_id"] = 0;
                        $variations[] = $foundVariation;
                    }
                }
            }
            
            if(sizeof($obj->items) < 100)
            {
                break;
            }
        }
        return $variations;
    }
    
    function getAllVariationsShopeeInApp($sku)
    {
        global $con;
        
        $escapeSku = mysqli_real_escape_string($con,$sku);
        $sql = "select * from shopeeProduct where sku = '$escapeSku'";
        $selectedRow = getSelectedRow($sql);
        $variations = array();
        for($i=0; $i<sizeof($selectedRow); $i++)
        {
            $shopeeProduct = $selectedRow[$i];
            
            $variation =  array();
            $variation["item_id"] = intval($shopeeProduct["ItemID"]);
            $variation["variation_id"] = intval($shopeeProduct["VariationID"]);
            $variations[] = $variation;
        }
        return $variations;
    }
    
    function hasShopeeProduct($sku)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //find item_id and variation_id
        $url = "https://partner.shopeemobile.com/api/v1/items/get";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $variations = array();
        for($j=0; $j<60; $j++)//เผื่อไว้เป็น 60 ไม่อยากใส่ while(true)
        {
            //param
            $pageEntries = 100;
            $pageOffset = $j*$pageEntries;
            $date = new DateTime();
            $timestamp = $date->getTimestamp();//1586502149;
            
            
            //payload
            $paramBody = array();
            $paramBody["pagination_offset"] = $pageOffset;
            $paramBody["pagination_entries_per_page"] = $pageEntries;
            $paramBody["partner_id"] = $partnerID;
            $paramBody["shopid"] = $shopID;
            $paramBody["timestamp"] = $timestamp;
            
            
            $payload = json_encode($paramBody);
            writeToLog("payload:" . $payload);
            
            
            $contentLength = strlen($payload);
            $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
            
            
            //header
            $header = array();
            $header[] = 'Host:' . $host;
            $header[] = 'Content-Type:' . $contentType;
            $header[] = 'Content-Length:' . $contentLength;
            $header[] = 'Authorization:' . $authorization;
            writeToLog("header:" . json_encode($header));
            
            
            //set header and payload
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            
            
            //exec curl
            $result = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_errno = curl_errno($ch);
            if ($http_status==503)
            {
                writeToLog( "HTTP Status == 503");
            }
              
            if ($result === false)
            {
                writeToLog('Curl error: ' . curl_error($ch));
            }
            

            $obj = json_decode($result);
            
            
            for($i=0; $i<sizeof($obj->items); $i++)
            {
                $item = $obj->items[$i];
                if($item->item_sku == "middleton")
                {
                    continue;
                }
                if(sizeof($item->variations) > 0)
                {
                    for($k=0; $k<sizeof($item->variations); $k++)
                    {
                        $variation = $item->variations[$k];
                        if($variation->variation_sku == $sku)
                        {
                            writeToLog("found shopee sku:".$sku);
                            return true;
                        }
                    }
                }
                else
                {
                    if($item->item_sku == $sku)
                    {
                        writeToLog("found shopee sku:".$sku);
                        return true;
                    }
                }
            }
            
            if(sizeof($obj->items) < 100)
            {
                break;
            }
        }
        writeToLog("not found shopee sku");
        return false;
    }
    
    function getShopeeProduct($variaions,$sku)
    {
        for($i=0; $i<sizeof($variaions); $i++)
        {
            $variation = $variaions[$i];
            $itemSku = $variation["item_sku"];
            if($itemSku == $sku)
            {
                return $variation;
            }
        }
        return null;
    }
    
    function getShopeeProducts($variaions,$sku)
    {
        $skuVariations = array();
        for($i=0; $i<sizeof($variaions); $i++)
        {
            $variation = $variaions[$i];
            $itemSku = $variation["item_sku"];
            if($itemSku == $sku)
            {
                $skuVariations[] = $variation;
            }
        }
        
        if(sizeof($skuVariations)>0)
        {
            return $skuVariations;
        }
        
        return null;
    }
    
    function getNormalOrUnListProducts($skuVariations)
    {
        $shopeeProducts = array();
        if($skuVariations)
        {
            for($i=0; $i<sizeof($skuVariations); $i++)
            {
                $variation = $skuVariations[$i];
                $itemID = $variation["item_id"];
                $shopeeItem = getItemShopee($itemID);
                if($shopeeItem->status != "DELETED")
                {
                    $shopeeProducts[] = $variation;
                }
            }
        }
        
        if(sizeof($shopeeProducts)>0)
        {
            return $shopeeProducts;
        }
        return null;
    }
    
//    function updateStockQuantityShopee($sku,$quantity)
//    {
//        global $host;
//        global $contentType;
//        global $key;
//        global $partnerID;
//        global $shopID;
//
//
//        //get all variations
//        writeToLog( "sku:" . $sku);
//        if($sku == "")
//        {
//            return false;
//        }
//        $variations = getAllVariationsShopee($sku);
//        writeToLog( "all variations:" . json_encode($variations));
//
//
//        //get stock
//        for($i=0; $i<sizeof($variations); $i++)
//        {
//            $variation = $variations[$i];
//            $stock = 0;
//            $stock += $quantity;
//
//
//            //update stock
//            $updateVariations = array();
//            $updateVariation = array();
//            $updateVariation["item_id"] = $variation["item_id"];
//            $updateVariation["variation_id"] = $variation["variation_id"];
//            $updateVariation["stock"] = $stock;
//            $updateVariations[] = $updateVariation;
//            writeToLog( "update variation:" . json_encode($updateVariations));
//            if($variation["variation_id"] > 0)
//            {
//                $ret = updateStockBatchShopee($updateVariations,"vars");
//                return $ret;
//            }
//            else
//            {
//                $ret = updateStockBatchShopee($updateVariations,"items");
//                return $ret;
//            }
//        }
//        return false;
//    }
    
    function updateStockQuantityShopeeInApp($sku,$quantity)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //get all variations
        writeToLog( "sku:" . $sku);
        if($sku == "")
        {
            return false;
        }
        
        $fail = 0;
        $updateItems = array();
        $updateVariations = array();
        $variations = getAllVariationsShopeeInApp($sku);
        writeToLog( "all variations:" . json_encode($variations));
        
        
        //get stock
        for($i=0; $i<sizeof($variations); $i++)
        {
            $variation = $variations[$i];
            $stock = 0;
            $stock += $quantity;
            
            
            //update stock
//            $updateVariations = array();
            $updateVariation = array();
            $updateVariation["item_id"] = $variation["item_id"];
            $updateVariation["variation_id"] = $variation["variation_id"];
            $updateVariation["stock"] = $stock;
            
            if($variation["variation_id"] > 0)
            {
                $updateVariations[] = $updateVariation;
            }
            else
            {
                $updateItems[] = $updateVariation;
            }
        }
        
        writeToLog( "update items:" . json_encode($updateItems));
        writeToLog( "update variation:" . json_encode($updateVariations));
        
        if(sizeof($updateItems)>0)
        {
            $ret = updateStockBatchShopee($updateItems,"items");
            if(!$ret)
            {
                $fail++;
            }
        }
        if(sizeof($updateVariations)>0)
        {
            $ret = updateStockBatchShopee($updateVariations,"vars");
            if(!$ret)
            {
                $fail++;
            }
        }
        
        return $fail == 0;
    }
    
//    function updateStockShopee($productName,$color,$size,$quantity)
//    {
//        writeToLog( "product:".$productName." / ".$color." / ".$size." / ".$quantity);
//        if($size == "-")
//        {
//            $sku = $productName . $color;
//        }
//        else
//        {
//            $sku = $productName . $color . $size;
//        }
//        $sku = str_replace(" ","",$sku);
//        $sku = strtolower($sku);
//
//
//        updateStockQuantityShopee($sku,$quantity);
//    }
    
    function addStockShopee($productName,$color,$size,$quantity)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        writeToLog( "product:".$productName." / ".$color." / ".$size." / ".$quantity);
        if($size == "-")
        {
            $sku = $productName . $color;
        }
        else
        {
            $sku = $productName . $color . $size;
        }
        $sku = str_replace(" ","",$sku);
        $sku = strtolower($sku);
        
        
        //get all variations
        writeToLog( "sku:" . $sku);
        if($sku == "")
        {
            return;
        }
        $variations = getAllVariationsShopee($sku);
        writeToLog( "all variations:" . json_encode($variations));
        
        
        //get stock
        for($i=0; $i<sizeof($variations); $i++)
        {
            $variation = $variations[$i];
            writeToLog( "item id:" . $variation["item_id"] . ", variation id:" . $variation["variation_id"]);
            $stock = getStockShopee($variation["item_id"],$variation["variation_id"]);
            writeToLog( "stock:" . $stock);
            $stock += $quantity;
            if($stock < 0)
            {
                $stock = 0;
            }
            
            //update stock
            $updateVariations = array();
            $updateVariation = array();
            $updateVariation["item_id"] = $variation["item_id"];
            $updateVariation["variation_id"] = $variation["variation_id"];
            $updateVariation["stock"] = $stock;
            $updateVariations[] = $updateVariation;
            writeToLog( "update variation:" . json_encode($updateVariations));
            if($variation["variation_id"] > 0)
            {
                updateStockBatchShopee($updateVariations,"vars");
            }
            else
            {
                updateStockBatchShopee($updateVariations,"items");
            }
        }
    }
    
    function updateStockBatchShopee($variations,$type)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();//1586502149;
        
        
        //url
        if($type == "vars")
        {
            $url = "https://partner.shopeemobile.com/api/v1/items/update/vars_stock";
        }
        else
        {
            $url = "https://partner.shopeemobile.com/api/v1/items/update/items_stock";
        }
        writeToLog("url:".$url);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        $message = "";
        for($i=0; $i<floor((sizeof($variations)-1)/50)+1; $i++)
        {
            $partVariations = array();
            for($j=50*$i; $j<50*($i+1) && $j<sizeof($variations); $j++)
            {
                $partVariations[] = $variations[$j];
            }
            

            //payload
            $paramBody = array();
            if($type == "vars")
            {
                $paramBody["variations"] = $partVariations;
            }
            else
            {
                $paramBody["items"] = $partVariations;
            }
            $paramBody["partner_id"] = $partnerID;
            $paramBody["shopid"] = $shopID;
            $paramBody["timestamp"] = $timestamp;
            
            
            $payload = json_encode($paramBody);
            writeToLog("payload:" . $payload);
            
            
            $contentLength = strlen($payload);
            $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
            
            
            //header
            $header = array();
            $header[] = 'Host:' . $host;
            $header[] = 'Content-Type:' . $contentType;
            $header[] = 'Content-Length:' . $contentLength;
            $header[] = 'Authorization:' . $authorization;
            writeToLog("header:" . json_encode($header));
            
            
            //set header and payload
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            
            
            //exec curl
            $result = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_errno = curl_errno($ch);
            if ($http_status==503)
            {
                writeToLog( "HTTP Status == 503");
                //notify
                $message = "shopee update stock: HTTP Status == 503";
                sendNotiToAdmin($message);
            }
              
            if ($result === false)
            {
                writeToLog('Curl error: ' . curl_error($ch));
                writeToLog( "Curl Errno returned $curl_errno");
                //notify
                $message = "shopee update stock: Curl Errno returned $curl_errno";
                sendNotiToAdmin($message);
            }
            
            $obj = json_decode($result);
            if($obj->error != "")
            {
                //notify
                $message = "shopee update stock -> $result->error:$result->msg";
                sendNotiToAdmin($message);
            }
            else if(sizeof($obj->batch_result->failures)>0)
            {
                
//                if($obj->batch_result->failures[0]->error_description == 'this item is not allowed to edit')
//                {
//                    writeToLog("this item is not allowed to edit");
//                    sendNotiToAdmin("this item is not allowed to edit [itemID, variationID]:"."[".$obj->batch_result->failures[0]->item_id.",".$obj->batch_result->failures[0]->variation_id."]" );
//                }
//                else
                {
                    //notify
                    $message = "shopee update stock fail";
                    sendNotiToAdmin($message);
                }
                
//                writeToLog("obj->batch_result->failures size > 0");
            }
            
            writeToLog( "curl result:".$result);
        }
        
        curl_close($ch);
        return $message == "";
    }
    
    function updateSkuShopee($itemID,$variationID,$itemSku,$variationSku,$sizeChart)
    {
        global $host;
        global $contentType;
        global $key;
        global $partnerID;
        global $shopID;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //param
        $itemID = intval($itemID);//"3803984978";
//        $itemSku = $_GET["itemSku"];
        $variationID = intval($variationID);//"8843388604";
//        $variationSku = $_GET["variationSku"];
//        $sizeChart = intval($_GET["sizeChart"]);
        $date = new DateTime();
        $timestamp = $date->getTimestamp();//1586502149;
        
        
        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/update";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        
        //payload
        $paramBody = array();
        $paramBody["item_id"] = $itemID;
        $paramBody["item_sku"] = $itemSku;
        $variations = array();
        $variation = array();
        $variation["variation_id"] = $variationID;
        $variation["variation_sku"] = $variationSku;
        $variations[] = $variation;
        $paramBody["variations"] = $variations;
        $paramBody["size_chart"] = $sizeChart;
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        
        
        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);
        
        
        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);
        
        
        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            echo "HTTP Status == 503 <br/>";
        }
        
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            echo "Curl Errno returned $curl_errno <br/>";
        }
        
        $obj = json_decode($result);
        
        if($obj->item)
        {
            echo "update success<br>";
            writeToLog("update success");
        }
        
        curl_close($ch);
    }
    
    function getStockWordPress($productName,$color,$size)
    {
        global $wordPressDB;
        global $con;
        
        
        if($size == "-")
        {
            $productSku = $productName . $color;
        }
        else
        {
            $productSku = $productName . $color . $size;
        }
        
        $productSku = str_replace(" ","",$productSku);
        
        
        //variation
        $sql = "SELECT product.ID FROM $wordPressDB.`wp_posts` as product LEFT JOIN $wordPressDB.wp_postmeta as product_sku ON product.ID = product_sku.post_ID WHERE product_sku.meta_key = '_sku' and product.post_status = 'publish' and replace(replace(product_sku.meta_value,'-',''),' ','') = '$productSku'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)>0)
        {
            return true;
        }
        else
        {
//            echo $productSku . ",   ";
            return false;
        }
    }
    
    function insertWebProductCurl($param)
    {
        global $contentType;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "http://www.minimalist.co.th/saim/SAIMWebProductInsert.php";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //payload
        $payload = json_encode($param,JSON_UNESCAPED_UNICODE);
        writeToLog("payload:" . $payload);
        
        
        //header
        $header = array();
        $header[] = 'Content-Type:' . $contentType;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("web product insert result:" . $result);
        $obj = json_decode($result);
        
        
        return $obj->success;
    }
    
    function insertWebProduct($param)
    {
        global $contentType;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "http://www.ralamusic.com/SAIM/SAIMWordPressProductInsert.php";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //payload
        $payload = json_encode($param,JSON_UNESCAPED_UNICODE);
        writeToLog("payload:" . $payload);
        
        
        //header
        $header = array();
        $header[] = 'Content-Type:' . $contentType;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("wordpress product insert result:" . $result);
        $obj = json_decode($result);
        
        
        return $obj->success;
    }
    
    function hasWebProduct($sku)
    {
        global $contentType;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = "http://www.ralamusic.com/SAIM/SAIMHasWebProductGet.php";
        curl_setopt($ch, CURLOPT_URL, $url);
        
        
        //payload
        $payload = json_encode(array("sku"=>$sku));
        writeToLog("payload:" . $payload);
        
        
        //header
        $header = array();
        $header[] = 'Content-Type:' . $contentType;
        writeToLog("header:" . json_encode($header));
        
        
        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        
        
        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }
          
        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }
        
        
        writeToLog("has web product result:" . $result);
        $obj = json_decode($result);
        
        
        return $obj->success;
    }
    
    function hasWebProductInRalaWeb($sku)
    {
        global $wordPressDB;
        global $con;
        
        
        $productSku = $sku;
        
        
        //variation
        $sql = "SELECT product.ID FROM $wordPressDB.`wp_posts` as product LEFT JOIN $wordPressDB.wp_postmeta as product_sku ON product.ID = product_sku.post_ID WHERE product_sku.meta_key = '_sku' and product_sku.meta_value = '$productSku'";// and product.post_status = 'publish'
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function updateStockWordPressSku($sku,$quantity)
    {
        global $wordPressDB;
        global $con;
        
        
        $productSku = $sku;
        $stockStatus = $quantity > 0?"instock":"outofstock";
        
        
        //variation
        $sql = "SELECT product.ID FROM $wordPressDB.`wp_posts` as product LEFT JOIN $wordPressDB.wp_postmeta as product_sku ON product.ID = product_sku.post_ID WHERE product_sku.meta_key = '_sku' and product.post_status = 'publish' and product_sku.meta_value = '$productSku'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow) > 0 && sizeof($selectedRow) <= 2)
        {
            for($j=0; $j<sizeof($selectedRow); $j++)
            {
                $postID = $selectedRow[$j]["ID"];
                $sql = "update $wordPressDB.wp_postmeta set meta_value = '$stockStatus' where meta_key = '_stock_status' and post_id = '$postID'";
                $ret = doQueryTask2($con,$sql,$_POST["modifiedUser"]);
                if($ret != "")
                {
                    writeToLog("updateStockWordPressSku fail [sku,quantity]:[$sku,$quantity], ".json_encode($ret));
                    sendNotiToAdmin("updateStockWordPressSku fail [sku,quantity]:[$sku,$quantity]");
                    return false;
                }
            }
        }
        
        return true;
    }
    
    function updateStockWordPress($productName,$color,$size,$stockStatus)
    {
        global $wordPressDB;
        global $con;
//        $wordPressDB = "test_mini";//test
        
        if($size == "-")
        {
            $productSku = $productName . $color;
        }
        else
        {
            $productSku = $productName . $color . $size;
        }
        
        $productSku = str_replace(" ","",$productSku);
        
        
        //variation
        $sql = "SELECT product.ID FROM $wordPressDB.`wp_posts` as product LEFT JOIN $wordPressDB.wp_postmeta as product_sku ON product.ID = product_sku.post_ID WHERE product_sku.meta_key = '_sku' and product.post_status = 'publish' and replace(replace(product_sku.meta_value,'-',''),' ','') = '$productSku'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow) > 0 && sizeof($selectedRow) <= 2)
        {
            for($j=0; $j<sizeof($selectedRow); $j++)
            {
                $postID = $selectedRow[$j]["ID"];
                $sql = "update $wordPressDB.wp_postmeta set meta_value = '$stockStatus' where meta_key = '_stock_status' and post_id = '$postID'";
                $ret = doQueryTask2($con,$sql,$_POST["modifiedUser"]);
                if($ret != "")
                {
                    writeToLog("updateStockWordPressSku fail [sku,quantity]:[$productSku,$quantity], ".json_encode($ret));
                    sendNotiToAdmin("updateStockWordPressSku fail [sku,quantity]:[$productSku,$quantity]");
                    return;
                }
            }
        }
        
        
        
        //product
        if($size != "-")//has parent sku
        {
            $parentSku = $productName . $color;
            $parentSku = str_replace(" ","",$parentSku);
            $updateParent = true;
            
            
            
            //check case out of stock with variation
            if($stockStatus == "outofstock")
            {
                $sql = "SELECT product.ID FROM $wordPressDB.`wp_posts` as product LEFT JOIN $wordPressDB.wp_postmeta as product_sku ON product.ID = product_sku.post_ID LEFT JOIN $wordPressDB.wp_postmeta as product_status ON product.ID = product_status.post_ID WHERE product.post_type = 'product_variation' and product_sku.meta_key = '_sku' and product.post_status = 'publish' and replace(replace(product_sku.meta_value,'-',''),' ','') like '$parentSku%' and product_status.meta_value = 'instock'";
                $selectedRow = getSelectedRow($sql);
                if(sizeof($selectedRow) > 0)
                {
                    $updateParent = false;
                }
            }
            
            
            if($updateParent)
            {
                $sql = "SELECT product.ID FROM $wordPressDB.`wp_posts` as product LEFT JOIN $wordPressDB.wp_postmeta as product_sku ON product.ID = product_sku.post_ID WHERE product_sku.meta_key = '_sku' and product.post_status = 'publish' and replace(replace(product_sku.meta_value,'-',''),' ','') = '$parentSku'";
                $selectedRow = getSelectedRow($sql);
                if(sizeof($selectedRow) > 0 && sizeof($selectedRow) <= 2)
                {
                    for($j=0; $j<sizeof($selectedRow); $j++)
                    {
                        $postID = $selectedRow[$j]["ID"];
                        $sql = "update $wordPressDB.wp_postmeta set meta_value = '$stockStatus' where meta_key = '_stock_status' and post_id = '$postID'";
                        $ret = doQueryTask2($con,$sql,$_POST["modifiedUser"]);
                        if($ret != "")
                        {
                            writeToLog("updateStockWordPressSku fail [sku,quantity]:[$productSku,$quantity], ".json_encode($ret));
                            sendNotiToAdmin("updateStockWordPressSku fail [sku,quantity]:[$productSku,$quantity]");
                            return;
                        }
                    }
                }
            }
        }
    }
    
    function updateLazadaProduct($lazadaProduct)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
                
        
        $lazadaProductAddNode = array("Product"=>$lazadaProduct);
        $xmlPayload = array2xml(json_decode(json_encode($lazadaProductAddNode),true),false);
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/product/update','POST');
        $request->addApiParam('payload',$xmlPayload);
        $resp = $c->execute($request, $accessToken);
        
        
        $sku = $lazadaProduct["Skus"]["Sku"]["SellerSku"];
        writeToLog("updateLazadaProduct result (sku: $sku):" . $resp);
        $respObject = json_decode($resp);
        return $respObject;
    }
    
    function getDocument($docType,$orderItemIDs)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/order/document/get','GET');
        $request->addApiParam('doc_type',$docType);
        $request->addApiParam('order_item_ids',$orderItemIDs);
        $resp = $c->execute($request, $accessToken);
        
        $respObject = json_decode($resp);
        return $respObject;
    }
    
    function setStatusToPacked($orderItemIDs)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;


        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/order/pack');
        $request->addApiParam('shipping_provider','LEX');
        $request->addApiParam('delivery_type','dropship');
        $request->addApiParam('order_item_ids',$orderItemIDs);
        $resp = $c->execute($request, $accessToken);
        
        $respObject = json_decode($resp);
        writeToLog("setStatusToPacked result:". json_encode($respObject));
        if($respObject->code != "0")
        {
            $message = "cannot set status to packed, [order_item_id]: " . $orderItemIDs;
            writeToLog($message);
            sendNotiToAdmin($message);
            return false;
        }
        return $respObject->data->order_items;
    }
    
    function setInvoiceNo($orderItemID,$invoiceNo)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/order/invoice_number/set');
        $request->addApiParam('order_item_id',$orderItemID);
        $request->addApiParam('invoice_number',$invoiceNo);
        $resp = $c->execute($request, $accessToken);
        
        $respObject = json_decode($resp);
        writeToLog("setInvoice result:". json_encode($respObject));
        if($respObject->code != "0")
        {
            $message = "cannot set invoice no., order no.: " . $invoiceNo . ", order_item_id: " . $orderItemID;
            writeToLog($message);
            sendNotiToAdmin($message);
            return false;
        }
        return true;
    }
    
    function setReadyToShip($orderItemIDs,$trackingNo)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
                
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/order/rts');
        $request->addApiParam('delivery_type','dropship');
        $request->addApiParam('order_item_ids',$orderItemIDs);
        $request->addApiParam('shipment_provider','LEX');
        $request->addApiParam('tracking_number',$trackingNo);
        $resp = $c->execute($request, $accessToken);
        
        $respObject = json_decode($resp);
        writeToLog("setReadyToShip result:". json_encode($respObject));
        if($respObject->code != "0")
        {
            $message = "cannot set ready to ship, [order_item_id]: " . $orderItemIDs;
            writeToLog($message);
            sendNotiToAdmin($message);
            return false;
        }
        return true;
    }
    
    function getSkuFromLazadaSku($lazadaSku)
    {
        $sql = "select * from lazadaSku where lazadaSku = '$lazadaSku'";
        $skuItem = executeQueryArray($sql);
    
        if(sizeof($skuItem) != 0)
        {
            return $skuItem[0]->Sku;
        }
        
        return $lazadaSku;
    }
    
    function getLazadaProducts()
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/products/get','GET');
        $request->addApiParam('filter','live');
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        
        return $respObject->data->products;
    }
    
    function getOrderLazada($orderNo)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/order/get','GET');
        $request->addApiParam('order_id',$orderNo);
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        
        $order = $respObject->data;
        $orderNo = $order->order_number;
        $orderItems = getOrderItemLazada($orderNo);
        $order->items = $orderItems;
        
        return $order;
    }
    
    function getPendingOrdersLazada()
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        
        $orders = array();
        for($i=0; $i<4; $i++)//set max 4 times
        {
            $c = new LazopClient($url,$appKey,$appSecret);
            $request = new LazopRequest('/orders/get','GET');
            $request->addApiParam('status','pending');
            $request->addApiParam('sort_direction','ASC');
            $request->addApiParam('offset',$i*100);
            $request->addApiParam('limit','100');
            $request->addApiParam('update_after','2020-01-01T09:00:00+08:00');
            $request->addApiParam('sort_by','updated_at');            
            $resp = $c->execute($request, $accessToken);
            $respObject = json_decode($resp);
            
            
            
            
            if(sizeof($respObject->data->orders) == 0)
            {
                break;
            }
            else
            {
                $orders = array_merge($orders,$respObject->data->orders);
            }
        }
        
        
        
        //fill order items for all orders
        for($i=0; $i<sizeof($orders); $i++)
        {
            $orderNo = $orders[$i]->order_number;
            $orderItems = getOrderItemLazada($orderNo);
            $orders[$i]->items = $orderItems;
        }
        return $orders;
    }
    
    function getOrderItemLazada($orderNo)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/order/items/get','GET');
        $request->addApiParam('order_id',$orderNo);
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        
        return $respObject->data;
    }
    
    function getStockQuantityLazada($sku)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        

        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/product/item/get','GET');
        $request->addApiParam('seller_sku',$sku);
        
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        
        
        if(($respObject->data) != null)
        {
            return $respObject->data->skus[0]->quantity;
        }
        else
        {
            writeToLog("cannot get stock quantity lazada sku:" . $sku);
            return 0;
        }
    }
    
    function hasLazadaProduct($sku)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        

        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/product/item/get','GET');
        $request->addApiParam('seller_sku',$sku);
        
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        
        
        if(($respObject->data) != null)
        {
            writeToLog("found lazada sku:".$sku);
            return true;
        }
        else
        {
            writeToLog("not found lazada sku:".$sku);
            return false;
        }
    }
    
    function addStockLazada($productName, $color, $size,$updateQuantity)
    {
        //set sku
        if($size == "-")
        {
            $sku = $productName . $color;
        }
        else
        {
            $sku = $productName . $color . $size;
        }
        $sku = str_replace(" ","",$sku);
        $sku = strtolower($sku);
        
        
        //get lazada sku
        $sku = getLazadaSku($sku);
        
        
        //get stock quantity
        writeToLog("get stock quantity sku:" . $sku);
        $quantity = getStockQuantityLazada($sku);
        writeToLog("get quantity:" . $quantity);
        
        
        //update quantity
        $quantity += $updateQuantity;
        if($quantity < 0)
        {
            $quantity = 0;
        }
        
        
        writeToLog("update quantity:" . $quantity);
        updateStockQuantityLazada($sku,$quantity);
    }
    
    function updateStockQuantityLazadaInApp($sku,$quantity)
    {
        global $con;
        
        $escapeSku = mysqli_real_escape_string($con,$sku);
        $sql = "select * from lazadaProduct where sku = '$escapeSku'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow) > 0)
        {
            return updateStockQuantityLazada($sku,$quantity);
        }
        return true;
    }
    
    function updateStockQuantityLazada($sku,$quantity)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        $id = getItemIDAndSkuIDLazada($sku);
        //update part
        $payLoad = file_get_contents('./lazadaUpdateQuantityTemplate3.php');
        $payLoad = str_replace("#sku_id#",$id["sku_id"],$payLoad);
        $payLoad = str_replace("#item_id#",$id["item_id"],$payLoad);
//        $payLoad = str_replace("#sku#",$sku,$payLoad);
        $payLoad = str_replace("#quantity#",$quantity,$payLoad);
            
            
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/product/price_quantity/update','POST');
        $request->addApiParam('payload',$payLoad);
        $resp = $c->execute($request, $accessToken);
        writeToLog("update stock quantity lazada result:".$resp);
        $respObject = json_decode($resp);
        
        if($respObject->code == "0")
        {
            writeToLog("update quantity success, sku:" . $sku . ", quantity:" . $quantity);
            return true;
        }
//        else if($respObject->message == "SELLER_SKU_NOT_FOUND")
//        {
//            writeToLog("sku:" . $sku . " not found, quantity:" . $quantity);
//            return true;
//        }
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
    }
    
    function getLazadaAccessToken()
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $refreshToken;
        
        
        //get lazada refresh token
        $sql = "select Value from setting where settingKey = 'lazadaRefreshToken'";
        $settingList = executeQueryArray($sql);
        $refreshToken = $settingList[0]->Value;
        
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/auth/token/refresh');
        $request->addApiParam('refresh_token',$refreshToken);
        $resp = $c->execute($request);
        
        
        $respObject = json_decode($resp);
        writeToLog("accessToken:" .  json_encode($respObject));
        return $respObject;
    }
    
    function replaceLazadaAccessToken()
    {
        global $con;
        
        $ret = getLazadaAccessToken();
        $token = $ret->access_token;
        
        
        $sql = "update setting set value = '$token' where enumKey = 'lazadaToken'";
        $ret2 = doQueryTask($con,$sql,$modifiedUser);
        
        return $ret;
    }
    
    function getStockLazada($productName, $color, $size)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        
        if($size == "-")
        {
            $searchProductName = $productName . $color;
        }
        else
        {
            $searchProductName = $productName . $color . $size;
        }
        $searchProductName = str_replace(" ","",$searchProductName);
        $searchProductName = strtolower($searchProductName);
        
        
        //get lazada sku
        $searchProductName = getLazadaSku($searchProductName);
        
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/product/item/get','GET');
        $request->addApiParam('seller_sku',$searchProductName);
        
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        
        
        if(($respObject->data) != null)
        {
            return true;
        }
        else
        {
//            echo $searchProductName . ",    ";
            return false;
        }
    }
    
    function getLazadaProduct($sku)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/product/item/get','GET');
        $request->addApiParam('seller_sku',$sku);
        
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        writeToLog("getLazadaProduct:" . $resp);
        
        if(($respObject->data) != null)
        {
            return $respObject->data;
        }
        else
        {
            writeToLog("Lazada: Cannot get product ($resp)");
            return null;
        }
    }
    
    function getItemIDAndSkuIDLazada($sku)
    {
        $product = getLazadaProduct($sku);
        $id = array();
        $id["item_id"] = $product->item_id;
        $id["sku_id"] = $product->skus[0]->SkuId;
        return $id;
    }
    
    function getLazadaProductByItemID($itemID)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/product/item/get','GET');
        $request->addApiParam('item_id',$itemID);
        
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        writeToLog("getLazadaProduct:" . $resp);
        
        if(($respObject->data) != null)
        {
            return $respObject->data;
        }
        else
        {
            writeToLog("Lazada: Cannot get product ($resp)");
            return null;
        }
    }
    
    function getLazadaProductApi($sku)
    {
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/product/item/get','GET');
        $request->addApiParam('seller_sku',$sku);
        
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        writeToLog("getLazadaProduct:" . $resp);
        
        return $respObject;
//        if(($respObject->data) != null)
//        {
//            return $respObject->data;
//        }
//        else
//        {
//            writeToLog("Lazada: Cannot get product ($resp)");
//            return null;
//        }
    }
    
    function updateStockLazada($productName, $color, $size,$quantity)
    {
        //set sku
        if($size == "-")
        {
            $sku = $productName . $color;
        }
        else
        {
            $sku = $productName . $color . $size;
        }
        $sku = str_replace(" ","",$sku);
        $sku = strtolower($sku);


        //get lazada sku
        $sku = getLazadaSku($sku);
        


        writeToLog("update sku:" . $sku);
        writeToLog("update quantity:" . $quantity);
        updateStockQuantityLazada($sku,$quantity);
    }
    
    function getLazadaSku($sku)
    {
        $sql = "select * from lazadaSku where lazadaSku = '$sku'";
        $skuItem = executeQueryArray($sql);
    
        if(sizeof($skuItem) != 0)
        {
            return $skuItem[0]->LazadaSku;
        }
        
        return $sku;
    }
    
    function executeMultiQueryArray($sql)
    {
        global $con;
        if (mysqli_multi_query($con, $sql)) {
            $arrOfTableArray = array();
            $resultArray = array();
            do {
                /* store first result set */
                if ($result = mysqli_store_result($con)) {
                    while ($row = mysqli_fetch_object($result)) {
                        array_push($resultArray, $row);
                    }
                    array_push($arrOfTableArray,$resultArray);
                    $resultArray = [];
                    mysqli_free_result($result);
                }
                if(!mysqli_more_results($con))
                {
                    break;
                }
            } while (mysqli_next_result($con));
            
            writeToLog("multi query sql: " . $sql . ", modified user: " . $_POST["modifiedUser"]);
            return $arrOfTableArray;
        }
        else
        {
            writeToLog("executeMultiQueryArray fail:" . $sql);
        }
        return "";
    }
    
    function executeQueryArray($sql)
    {
        global $con;
        global $modifiedUser;

        if ($result = mysqli_query($con, $sql)) {
            $resultArray = array();

            while ($row = mysqli_fetch_object($result)) {
                array_push($resultArray, $row);
            }
            mysqli_free_result($result);
            
            $rowCount = sizeof($resultArray);
            writeToLog("query: row count = $rowCount, sql: " . $sql . ", modified user: " . $modifiedUser);
            return $resultArray;
        }
        else
        {
            writeToLog( "executeQueryArray fail:" . $sql);
        }
        return null;
    }
    
    function printAllPost()
    {
        global $con;
        $paramAndValue;
        $i = 0;
        foreach ($_POST as $param_name => $param_val)
        {
            if($i == 0)
            {
                $paramAndValue = "PostParam=Value: ";
            }
            $paramAndValue .= "$param_name=$param_val&";
            $_POST['$param_name'] = mysqli_real_escape_string($con,$param_val);
            $i++;
        }
        
        if(sizeof($_POST) > 0)
        {
            writeToLog($paramAndValue);
        }
    }
    
    function printAllGet()
    {
        global $con;
        $paramAndValue;
        $i = 0;
        foreach ($_GET as $param_name => $param_val)
        {
            if($i == 0)
            {
                $paramAndValue = "GetParam=Value: ";
            }
            $paramAndValue .= "$param_name=$param_val&";
            $_POST['$param_name'] = mysqli_real_escape_string($con,$param_val);
            $i++;
        }
        
        if(sizeof($_GET) > 0)
        {
            writeToLog($paramAndValue);
        }
    }
    
    function putAlertToDevice($user)
    {
        global $con;
        // push alert to device
        // Set autocommit to on
        mysqli_autocommit($con,TRUE);
        writeToLog("set auto commit to on");
        
        
        //alert query fail-> please check recent transactions again
        $type = 'alert';
        $action = '';
        writeToLog("fail from push notification: " . $type);
        
        
        $deviceToken = getDeviceTokenFromUsername($user);
        $sql = "insert into pushSync (DeviceToken, TableName, Action, Data, TimeSync) values ('$deviceToken','$type','$action','',now())";        
        $res = mysqli_query($con,$sql);
        if(!$res)
        {
            $error = "query fail, sql: " . $sql . ", modified user: " . $user . " error: " . mysqli_error($con);
            writeToLog($error);
        }
        else
        {
            writeToLog("query success, sql: " . $sql . ", modified user: " . $user);
            
            $pushSyncID = mysqli_insert_id($con);
            writeToLog('pushsyncid: '.$pushSyncID);
            $paramBody = array(
                               'badge' => 0
                               );
            sendPushNotification($deviceToken, $paramBody);
            //----------
        }
        mysqli_close($con);
    }
    
    function setConnectionValue($dbName)
    {
        global $con;
        global $globalDBName;
        global $wordPressDB;
        $host = "localhost";
        $dbPassword = "123456";
        
        
        global $url;
        global $appKey;
        global $appSecret;
        global $accessToken;
        
        global $key;
        global $partnerID;
        global $shopID;
        
        global $appKeyJd;
        global $appSecretJd;
        global $accessTokenJd;
        
        
        if($dbName == "")
        {
            $dbName = "MINIMALIST";
            $globalDBName = $dbName;
            $dbUser = $dbName;
        }
        else
        {
            $globalDBName = $dbName;
            $dbUser = $dbName;
            if($dbName == "RALAMUSIC")
            {
                //web
                $wordPressDB = "RALAMUSIC";
                
                
                //LAZADA
                $url = "https://api.lazada.co.th/rest";
                $appKey = "119433";
                $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
                $accessToken = "50000800941srTr7jlPaRTABafvGJra0wDBhET8MyXcneJhakI08S1f443451ibq";//ralaTokenStart: 16-08-2020 02:20
                $refreshToken = "50001800132cSOwTpBCUzHRjJ9pZOrah6GYiLTeTvGWj12d1cfe9bp4PyLkeT59x";//expire in 15544206 ประมาณ16 feb 2021
                
                
                //shopee
                $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
                $partnerID = 845652;
                $shopID = 1396523;
                
                
                //jd
                $appKeyJd = "4f167657105d3afd732653da83fb49a5";
                $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
                $accessTokenJd = "64514eb4d73290d0c9974b648058adec";
                
                $dbUser = "MINIMALIST";
            }
            else if( $dbName == "MINIMALISTTEST")
            {
                //shopee
                $key = "adfd6427d69ccda13459756d57acb7f93002e134882c1da29917afdfd094a193";
                $partnerID = 842613;
                $shopID = 215964291;
            }
            else if( $dbName == "RALAMUSICTEST")
            {
                //web
                $wordPressDB = "RALAMUSIC";
                
                
                //LAZADA
                $url = "https://api.lazada.co.th/rest";
                $appKey = "119433";
                $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
                $accessToken = "50000800941srTr7jlPaRTABafvGJra0wDBhET8MyXcneJhakI08S1f443451ibq";//ralaTokenStart: 16-08-2020 02:20
                $refreshToken = "50001800132cSOwTpBCUzHRjJ9pZOrah6GYiLTeTvGWj12d1cfe9bp4PyLkeT59x";//expire in 15544206 ประมาณ16 feb 2021
                
                
                //shopee
//                $key = " e11e4df1f0badd2d79fe2c1ca176a99de792d3b0456cef0dd97bfe6367f4f667";
//                $partnerID = 842997;
//                $shopID = 220004172;
                $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
                $partnerID = 845652;
                $shopID = 1396523;
                
                
                //jd
                $appKeyJd = "4f167657105d3afd732653da83fb49a5";
                $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
                $accessTokenJd = "64514eb4d73290d0c9974b648058adec";
                
                $dbUser = "MINIMALIST";
            }
            else if( $dbName == "RALAMUSICWEB")
            {
                //web
                $wordPressDB = "ralamusi_2020";
                
                
                //LAZADA
                $url = "https://api.lazada.co.th/rest";
                $appKey = "119433";
                $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
                $accessToken = "50000800941srTr7jlPaRTABafvGJra0wDBhET8MyXcneJhakI08S1f443451ibq";//tokenStart: 23-06-2020 20:00
                $refreshToken = "50001800132cSOwTpBCUzHRjJ9pZOrah6GYiLTeTvGWj12d1cfe9bp4PyLkeT59x";//expire in 23-12-2020  20:20
                
                
                //shopee
//                $key = " e11e4df1f0badd2d79fe2c1ca176a99de792d3b0456cef0dd97bfe6367f4f667";
//                $partnerID = 842997;
//                $shopID = 220004172;
                $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
                $partnerID = 845652;
                $shopID = 1396523;
                
                
                //jd
                $appKeyJd = "4f167657105d3afd732653da83fb49a5";
                $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
                $accessTokenJd = "64514eb4d73290d0c9974b648058adec";
                
                
                $dbName = "ralamusi_2020";
                $dbUser = "ralamusi_2020";
                $dbPassword = "4p8GzaN8j9";
                $host = "localhost";
//                $host = "ralamusic.com";
            }
        }
        
        
//        $dbUser = "FFD";
        
        // Create connection
        $con=mysqli_connect($host,$dbUser,$dbPassword,$dbName);

        
        $timeZone = mysqli_query($con,"SET SESSION time_zone = '+07:00'");
        mysqli_set_charset($con, "utf8");
        $_POST["modifiedDate"] = date("Y-m-d H:i:s");
        
        
        //get lazada token
        $sql = "select Value from setting where settingKey = 'lazadaToken'";
        $settingList = executeQueryArray($sql);
        $accessToken = $settingList[0]->Value;
    }
    
    function getDeviceTokenFromUsername($user)
    {
        global $con;
        $sql = "select DeviceToken from useraccount where username = '$user'";
        $selectedRow = getSelectedRow($sql);
        $deviceToken = $selectedRow[0]['DeviceToken'];
        
        
        writeToLog('getDeviceTokenFromUsername deviceToken: ' . $deviceToken);
        return $deviceToken;
    }
    function doQueryTask($con,$sql,$user)
    {
        global $modifiedUser;
        $res = mysqli_query($con,$sql);        
        if(!$res)
        {
            $error = "query fail, sql: " . $sql . ", modified user: " . $modifiedUser . " error: " . mysqli_error($con);
            writeToLog($error);
            
            
            // Rollback transaction
            mysqli_rollback($con);
            $response = array('status' => $error, 'sucess'=>false);
            return $response;
        }
        else
        {
            writeToLog("query success, sql: " . $sql . ", modified user: " . $modifiedUser);
        }
        return "";
    }
    function doQueryTask2($con,$sql,$user)
    {
        $res = mysqli_query($con,$sql);
        if(!$res)
        {
            $error = "query fail, sql: " . $sql . ", modified user: " . $user . " error: " . mysqli_error($con);
            writeToLog($error);
            
    
            $response = array('status' => $error);
            return $response;
        }
        else
        {
            writeToLog("query success, sql: " . $sql . ", modified user: " . $_POST["modifiedUser"]);
        }
        return "";
    }

    function doPushNotificationTaskToDevice($con,$user,$deviceToken,$selectedRow,$type,$action)
    {
        $sql = "insert into pushSync (DeviceToken, TableName, Action, Data, TimeSync) values ('$deviceToken','$type','$action','" . json_encode($selectedRow, JSON_UNESCAPED_UNICODE) . "',now())";
        $ret = doQueryTask2($con,$sql,$_POST["modifiedUser"]);
        if($ret != "")
        {
            mysqli_rollback($con);
            return $ret;
        }
        $pushSyncID = mysqli_insert_id($con);
        writeToLog('pushsyncid: '.$pushSyncID);
        
        return "";
    }
    
    function doPushNotificationTask($con,$user,$deviceToken,$selectedRow,$type,$action)
    {
        $pushDeviceTokenList = getDeviceTokenList($deviceToken);
        
        foreach ($pushDeviceTokenList as $iDeviceToken)
        {
            //query statement
            if(strcmp($type,"sProductSales") == 0)
            {
                $sql = "insert into pushSync (DeviceToken, TableName, Action, Data, TimeSync) values ('$iDeviceToken','$type','$action','" . $selectedRow . "',now())";
            }
            else if(strcmp($type,"sCompareInventory") == 0)
            {
                $sql = "insert into pushSync (DeviceToken, TableName, Action, Data, TimeSync) values ('$iDeviceToken','$type','$action','" . $selectedRow . "',now())";
            }
            else
            {
                $sql = "insert into pushSync (DeviceToken, TableName, Action, Data, TimeSync) values ('$iDeviceToken','$type','$action','" . json_encode($selectedRow, JSON_UNESCAPED_UNICODE) . "',now())";
            }
            $ret = doQueryTask2($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_rollback($con);
                return $ret;
            }
            $pushSyncID = mysqli_insert_id($con);
            writeToLog('pushsyncid: '.$pushSyncID);
        }
        return "";
    }
    
    function doPushNotificationTaskAsLog($con,$user,$deviceToken,$selectedRow,$type,$action)
    {
        //query statement
        $sql = "insert into pushSync (DeviceToken, TableName, Action, Data, TimeSync,TimeSynced) values ('$deviceToken','$type','delete log','" . json_encode($selectedRow, true) . "',now(),now())";
        $ret = doQueryTask2($con,$sql,$_POST["modifiedUser"]);
        if($ret != "")
        {
            mysqli_rollback($con);
            return $ret;
        }
        $pushSyncID = mysqli_insert_id($con);
        writeToLog('delete log pushsyncid: '.$pushSyncID);
        return "";
    }
    
    function sendPushNotificationToAllDevices()
    {
        $pushDeviceTokenList = getAllDeviceTokenList();
        
        foreach ($pushDeviceTokenList as $iDeviceToken)
        {
            sendPushNotificationToDevice($iDeviceToken);
        }
    }
    
    function sendPushNotificationToOtherDevices($deviceToken)
    {
        $pushDeviceTokenList = getDeviceTokenList($deviceToken);
        foreach ($pushDeviceTokenList as $iDeviceToken)
        {
            sendPushNotificationToDevice($iDeviceToken);
        }
    }
    
    function sendPushNotificationToDevice($deviceToken)
    {
        $paramBody = array(
                           'badge' => 0
                           );
        sendPushNotification($deviceToken, $paramBody);
    }
    
    function doApplePushNotificationTask($con,$user,$deviceToken,$badge)
    {
        $deviceTokenAndCountNotSeenList = getDeviceTokenAndCountNotSeenList($user,$deviceToken);
        foreach ($deviceTokenAndCountNotSeenList as $deviceTokenAndCountNotSeen)
        {
            $deviceTokenCountNotSeen = $deviceTokenAndCountNotSeen["DeviceToken"];
            $countNotSeen = $deviceTokenAndCountNotSeen["CountNotSeen"];
            $username = $deviceTokenAndCountNotSeen["Username"];
            writeToLog('device token: ' . $deviceToken. ', count not seen: ' . $countNotSeen);
            $updateBadge = $badge+$countNotSeen;
            

            //query statement
            $sql = "update useraccount set countnotseen = '$updateBadge' where username = '$username'";
            $res = mysqli_query($con,$sql);
            if(!$res)
            {
                $error = "query fail, sql: " . $sql . ", modified user: " . $user . " error: " . mysqli_error($con);
                writeToLog($error);
                
                
                $response = array('status' => $error);
                return $response;
            }
            else
            {
                writeToLog("query success, sql: " . $sql . ", modified user: " . $_POST["modifiedUser"]);
            }
 
            $paramBody = array(
                               'badge' => $updateBadge
                               );
            sendApplePushNotification($deviceTokenCountNotSeen, $paramBody);
        }
        return "";
    }
    
    function updateCountNotSeen($con,$user,$deviceToken,$badge)
    {
        $deviceTokenAndCountNotSeenList = getDeviceTokenAndCountNotSeenList($user,$deviceToken);
        foreach ($deviceTokenAndCountNotSeenList as $deviceTokenAndCountNotSeen)
        {
            $deviceTokenCountNotSeen = $deviceTokenAndCountNotSeen["DeviceToken"];
            $countNotSeen = $deviceTokenAndCountNotSeen["CountNotSeen"];
            $username = $deviceTokenAndCountNotSeen["Username"];
            writeToLog('device token: ' . $deviceToken. ', count not seen: ' . $countNotSeen);
            writeToLog('badge to add: ' . $badge);
            $updateBadge = $badge+$countNotSeen;
            
            
            //query statement
            $sql = "update useraccount set countnotseen = $updateBadge where username = '$username'";
            $ret = doQueryTask2($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
//                mysqli_rollback($con);
                return $ret;
            }
            
            $paramBody = array(
                               'badge' => $updateBadge
                               );
            sendPushNotification($deviceTokenCountNotSeen, $paramBody);
        }
        return "";
    }
    
    function getSelectedRow($sql)
    {
        global $con;
        global $modifiedUser;
        
        
        $resultArray = array();
        $tempArray = array();
        if ($result = mysqli_query($con, $sql))
        {            
            while($row = mysqli_fetch_array($result))
            {
                $tempArray = $row;
                array_push($resultArray, $tempArray);
            }
            mysqli_free_result($result);
        }
        if(sizeof($resultArray) == 0)
        {
            $error = "query: selected row count = 0, sql: " . $sql . ", modified user: " . $modifiedUser;
            writeToLog($error);
        }
        else
        {
            writeToLog("query success, sql: " . $sql . ", modified user: " . $modifiedUser);
        }
        
        return $resultArray;
    }
    
    function getAllDeviceTokenList()
    {
        global $con;
        $sql = "select DeviceToken from Device where DeviceToken != ''";
        if ($result = mysqli_query($con, $sql))
        {
            $deviceTokenList = array();
            while($row = mysqli_fetch_array($result))
            {
                $strDeviceToken = $row["DeviceToken"];
                array_push($deviceTokenList, $strDeviceToken);
            }
            mysqli_free_result($result);
        }
        return $deviceTokenList;
    }
    
    function getDeviceTokenList($modifiedDeviceToken)
    {
        global $con;
        $sql = "select DeviceToken from Device where DeviceToken != '' and DeviceToken != '" . $modifiedDeviceToken . "'";
        if ($result = mysqli_query($con, $sql))
        {
            $deviceTokenList = array();
            while($row = mysqli_fetch_array($result))
            {
                $strDeviceToken = $row["DeviceToken"];
                array_push($deviceTokenList, $strDeviceToken);
            }
            mysqli_free_result($result);
        }

        return $deviceTokenList;
    }
    
    function getDeviceTokenAndCountNotSeenList($modifiedUser,$modifiedDeviceToken)
    {
        global $con;
        $sql = "select Device.DeviceToken, UserAccount.CountNotSeen, UserAccount.Username from Device left join UserAccount on Device.DeviceToken = UserAccount.DeviceToken where Device.DeviceToken != '" . $modifiedDeviceToken . "' and Device.DeviceToken != '' and UserAccount.PushOnSale = 1";
        writeToLog("countNotSeenList: " . $sql);
        if ($result = mysqli_query($con, $sql))
        {
            $deviceTokenAndCountNotSeenList = array();
            while($row = mysqli_fetch_array($result))
            {
                $strDeviceToken = $row["DeviceToken"];
                $strCountNotSeen = $row["CountNotSeen"];
                $strUsername = $row["Username"];
                array_push($deviceTokenAndCountNotSeenList, array("DeviceToken" => $strDeviceToken,"CountNotSeen" => $strCountNotSeen,"Username"=>$strUsername));
            }
            mysqli_free_result($result);
        }
        return $deviceTokenAndCountNotSeenList;
    }
    
    function writeToLogFromParentFolder($message)
    {
        
        global $globalDBName;
        $mday = getdate()["mday"];
        $day = sprintf("%02d", $mday);
        $mon = getdate()["mon"];
        $month = sprintf("%02d", $mon);
        $year = getdate()["year"];
        $logPath = './' . $globalDBName . '/TransactionLog/';
        $logFile = 'saimTransactinLog' . $year . $month . $day . '.log';
        if (!file_exists($logPath)) {
            mkdir($logPath, 0777, true);
        }
        $logPath = $logPath . $logFile;
        
        
        if ($fp = fopen($logPath, 'at'))
        {
            fwrite($fp, date('c') . ' ' . $message . PHP_EOL);
            fclose($fp);
        }
    }
    
    function writeToLog($message)
    {
        $message = "pid: ".getmypid().", ".$message;
        global $globalDBName;
        $mday = getdate()["mday"];
        $day = sprintf("%02d", $mday);
        $mon = getdate()["mon"];
        $month = sprintf("%02d", $mon);
        $year = getdate()["year"];
        
        $fileName = 'saimTransactinLog' . $year . $month . $day . '.log';
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/SAIM/' . $globalDBName . '/TransactionLog/';
        if (!file_exists($filePath))
        {
            mkdir($filePath, 0777, true);
        }
        
        $filePath = $filePath . $fileName;
        
        
        if ($fp = fopen($filePath, 'at'))
        {
            $arrMessage = explode("\\n",$message);
            if(sizeof($arrMessage) > 1)
            {
                foreach($arrMessage as $eachLine)
                {
                    $newMessge .= PHP_EOL . $eachLine ;
                }
            }
            else
            {
                $newMessge = $message;
            }
            
            fwrite($fp, date('c') . ' ' . $newMessge . PHP_EOL);
            fclose($fp);
        }
    }

    function writeToErrorLog($message)
    {
        global $globalDBName;
        $mday = getdate()["mday"];
        $day = sprintf("%02d", $mday);
        $mon = getdate()["mon"];
        $month = sprintf("%02d", $mon);
        $year = getdate()["year"];
        
        $fileName = 'saimErrorLog' . $year . $month . $day . '.log';
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/SAIM/' . $globalDBName . '/TransactionLog/';
        if (!file_exists($filePath))
        {
            mkdir($filePath, 0777, true);
        }
        
        $filePath = $filePath . $fileName;
        
        
        if ($fp = fopen($filePath, 'at'))
        {
            $arrMessage = explode("\\n",$message);
            if(sizeof($arrMessage) > 1)
            {
                foreach($arrMessage as $eachLine)
                {
                    $newMessge .= PHP_EOL . $eachLine ;
                }
            }
            else
            {
                $newMessge = $message;
            }
            
            fwrite($fp, date('c') . ' ' . $newMessge . PHP_EOL);
            fclose($fp);
        }
    }
    
    function sendPushNotification($strDeviceToken,$arrBody)
    {
//        writeToLog("send push to device: " . $strDeviceToken . ", body: " . json_encode($arrBody));
//        global $pushFail;
//        $token = $strDeviceToken;
//        $pass = 'jill';
//        $message = 'คุณพิสุทธิ์ กำลังไปเขาใหญ่กับฉัน แกอยากได้อะไรไหมกั๊ง (สายน้ำผึ้ง)pushnotification';
//
//
//        $ctx = stream_context_create();
//        stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
//        stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
//
//
//        if(!$pushFail)
//        {
//            $fp = stream_socket_client(
//                                       'ssl://gateway.sandbox.push.apple.com:2195', $err,
//                                       $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
//        }
//
//
//        if (!$fp)
//        {
//            $pushFail = true;
//            $error = "ติดต่อ Server ไม่ได้ ให้ลองย้อนกลับไป สร้าง pem ใหม่: $err $errstr" . PHP_EOL;
//            writeToLog($error);
//
//            return;
//        }
//
//
//        $body['aps'] = $arrBody;
//        $json = json_encode($body);
//        $msg = chr(0).pack('n', 32).pack('H*',$token).pack('n',strlen($json)).$json;
//        $result = fwrite($fp, $msg, strlen($msg));
//        if (!$result)
//        {
//            $status = "0";
//            writeToLog("push notification: fail, device token : " . $strDeviceToken . ", payload: " . json_encode($arrBody));
//        }
//        else
//        {
//            $status = "1";
//            writeToLog("push notification: success, device token : " . $strDeviceToken . ", payload: " . json_encode($arrBody));
//        }
//
//        fclose($fp);
//        return $status;
    }

    function sendLineNotify($sMessage)
    {
        global $lineNotifyToken;
        
        sendNotifyToDevice($lineNotifyToken,$sMessage);
    }
    
    function sendNotiToAdmin($sMessage)
    {
        global $lineAdminToken;
        global $globalDBName;
        
        sendNotifyToDevice($lineAdminToken,"[$globalDBName]".$sMessage);
    }
    
    function sendNotifyToDevice($lineNotifyToken,$sMessage)
    {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(E_ALL);
        date_default_timezone_set("Asia/Bangkok");


        $sToken = $lineNotifyToken;
//        $sToken = "UHpcdJ6MfMVkN3FBpKEyiapJjuUkKLDB3SWdCQLS1DL";
//        $sMessage = "ทดสอบ reminder stock to testReminderStock Group....";

        
        $chOne = curl_init();
        curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt( $chOne, CURLOPT_POST, 1);
        curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage);
        $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
        curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec( $chOne );

        //Result error
        if(curl_error($chOne))
        {
            writeToLog('error:' . curl_error($chOne));
        }
        else {
            $result_ = json_decode($result, true);
            writeToLog( "status : ".$result_['status']);
            writeToLog( "message : ". $result_['message']);
        }
        curl_close( $chOne );
    }
    
    function luhnAlgorithm($number)
    {
        $stack = 0;
        $number = str_split(strrev($number));

        foreach ($number as $key => $value)
        {
            if ($key % 2 == 0)
            {
                $value = array_sum(str_split($value * 2));
            }
            $stack += $value;
        }
        $stack %= 10;

        if ($stack != 0)
        {
            $stack -= 10;     $stack = abs($stack);
        }


        $number = implode('', array_reverse($number));
        $number = $number . strval($stack);
        return $number;
    }
    
    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    function sendEmail($toAddress,$subject,$body)
    {
        require './phpmailermaster/PHPMailerAutoload.php';
        $mail = new PHPMailer;
//        writeToLog("phpmailer");
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.ralamusic.com';//'cpanel02mh.bkk1.cloud.z.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication // if not need put false
        $mail->Username = 'app@ralamusic.com';                 // SMTP username
        $mail->Password = 'Ralamusic12';                           // SMTP password
        
//        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted // if nedd
//        $mail->Port = 465;                                    // TCP port to connect to // if nedd
        
        $mail->From = 'app@ralamusic.com'; // mail form user mail auth smtp
        $mail->FromName = 'RALA MUSIC APP';//$_POST['dbName'];
        $mail->addAddress($toAddress); // Add a recipient
        //$mail->addAddress('ellen@example.com'); // if nedd
        //$mail->addReplyTo('info@example.com', 'Information'); // if nedd
        //$mail->addCC('cc@example.com'); // if nedd
        //$mail->addBCC('bcc@example.com'); // if nedd
        
        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments // if nedd
        //$mail->addAttachment('http://minimalist.co.th/imageupload/34664/minimalistLogoReceipt.gif', 'logo.gif');    // Optional name // if nedd
//        $mail->AddEmbeddedImage('minimalistLogoReceipt.jpg', 'logo', 'minimalistLogoReceipt.jpg');
        $mail->isHTML(true);                                  // Set email format to HTML // if format mail html // if no put false
        
        $mail->Subject = $subject; // text subject
        $mail->Body    = $body; // body
        
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; // if nedd
//        writeToLog("before send()");
        if(!$mail->send())
        { // check send mail true/false
            echo 'Message could not be sent.'; // message if send mail not complete
            echo 'Mailer Error: ' . $mail->ErrorInfo; // message error
            $response = array('success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo);
            
            $error = "send email fail, Mailer Error: " . $mail->ErrorInfo . ", modified user: " . $user;
            writeToLog($error);
        }
        else
        {
            //    echo 'Message has been sent'; // message if send mail complete
            $response = array('success' => true);
        }
        return $response;
    }
    
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    function sendFirebasePushNotification($token, $noti)
    {        
        global $fcmServerKey;
        $key = $fcmServerKey;
        
        writeToLog("send firebase push");
        // create curl resource
        $ch = curl_init();
        
        // set url
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        
        
        
        //payload
        $paramBody = array(
                           "to" => $token
                           ,"notification" => $noti
//                           ,"data" => $data
                           );
        $payload = json_encode($paramBody);
        writeToLog($payload);
        
        
        //header
        $header = array();
        $header[] = 'Content-Type:application/json';
        $header[] = 'Authorization: key=' . $key;
        
        
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        
        
        
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // $output contains the output string
        $output = curl_exec($ch);
        
        
        if ($output === false)
        {
            // throw new Exception('Curl error: ' . curl_error($crl));
            print_r('Curl error: ' . curl_error($ch));
        }
        // close curl resource to free up system resources
        curl_close($ch);
    }
    
    function sendApplePushNotification($token,$noti)
    {
        writeToLog("send push to device: " . $token . ", body: " . json_encode($noti));
//        global $pushFail;
        $pass = "jilljill";
        $message = 'pushnotification';
        

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
        
        
//        if(!$pushFail)
        {
            global $sandBox;
            $sandBox = false;
            if($sandBox)
            {
                $fp = stream_socket_client(
                                       'ssl://gateway.sandbox.push.apple.com:2195', $err,
                                       $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
            }
            else
            {
                $fp = stream_socket_client(
                                           'ssl://gateway.push.apple.com:2195', $err,
                                           $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
            }
        }
        
        
        if (!$fp)
        {
            $pushFail = true;
            $error = "ติดต่อ Server ไม่ได้ ให้ลองย้อนกลับไป สร้าง pem ใหม่: $err $errstr" . PHP_EOL;
            writeToLog($error);
            
            return;
        }
        $aps = array();
        $aps["alert"] = $noti;
        $body['aps'] = $aps;
        $json = json_encode($body);
        $msg = chr(0).pack('n', 32).pack('H*',$token).pack('n',strlen($json)).$json;
        $result = fwrite($fp, $msg, strlen($msg));
        if (!$result)
        {
            $status = "0";
            writeToLog("push notification: fail, device token : " . $token . ", payload: " . json_encode($noti));
        }
        else
        {
            $status = "1";
            writeToLog("push notification: success, device token : " . $token . ", payload: " . json_encode($noti));
        }
        
        fclose($fp);
        return $status;
    }
    
    function array2xml($array, $xml = false)
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
?>
