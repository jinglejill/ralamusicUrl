<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $sku = json_decode($json_str)->sku;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    
    if(!$storeName)
    {
        $storeName = $_POST["storeName"];
        $sku = $_POST["sku"];
        $modifiedUser = $_POST["modifiedUser"];
    }
    
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    if(!$storeName)
    {
        printAllPost();
    }
    else
    {
        writeToLog("post json: " . $json_str);
    }
    writeToLog("userAgent: ".$_SERVER['HTTP_USER_AGENT']);
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    $escapeSku = mysqli_real_escape_string($con,$sku);
    $sql = "select Brand, Sku, Quantity, Price, Cost, Remark, Name, MainImage, Image2, Image3, Image4, Image5, Image6, Image7, Image8, AccImage1, AccImage2, AccImage3, AccImage4, AccImage5, AccImage6, AccImage7, AccImage8 from MainProduct where sku = '$escapeSku'";
    $products = executeQueryArray($sql);
    $product = $products[0];
    
    
    $imageList = array();
    $image1 = array("Id"=>1,"Image"=>$product->MainImage,"Base64"=>"","Type"=>"");
    $image2 = array("Id"=>2,"Image"=>$product->Image2,"Base64"=>"","Type"=>"");    
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
    unset($product->MainImage);
    unset($product->Image2);
    unset($product->Image3);
    unset($product->Image4);
    unset($product->Image5);
    unset($product->Image6);
    unset($product->Image7);
    unset($product->Image8);
    
    
    //accImage
    $accImageList = array();
    $accImage1 = array("Id"=>1,"Image"=>$product->AccImage1,"Base64"=>"","Type"=>"");
    $accImage2 = array("Id"=>2,"Image"=>$product->AccImage2,"Base64"=>"","Type"=>"");
    $accImage3 = array("Id"=>3,"Image"=>$product->AccImage3,"Base64"=>"","Type"=>"");
    $accImage4 = array("Id"=>4,"Image"=>$product->AccImage4,"Base64"=>"","Type"=>"");
    $accImage5 = array("Id"=>5,"Image"=>$product->AccImage5,"Base64"=>"","Type"=>"");
    $accImage6 = array("Id"=>6,"Image"=>$product->AccImage6,"Base64"=>"","Type"=>"");
    $accImage7 = array("Id"=>7,"Image"=>$product->AccImage7,"Base64"=>"","Type"=>"");
    $accImage8 = array("Id"=>8,"Image"=>$product->AccImage8,"Base64"=>"","Type"=>"");
    $accImageList[] = $accImage1;
    $accImageList[] = $accImage2;
    $accImageList[] = $accImage3;
    $accImageList[] = $accImage4;
    $accImageList[] = $accImage5;
    $accImageList[] = $accImage6;
    $accImageList[] = $accImage7;
    $accImageList[] = $accImage8;
    
    $product->AccImage = $accImageList;
    unset($product->AccImage1);
    unset($product->AccImage2);
    unset($product->AccImage3);
    unset($product->AccImage4);
    unset($product->AccImage5);
    unset($product->AccImage6);
    unset($product->AccImage7);
    unset($product->AccImage8);
    
    {
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
    
    
    //stockSharingList
    $sql = "select stocksharing.Sku,mainproduct.MainImage from stocksharing left join mainproduct on stocksharing.sku = mainproduct.Sku where StockSharingGroupID in (SELECT StockSharingGroupID FROM `stocksharing` WHERE sku = '$escapeSku') and stocksharing.Sku != '$escapeSku' order by Sku";
    $stockSharingList = executeQueryArray($sql);
    $product->StockSharingList = $stockSharingList;
    
    
    
    //mapSku
    $sql = "select * from mapsku where sku = '$escapeSku'";
    $mapSkuList = executeQueryArray($sql);
    $product->MapSku = $mapSkuList[0];
    
    
    $result = array("product"=>$product, "success"=>true);
    writeToLog("mainProductDetailGet result: ".json_encode($result));
    echo json_encode($result);
    
    
    exit();
?>
