<?php
    include_once('dbConnect.php');
    set_time_limit(1200);
  
  
    $json_str = file_get_contents('php://input');
//    $skus = json_decode($json_str)->skus;
    $storeName = json_decode($json_str)->storeName;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    if(!$storeName)
    {
        $storeName = "RALAMUSIC";
        $modifiedUser = "";
    }
    
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
   
    
    // Set autocommit to off
//    mysqli_autocommit($con,FALSE);
//    writeToLog("set auto commit to off");

    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    $success = true;
    
    
    //lazada
    $orders = getPendingOrdersLazada();
    writeToLog("size of orders: " . sizeof($orders));
    $pendingOrderCount = sizeof($orders);
    if($pendingOrderCount > 0)
    {
        for($i=0; $i<sizeof($orders); $i++)
        {
            $order = $orders[$i];
            $orderNo = $order->order_number;
            $sql = "select * from lazadaOrder where orderNo = '$orderNo'";
            $selectedRow = getSelectedRow($sql);
            if(sizeof($selectedRow) == 0)
            {
                //update stock 4 ช่องทาง
                for($j=0; $j<sizeof($order->items); $j++)
                {
                    $orderItem = $order->items[$j];
                    $sku = $orderItem->sku;
                    $quantity = 1;
//                    echo $sku;
//                    exit();

                    $sql = "select * from mainproduct where sku = '$sku'";
                    $selectedRow = getSelectedRow($sql);
                    if(sizeof($selectedRow)==0)
                    {
                        $success = false;
                        $message = "lazada order ($orderNo) cannot update quantity in main, sku ($sku) not found";
                        writeToLog($message);
                        sendNotiToAdmin($message);

                        //noti to shop
                        //อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                        //ไม่พบ sku ในระบบ [sku]:[$sku]
                    }
                    else
                    {
                        $mainProductQuantity = $selectedRow[0]["Quantity"];
                        if($mainProductQuantity - $quantity < 0)
                        {
                            $success = false;
                            $message = "lazada order ($orderNo) cannot update quantity in main, quantity not enough [sku,quantity,mainQuantity]: [$sku,$quantity,$mainProductQuantity]";
                            writeToLog($message);
                            sendNotiToAdmin($message);

                            //noti to shop
                            //อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                            //จำนวนสินค้าในระบบไม่พอ [sku,quantity]:[$sku,$quantity]
                        }
                        else
                        {
                            $mainProductQuantity -= $quantity;
                            $sql = "update mainProduct set quantity = '$mainProductQuantity' where sku = '$sku'";
                            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                            if($ret != "")
                            {
                                $success = false;
                                $message = "lazada order ($orderNo) cannot update quantity in main, [sku,quantity]: [$sku,$quantity], error:".$ret->status;
                                writeToLog($message);
                                sendNotiToAdmin($message);


                                //noti to shop
                                //เกิดข้อผิดพลาด อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                                //[sku,quantity]:[$sku,$quantity]

                            }
                            else
                            {
                                //insert lazadaOrder in app
                                $lazadaOrder = mysqli_real_escape_string($con,json_encode($order, JSON_UNESCAPED_UNICODE));
                                $sql = "insert into lazadaOrder (lazadaOrder,orderNo) values('$lazadaOrder','$orderNo')";
                                $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                                if($ret != "")
                                {
                                    $success = false;
                                    $message = "lazada order ($orderNo) cannot insert orderNo in lazadaOrder, [sku,quantity]: [$sku,$quantity], error:".$ret->status;
                                    writeToLog($message);
                                    sendNotiToAdmin($message);


                                }



                                //update marketplace
                                $failMarketplace = array();


//                                //update product in lazada
//                                $ret = updateStockQuantityLazadaInApp($sku,$mainProductQuantity);
//                                if(!$ret)
//                                {
//                            //        mysqli_close($con);
//                                    $failMarketplace[] = "Lazada";
//                                }


                                //update product in shopee
                                $ret = updateStockQuantityShopeeInApp($sku,$mainProductQuantity);
                                if(!$ret)
                                {
                            //        mysqli_close($con);
                                    $failMarketplace[] = "Shopee";
                                }


                                //update product in jd
                                $ret = updateStockQuantityJdInApp($sku,$mainProductQuantity);
                                if(!$ret)
                                {
                            //        mysqli_close($con);
                                    $failMarketplace[] = "JD";
                                }


                                if(sizeof($failMarketplace)>0)
                                {
                                    $marketplaceFail = $failMarketplace[0];
                                    for($k=1; $k<sizeof($failMarketplace); $k++)
                                    {
                                        $marketplaceFail .= ", $failMarketplace[$k]";
                                    }


                                    $success = false;
                                    $message = "lazada order ($orderNo) cannot update quantity in marketplace($marketplaceFail), [sku,quantity]: [$sku,$quantity]";
                                    writeToLog($message);
                                    sendNotiToAdmin($message);


                                    //noti to shop
                                    //เกิดข้อผิดพลาด อัพเดทจำนวนสินค้าใน marketplace ($marketplaceFail) ไม่สำเร็จ shopee order no. $orderSn
                                    //[sku,quantity]:[$sku,$quantity]
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    
    //jd
    $orders = getPendingOrdersJd();
    $pendingOrderCount = sizeof($orders);
    if($pendingOrderCount > 0)
    {
        for($i=0; $i<sizeof($orders); $i++)
        {
            $order = $orders[$i];
            $orderNo = $order->orderId;
            $sql = "select * from jdOrder where orderNo = '$orderNo'";
            $selectedRow = getSelectedRow($sql);
            if(sizeof($selectedRow) == 0)
            {
                //update stock 4 ช่องทาง
                for($j=0; $j<sizeof($order->skus); $j++)
                {
                    $orderItem = $order->skus[$j];
                    $sku = $orderItem->sellerSkuId;
                    $quantity = 1;
                    //                     echo $sku;
                    //                     exit();

                    $sql = "select * from mainproduct where sku = '$sku'";
                    $selectedRow = getSelectedRow($sql);
                    if(sizeof($selectedRow)==0)
                    {
                     $success = false;
                     $message = "jd order ($orderNo) cannot update quantity in main, sku ($sku) not found";
                     writeToLog($message);
                     sendNotiToAdmin($message);

                     //noti to shop
                     //อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                     //ไม่พบ sku ในระบบ [sku]:[$sku]
                    }
                    else
                    {
                         $mainProductQuantity = $selectedRow[0]["Quantity"];
                         if($mainProductQuantity - $quantity < 0)
                         {
                             $success = false;
                             $message = "jd order ($orderNo) cannot update quantity in main, quantity not enough [sku,quantity,mainQuantity]: [$sku,$quantity,$mainProductQuantity]";
                             writeToLog($message);
                             sendNotiToAdmin($message);

                             //noti to shop
                             //อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                             //จำนวนสินค้าในระบบไม่พอ [sku,quantity]:[$sku,$quantity]
                         }
                         else
                         {
                             $mainProductQuantity -= $quantity;
                             $sql = "update mainProduct set quantity = '$mainProductQuantity' where sku = '$sku'";
                             $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                             if($ret != "")
                             {
                                 $success = false;
                                 $message = "jd order ($orderNo) cannot update quantity in main, [sku,quantity]: [$sku,$quantity], error:".$ret->status;
                                 writeToLog($message);
                                 sendNotiToAdmin($message);


                                 //noti to shop
                                 //เกิดข้อผิดพลาด อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                                 //[sku,quantity]:[$sku,$quantity]

                             }
                             else
                             {
                                 //insert jdOrder in app
                                 $jdOrder = mysqli_real_escape_string($con,json_encode($order, JSON_UNESCAPED_UNICODE));
                                 $sql = "insert into jdOrder (jdOrder,orderNo) values('$jdOrder','$orderNo')";
                                 $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                                 if($ret != "")
                                 {
                                     $success = false;
                                     $message = "jd order ($orderNo) cannot insert orderNo in jdOrder, [sku,quantity]: [$sku,$quantity], error:".$ret->status;
                                     writeToLog($message);
                                     sendNotiToAdmin($message);


                                 }



                                 //update marketplace
                                 $failMarketplace = array();


                                 //update product in lazada
                                 $ret = updateStockQuantityLazadaInApp($sku,$mainProductQuantity);
                                 if(!$ret)
                                 {
                             //        mysqli_close($con);
                                     $failMarketplace[] = "Lazada";
                                 }


                                 //update product in shopee
                                 $ret = updateStockQuantityShopeeInApp($sku,$mainProductQuantity);
                                 if(!$ret)
                                 {
                             //        mysqli_close($con);
                                     $failMarketplace[] = "Shopee";
                                 }


//                                 //update product in jd
//                                 $ret = updateStockQuantityJdInApp($sku,$mainProductQuantity);
//                                 if(!$ret)
//                                 {
//                             //        mysqli_close($con);
//                                     $failMarketplace[] = "JD";
//                                 }


                                 if(sizeof($failMarketplace)>0)
                                 {
                                     $marketplaceFail = $failMarketplace[0];
                                     for($k=1; $k<sizeof($failMarketplace); $k++)
                                     {
                                         $marketplaceFail .= ", $failMarketplace[$k]";
                                     }


                                     $success = false;
                                     $message = "jd order ($orderNo) cannot update quantity in marketplace($marketplaceFail), [sku,quantity]: [$sku,$quantity]";
                                     writeToLog($message);
                                     sendNotiToAdmin($message);


                                     //noti to shop
                                     //เกิดข้อผิดพลาด อัพเดทจำนวนสินค้าใน marketplace ($marketplaceFail) ไม่สำเร็จ shopee order no. $orderSn
                                     //[sku,quantity]:[$sku,$quantity]
                                 }
                             }
                         }
                    }
                }
            }
        }
    }
    
    
    // Close connections
//    mysqli_commit($con);
    mysqli_close($con);
    $message = $success?"อัพเดทจำนวนสินค้าสำเร็จ":"เกิดข้อผิดพลาด อัพเดทจำนวนสินค้าไม่สำเร็จ";
    echo json_encode(array("success"=>$success,"message"=>$message));
    
?>
