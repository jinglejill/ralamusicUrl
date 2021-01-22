<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str,true)["storeName"];
    $orderDeliveryGroupID = json_decode($json_str,true)["orderDeliveryGroupID"];
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
    
    
    $sql = "select count(*) Count from orderDelivery where orderDeliveryGroupID = '$orderDeliveryGroupID'";
    $orderDeliveryList = executeQueryArray($sql);
    $orderCount = $orderDeliveryList[0]->Count;
    
    
    $sql = "select date_format(CheckDate,'%e %b %Y %H:%i') CheckDate from orderDeliveryGroup where orderDeliveryGroupID = '$orderDeliveryGroupID'";
    $orderDeliveryList = executeQueryArray($sql);
    $checkDate = $orderDeliveryList[0]->CheckDate;
    
    
    if($orderCount > 0)
    {
        $noti = array();
        $noti["title"] = "Order delivery recheck";
        $noti["body"] = "#$orderDeliveryGroupID (qty. $orderCount) at $checkDate";
        $sql = "select useraccount.* from userrole left join role on userrole.roleID = role.roleID left join useraccount on userrole.UserAccountID = useraccount.UserAccountID where role.code = 'super_admin'";
        $userAccountList = executeQueryArray($sql);
        for($i=0; $i<sizeof($userAccountList); $i++)
        {
            $username = $userAccountList[$i]->Username;
            $sql = "select Token from login where username = '$username' order by modifiedDate desc limit 1";
            $loginList = executeQueryArray($sql);
            $token = $loginList[0]->Token;
            if($token && $token != "")
            {
                if(strlen($token) == 64 || strlen($token) == 32)
                {
                    writeToLog("send apple");
                    sendApplePushNotification($token,$noti);
                }
                else
                {
                    writeToLog("send firebase");
//                    if($token != 'csK19RJER_OYJWgmIanZJc:APA91bGhiFWWZw1VTqD7O0dAt8muk7n7NKR_PrlXb_Fo5jahkHUgGuRIRggN52VkT3fYWSQ-bOr-6PBnCJrW9BnhMQWG19o2yrpzGOgdAO-fbbm1LH_RApXTXNgSZVSiZzyn5DJCrSz_')
                    {
                        sendFirebasePushNotification($token,$noti);
                    }
                }
            }
        }
    }
    else
    {
        //OrderDeliveryGroup
        $sql = "select * from OrderDeliveryGroup where orderDeliveryGroupID = '$orderDeliveryGroupID'";
        $dataList = executeQueryArray($sql);
        $json = json_encode($dataList);
        $tableName = "OrderDeliveryGroup";
        $ret = keepDeleteRecord($tableName,$json);
        
        
        $sql = "delete from orderDeliveryGroup where orderDeliveryGroupID = '$orderDeliveryGroupID'";
        $ret = doQueryTask($con,$sql,$modifiedUser);
//        if($ret != "")
//        {
//            $ret["message"] = "ลบ order delivery recheck ไม่สำเร็จ";
//            mysqli_close($con);
//
//            echo json_encode($ret);
//            exit();
//        }
    }
    
    
    //-----
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    
    echo json_encode(array("success"=>true));
    exit();
    
    
    function keepDeleteRecord($tableName,$json)
    {
        global $con;
        global $modifiedUser;
        
        $json = mysqli_real_escape_string($con,$json);
        $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        return $ret == "";
    }
?>
