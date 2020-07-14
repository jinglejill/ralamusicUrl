<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $sku = json_decode($json_str)->sku;
 
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $data["modifiedUser"]);
    writeToLog("post json: " . $json_str);
    writeToLog("userAgent: ".$_SERVER['HTTP_USER_AGENT']);
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    $sql = "select false AnimatingLazada, false AnimatingShopee, false AnimatingJd, Brand, Sku, Quantity, Price, Cost, Remark, Name, MainImage, Image2, Image3, Image4, Image5, Image6, Image7, Image8 from MainProduct where sku = '$sku'";
    $products = executeQueryArray($sql);
    $product = $products[0];
    
    $imageList = array();
    $image1 = array("Id"=>1,"Image"=>$product->MainImage,"Base64"=>"","Type"=>"");
    $image28 = array("Id"=>2,"Image"=>$product->Image2,"Base64"=>"","Type"=>"");
    $image3 = array("Id"=>3,"Image"=>$product->Image3,"Base64"=>"","Type"=>"");
    $image4 = array("Id"=>4,"Image"=>$product->Image4,"Base64"=>"","Type"=>"");
    $image5 = array("Id"=>5,"Image"=>$product->Image5,"Base64"=>"","Type"=>"");
    $image6 = array("Id"=>6,"Image"=>$product->Image6,"Base64"=>"","Type"=>"");
    $image7 = array("Id"=>7,"Image"=>$product->Image7,"Base64"=>"","Type"=>"");
    $image8 = array("Id"=>8,"Image"=>$product->Image8,"Base64"=>"","Type"=>"");
    $imageList[] = $image1;
    $imageList[] = $image2;
    $imageList[] = $image3;
    $imageList[] = $image4;
    $imageList[] = $image5;
    $imageList[] = $image6;
    $imageList[] = $image7;
    $imageList[] = $image8;
    
    $product->Image = $imageList;
    
    
    
//    for($i=0; $i<sizeof($productPage); $i++)
    {
//        $product = $productPage[$i];
        $sku = $product->Sku;

        //hasLazadaProduct
//        $hasProduct = hasLazadaProduct($sku);
        $hasProduct = hasLazadaProductInApp($sku);
        $product->LazadaExist = $hasProduct?1:0;
        
        
        //hasShopeeProduct
//        $hasProduct = hasShopeeProductOneTimeFeed($sku,$variations);
//        $hasProduct = hasShopeeProduct($sku);
        $hasProduct = hasShopeeProductInApp($sku);
        $product->ShopeeExist = $hasProduct?1:0;


        //hasJdProduct
//        $hasProductJd = hasJdProduct($sku);
        $hasProduct = hasJdProductInApp($sku);
        $product->JdExist = $hasProductJd?1:0;
        
    }
    
    
    echo json_encode(array("product"=>$product, "success"=>true));
    
    
    exit();
?>
