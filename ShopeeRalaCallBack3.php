<?php
    include_once("dbConnect.php");
    setConnectionValue("RALAMUSIC");
    writeToLog("file: " . basename(__FILE__));
    
    
    $json_str = file_get_contents('php://input');
    writeToLog("shopee rala callback json: " . $json_str);
//    sendNotiToAdmin("shopee order coming!");
//    exit();
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
//    // Set autocommit to off
//    mysqli_autocommit($con,FALSE);
//    writeToLog("set auto commit to off");
    
    
    
    $json_obj = json_decode($json_str);
    if($json_obj->code == 3)
    {
       $data = $json_obj->data;
       if($data->status == "READY_TO_SHIP")
       {
           $orderSn = $data->ordersn;
           if(shopeeOrderExist($orderSn))//test
           {
               writeToLog("Shopee order exist");
               sendNotiToAdmin("Shopee order exist");

               writeToLog("end of callback");
               exit();
           }


           $orderObj = getShopeeOrder($orderSn);
           if(!$orderObj->orders)
           {
               writeToLog("cannot get shopee order");
               sendNotiToAdmin("cannot get shopee order");

               //noti to shop
               //อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn


               writeToLog("end of callback");
               exit();
           }

           $ret = updateStockToAllSkuInOrder2($orderObj);
           if(!$ret)
           {
               writeToLog("updateStockToAllSkuInOrder fail");
               sendNotiToAdmin("updateStockToAllSkuInOrder fail");

               //noti to shop
               //อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
           }


           //update main quantity
           $items = $orderObj->orders[0]->items;
           for($i=0; $i<sizeof($items); $i++)
           {
               $sku = $items[$i]->item_sku;
               $escapeSku = mysqli_real_escape_string($con,$sku);
               $quantity = $items[$i]->variation_quantity_purchased;
               
               
               $sql = "select * from mapsku where shopeesku = '$escapeSku'";
               $mapSku = executeQueryArray($sql);
               $mainSku = $mapSku[0]->Sku;
               
               
               $escapeMainSku = mysqli_real_escape_string($con,$mainSku);
               
               
               
               $sql = "select * from mainproduct where sku = '$escapeMainSku'";
               $selectedRow = getSelectedRow($sql);
               if(sizeof($selectedRow)==0)
               {
                   $message = "shopee order ($orderSn) cannot update quantity in main, sku ($mainSku) not found";
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
                       $message = "shopee order ($orderSn) cannot update quantity in main, quantity not enough [sku,quantity,mainQuantity]: [$mainSku,$quantity,$mainProductQuantity]";
                       writeToLog($message);
                       sendNotiToAdmin($message);

                       //noti to shop
                       //อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                       //จำนวนสินค้าในระบบไม่พอ [sku,quantity]:[$sku,$quantity]
                   }
                   else
                   {
                       $mainProductQuantity -= $quantity;
//                       $sql = "update mainProduct set quantity = '$mainProductQuantity' where sku = '$sku'";
                       $sql = "update mainProduct set quantity = '$mainProductQuantity' where sku in (select sku from stocksharing where stocksharinggroupID in (select stocksharinggroupID from stocksharing where sku = '$escapeMainSku')) or sku = '$escapeMainSku'";
                       $ret = doQueryTask($con,$sql,$modifiedUser);
                       if($ret != "")
                       {
                           $message = "shopee order ($orderSn) cannot update quantity in main, [sku,quantity]: [$mainSku,$quantity], error:".$ret->status;
                           writeToLog($message);
                           sendNotiToAdmin($message);


                           //noti to shop
                           //เกิดข้อผิดพลาด อัพเดทจำนวนสินค้าไม่สำเร็จ shopee order no. $orderSn
                           //[sku,quantity]:[$sku,$quantity]

                       }
                       else
                       {
                           //insert shopeeOrder in app
                           $shopeeOrder = mysqli_real_escape_string($con,json_encode($orderObj->orders[0], JSON_UNESCAPED_UNICODE));
                           $sql = "insert into shopeeOrder (shopeeOrder,orderNo) values('$shopeeOrder','$orderSn')";
                           $ret = doQueryTask($con,$sql,$modifiedUser);
                           if($ret != "")
                           {
                               $success = false;
                               $message = "shopee order ($orderSn) cannot insert orderNo in shopeeOrder, [sku,quantity]: [$sku,$quantity], error:".$ret->status;
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
                               $lazadaSku = $mapSku[0]->LazadaSku;
                               $jdSku = $mapSku[0]->JdSku;
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


//                           //update product in shopee
//                           $ret = updateStockQuantityShopeeInApp($sku,$mainProductQuantity);
//                           if(!$ret)
//                           {
//                       //        mysqli_close($con);
//                               $failMarketplace[] = "Shopee";
//                           }


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
                               for($j=1; $j<sizeof($failMarketplace); $j++)
                               {
                                   $marketplaceFail .= ", $failMarketplace[$j]";
                               }


                               $message = "shopee order ($orderSn) cannot update quantity in marketplace($marketplaceFail), [shopeeOrderNo,sku,quantity]: [$orderSn,$sku,$quantity]";
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


    writeToLog("end of callback");
    exit();
?>
