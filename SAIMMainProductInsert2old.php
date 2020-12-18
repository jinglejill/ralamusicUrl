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
        
        
        for($i=0; $i<sizeof($imageList); $i++)
        {
            $image = $imageList[$i];
            $type = $image["Type"];
            $base64 = $image["Base64"];
            if($image["Image"] != "")
            {
                $date = date("YmdGi");
                $fileName = $sku . "_" .  $date . "." . $type;
                $menuFolder = "\\$storeName\\Images\\";
                $currentFolder = getcwd();
                file_put_contents($currentFolder . $menuFolder . $fileName, base64_decode($base64));
                
                $imageUrl[$i+1] = "$appImageUrl/$storeName/Images/$fileName";
            }
        }
        
        
        $sql = "insert into MainProduct (Brand, Sku, Quantity, Price, Cost, Remark, Name,MainImage,Image2,Image3,Image4,Image5,Image6,Image7,Image8, ModifiedUser) values ('$brand','$escapeSku','$quantity','$price','$cost','$remark','$name','$imageUrl[1]','$imageUrl[2]','$imageUrl[3]','$imageUrl[4]','$imageUrl[5]','$imageUrl[6]','$imageUrl[7]','$imageUrl[8]','$modifiedUser')";
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
        //update
        for($i=0; $i<sizeof($imageList); $i++)
        {
            $image = $imageList[$i];
            $type = $image["Type"];
            $base64 = $image["Base64"];
            if($base64 != "")
            {
                $date = date("YmdGi");
                $fileName = $sku . "_" .  $date . "." . $type;
                $menuFolder = "\\$storeName\\Images\\";
                $currentFolder = getcwd();
                file_put_contents($currentFolder . $menuFolder . $fileName, base64_decode($base64));
                
                $imageUrl[$i+1] = "$appImageUrl/$storeName/Images/$fileName";
            }
            else
            {
                $imageUrl[$i+1] = $image["Image"];
            }
        }
        
                
        $sql = "update MainProduct set Brand = '$brand', Sku = '$escapeSku', Quantity = '$quantity', Price = '$price', Cost = '$cost', Remark = '$remark', Name = '$name', MainImage = '$imageUrl[1]', Image2 = '$imageUrl[2]', Image3 = '$imageUrl[3]', Image4 = '$imageUrl[4]', Image5 = '$imageUrl[5]', Image6 = '$imageUrl[6]', Image7 = '$imageUrl[7]', Image8 = '$imageUrl[8]', modifiedUser = '$modifiedUser' where sku = '$escapeSku'";
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
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>true,"product"=>$product));
    exit();
?>
