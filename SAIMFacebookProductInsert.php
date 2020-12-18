<?php
    include_once('dbConnect.php');
    
    

    
//    $sku = $_GET['sku'];
    $page = $_GET['page'];
    $perPage = $_GET['perPage'];
    $storeName = 'RALAMUSIC';
    $modifiedUser = 'bot';
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
  
    $sql = "truncate facebookproduct;";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $message = "truncate facebookproduct ไม่สำเร็จ";
        sendNotiToAdmin($message);
        $ret["message"] = $message;
        mysqli_close($con);

        echo json_encode($ret);
        exit();
    }
    
//    $sql = "select * from mainproduct where sku = '$sku'";
    $sql = "select * from (select @row:=@row+1 as row, mainproduct.* from mainproduct, (select @row := 0)r)a where row > ($page-1)*$perPage limit $perPage";
    $product = executeQueryArray($sql);
    
    for($i=0; $i<sizeof($product); $i++)
    {
        $sku = $product[$i]->Sku;
        $name = $product[$i]->Name;//name
        $name = mysqli_real_escape_string($con,$name);
    //    $name = iconv_substr($name, 0, 120,'UTF-8'); //substr($name,0,120);
        $price = floatval($product[$i]->Price);//skus[0]->price
        $stock = intval($product[$i]->Quantity);//skus[0]->quantity
        $itemSku = $product[$i]->Sku;//skus[0]->SellerSku
        $brand = $product[$i]->Brand;
        $brand = mysqli_real_escape_string($con,$brand);
        $brand = $brand == ""?"No brand":$brand;
        $video = $product[$i]->Video;
        $condition = "NEW";
        $availability = $stock>0?"in stock":"out of stock";
        
        //*****lazada data
        $sql = "select * from lazadaproducttemp where sellersku = '$sku'";
        $lazadaProductList = executeQueryArray($sql);
        $lazadaProduct = $lazadaProductList[0];
        {

            $description = $lazadaProduct->short_description?$lazadaProduct->short_description:$lazadaProduct->name;
            $description = strip_tags($description);
            
            //format description
            $string = $description;
            $pattern = '/(\t)/';
            $replacement = '- ';
            $description = preg_replace($pattern, $replacement, $string);

            
            $string = $description;
            $pattern = '/^(\r\n)/';
            $replacement = '';
            $description = preg_replace($pattern, $replacement, $string);
            
            
            $string = $description;
            $pattern = '/(\r\n)$/';
            $replacement = '';
            $description = preg_replace($pattern, $replacement, $string);
            $description = mysqli_real_escape_string($con,$description);
            $description = $description == ''?$name:$description;
            
            $salePrice = floatval($lazadaProduct->special_price);
            $salePriceEffectiveDate = "2019-12-01T0:00-23:59/2030-12-31T0:00-23:59";
            $packageWeight = floatval($lazadaProduct->package_weight)>20?20:floatval($lazadaProduct->package_weight);
            $packageLength = floatval($lazadaProduct->package_length);
            $packageWidth = floatval($lazadaProduct->package_width);
            $packageHeight = floatval($lazadaProduct->package_height);
            

        }
        //*****lazada data
        
        
        
        {
            $imageLink = "";
            $additionalImageLink = "";
            if($product[$i]->MainImage != "")
            {
                $imageLink = $product[$i]->MainImage;
            }
            if($product[$i]->Image2 != "")
            {
                $additionalImageLink = $product[$i]->Image2;
            }
            if($product[$i]->Image3 != "")
            {
                $additionalImageLink .= "," . $product[$i]->Image3;
            }
            if($product[$i]->Image4 != "")
            {
                $additionalImageLink .= "," . $product[$i]->Image4;
            }
            if($product[$i]->Image5 != "")
            {
                $additionalImageLink .= "," . $product[$i]->Image5;
            }
            if($product[$i]->Image6 != "")
            {
                $additionalImageLink .= "," . $product[$i]->Image6;
            }
            if($product[$i]->Image7 != "")
            {
                $additionalImageLink .= "," . $product[$i]->Image7;
            }
            if($product[$i]->Image8 != "")
            {
                $additionalImageLink .= "," . $product[$i]->Image8;
            }
        }

        $skuEscape = mysqli_real_escape_string($con,$sku);
        $sql = "INSERT INTO `facebookproduct`(`id`, `title`, `description`, `availability`, `condition`, `price`, `link`, `image_link`,`additional_image_link`, `brand`, `sale_price`, `sale_price_effective_date`) select '$skuEscape', '$name', '$description', '$availability', '$condition', concat('$price',' THB'),concat('http://ralamusic.com/','$skuEscape','/'), '$imageLink','$additionalImageLink','$brand', concat('$salePrice',' THB'),'$salePriceEffectiveDate'";
     
        
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $message = "เพิ่ม facebook sku in database ไม่สำเร็จ";
    //        sendNotiToAdmin($message);
            $ret["message"] = $message;
            mysqli_close($con);

            echo json_encode($ret);
            exit();
        }
    }
    
    $sql = "select * from facebookproduct";
    $facebookProduct = executeQueryArray($sql);
    for($i=0; $i<sizeof($facebookProduct); $i++)
    {
        $facebookProduct[$i] = json_decode(json_encode($facebookProduct[$i]), true);
    }
    
    
    $fp = fopen('facebookproduct-8.csv', 'w');
    $header = array_keys($facebookProduct[0]);
    fputcsv($fp, $header);
    foreach($facebookProduct as $row)
    {
         fputcsv($fp, $row);
    }
    fclose($fp);
    
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true));
    exit();
    

?>


