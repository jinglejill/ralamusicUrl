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
           if(shopeeOrderExist($orderSn))
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

           $ret = updateStockToAllSkuInOrder($orderObj);
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
               $quantity = $items[$i]->variation_quantity_purchased;
               $sql = "select * from mainproduct where sku = '$sku'";
               $selectedRow = getSelectedRow($sql);
               if(sizeof($selectedRow)==0)
               {
                   $message = "shopee order ($orderSn) cannot update quantity in main, sku ($sku) not found";
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
                       $message = "shopee order ($orderSn) cannot update quantity in main, quantity not enough [sku,quantity,mainQuantity]: [$sku,$quantity,$mainProductQuantity]";
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
                           $message = "shopee order ($orderSn) cannot update quantity in main, [sku,quantity]: [$sku,$quantity], error:".$ret->status;
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
                           $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
                           if($ret != "")
                           {
                               $success = false;
                               $message = "shopee order ($orderSn) cannot insert orderNo in shopeeOrder, [sku,quantity]: [$sku,$quantity], error:".$ret->status;
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


//                           //update product in shopee
//                           $ret = updateStockQuantityShopeeInApp($sku,$mainProductQuantity);
//                           if(!$ret)
//                           {
//                       //        mysqli_close($con);
//                               $failMarketplace[] = "Shopee";
//                           }


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
?>
