<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $orderDeliveryGroupID = json_decode($json_str)->orderDeliveryGroupID;
    $searchText = json_decode($json_str)->searchText;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    $page = json_decode($json_str)->page;;
    $limit = json_decode($json_str)->limit;;
    

    
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
    
    
    //search process
    $search = trim($searchText);
    $search = mysqli_real_escape_string($con,$search);
    if($search == '')
    {
        $sql = "select c.* from (select @row:=@row+1 as RowNum, b.* from (select OrderDeliveryID, Channel, OrderNo, OrderDate, `Image1`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8` from orderDelivery where orderDeliveryGroupID = '$orderDeliveryGroupID' order by orderDelivery.ModifiedDate desc)b, (select @row:=0)t)c where c.RowNum > ($page-1)*$limit limit $limit";
        $orderDeliveryList = executeQueryArray($sql);
    }
    else
    {
        $sql = "select b.* from (select @row:=@row+1 as RowNum, a.* from (select orderDelivery.OrderDeliveryID, Channel, OrderNo, OrderDate, `Image1`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8` from orderDelivery left join orderDeliveryItem on orderDelivery.orderDeliveryID = orderDeliveryItem.orderDeliveryID where orderDeliveryGroupID = '$orderDeliveryGroupID' and match(Sku,Name) against ('$search') order by match(Sku,Name) against ('$search') desc, orderDelivery.modifiedDate desc, sku)a,(select @row:=0)t)b where b.RowNum > ($page-1)*$limit limit $limit";
        $orderDeliveryList = executeQueryArray($sql);
        
        if(sizeof($orderDeliveryList) == 0)
        {
            $sql = "select c.* from (select @row:=@row+1 as RowNum, b.* from (select a.* from (select OrderDelivery.OrderDeliveryID, Channel, OrderNo, OrderDate, `Image1`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8`, orderDelivery.ModifiedDate, Sku from orderDelivery left join orderDeliveryItem on orderDelivery.orderDeliveryID = orderDeliveryItem.orderDeliveryID where orderDeliveryGroupID = '$orderDeliveryGroupID' and sku like '%$search%' union select OrderDelivery.OrderDeliveryID, Channel, OrderNo, OrderDate, `Image1`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8`, orderDelivery.ModifiedDate, Sku from orderDelivery left join orderDeliveryItem on orderDelivery.orderDeliveryID = orderDeliveryItem.orderDeliveryID where orderDeliveryGroupID = '$orderDeliveryGroupID' and name like '%$search%')a order by ModifiedDate desc, Sku)b, (select @row:=0)t)c where c.RowNum > ($page-1)*$limit limit $limit";
            $orderDeliveryList = executeQueryArray($sql);
        }
    }
    
    
    $orderDeliveryList = executeQueryArray($sql);
    
    
    for($i=0; $i<sizeof($orderDeliveryList); $i++)
    {
        $orderDelivery = $orderDeliveryList[$i];
        $orderDeliveryID = $orderDelivery->OrderDeliveryID;
        $orderNo = $orderDelivery->OrderNo;
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
        
        $orderDelivery->Images = $imageList;
        unset($orderDelivery->Image1);
        unset($orderDelivery->Image2);
        unset($orderDelivery->Image3);
        unset($orderDelivery->Image4);
        unset($orderDelivery->Image5);
        unset($orderDelivery->Image6);
        unset($orderDelivery->Image7);
        unset($orderDelivery->Image8);
        
        
        
        $sql = "select '$orderNo' as OrderNo, orderDeliveryItem.* from orderDeliveryItem where orderDeliveryID = '$orderDeliveryID'";
        $items = executeQueryArray($sql);
        $orderDelivery->Items = $items;
        
        
        for($j=0; $j<sizeof($items); $j++)
        {
            $sku = $items[$j]->Sku;
            $accImage = getAccImage($sku);
            $items[$j]->AccImages = $accImage;
        }
    }
    
   
    
    $data = array("OrderDeliveryList"=>$orderDeliveryList);
    writeToLog("OrderDeliveryList: " . json_encode($data) );
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
