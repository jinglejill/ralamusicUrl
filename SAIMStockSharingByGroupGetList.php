<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $sku = json_decode($json_str)->sku;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    
    
    
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    writeToLog("userAgent: ".$_SERVER['HTTP_USER_AGENT']);
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    $escapeSku = mysqli_real_escape_string($con,$sku);
    $sql = "select Sku from MainProduct where sku = '$escapeSku'";
    $products = executeQueryArray($sql);
    $product = $products[0];
    
    
    
    
    $sql = "select 0 as PressStatusDelete,stocksharing.Sku,mainproduct.MainImage from stocksharing left join mainproduct on stocksharing.sku = mainproduct.Sku where StockSharingGroupID in (SELECT StockSharingGroupID FROM `stocksharing` WHERE sku = '$escapeSku') order by Sku";
    $stockSharingList = executeQueryArray($sql);
    $product->StockSharingList = $stockSharingList;
    
    
    $result = array("product"=>$product, "success"=>true);
    echo json_encode($result);
    
    
    exit();
?>
