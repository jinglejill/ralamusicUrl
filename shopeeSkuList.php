<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    set_time_limit(720);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    //truncate temp
    $sql = "truncate `shopeeproducttemp`;";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        mysqli_close($con);
        
        echo json_encode($ret);
        sendNotiToAdmin($dbName.": Cannot fetch shopee sku");
        exit();
    }
    
    
    
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
    //    echo "Curl Errno returned $curl_errno <br/>";
          
        if ($result === false)
        {
            writeToLog('Curl error: ' . curl_error($ch));
        }
        

        $obj = json_decode($result);
        
        
        for($i=0; $i<sizeof($obj->items); $i++)
        {
            $item = $obj->items[$i];
//            if($item->item_sku == "middleton")
//            {
//                continue;
//            }
            if(sizeof($item->variations) > 0)
            {
                for($k=0; $k<sizeof($item->variations); $k++)
                {
                    $variation = $item->variations[$k];
//                    if($variation->variation_sku == $sku)
                    {
//                        echo "<br>".$item->item_id.";".$item->item_sku.";".$variation->variation_id.";".$variation->variation_sku;
                        
                        
                    }
                }
            }
            else
            {
//                if($item->item_sku == $sku)
                {
//                    echo "<br>".$item->item_id.";".$item->item_sku.";"."0".";"."";
                    
                    
                    //insert new fetch to temp*****
                    $sku = mysqli_real_escape_string($con,$item->item_sku);
                    $sql = "INSERT INTO `shopeeproducttemp`(`Sku`, `ItemID`, `ItemSku`, `VariationID`, `VariationSku`, `Quantity`, `ModifiedUser`) values('$sku','$item->item_id','$sku','0','','$item->quantity','bot')";
                    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                    if($ret != "")
                    {
                        mysqli_close($con);
                        
                        echo json_encode($ret);
                        sendNotiToAdmin($dbName.": Cannot fetch shopee sku");
                        exit();
                    }
                    //insert new fetch to temp*****
                }
            }
        }
        
        if(sizeof($obj->items) < 100)
        {
            //truncate backup
            //move current to backup
            $sql = "truncate shopeeProductBackup;";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_close($con);

                echo json_encode($ret);
                sendNotiToAdmin($dbName.": Cannot fetch shopee sku");
                exit();
            }

            $sql = "insert into shopeeproductbackup(`Sku`, `ItemID`, `ItemSku`, `VariationID`, `VariationSku`, `Quantity`, `ModifiedUser`) select `Sku`, `ItemID`, `ItemSku`, `VariationID`, `VariationSku`, `Quantity`, `ModifiedUser` from shopeeProduct";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_close($con);

                echo json_encode($ret);
                sendNotiToAdmin($dbName.": Cannot fetch shopee sku");
                exit();
            }


            //truncate current
            //move from temp to current
            $sql = "truncate shopeeProduct;";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_close($con);

                echo json_encode($ret);
                sendNotiToAdmin($dbName.": Cannot fetch shopee sku");
                exit();
            }

            $sql = "insert into shopeeproduct(`Sku`, `ItemID`, `ItemSku`, `VariationID`, `VariationSku`, `Quantity`, `ModifiedUser`) select `Sku`, `ItemID`, `ItemSku`, `VariationID`, `VariationSku`, `Quantity`, `ModifiedUser` from shopeeProductTemp";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_close($con);

                echo json_encode($ret);
                sendNotiToAdmin($dbName.": Cannot fetch shopee sku");
                exit();
            }

            mysqli_commit($con);
            mysqli_close($con);
            
            break;
        }
    }
    
    echo json_encode(array("success"=>true));
    exit();
?>
