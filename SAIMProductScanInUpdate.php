<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str,true)["storeName"];
    $sku = json_decode($json_str,true)["sku"];
    $quantity = json_decode($json_str,true)["quantity"];
    $quantityIn = $quantity;
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $data["modifiedUser"]);
    writeToLog("post json: " . $json_str);
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    function getProductQuantity($sku)
    {
        $product = getProduct($sku);
        return $product["Quantity"];
    }
    
    function getProductImage($sku)
    {
        $product = getProduct($sku);
        return $product["MainImage"];
    }
    
    function getProduct($sku)
    {
        $sql = "select * from mainProduct where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
//        writeToLog("get product:".json_encode($selectedRow));
        return $selectedRow[0];
    }
    
    
    $sql = "select * from mainproduct where sku = '$sku'";
    $selectedRow = getSelectedRow($sql);
    if(!sizeof($selectedRow)>0)
    {
        $ret = array();
        $ret["success"] = false;
        $ret["quantity"] = getProductQuantity($sku);
        $ret["sku"] = $sku;
        $ret["message"] = "Sku: $sku\r\nไม่พบสินค้านี้";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    $mainProductQuantity = $selectedRow[0]["Quantity"];
    $quantity += $mainProductQuantity;
    
    
    //update product in Main
    $sql = "update mainProduct set quantity = '$quantity' where sku = '$sku'";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        $ret["quantity"] = getProductQuantity($sku);
        $ret["sku"] = $sku;
        $ret["message"] = "Sku: $sku\r\nแก้ไขจำนวนไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    //update marketplace
    $failMarketplace = array();
    
    
    //update product in lazada
    $ret = updateStockQuantityLazadaInApp($sku,$quantity);
    if(!$ret)
    {
//        mysqli_close($con);
        $failMarketplace[] = "Lazada";
    }
    
    
    //update product in shopee
    $ret = updateStockQuantityShopeeInApp($sku,$quantity);
    if(!$ret)
    {
//        mysqli_close($con);
        $failMarketplace[] = "Shopee";
    }
    
    
    //update product in jd
    $ret = updateStockQuantityJdInApp($sku,$quantity);
    if(!$ret)
    {
//        mysqli_close($con);
        $failMarketplace[] = "JD";
    }
    
    
    if(sizeof($failMarketplace)>0)
    {
        $success = false;
        $message = "Sku: $sku\r\nไม่สามารถแก้ไขจำนวนที่ $failMarketplace[0]";
        for($i=1; $i<sizeof($failMarketplace); $i++)
        {
            $message .= ", $failMarketplace[$i]";
        }
    }
    else
    {
        $success = true;
        $message = "";
    }
    mysqli_commit($con);
    
    
    $asIsQuantity = getProductQuantity($sku);
    $mainImage = getProductImage($sku);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>$success, "message"=>$message, "sku"=>$sku, "quantity"=>$asIsQuantity, "quantityIn"=>$quantityIn, "mainImage"=>$mainImage));
    exit();
?>
