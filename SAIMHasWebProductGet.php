<?php
    include_once('dbConnect.php');
    setConnectionValue("RALAMUSICWEB");

    
    $json_str = file_get_contents('php://input');
    writeToLog("has web product json: " . $json_str);
    
    
    $json_obj = json_decode($json_str);
    
    $sku = $json_obj->sku;
//    $sku = $_GET["sku"];
    $ret = hasWebProductInRalaWeb($sku);
    echo json_encode(array("success"=>$ret));
    
    exit();

    
?>


