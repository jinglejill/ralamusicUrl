<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str)->storeName;
    $sku = json_decode($json_str)->sku;
    $stockSharingList = json_decode($json_str)->stockSharingList;
    $modifiedUser = json_decode($json_str)->modifiedUser;
 
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    
    
    $retJsonArray = array();
    if(sizeof($stockSharingList) == 0)
    {
        $retJsonArray["message"] = "";
        mysqli_close($con);
        
        echo json_encode($retJsonArray);
        exit();
    }
    
    
    $sku =  mysqli_real_escape_string($con,$sku);
    
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    for($i=0; $i<sizeof($stockSharingList); $i++)
    {
        $shareSku = $stockSharingList[$i]->Sku;
        $shareSku =  mysqli_real_escape_string($con,$shareSku);
        $stockSharing = $stockSharingList[$i]->StockSharing;
        
        
        if($stockSharing)
        {
            $sql = "select * from stockSharing where sku = '$shareSku'";//check ว่าส่งค่ามาตามที่มีใน db จริง
            $selectedRow = getSelectedRow($sql);
            if(sizeof($selectedRow) == 0)
            {
                $sql = "select * from stockSharing where sku = '$sku'";
                $selectedRow = getSelectedRow($sql);
                if(sizeof($selectedRow) == 0)
                {
                    $sql = "insert into stockSharingGroup (modifiedUser) values ('$modifiedUser')";
                    $ret = doQueryTask($con,$sql,$modifiedUser);
                    if($ret != "")
                    {
                        $retJsonArray["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                        mysqli_close($con);
                        
                        echo json_encode($retJsonArray);
                        exit();
                    }
                    $stockSharingGroupID  = mysqli_insert_id($con);
                    
                    
                    //insert stockSharing
                    $sql = "insert into stockSharing (stockSharingGroupID,sku,modifiedUser) values('$stockSharingGroupID','$shareSku','$modifiedUser'),('$stockSharingGroupID','$sku','$modifiedUser')";//insert 2 sku เลย
                    $ret = doQueryTask($con,$sql,$modifiedUser);
                    if($ret != "")
                    {
                        $retJsonArray["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                        mysqli_close($con);
                        
                        echo json_encode($retJsonArray);
                        exit();
                    }
                }
                else
                {
                    $stockSharingGroupID = $selectedRow[0]["StockSharingGroupID"];
    
    
                    //insert stockSharing
                    $sql = "insert into stockSharing (stockSharingGroupID,sku,modifiedUser) values('$stockSharingGroupID','$shareSku','$modifiedUser')";
                    $ret = doQueryTask($con,$sql,$modifiedUser);
                    if($ret != "")
                    {
                        $retJsonArray["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                        mysqli_close($con);
    
                        echo json_encode($retJsonArray);
                        exit();
                    }
                }
            }
            else//ส่งค่ามาไม่ตามใน db ให้ return fail ไป
            {
                $retJsonArray["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                mysqli_close($con);
                
                echo json_encode($retJsonArray);
                exit();
            }
            
        }
        else
        {
            //prepare before delete
            $sql = "select * from stockSharing where sku = '$shareSku'";
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
                    $retJsonArray["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                    mysqli_close($con);
                    
                    echo json_encode($retJsonArray);
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
                        $retJsonArray["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                        mysqli_close($con);
                        
                        echo json_encode($retJsonArray);
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
                        $retJsonArray["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                        mysqli_close($con);
                        
                        echo json_encode($retJsonArray);
                        exit();
                    }
                }
            }
            else//ส่งค่ามาไม่ตามใน db ให้ return fail ไป
            {
                $retJsonArray["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
                mysqli_close($con);
                
                echo json_encode($retJsonArray);
                exit();
            }
        }
    }
    
    
    
    
    $retJsonArray["success"] = true;
    $retJsonArray["message"] = "";

    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode($retJsonArray);
    exit();
    
    
    function getStockSharing()
    {
        global $sku;
        global $shareSku;
        
        $sql = "select * from stockSharing where stockSharingGroupID in (select stockSharingGroupID from stockSharing where sku = '$sku') and sku = '$shareSku'";
        $selectedRow = getSelectedRow($sql);
        $stockSharing = sizeof($selectedRow) > 0?1:0;
        
        if($stockSharing == 0)
        {
            $sql = "select * from stockSharing where sku = '$shareSku'";
            $selectedRow = getSelectedRow($sql);
            $stockSharing = sizeof($selectedRow) > 0?2:0;
        }
        return $stockSharing;
    }
    
    function keepDeleteRecord($tableName,$json)
    {
        global $con;
        global $modifiedUser;
        
        $json = mysqli_real_escape_string($con,$json);
        $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $ret["message"] = "ตั้งค่า Stock sharing ไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
    }
?>
