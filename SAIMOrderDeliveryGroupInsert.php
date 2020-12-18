<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str,true)["storeName"];
    $modifiedUser = json_decode($json_str,true)["modifiedUser"];
    
    

    
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
    
   
    $sql = "insert into OrderDeliveryGroup (CheckDate,ModifiedUser) values (now(),'$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "เพิ่ม order delivery recheck ไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    $orderDeliveryGroupID  = mysqli_insert_id($con);
    
    
    
    //-----
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    
    echo json_encode(array("success"=>true,"orderDeliveryGroupID"=>$orderDeliveryGroupID));
    exit();
?>
