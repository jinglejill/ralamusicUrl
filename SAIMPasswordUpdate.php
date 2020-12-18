<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');

    $storeName = json_decode($json_str)->storeName;
    $username = json_decode($json_str)->username;
    $currentPassword = json_decode($json_str)->currentPassword;
    $password = json_decode($json_str)->password;
    $passwordAgain = json_decode($json_str)->passwordAgain;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    $hashPassword = hash('sha256', "$currentPassword$salt");
    $sql = "select * from useraccount where username = '$username' and password = '$hashPassword'";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) == 0)
    {
        $message = "รหัสผ่านปัจจุบันไม่ถูกต้อง";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("current password is not correct: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    if($password == "")
    {
        mysqli_commit($con);
        mysqli_close($con);
        
        writeToLog("$message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode(array("success"=>false,"message"=>"กรุณาใส่รหัสผ่านใหม่"));
        exit();
    }
    
    if($password != $passwordAgain)
    {
        $message = "รหัสผ่านใหม่ทั้ง 2 อันไม่ตรงกัน โปรดตรวจสอบอีกครั้ง";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("The 2 passwords are not equal: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    
    $hashPassword = hash('SHA256',$password.$salt);
    $sql = "update useraccount set password = '$hashPassword' where username = '$username'";
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
    
    
    $message = "ตั้งค่ารหัสผ่านใหม่สำเร็จ";
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true, "message"=>$message));
    exit();
    

?>


