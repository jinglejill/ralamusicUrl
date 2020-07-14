<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str,true)["storeName"];
    $sku = json_decode($json_str,true)["sku"];
    $modifiedUser = json_decode($json_str,true)["modifiedUser"];
    
    
    
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
    
    
    //main product
    $sql = "select * from mainProduct where sku = '$sku'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    
    $tableName = "MainProduct";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from mainProduct where sku = '$sku'";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    //lazada product
    $sql = "select * from lazadaProduct where sku = '$sku'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    
    $tableName = "LazadaProduct";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from lazadaProduct where sku = '$sku'";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }

    
    //shopee product
    $sql = "select * from shopeeProduct where sku = '$sku'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    
    $tableName = "ShopeeProduct";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from shopeeProduct where sku = '$sku'";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    //jd product
    $sql = "select * from jdProduct where sku = '$sku'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    
    $tableName = "JdProduct";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from jdProduct where sku = '$sku'";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
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
