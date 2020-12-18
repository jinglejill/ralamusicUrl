<?php
//    include_once('dbConnect.php');
    $json_str = file_get_contents('php://input');
    
    $storeName = $_POST["storeName"];
    $skus = $_POST["skus"];
    
    $success = false;
    $message = "test";
    echo json_encode(array("success"=>$success,"message"=>$message));
    exit();
//
//    echo json_encode(array("a"=>"b"));
?>


