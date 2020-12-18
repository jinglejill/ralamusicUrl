<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str)->storeName;
    $sku = json_decode($json_str)->sku;
    $shareSku = json_decode($json_str)->shareSku;
    $stockSharing = json_decode($json_str)->stockSharing;
    $modifiedUser = json_decode($json_str)->modifiedUser;
 
    
    if(!$storeName)
    {
        $storeName = $_POST["storeName"];
        $sku = $_POST["sku"];
        $shareSku = $_POST["shareSku"];
        $stockSharing = $_POST["stockSharing"];
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
    
    
    
    
    $sku =  mysqli_real_escape_string($con,$sku);
    $escapeShareSku =  mysqli_real_escape_string($con,$shareSku);
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    if($stockSharing)
    {
        $sql = "select * from stockSharing where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow) == 0)
        {
            $sql = "insert into stockSharingGroup (modifiedUser) values ('$modifiedUser')";
            $ret = doQueryTask($con,$sql,$modifiedUser);
            if($ret != "")
            {
                $stockSharing = getStockSharing();
                $ret["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                $ret["sku"] = $shareSku;
                $ret["stockSharing"] = $stockSharing;
                mysqli_close($con);
                
                echo json_encode($ret);
                exit();
            }
            $stockSharingGroupID  = mysqli_insert_id($con);
            
            
            //insert stockSharing
            $sql = "insert into stockSharing (stockSharingGroupID,sku,modifiedUser) values('$stockSharingGroupID','$escapeShareSku','$modifiedUser'),('$stockSharingGroupID','$sku','$modifiedUser')";
            $ret = doQueryTask($con,$sql,$modifiedUser);
            if($ret != "")
            {
                $stockSharing = getStockSharing();
                $ret["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                $ret["sku"] = $shareSku;
                $ret["stockSharing"] = $stockSharing;
                mysqli_close($con);
                
                echo json_encode($ret);
                exit();
            }
        }
        else
        {
            $stockSharingGroupID = $selectedRow[0]["StockSharingGroupID"];
            
            
            //insert stockSharing
            $sql = "insert into stockSharing (stockSharingGroupID,sku,modifiedUser) values('$stockSharingGroupID','$escapeShareSku','$modifiedUser')";
            $ret = doQueryTask($con,$sql,$modifiedUser);
            if($ret != "")
            {
                $stockSharing = getStockSharing();
                $ret["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                $ret["sku"] = $shareSku;
                $ret["stockSharing"] = $stockSharing;
                mysqli_close($con);
                
                echo json_encode($ret);
                exit();
            }
        }
    }
    else
    {
        //prepare before delete
        $sql = "select * from stockSharing where stockSharingGroupID in (select stockSharingGroupID from stockSharing where sku = '$sku') and sku = '$escapeShareSku'";
        $dataList = executeQueryArray($sql);
        if(sizeof($dataList)>0)
        {
            $json = json_encode($dataList);
            $tableName = "StockSharing";
            keepDeleteRecord($tableName,$json);
            
            
            //delete
            $stockSharingID = $dataList[0]->StockSharingID;
            $sql = "delete from stockSharing where stockSharingID = '$stockSharingID'";
            $ret = doQueryTask($con,$sql,$modifiedUser);
            if($ret != "")
            {
                $stockSharing = getStockSharing();
                $ret["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                $ret["sku"] = $shareSku;
                $ret["stockSharing"] = $stockSharing;
                mysqli_close($con);
                
                echo json_encode($ret);
                exit();
            }
            
            
            
            //if has only one , delete it and the groupID
            $sql = "select * from stockSharing where stockSharingGroupID in (select stockSharingGroupID from stockSharing where sku = '$sku') and sku != '$sku'";
            $selectedRow = getSelectedRow($sql);
            if(sizeof($selectedRow) == 0)
            {
                //delete stocksharing
                $sql = "select * from stockSharing where sku = '$sku'";
                $dataList = executeQueryArray($sql);
                $stockSharingGroupID = $dataList[0]->StockSharingGroupID;
                $json = json_encode($dataList);
                $tableName = "StockSharing";
                keepDeleteRecord($tableName,$json);
                
                                
                $sql = "delete from stockSharing where sku = '$sku'";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $stockSharing = getStockSharing();
                    $ret["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                    $ret["sku"] = $shareSku;
                    $ret["stockSharing"] = $stockSharing;
                    mysqli_close($con);
                    
                    echo json_encode($ret);
                    exit();
                }
                
                
                
                //delete group
                $sql = "select * from stockSharingGroup where stockSharingGroupID = '$stockSharingGroupID'";
                $dataList = executeQueryArray($sql);
                $json = json_encode($dataList);
                $tableName = "StockSharingGroup";
                keepDeleteRecord($tableName,$json);
                
                
                
                $sql = "delete from stockSharingGroup where stockSharingGroupID = '$stockSharingGroupID'";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $stockSharing = getStockSharing();
                    $ret["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                    $ret["sku"] = $shareSku;
                    $ret["stockSharing"] = $stockSharing;
                    mysqli_close($con);
                    
                    echo json_encode($ret);
                    exit();
                }
            }
        }
    }
    
    
    
    $stockSharing = getStockSharing();
    $ret["success"] = true;
    $ret["message"] = "";
    $ret["sku"] = $shareSku;
    $ret["stockSharing"] = $stockSharing;
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode($ret);
    exit();
    
    
    function getStockSharing()
    {
        global $sku;
        global $shareSku;
        global $escapeShareSku;
        
        $sql = "select * from stockSharing where stockSharingGroupID in (select stockSharingGroupID from stockSharing where sku = '$sku') and sku = '$escapeShareSku'";
        $selectedRow = getSelectedRow($sql);
        $stockSharing = sizeof($selectedRow) > 0?1:0;
        
        if($stockSharing == 0)
        {
            $sql = "select * from stockSharing where sku = '$escapeShareSku'";
            $selectedRow = getSelectedRow($sql);
            $stockSharing = sizeof($selectedRow) > 0?2:0;
        }
        return $stockSharing;
    }
    
    function keepDeleteRecord($tableName,$json)
    {
        global $con;
        global $modifiedUser;
        global $shareSku;
        global $escapeShareSku;
        
        
        $json =  mysqli_real_escape_string($con,$json);
        $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $stockSharing = getStockSharing();
            $ret["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
            $ret["sku"] = $shareSku;
            $ret["stockSharing"] = $stockSharing;
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
    }
?>
