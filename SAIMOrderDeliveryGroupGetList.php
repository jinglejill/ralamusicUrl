<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $page = json_decode($json_str)->page;
    $limit = json_decode($json_str)->limit;
    $searchText = json_decode($json_str)->searchText;
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
    
   
    
    if($searchText != "")
    {
        $sql = "select c.* from (select @row:=@row+1 as RowNum, a.* from (select OrderDeliveryGroupID, date_format(CheckDate,'%e %b %Y %H:%i') CheckDate from OrderDeliveryGroup where date_format(CheckDate,'%Y-%m-%d') = date_format('$searchText','%Y-%m-%d') order by OrderDeliveryGroupID desc)a, (select @row:=0)t)c where c.RowNum > ($page-1)*$limit limit $limit";
    }
    else
    {
        $sql = "select c.* from (select @row:=@row+1 as RowNum, a.* from (select OrderDeliveryGroupID, date_format(CheckDate,'%e %b %Y %H:%i') CheckDate from OrderDeliveryGroup order by OrderDeliveryGroupID desc)a, (select @row:=0)t)c where c.RowNum > ($page-1)*$limit limit $limit";
    }
    
    $orderDeliveryGroupList = executeQueryArray($sql);
    

    for($i=0; $i<sizeof($orderDeliveryGroupList); $i++)
    {
        $orderDeliveryGroupID = $orderDeliveryGroupList[$i]->OrderDeliveryGroupID;
        $sql = "select count(*) Count from orderDelivery where orderDeliveryGroupID = '$orderDeliveryGroupID'";
        $orderDeliveryList = executeQueryArray($sql);
        $orderDeliveryGroupList[$i]->OrderCount = $orderDeliveryList[0]->Count;
    }
    
    
    $data = array("orderDeliveryGroupList"=>$orderDeliveryGroupList);
    writeToLog("orderDeliveryGroupList: " . json_encode($data) );
    echo json_encode($data);
    
    
    //-----
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    
    
    exit();
?>
