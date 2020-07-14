<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    ini_set("memory_limit","50M");
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
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
                    echo $item->item_id . ";" . $item->item_sku . ";" . $variation->variation_id . ";" . $variation->variation_sku . "<br>";
                }
            }
            else
            {
                echo $item->item_id . ";" . $item->item_sku . "<br>";
            }
        }
        
        if(sizeof($obj->items) < 100)
        {
            break;
        }
    }
?>
