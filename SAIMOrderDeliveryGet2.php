<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $channel = json_decode($json_str)->channel;
    $orderNo = json_decode($json_str)->orderNo;
    $edit = json_decode($json_str)->edit;
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
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    if(!$edit)//add
    {
        $sql = "select * from orderDelivery where orderNo = '$orderNo'";
        $orderDeliveryList = executeQueryArray($sql);
        if(sizeof($orderDeliveryList)>0)
        {
            $groupID = $orderDeliveryList[0]->OrderDeliveryGroupID;
            $data = array("success"=>false,"message"=>"Order no. นี้มีข้อมูลแล้วใน Group #$groupID");
            echo json_encode($data);
            
            mysqli_commit($con);
            mysqli_close($con);
            exit();
        }
        
        
        if($channel == 1)//lazada
        {
            $lazadaOrder = getOrderLazada($orderNo);

            $order = array();
            $order["OrderNo"] = $orderNo;
            $order["OrderDate"] = substr($lazadaOrder->created_at,0,16);
            $order["Channel"] = 1;
            
            
            $items = array();
            for($i=0; $i<sizeof($lazadaOrder->items); $i++)
            {
                $item = array();
                $item["Sku"] = $lazadaOrder->items[$i]->sku;
                $item["Name"] = $lazadaOrder->items[$i]->name;
                $item["Quantity"] = 1;//$orderObj->orders[0]->items[$i]->variation_quantity_purchased;
                $item["Image"] = $lazadaOrder->items[$i]->product_main_image;
                
        
                $accImage = getAccImage($item["Sku"]);
                $item["AccImages"] = $accImage;
                $items[] = $item;
            }
            $order["Items"] = $items;
        }
        else if($channel == 2)//shopee
        {
            $orderSn = $orderNo;
            $orderObj = getShopeeOrder($orderSn);
            $shopeeOrder = $orderObj->orders[0];
            $order = array();
            $order["OrderNo"] = $orderNo;
            $order["OrderDate"] = $orderObj->orders[0]->create_time?date("Y-m-d H:i", $orderObj->orders[0]->create_time):null;
            $order["Channel"] = 2;
            
            
            $items = array();
            for($i=0; $i<sizeof($orderObj->orders[0]->items); $i++)
            {
                $item = array();
                $item["Sku"] = $orderObj->orders[0]->items[$i]->item_sku;
                $item["Name"] = $orderObj->orders[0]->items[$i]->item_name;
                $item["Quantity"] = $orderObj->orders[0]->items[$i]->variation_quantity_purchased;
                
                //get image from itemID
                $shopeeItem = getItemShopee($orderObj->orders[0]->items[$i]->item_id);
                $item["Image"] = $shopeeItem->images[0];
                
                
                $accImage = getAccImage($item["Sku"]);
                $item["AccImages"] = $accImage;
                $items[] = $item;
            }
            $order["Items"] = $items;
        }
        else if($channel == 3)//jd
        {
            $jdOrderNo = substr($orderNo,4);
            $jdOrder = getOrderItemJD($jdOrderNo);

            $order = array();
            $order["OrderNo"] = $jdOrderNo;
            $order["OrderDate"] = substr($jdOrder->order_time,0,16);//substr($lazadaOrder->created_at,0,16);
            $order["Channel"] = 3;
            
            
            $items = array();
            for($i=0; $i<sizeof($jdOrder->skus); $i++)
            {
                $item = array();
                $item["Sku"] = getJdSkuById($jdOrder->skus[$i]->id);
                $item["Name"] = $jdOrder->skus[$i]->name;
                $item["Quantity"] = $jdOrder->skus[$i]->num;
                $item["Image"] = getMainImageBySku($item["Sku"]);
                
                
                $accImage = getAccImage($item["Sku"]);
                $item["AccImages"] = $accImage;
                $items[] = $item;
            }
            $order["Items"] = $items;
        }
        
        $imageList = array();
        $image1 = array("Id"=>1,"Image"=>"","Base64"=>"","Type"=>"");
        $image2 = array("Id"=>2,"Image"=>"","Base64"=>"","Type"=>"");
        $image3 = array("Id"=>3,"Image"=>"","Base64"=>"","Type"=>"");
        $image4 = array("Id"=>4,"Image"=>"","Base64"=>"","Type"=>"");
        $image5 = array("Id"=>5,"Image"=>"","Base64"=>"","Type"=>"");
        $image6 = array("Id"=>6,"Image"=>"","Base64"=>"","Type"=>"");
        $image7 = array("Id"=>7,"Image"=>"","Base64"=>"","Type"=>"");
        $image8 = array("Id"=>8,"Image"=>"","Base64"=>"","Type"=>"");
        $imageList[] = $image1;
        $imageList[] = $image2;
        $imageList[] = $image3;
        $imageList[] = $image4;
        $imageList[] = $image5;
        $imageList[] = $image6;
        $imageList[] = $image7;
        $imageList[] = $image8;
        $order["Images"] = $imageList;
        
        
        $order = (object)$order;
    }
    else
    {
        $sql = "select * from OrderDelivery where orderNo = '$orderNo'";
        $orderDeliveryList = executeQueryArray($sql);
        $orderDelivery = $orderDeliveryList[0];
        
        $orderDeliveryID = $orderDelivery->OrderDeliveryID;
        $image1 = $orderDelivery->Image1;
        $image2 = $orderDelivery->Image2;
        $image3 = $orderDelivery->Image3;
        $image4 = $orderDelivery->Image4;
        $image5 = $orderDelivery->Image5;
        $image6 = $orderDelivery->Image6;
        $image7 = $orderDelivery->Image7;
        $image8 = $orderDelivery->Image8;
        
        
        $imageList = array();
        if($image1 != "")
        {
            $image = array("Id"=>1,"Image"=>$image1,"Base64"=>"","Type"=>"");
            $imageList[] = $image;
        }
        if($image2 != "")
        {
            $image = array("Id"=>2,"Image"=>$image2,"Base64"=>"","Type"=>"");
            $imageList[] = $image;
        }
        if($image3 != "")
        {
            $image = array("Id"=>3,"Image"=>$image3,"Base64"=>"","Type"=>"");
            $imageList[] = $image;
        }
        if($image4 != "")
        {
            $image = array("Id"=>4,"Image"=>$image4,"Base64"=>"","Type"=>"");
            $imageList[] = $image;
        }
        if($image5 != "")
        {
            $image = array("Id"=>5,"Image"=>$image5,"Base64"=>"","Type"=>"");
            $imageList[] = $image;
        }
        if($image6 != "")
        {
            $image = array("Id"=>6,"Image"=>$image6,"Base64"=>"","Type"=>"");
            $imageList[] = $image;
        }
        if($image7 != "")
        {
            $image = array("Id"=>7,"Image"=>$image7,"Base64"=>"","Type"=>"");
            $imageList[] = $image;
        }
        if($image8 != "")
        {
            $image = array("Id"=>8,"Image"=>$image8,"Base64"=>"","Type"=>"");
            $imageList[] = $image;
        }
        
        for($i=sizeof($imageList); $i<8; $i++)
        {
            $image = array("Id"=>$i+1,"Image"=>"","Base64"=>"","Type"=>"");
            $imageList[] = $image;
        }
        
        $orderDelivery->Images = $imageList;
        
        
        $sql = "select * from orderDeliveryItem where orderDeliveryID = '$orderDeliveryID'";
        $items = executeQueryArray($sql);
        for($i=0; $i<sizeof($items); $i++)
        {
            $sku = $items[$i]->Sku;
            $accImage = getAccImage($sku);
            $items[$i]->AccImages = $accImage;
        }
        $orderDelivery->Items = $items;
        
        unset($orderDelivery->Image1);
        unset($orderDelivery->Image2);
        unset($orderDelivery->Image3);
        unset($orderDelivery->Image4);
        unset($orderDelivery->Image5);
        unset($orderDelivery->Image6);
        unset($orderDelivery->Image7);
        unset($orderDelivery->Image8);
        
        $order = $orderDelivery;
    }
    
    
    if(sizeof($order->Items) == 0)
    {
        $data = array("success"=>false,"message"=>"Order no. not found");
    }
    else
    {
        $data = array("success"=>true,"Order"=>$order);
    }
    
    writeToLog("order: " . json_encode($data) );
    echo json_encode($data);
    
    
    //-----
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    
    
    exit();
    
    
    function getAccImage($sku)
    {
        global $con;
        
        $sku = mysqli_real_escape_string($con,$sku);
        $sql = "select AccImage1, AccImage2, AccImage3, AccImage4, AccImage5, AccImage6, AccImage7, AccImage8 from mainproduct where sku = '$sku'";
        $mainProductList = executeQueryArray($sql);
        $mainProduct = $mainProductList[0];
        
        $accImage1 = $mainProduct->AccImage1;
        $accImage2 = $mainProduct->AccImage2;
        $accImage3 = $mainProduct->AccImage3;
        $accImage4 = $mainProduct->AccImage4;
        $accImage5 = $mainProduct->AccImage5;
        $accImage6 = $mainProduct->AccImage6;
        $accImage7 = $mainProduct->AccImage7;
        $accImage8 = $mainProduct->AccImage8;
        
        
        $accImage = array();
        if($accImage1 != "")
        {
            $accImage[] = (object)array("Id"=>1,"ImageUrl"=>$mainProduct->AccImage1);
        }
        if($accImage2 != "")
        {
            $accImage[] = (object)array("Id"=>2,"ImageUrl"=>$mainProduct->AccImage2);
        }
        if($accImage3 != "")
        {
            $accImage[] = (object)array("Id"=>3,"ImageUrl"=>$mainProduct->AccImage3);
        }
        if($accImage4 != "")
        {
            $accImage[] = (object)array("Id"=>4,"ImageUrl"=>$mainProduct->AccImage4);
        }
        if($accImage5 != "")
        {
            $accImage[] = (object)array("Id"=>5,"ImageUrl"=>$mainProduct->AccImage5);
        }
        if($accImage6 != "")
        {
            $accImage[] = (object)array("Id"=>6,"ImageUrl"=>$mainProduct->AccImage6);
        }
        if($accImage7 != "")
        {
            $accImage[] = (object)array("Id"=>7,"ImageUrl"=>$mainProduct->AccImage7);
        }
        if($accImage8 != "")
        {
            $accImage[] = (object)array("Id"=>8,"ImageUrl"=>$mainProduct->AccImage8);
        }
       
        return $accImage;
    }
?>
