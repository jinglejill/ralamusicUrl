<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str)->storeName;
    $username = json_decode($json_str)->username;
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
    
    
    //user
    $sql = "select * from useraccount where username = '$username'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $userAccountID = $dataList[0]->UserAccountID;
    
    $tableName = "UserAccount";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบผู้ใช้ไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    //user role
    $sql = "select * from userrole where useraccountID = '$userAccountID'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    
    $tableName = "UserRole";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบผู้ใช้ไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    
    $sql = "delete from userAccount where username = '$username'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบผู้ใช้ไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from userRole where useraccountID = '$userAccountID'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบผู้ใช้ไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>true));
    exit();
?>
