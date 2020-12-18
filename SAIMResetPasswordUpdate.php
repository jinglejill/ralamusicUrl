<?php
    include_once('dbConnect.php');
    
    
//    $json_str = file_get_contents('php://input');
//
//
//    $storeName = json_decode($json_str)->storeName;
//    $email = json_decode($json_str)->email;
//    $deviceInfo = json_decode($json_str)->deviceInfo;
//    $modifiedUser = json_decode($json_str)->modifiedUser;
    
    $codeReset = $_POST["codeReset"];
    $newPassword = $_POST["newPassword"];
    $newPasswordAgain = $_POST["newPasswordAgain"];
    
    $storeName = $_POST["storeName"];
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
    
  
    
    if($newPassword != $newPasswordAgain)
    {
        $message = "รหัสผ่านทั้ง 2 อันไม่ตรงกัน โปรดตรวจสอบอีกครั้ง";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("The 2 passwords are not equal: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    
    $sql = "select * from forgotPassword where codeReset = '$codeReset' and status = 2";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow)>0)
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
    if(sizeof($selectedRow) == 0)
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
    
    
    $requestDate = $selectedRow[0]["RequestDate"];
    $email = $selectedRow[0]["Email"];
    
    
    writeToLog("time left:".time()-StrToTime($requestDate));
//    if(time()-StrToTime($requestDate) > 1*10)//1*60 = 1 min
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
    
    writeToLog("password and salt:".$newPassword.$salt);
    $hashPassword = hash('SHA256',$newPassword.$salt);
    $sql = "update useraccount set password = '$hashPassword' where email = '$email'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $message = "ไม่สามารถตั้งค่ารหัสผ่านใหม่ได้ กรุณาติดต่อเจ้าหน้าที่";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("update password fail: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    
    
    $sql = "update forgotPassword set status = '2' where codeReset = '$codeReset'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $message = "ไม่สามารถตั้งค่ารหัสผ่านใหม่ได้ กรุณาติดต่อเจ้าหน้าที่";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("update forgotPassword status fail: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    
    $message = "ตั้งค่ารหัสผ่านใหม่สำเร็จ";
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true, "message"=>$message));
    exit();
    

?>


