<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str)->storeName;
    $email = json_decode($json_str)->email;
    $deviceInfo = json_decode($json_str)->deviceInfo;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    
    
    if(!$storeName)
    {
        $storeName = $_POST["storeName"];
        $email = $_POST["email"];
        $deviceInfo = $_POST["deviceInfo"];
        $modifiedUser = $_POST["modifiedUser"];
    }
    


    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    if(!$storeName)
    {
        printAllPost();
    }
    else
    {
        writeToLog("post json: " . $json_str);
    }

    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    
    $sql = "select * from userAccount where email = '$email'";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) == 0)
    {
        $message = "ไม่พบ email นี้ในระบบ";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("insert forgotPassword fail: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    else
    {
        $username = $selectedRow[0]["Username"];
    }
    
    
    //send email
    $requestDate = date('Y-m-d H:i:s', time());
    $randomString = generateRandomString();
    $codeReset = password_hash($email . $requestDate . $randomString, PASSWORD_DEFAULT);
    $content = file_get_contents('./SAIMEmailTemplateResetPassword.php');
    $content = str_replace("#DBNAME#",$dbName,$content);
    $content = str_replace("#USERNAME#",$username,$content);
    $content = str_replace("#CODERESET#",$codeReset,$content);
    $subject = 'RALA MUSIC APP ตั้งค่ารหัสผ่านใหม่';    
    $sql = "INSERT INTO `forgotpassword`(`CodeReset`, `Email`, `RequestDate`, `Status`, `ModifiedUser`) VALUES ('$codeReset','$email','$requestDate','1','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $message = "ไม่สามารถส่งอีเมลได้ กรุณาติดต่อเจ้าหน้าที่";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("insert forgotPassword fail: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    
    
    $result = sendEmail($email,$subject,$content);
    if(!$result)
    {
        $message = "ไม่สามารถส่งอีเมลได้ กรุณาติดต่อเจ้าหน้าที่";
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_commit($con);
        mysqli_close($con);

        
        writeToLog("sendEmail fail: $message, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
        echo json_encode($ret);
        exit();
    }
    
    
    $message = "อีเมลถูกส่งแล้ว คุณสามารถตั้งค่ารหัสผ่านใหม่ได้จากในอีเมล";
    
    
        
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true, "message"=>$message));
    exit();
    

?>


