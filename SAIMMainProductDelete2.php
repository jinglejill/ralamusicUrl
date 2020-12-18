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
    $escapeSku = mysqli_real_escape_string($con,$sku);
    $sql = "select * from mainProduct where sku = '$escapeSku'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $json = mysqli_real_escape_string($con,$json);
    
    
    //move images to deleted
    $image1 = $dataList[0]->MainImage;
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
    //******
    
    
    $tableName = "MainProduct";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from mainProduct where sku = '$escapeSku'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    $sql = "select * from mapsku where sku = '$escapeSku'";
    $mapSku = executeQueryArray($sql);
    $lazadaSku = $mapSku[0]->LazadaSku;
    $shopeeSku = $mapSku[0]->ShopeeSku;
    $jdSku = $mapSku[0]->JdSku;
    $webSku = $mapSku[0]->WebSku;
    
    $escapeLazadaSku = mysqli_real_escape_string($con,$lazadaSku);
    $escapeShopeeSku = mysqli_real_escape_string($con,$shopeeSku);
    $escapeJdSku = mysqli_real_escape_string($con,$jdSku);
    $escapeWebSku = mysqli_real_escape_string($con,$webSku);
    
    //lazada product
    $sql = "select * from lazadaProduct where sku = '$escapeLazadaSku'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $json = mysqli_real_escape_string($con,$json);
    
    
    $tableName = "LazadaProduct";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from lazadaProduct where sku = '$escapeLazadaSku'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }

    
    //shopee product
    $sql = "select * from shopeeProduct where sku = '$escapeShopeeSku'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $json = mysqli_real_escape_string($con,$json);
    
    
    $tableName = "ShopeeProduct";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from shopeeProduct where sku = '$escapeShopeeSku'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    //jd product
    $sql = "select * from jdProduct where sku = '$escapeJdSku'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $json = mysqli_real_escape_string($con,$json);
    
    
    $tableName = "JdProduct";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from jdProduct where sku = '$escapeJdSku'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    
    //delete mapsku product
    $sql = "select * from mapSku where sku = '$escapeSku'";
    $dataList = executeQueryArray($sql);
    $json = json_encode($dataList);
    $json = mysqli_real_escape_string($con,$json);
    
    
    $tableName = "MapSku";
    $sql = "insert into deleted (json,tableName,ModifiedUser) values ('$json','$tableName','$modifiedUser')";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $sql = "delete from mapSku where sku = '$escapeSku'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลบสินค้าไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    //stocksharing
    $sql = "select * from stocksharing where sku = '$escapeSku'";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) > 0)
    {
        $stockSharingGroupID = $selectedRow[0]["StockSharingGroupID"];
        $sql = "select * from stocksharing where stockSharingGroupID = '$stockSharingGroupID' and sku != '$escapeSku'";
        $selectedRow = getSelectedRow($sql);
        $mainSku = $selectedRow[0]["Sku"];
        $shareSku = $sku;
        $stockSharingList = array();
        $stockSharing = array();
        $stockSharing["Sku"] = $shareSku;
        $stockSharing["StockSharing"] = false;
        $stockSharingList[] = $stockSharing;
        
        
        $param = array();
        $param["storeName"] = $storeName;
        $param["sku"] = $mainSku;
        $param["stockSharingList"] = $stockSharingList;
        $param["modifiedUser"] = $modifiedUser;
        $ret = editStockSharingList($param);
        if(!$ret)
        {
            $ret["message"] = "ลบสินค้าไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
    }
    
    
    
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>true));
    exit();
?>
