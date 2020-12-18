<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');
    $username = json_decode($json_str)->username;
    $password = json_decode($json_str)->password;
    $deviceInfo = json_decode($json_str)->deviceInfo;
    $token = json_decode($json_str)->token;
    $storeName = json_decode($json_str)->storeName;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    $logInOut = 1;

    
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
    
    
    //validate************
    if(trim($username) == "")
    {
        $message = "กรุณาระบุ username";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_close($con);

        
        writeToLog("validate fail: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    
    if(trim($password) == "")
    {
        $message = "กรุณาระบุ password";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_close($con);

        
        writeToLog("validate fail: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    $passwordHash = hash('sha256', "$password$salt");
    $sql = "select * from userAccount where username = '$username' and password = '$passwordHash'";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) == 0)
    {
        $message = "Username/Password ไม่ถูกต้อง";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_close($con);

        
        writeToLog("validate fail: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    
    $sql = "insert into login (`Username`, `LogInOut`, `DeviceInfo`, `Token`, `ModifiedUser`) values ('$username','$logInOut','$deviceInfo','$token','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $message = "ไม่สามารถ Log in ได้\nมีข้อผิดพลาดเกิดขึ้น";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_close($con);

        
        writeToLog("insert login fail: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    $sql = "select * from useraccount where username = '$username'";
    $userList = executeQueryArray($sql);
    $user = $userList[0];
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true,"user"=>$user));
    exit();
    
    
?>


