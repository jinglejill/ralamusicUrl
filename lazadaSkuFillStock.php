<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    set_time_limit(600);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    
    $limit = $_GET["limit"];
    $page = $_GET["page"];
    $sql = "select * from lazadaProduct where ProductID > ($page-1)*$limit order by ProductID limit $limit";
    $selectedRow = getSelectedRow($sql);
    
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $productID = $selectedRow[$i]["ProductID"];
        $sku = $selectedRow[$i]["Sku"];
        $quantity = getStockQuantityLazada($sku);

        $sql = "update lazadaProduct set quantity = '$quantity' where productID = '$productID'";
        $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
        if($ret != "")
        {
            echo json_encode($ret);
            exit();
        }
    }
?>
