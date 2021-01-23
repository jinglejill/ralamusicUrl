<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $orderDeliveryGroupID = json_decode($json_str)->orderDeliveryGroupID;
    $modifiedUser = json_decode($json_str)->modifiedUser;


    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    writeToLog("userAgent: ".$_SERVER['HTTP_USER_AGENT']);

    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    $sql = "select * from OrderDelivery where orderDeliveryGroupID = '$orderDeliveryGroupID'";
    $dataList = executeQueryArray($sql);
    if(sizeof($dataList) > 0)
    {
        $ret["success"] = false;
        $ret["message"] = "มีรายการออเดอร์ภายใต้ Group นี้\nยืนยันลบ Group นี้หรือไม่";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $param = array();
    $param["storeName"] = $storeName;
    $param["orderDeliveryGroupID"] = $orderDeliveryGroupID;
    $param["modifiedUser"] = $modifiedUser;
    
    $ret = deleteOrderDeliveryGroup($param);
    echo json_encode($ret);
    exit();
    
    
    function deleteOrderDeliveryGroup($param)
    {
        global $contentType;
        global $appUrl;
        
        
        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        
        //url
        $url = $appUrl . "/SAIMOrderDeliveryGroupDelete.php";
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
        
        
        return $obj;
    }
?>
