<?php
    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
    ini_set("memory_limit","50M");
    writeToLog("file: " . basename(__FILE__));
    
    

    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    $sql = "INSERT INTO Receipt(EventID, Channel, ReferenceOrderNo, PayPrice, PaymentMethod, CreditAmount, CashAmount, TransferAmount, CashReceive, Remark, ShippingFee, Discount, DiscountValue, DiscountPercent, DiscountReason, RedeemedValue, EarnedPoints, ReceiptDate, SalesUser) VALUES ('$eventID', '$channel', '$referenceOrderNo', '$payPrice', '$paymentMethod', '$creditAmount', '$cashAmount', '$transferAmount', '$cashReceive', '$remark', '$shippingFeeReceipt', '$discountReceipt', '$discountValueReceipt', '$discountPercentReceipt', '$discountReasonReceipt', '$redeemedValue', '$earnedPoints', now(), '$salesUser')";
    $ret = doQueryTask2($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        mysqli_rollback($con);
//        putAlertToDevice($_POST["modifiedUser"]);
        echo json_encode($ret);
        exit();
    }
    
    
    //-----
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    
    
    $response = array('status' => '1', 'sql' => $sql);
    echo json_encode($response);
    exit();
?>
