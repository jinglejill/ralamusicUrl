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
                    $escapeSku = mysqli_real_escape_string($con,$sku);
                    $quantity = 1;


                    $sql = "select * from mainproduct where sku = '$escapeSku'";
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
//                            $sql = "update mainProduct set quantity = '$mainProductQuantity' where sku = '$sku'";
                            $sql = "update mainProduct set quantity = '$mainProductQuantity' where sku in (select sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$escapeSku')) or sku = '$escapeSku'";
                            $ret = doQueryTask($con,$sql,$modifiedUser);
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
                                $ret = doQueryTask($con,$sql,$modifiedUser);
                                if($ret != "")
                                {
                                    $success = false;
                                    $message = "lazada order ($orderNo) cannot insert orderNo in lazadaOrder, [sku,quantity]: [$sku,$quantity], error:".$ret->status;
                                    writeToLog($message);
                                    sendNotiToAdmin($message);


                                }



                                //update marketplace
                                $failMarketplace = array();


                                //update every sku that share the same pool of stock
                                $sql = "select * from (select Sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$escapeSku') UNION select '$escapeSku' as Sku)a order by Sku";
                                $selectedRow = getSelectedRow($sql);
                                for($k=0; $k<sizeof($selectedRow); $k++)
                                {
                                    $sku = $selectedRow[$k]["Sku"];
                                    $escapeSku = mysqli_real_escape_string($con,$sku);
    //                                //update product in lazada
    //                                $ret = updateStockQuantityLazadaInApp($sku,$mainProductQuantity);
    //                                if(!$ret)
    //                                {
    //                            //        mysqli_close($con);
    //                                    $failMarketplace[] = "Lazada";
    //                                }

                                    //*****map sku******
                                    $sql = "select * from mapsku where sku = '$escapeSku'";
                                    $mapSku = executeQueryArray($sql);
                                    $shopeeSku = $mapSku[0]->ShopeeSku;
                                    $jdSku = $mapSku[0]->JdSku;
                                    //*****map sku******


                                    //update product in shopee
                                    $ret = updateStockQuantityShopeeInApp($shopeeSku,$mainProductQuantity);
                                    if(!$ret)
                                    {
                                        if(!in_array("Shopee", $failMarketplace))
                                        {
                                            $failMarketplace[] = "Shopee";
                                        }
                                    }


                                    //update product in jd
                                    $ret = updateStockQuantityJdInApp($jdSku,$mainProductQuantity);
                                    if(!$ret)
                                    {
                                        if(!in_array("JD", $failMarketplace))
                                        {
                                            $failMarketplace[] = "JD";
                                        }
                                    }
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
                    $escapeSku = mysqli_real_escape_string($con,$sku);
                    $quantity = 1;
                    
                    
                    //map sku by jdsku
                    $sql = "select * from mapsku where jdsku = '$escapeSku'";
                    $mapSku = executeQueryArray($sql);
                    $mainSku = $mapSku[0]->Sku;
                    $lazadaSku = $mapSku[0]->LazadaSku;

                    
                    $escapeMainSku = mysqli_real_escape_string($con,$mainSku);
                    //map sku
                    
                    

                    $sql = "select * from mainproduct where sku = '$escapeMainSku'";
                    $selectedRow = getSelectedRow($sql);
                    if(sizeof($selectedRow)==0)
                    {
                         $success = false;
                         $message = "jd order ($orderNo) cannot update quantity in main, sku ($escapeMainSku) not found";
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
                             $message = "jd order ($orderNo) cannot update quantity in main, quantity not enough [sku,quantity,mainQuantity]: [$escapeMainSku,$quantity,$mainProductQuantity]";
                             writeToLog($message);
                             sendNotiToAdmin($message);

                             //noti to shop
                             //อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                             //จำนวนสินค้าในระบบไม่พอ [sku,quantity]:[$sku,$quantity]
                         }
                         else
                         {
                             $mainProductQuantity -= $quantity;
//                             $sql = "update mainProduct set quantity = '$mainProductQuantity' where sku = '$sku'";
                             $sql = "update mainProduct set quantity = '$mainProductQuantity' where sku in (select sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$escapeMainSku')) or sku = '$escapeMainSku'";
                             $ret = doQueryTask($con,$sql,$modifiedUser);
                             if($ret != "")
                             {
                                 $success = false;
                                 $message = "jd order ($orderNo) cannot update quantity in main, [sku,quantity]: [$escapeMainSku,$quantity], error:".$ret->status;
                                 writeToLog($message);
                                 sendNotiToAdmin($message);


                                 //noti to shop
                                 //เกิดข้อผิดพลาด อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                                 //[sku,quantity]:[$sku,$quantity]

                             }
                             else
                             {
                                 //insert jdOrder in app
                                 $waybillNumber = getWaybillNumberJd($orderNo);
                                 $jdOrder = mysqli_real_escape_string($con,json_encode($order, JSON_UNESCAPED_UNICODE));
                                 $sql = "insert into jdOrder (jdOrder,orderNo,waybillNumber) values('$jdOrder','$orderNo','$waybillNumber')";
                                 $ret = doQueryTask($con,$sql,$modifiedUser);
                                 if($ret != "")
                                 {
                                     $success = false;
                                     $message = "jd order ($orderNo) cannot insert orderNo in jdOrder, [sku,quantity]: [$sku,$quantity], error:".$ret->status;
                                     writeToLog($message);
                                     sendNotiToAdmin($message);


                                 }



                                 //update marketplace
                                 $failMarketplace = array();


                                 //update every sku that share the same pool of stock
                                 $sql = "select * from (select Sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$escapeMainSku') UNION select '$escapeMainSku' as Sku)a order by Sku";
                                 $selectedRow = getSelectedRow($sql);
                                 for($k=0; $k<sizeof($selectedRow); $k++)
                                 {
                                     $sku = $selectedRow[$k]["Sku"];
                                     $escapeSku = mysqli_real_escape_string($con,$sku);
                                     
                                     //*****map sku******
                                     $sql = "select * from mapsku where sku = '$escapeSku'";
                                     $mapSku = executeQueryArray($sql);
                                     $shopeeSku = $mapSku[0]->ShopeeSku;
                                     $lazadaSku = $mapSku[0]->LazadaSku;
                                     //*****map sku******
                                     
                                     
                                     
                                     
                                     //update product in lazada
                                     $ret = updateStockQuantityLazadaInApp($lazadaSku,$mainProductQuantity);
                                     if(!$ret)
                                     {
                                        if(!in_array("Lazada", $failMarketplace))
                                        {
                                          $failMarketplace[] = "Lazada";
                                        }
                                     }


                                      //update product in shopee
                                     $ret = updateStockQuantityShopeeInApp($shopeeSku,$mainProductQuantity);
                                     if(!$ret)
                                     {
                                        if(!in_array("Shopee", $failMarketplace))
                                        {
                                          $failMarketplace[] = "Shopee";
                                        }
                                     }


     //                                 //update product in jd
     //                                 $ret = updateStockQuantityJdInApp($sku,$mainProductQuantity);
     //                                 if(!$ret)
     //                                 {
     //                             //        mysqli_close($con);
     //                                     $failMarketplace[] = "JD";
     //                                 }
                                 }
                                 


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
    $result = json_encode(array("success"=>$success,"message"=>$message));
    echo $result;
    writeToLog($result);
    writeToLog("query commit, file: " . basename(__FILE__));
    exit();
?>
