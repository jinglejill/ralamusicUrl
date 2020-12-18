<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');

    $storeName = json_decode($json_str)->storeName;
    $username = json_decode($json_str)->username;
    $email = json_decode($json_str)->email;
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
    
    
    if($email == "")
    {
        mysqli_close($con);
        
        echo json_encode(array("success"=>false,"message"=>"กรุณาใส่ Email"));
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      mysqli_close($con);
      
      echo json_encode(array("success"=>false,"message"=>"รูปแบบ Email ไม่ถูกต้อง"));
      exit();
    }
    
    
    $sql = "update `useraccount` set `Email` = '$email', `ModifiedUser`='$modifiedUser' where username = '$username'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "แก้ไข Email ไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    $message = "แก้ไข Email สำเร็จ";
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true, "message"=>$message, "email"=>$email));
    exit();
    

?>


