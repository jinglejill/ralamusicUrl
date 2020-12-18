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
    
   
    //OrderDeliveryGroup
    $sql = "select * from OrderDeliveryGroup where orderDeliveryGroupID = '$orderDeliveryGroupID'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $tableName = "OrderDeliveryGroup";
    $ret = keepDeleteRecord($tableName,$json);
    
    
    //OrderDelivery
    $sql = "select * from OrderDelivery where orderDeliveryGroupID = '$orderDeliveryGroupID'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $tableName = "OrderDelivery";
    $ret = keepDeleteRecord($tableName,$json);
    
    
    //move images to deleted
    $image1 = $dataList[0]->Image1;
    $image2 = $dataList[0]->Image2;
    $image3 = $dataList[0]->Image3;
    $image4 = $dataList[0]->Image4;
    $image5 = $dataList[0]->Image5;
    $image6 = $dataList[0]->Image6;
    $image7 = $dataList[0]->Image7;
    $image8 = $dataList[0]->Image8;
    if($image1 != "")
    {
        $source = str_replace($appImageUrl,'.',$image1);
        rename($source,str_replace("/Images/","/Deleted/",$source));
    }
    if($image2 != "")
    {
        $source = str_replace($appImageUrl,'.',$image2);
        rename($source,str_replace("/Images/","/Deleted/",$source));
    }
    if($image3 != "")
    {
        $source = str_replace($appImageUrl,'.',$image3);
        rename($source,str_replace("/Images/","/Deleted/",$source));
    }
    if($image4 != "")
    {
        $source = str_replace($appImageUrl,'.',$image4);
        rename($source,str_replace("/Images/","/Deleted/",$source));
    }
    if($image5 != "")
    {
        $source = str_replace($appImageUrl,'.',$image5);
        rename($source,str_replace("/Images/","/Deleted/",$source));
    }
    if($image6 != "")
    {
        $source = str_replace($appImageUrl,'.',$image6);
        rename($source,str_replace("/Images/","/Deleted/",$source));
    }
    if($image7 != "")
    {
        $source = str_replace($appImageUrl,'.',$image7);
        rename($source,str_replace("/Images/","/Deleted/",$source));
    }
    if($image8 != "")
    {
        $source = str_replace($appImageUrl,'.',$image8);
        rename($source,str_replace("/Images/","/Deleted/",$source));
    }
    
    
    //OrderDeliveryItem
    $sql = "select * from OrderDeliveryItem where orderDeliveryID in (select OrderDeliveryID from OrderDelivery where orderDeliveryGroupID = '$orderDeliveryGroupID')";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $tableName = "OrderDeliveryItem";
    $ret = keepDeleteRecord($tableName,$json);
   
    
    
    //delete
    $sql = "delete from OrderDeliveryItem where orderDeliveryID in (select OrderDeliveryID from OrderDelivery where orderDeliveryGroupID = '$orderDeliveryGroupID')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบ order delivery recheck ไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from OrderDelivery where orderDeliveryGroupID = '$orderDeliveryGroupID'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบ order delivery recheck ไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from OrderDeliveryGroup where orderDeliveryGroupID = '$orderDeliveryGroupID'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบ order delivery recheck ไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    //delete*****
    
    
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
        
        $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        return $ret == "";
    }
?>
