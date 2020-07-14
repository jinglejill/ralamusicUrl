<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    set_time_limit(720);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    
    $limit = $_GET["limit"];
    $page = $_GET["page"];
    $sql = "select * from shopeeProduct where shopeeProductID > ($page-1)*$limit order by shopeeProductID limit $limit";
    $selectedRow = getSelectedRow($sql);
    
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $shopeeProductID = $selectedRow[$i]["ShopeeProductID"];
        $itemID = $selectedRow[$i]["ItemID"];
        $variationID = $selectedRow[$i]["VariationID"];
        $quantity = getStockShopee($itemID,$variationID);
        
        $sql = "update shopeeProduct set quantity = '$quantity' where shopeeProductID = '$shopeeProductID'";
        $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
        if($ret != "")
        {
            echo json_encode($ret);
            exit();
        }
    }
?>
