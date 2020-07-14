<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    set_time_limit(720);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
   
    
    $limit = $_GET["limit"];
    $page = $_GET["page"];
    $sql = "select a.*,jdproduct.SkuId from (select Sku, Quantity from mainProduct where ProductID > ($page-1)*$limit order by ProductID limit $limit)a left join jdProduct on a.sku = jdProduct.sku where jdProductID is not null";
    $selectedRow = getSelectedRow($sql);
    
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $quantity = $selectedRow[$i]["Quantity"];
        $skuId = $selectedRow[$i]["SkuId"];


        $ret = updateStockJd($skuId,$quantity,0);
        echo "<br>$skuId;".$ret;
    }
?>
