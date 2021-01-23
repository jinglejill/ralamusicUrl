<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str,true)["storeName"];
    $item = json_decode($json_str,true)["item"];
    $insert = json_decode($json_str,true)["insert"];
    $modifiedUser = json_decode($json_str,true)["modifiedUser"];
 
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    
    
    
    
    $brand = mysqli_real_escape_string($con,$item["Brand"]);
    $sku = mysqli_real_escape_string($con,$item["Sku"]);
    $quantity = $item["Quantity"];
    $price = $item["Price"];
    $cost = $item["Cost"];
    $remark = mysqli_real_escape_string($con,$item["Remark"]);
    $name = mysqli_real_escape_string($con,$item["Name"]);
    $imageList = $item["Image"];
    $accImageList = $item["AccImage"];
    
    $lazadaSku = $item["MapSku"]["LazadaSku"];
    $shopeeSku = $item["MapSku"]["ShopeeSku"];
    $jdSku = $item["MapSku"]["JdSku"];
    $webSku = $item["MapSku"]["WebSku"];
    
    
    $escapeSku = mysqli_real_escape_string($con,$sku);
    $escapeLazadaSku = mysqli_real_escape_string($con,$lazadaSku);
    $escapeShopeeSku = mysqli_real_escape_string($con,$shopeeSku);
    $escapeJdSku = mysqli_real_escape_string($con,$jdSku);
    $escapeWebSku = mysqli_real_escape_string($con,$webSku);
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    if($brand == "")
    {
        mysqli_close($con);
        
        echo json_encode(array("success"=>false,"message"=>"กรุณาใส่ชื่อแบรนด์"));
        exit();
    }
    
    if($sku == "")
    {
        mysqli_close($con);
        
        echo json_encode(array("success"=>false,"message"=>"กรุณาใส่ Sku"));
        exit();
    }
    
    if($insert)
    {
        //insert
        $sql = "select * from MainProduct where sku = '$escapeSku'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)>0)
        {
            mysqli_close($con);
            
            echo json_encode(array("success"=>false,"message"=>"ชื่อ Sku ซ้ำ"));
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
                $fileName = $sku . "_" . $date . "_" . $i . "." . $type;
                $menuFolder = "\\$storeName\\Images\\";
                $currentFolder = getcwd();
                file_put_contents($currentFolder . $menuFolder . $fileName, base64_decode($base64));
                
                $imageUrl[$i+1] = "$appImageUrl/$storeName/Images/$fileName";
                resizeImage($imageUrl[$i+1]);
            }
        }
        
        //accImage
        $accImageUrl = array();
        for($i=0; $i<sizeof($accImageList); $i++)
        {
            $image = $accImageList[$i];
            $type = $image["Type"];
            $base64 = $image["Base64"];
            if($image["Image"] != "")
            {
                $date = date("YmdGi");
                $fileName = "acc_".$sku . "_" . $date . "_" . $i . "." . $type;
                $menuFolder = "\\$storeName\\Images\\";
                $currentFolder = getcwd();
                file_put_contents($currentFolder . $menuFolder . $fileName, base64_decode($base64));
                
                $accImageUrl[$i+1] = "$appImageUrl/$storeName/Images/$fileName";
                resizeImage($accImageUrl[$i+1]);
            }
        }
        
        
        $sql = "insert into MainProduct (Brand, Sku, Quantity, Price, Cost, Remark, Name,MainImage,Image2,Image3,Image4,Image5,Image6,Image7,Image8,AccImage1,AccImage2,AccImage3,AccImage4,AccImage5,AccImage6,AccImage7,AccImage8, ModifiedUser) values ('$brand','$escapeSku','$quantity','$price','$cost','$remark','$name','$imageUrl[1]','$imageUrl[2]','$imageUrl[3]','$imageUrl[4]','$imageUrl[5]','$imageUrl[6]','$imageUrl[7]','$imageUrl[8]','$accImageUrl[1]','$accImageUrl[2]','$accImageUrl[3]','$accImageUrl[4]','$accImageUrl[5]','$accImageUrl[6]','$accImageUrl[7]','$accImageUrl[8]','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $ret["message"] = "เพิ่มสินค้าไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
        
        
        $sql = "insert into mapSku (Sku, LazadaSku, ShopeeSku, JdSku, WebSku, ModifiedUser) values ('$escapeSku','$escapeSku','$escapeSku','$escapeSku','$escapeSku','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $ret["message"] = "เพิ่มสินค้าไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
    }
    else
    {
        $sql = "select * from mainProduct where sku = '$escapeSku'";
        $mainProductList = executeQueryArray($sql);
        $mainProduct = $mainProductList[0];
        $imageOld = array();
        $imageOld[] = $mainProduct->MainImage;
        $imageOld[] = $mainProduct->Image2;
        $imageOld[] = $mainProduct->Image3;
        $imageOld[] = $mainProduct->Image4;
        $imageOld[] = $mainProduct->Image5;
        $imageOld[] = $mainProduct->Image6;
        $imageOld[] = $mainProduct->Image7;
        $imageOld[] = $mainProduct->Image8;
        
        //accImage old
        $accImageOld = array();
        $accImageOld[] = $mainProduct->AccImage1;
        $accImageOld[] = $mainProduct->AccImage2;
        $accImageOld[] = $mainProduct->AccImage3;
        $accImageOld[] = $mainProduct->AccImage4;
        $accImageOld[] = $mainProduct->AccImage5;
        $accImageOld[] = $mainProduct->AccImage6;
        $accImageOld[] = $mainProduct->AccImage7;
        $accImageOld[] = $mainProduct->AccImage8;
        
        
        //update
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
                $fileName = $sku . "_" . $date . "_" . $i . "." . $type;                
                $menuFolder = "\\$storeName\\Images\\";
                $currentFolder = getcwd();
                file_put_contents($currentFolder . $menuFolder . $fileName, base64_decode($base64));
                
                $imageUrl[$i+1] = "$appImageUrl/$storeName/Images/$fileName";
                resizeImage($imageUrl[$i+1]);
                
                if($imageOld[$i] != "")
                {
                    $deleteImageList[] = $imageOld[$i];
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
        writeToLog("deleteImageList:".json_encode($deleteImageList));
        writeToLog("imageUrl:".json_encode($imageUrl));
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
                writeToLog("deleteImageList:" . $deleteImageList[$i]);
                $source = str_replace($appImageUrl,'.',$deleteImageList[$i]);
                rename($source,str_replace("/Images/","/Deleted/",$source));
            }
        }
        
        
        //update accImage
        $deleteAccImageList = array();
        $accImageUrl = array();
        for($i=0; $i<sizeof($accImageList); $i++)
        {
            $image = $accImageList[$i];
            $type = $image["Type"];
            $base64 = $image["Base64"];
            if($base64 != "")
            {
                $date = date("YmdGi");
                $fileName = "acc_".$sku . "_" . $date . "_" . $i . "." . $type;
                $menuFolder = "\\$storeName\\Images\\";
                $currentFolder = getcwd();
                file_put_contents($currentFolder . $menuFolder . $fileName, base64_decode($base64));
                
                $accImageUrl[$i+1] = "$appImageUrl/$storeName/Images/$fileName";
                resizeImage($accImageUrl[$i+1]);
                
                if($accImageOld[$i] != "")
                {
                    $deleteAccImageList[] = $accImageOld[$i];
                }
            }
            else
            {
                if($accImageOld[$i] != $image["Image"])
                {
                    $deleteAccImageList[] = $accImageOld[$i];
                }
                $accImageUrl[$i+1] = $image["Image"];
            }
        }
        writeToLog("deleteAccImageList:".json_encode($deleteAccImageList));
        writeToLog("accImageUrl:".json_encode($accImageUrl));
        for($i=0; $i<sizeof($deleteAccImageList); $i++)
        {
            $found = false;
            for($j=1; $j<sizeof($accImageUrl); $j++)
            {
                if($deleteAccImageList[$i] == $accImageUrl[$j])
                {
                    $found = true;
                    break;
                }
            }
            if(!$found)
            {
                writeToLog("deleteAccImageList:" . $deleteAccImageList[$i]);
                $source = str_replace($appImageUrl,'.',$deleteAccImageList[$i]);
                rename($source,str_replace("/Images/","/Deleted/",$source));
            }
        }
        
        $sql = "update MainProduct set Brand = '$brand', Sku = '$escapeSku', Quantity = '$quantity', Price = '$price', Cost = '$cost', Remark = '$remark', Name = '$name', MainImage = '$imageUrl[1]', Image2 = '$imageUrl[2]', Image3 = '$imageUrl[3]', Image4 = '$imageUrl[4]', Image5 = '$imageUrl[5]', Image6 = '$imageUrl[6]', Image7 = '$imageUrl[7]', Image8 = '$imageUrl[8]', AccImage1 = '$accImageUrl[1]', AccImage2 = '$accImageUrl[2]', AccImage3 = '$accImageUrl[3]', AccImage4 = '$accImageUrl[4]', AccImage5 = '$accImageUrl[5]', AccImage6 = '$accImageUrl[6]', AccImage7 = '$accImageUrl[7]', AccImage8 = '$accImageUrl[8]', modifiedUser = '$modifiedUser' where sku = '$escapeSku'";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $ret["message"] = "แก้ไขสินค้าไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
        
        $sql = "update mapsku set ShopeeSku = '$escapeShopeeSku', JdSku = '$escapeJdSku', WebSku = '$escapeWebSku', modifiedUser = '$modifiedUser' where sku = '$escapeSku'";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $ret["message"] = "แก้ไขสินค้าไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
        
        
        //check shopee sku
        $sql = "select Sku from shopeeProduct where sku = '$escapeShopeeSku'";
        $shopeeProductList = executeQueryArray($sql);
        if(sizeof($shopeeProductList) == 0)
        {
            //check shopee api
            $variations = getAllVariationsShopee($shopeeSku);
            if(sizeof($variations) > 0)
            {
                $itemID = $variations[0]["item_id"];
                $sql = "insert into shopeeProduct (Sku, ItemID) values('$escapeShopeeSku','$itemID')";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $ret["message"] = "แก้ไขสินค้าไม่สำเร็จ";
                    mysqli_close($con);
                    
                    echo json_encode($ret);
                    exit();
                }
            }
        }
        
        
        
        
        {
            $product = (object)array();
            $sql = "select * from mapsku where sku = '$escapeSku'";
            $mapSku = executeQueryArray($sql);
            $lazadaSku = $mapSku[0]->LazadaSku;
            $shopeeSku = $mapSku[0]->ShopeeSku;
            $jdSku = $mapSku[0]->JdSku;
            $webSku = $mapSku[0]->WebSku;
            
            
            
            //hasLazadaProduct
            $hasProduct = hasLazadaProductInApp($lazadaSku);
            $product->LazadaExist = $hasProduct?1:0;
            
            
            //hasShopeeProduct
            $hasProduct = hasShopeeProductInApp($shopeeSku);
            $product->ShopeeExist = $hasProduct?1:0;


            //hasJdProduct
            $hasProduct = hasJdProductInApp($jdSku);
            $product->JdExist = $hasProduct?1:0;
            
            
            //hasWebProduct
            $hasProduct = hasWebProduct($webSku);
            $product->WebExist = $hasProduct?1:0;
        }
    }
    
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog(json_encode(array("success"=>true,"product"=>$product)));//test
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true,"product"=>$product));
    exit();
?>
