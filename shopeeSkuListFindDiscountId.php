<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSICTEST");
//    setConnectionValue("MINIMALIST");
    set_time_limit(720);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    $page = $_GET["page"];
    $array = array();
    $sql = "select * from (select @row:=@row+1 as row, shopeeProduct.* from shopeeProduct,(select @row:=0)t)a where row > ($page-1)*1000 limit 1000";
    $dataList = executeQueryArray($sql);
    for($i=0; $i<sizeof($dataList); $i++)
    {
        $data = $dataList[$i];
        $item = getItemShopee($data->ItemID);
        if($item->discount_id != 0)
        {
            echo $data->ItemID;
            exit();
        }
        $sku = mysqli_real_escape_string($con,$data->Sku);
//        $attributesJson = mysqli_real_escape_string($con,json_encode($item->attributes));
//        $sql = "update shopeeProduct set categoryID = $item->category_id, attributesJson = '$attributesJson' where sku = '$sku'";
//        $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
//        if($ret != "")
//        {
//            mysqli_close($con);
//
//            echo json_encode($ret);
//            exit();
//        }
    
    }
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    exit();
    
    
//select DISTINCT mainproduct.PrimaryCategory,shopeeproduct.CategoryID from mainproduct LEFT JOIN shopeeproduct on mainproduct.Sku = shopeeproduct.Sku where shopeeproduct.ShopeeProductID is not null and mainproduct.PrimaryCategory != 0
?>
