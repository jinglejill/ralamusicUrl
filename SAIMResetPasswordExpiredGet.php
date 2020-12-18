<?php
    include_once('dbConnect.php');
    
    
//    $json_str = file_get_contents('php://input');
//
//
//    $storeName = json_decode($json_str)->storeName;
//    $codeReset = json_decode($json_str)->codeReset;
//    $deviceInfo = json_decode($json_str)->deviceInfo;
//    $modifiedUser = json_decode($json_str)->modifiedUser;
    $storeName = $_POST["storeName"];
    $codeReset = $_POST["codeReset"];
    $deviceInfo = $_POST["deviceInfo"];
    $modifiedUser = $_POST["modifiedUser"];
    

    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    printAllPost();
//    writeToLog("post json: " . $json_str);
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
  
    
    
    
    $sql = "select * from forgotPassword where codeReset = '$codeReset' and status = 2";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) > 0)
    {
        $message = "คุณได้ใช้ลิงค์นี้ตั้งค่ารหัสผ่านใหม่ไปแล้ว หากคุณต้องการตั้งค่ารหัสผ่านใหม่อีกครั้งหนึ่ง ให้กดลิงค์ลืมรหัสผ่าน? ด้านล่างนี้";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("This reset password link has already been used: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    $sql = "select * from forgotPassword where codeReset = '$codeReset' and status = 1";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) > 0)
    {
        $requestDate = $selectedRow[0]["RequestDate"];
        $email = $selectedRow[0]["Email"];
        
        
//        if(time()-StrToTime($requestDate) > 1*20)//1*60 = 1 mins
        if(time()-StrToTime($requestDate) > 2*60*60)//2 hours
        {
            $message = "EXPIRED";//ลิงค์นี้หมดอายุแล้ว
            $ret = array();
            $ret["success"] = false;
            $ret["message"] = $message;
            mysqli_commit($con);
            mysqli_close($con);

            
            writeToLog("This reset password link is expired: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
            echo json_encode($ret);
            exit();
        }
    }
    else
    {
        $message = "ลิงค์ตั้งค่ารหัสผ่านใหม่นี้ไม่ถูกต้อง หากคุณยังคงต้องการตั้งค่ารหัสผ่านใหม่ ให้กดลิงค์ลืมรหัสผ่าน? ด้านล่างนี้";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("This reset password link is invalid: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
  
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true, "message"=>$message));
    exit();
    

?>


