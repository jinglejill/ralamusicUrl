<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str,true)["storeName"];
    $channel = json_decode($json_str,true)["channel"];
    $order = json_decode($json_str,true)["order"];
    $orderDeliveryGroupID = json_decode($json_str,true)["orderDeliveryGroupID"];
    $edit = json_decode($json_str,true)["edit"];
    $modifiedUser = json_decode($json_str,true)["modifiedUser"];
    
    

    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    writeToLog("userAgent: ".$_SERVER['HTTP_USER_AGENT']);

    
    
    $orderNo = $order["OrderNo"];
    $orderDate = $order["OrderDate"];
    $imageList = $order["Images"];
    $items = $order["Items"];
    
    
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
        $sql = "select * from orderDeliveryGroup where orderDeliveryGroupID = '$orderDeliveryGroupID'";
        $orderDeliveryGroupList = executeQueryArray($sql);
        if(sizeof($orderDeliveryGroupList)==0)
        {
            $ret = array();
            $ret["success"] = false;
            $ret["message"] = "Group นี้ ถูกลบไปแล้ว\nกรุณาสร้าง group ขึ้นใหม่ก่อน แล้วจึงเพิ่มรายการ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
        
        
        $sql = "select * from orderDelivery where orderNo = '$orderNo'";
        $orderDeliveryList = executeQueryArray($sql);
        if(sizeof($orderDeliveryList)>0)
        {
            $groupID = $orderDeliveryList[0]->OrderDeliveryGroupID;
            $ret = array();
            $ret["success"] = false;
            $ret["message"] = "Order no. นี้มีข้อมูลแล้วใน Group #$groupID";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
        
        $imageUrl = array();
        for($i=0; $i<sizeof($imageList); $i++)
        {
            $image = $imageList[$i];
            $type = $image["Type"];
            $base64 = $image["Base64"];
            if($image["Image"] != "")
            {
                $date = date("YmdGi");
                $fileName = $orderNo . "_" .  $date . "_" .  $i . "." . $type;
                $menuFolder = "\\$storeName\\Images\\";
                $currentFolder = getcwd();
                file_put_contents($currentFolder . $menuFolder . $fileName, base64_decode($base64));
                
                $imageUrl[$i+1] = "$appImageUrl/$storeName/Images/$fileName";
                resizeImage($imageUrl[$i+1]);
            }
        }
        
        
        
        $sql = "insert into OrderDelivery (OrderDeliveryGroupID,Channel,OrderNo,OrderDate, Image1,Image2,Image3,Image4,Image5,Image6,Image7,Image8, ModifiedUser) values ('$orderDeliveryGroupID','$channel','$orderNo','$orderDate','$imageUrl[1]','$imageUrl[2]','$imageUrl[3]','$imageUrl[4]','$imageUrl[5]','$imageUrl[6]','$imageUrl[7]','$imageUrl[8]','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $ret["message"] = "เพิ่ม order delivery recheck ไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
        $orderDeliveryID  = mysqli_insert_id($con);
        
        
        for($i=0; $i<sizeof($items); $i++)
        {
            $sku = $items[$i]["Sku"];
            $name = $items[$i]["Name"];
            $quantity = $items[$i]["Quantity"];
            $image = $items[$i]["Image"];
            $escapeSku = mysqli_real_escape_string($con,$sku);
            $escapeName = mysqli_real_escape_string($con,$name);
            $sql = "insert into orderDeliveryItem (OrderDeliveryID, Sku, Name, Quantity, Image, ModifiedUser) values ('$orderDeliveryID','$escapeSku','$escapeName','$quantity','$image','$modifiedUser')";
            $ret = doQueryTask($con,$sql,$modifiedUser);
            if($ret != "")
            {
                $ret["message"] = "เพิ่ม order delivery recheck ไม่สำเร็จ";
                mysqli_close($con);
                
                echo json_encode($ret);
                exit();
            }
        }
    }
    else
    {
        $sql = "select * from orderDelivery where orderNo = '$orderNo'";
        $orderDeliveryList = executeQueryArray($sql);
        if(sizeof($orderDeliveryList) == 0)
        {
            $ret = array();
            $ret["success"] = false;
            $ret["message"] = "ไม่พบ Order no. นี้";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
        $orderDelivery = $orderDeliveryList[0];
        $imageOld = array();
        $imageOld[] = $orderDelivery->Image1;
        $imageOld[] = $orderDelivery->Image2;
        $imageOld[] = $orderDelivery->Image3;
        $imageOld[] = $orderDelivery->Image4;
        $imageOld[] = $orderDelivery->Image5;
        $imageOld[] = $orderDelivery->Image6;
        $imageOld[] = $orderDelivery->Image7;
        $imageOld[] = $orderDelivery->Image8;
        
        
        $deleteImageList = array();
        $imageUrl = array();
        for($i=0; $i<sizeof($imageList); $i++)
        {
            $image = $imageList[$i];
            $type = $image["Type"];
            $base64 = $image["Base64"];
            if($base64 != "")
            {
                $date = date("YmdGi");
                $fileName = $orderNo . "_" .  $date . "_" .  $i . "." . $type;
                $menuFolder = "\\$storeName\\Images\\";
                $currentFolder = getcwd();
                file_put_contents($currentFolder . $menuFolder . $fileName, base64_decode($base64));
                
                $imageUrl[$i+1] = "$appImageUrl/$storeName/Images/$fileName";
                resizeImage($imageUrl[$i+1]);
                if($imageOld[$i] != "")
                {
                    $deleteImageList[] = $imageOld[$i];
//                    $source = str_replace($appImageUrl,'.',$imageOld[$i]);
//                    rename($source,str_replace("/Images/","/Deleted/",$source));
                }
            }
            else
            {
                if($imageOld[$i] != $image["Image"])
                {
                    $deleteImageList[] = $imageOld[$i];
                }
                $imageUrl[$i+1] = $image["Image"];
            }
        }
        for($i=0; $i<sizeof($deleteImageList); $i++)
        {
            $found = false;
            for($j=1; $j<sizeof($imageUrl); $j++)
            {
                if($deleteImageList[$i] == $imageUrl[$j])
                {
                    $found = true;
                    break;
                }
            }
            if(!$found)
            {
                $source = str_replace($appImageUrl,'.',$deleteImageList[$i]);
                rename($source,str_replace("/Images/","/Deleted/",$source));
            }
        }
        
        $sql = "update OrderDelivery set Image1 = '$imageUrl[1]',Image2 = '$imageUrl[2]',Image3 = '$imageUrl[3]',Image4 = '$imageUrl[4]',Image5 = '$imageUrl[5]',Image6 = '$imageUrl[6]',Image7 = '$imageUrl[7]',Image8 = '$imageUrl[8]', ModifiedUser='$modifiedUser' where orderNo = '$orderNo'";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $ret["message"] = "แก้ไข order delivery recheck ไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
    }
    
    $sql = "select * from orderDelivery where orderNo = '$orderNo'";
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
    
    $orderDelivery->Images = $imageList;
    
    
    $sql = "select * from orderDeliveryItem where orderDeliveryID = '$orderDeliveryID'";
    $items = executeQueryArray($sql);
    $orderDelivery->Items = $items;
    
    unset($orderDelivery->Image1);
    unset($orderDelivery->Image2);
    unset($orderDelivery->Image3);
    unset($orderDelivery->Image4);
    unset($orderDelivery->Image5);
    unset($orderDelivery->Image6);
    unset($orderDelivery->Image7);
    unset($orderDelivery->Image8);
    
    
    
    //-----
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    
    echo json_encode(array("success"=>true,"order"=>$orderDelivery));
    exit();
?>
