<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    set_time_limit(720);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    //this item is not allowed to edit -> status = deleted
    //2809924523
    //6315932908
//    6901758234
//    6015653442
//    4615652095
//    4515651963
//    6914350181
//    3107403153
//    3104212418
//    7701752381
    //2832452235
    //2832436160
    //2825837439
    //2825756235
    //2825707386
//    2893181098
//    2207485733
//    2207471485
//    2887327652
//    2207513500
    //1258329068
    //2904880456
    //2905626093
    //2187538885
    
    $limit = $_GET["limit"];
    $page = $_GET["page"];
    $sql = "select a.Quantity,ShopeeProduct.ItemID from (select Sku, Quantity from mainProduct where ProductID > ($page-1)*$limit order by ProductID limit $limit)a left join shopeeProduct on a.sku = shopeeProduct.sku where shopeeProduct.shopeeProductID is not null";
    $selectedRow = getSelectedRow($sql);
    
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $quantity = $selectedRow[$i]["Quantity"];
        $itemID = $selectedRow[$i]["ItemID"];


//        $ret = updateStockQuantityShopee($sku,$quantity);
        $updateVariations = array();
        $updateVariation = array();
        $updateVariation["item_id"] = intval($itemID);
        $updateVariation["variation_id"] = 0;
        $updateVariation["stock"] = intval($quantity);
        $updateVariations[] = $updateVariation;
//        echo json_encode($updateVariations);
//        exit();
        $ret = updateStockBatchShopee($updateVariations,"items");
        echo "<br>".$ret;
    }
?>
