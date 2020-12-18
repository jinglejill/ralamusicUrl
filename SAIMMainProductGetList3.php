<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $page = json_decode($json_str)->page;
    $limit = json_decode($json_str)->limit;
    $searchText = json_decode($json_str)->searchText;
    $outOfStock = json_decode($json_str)->outOfStock;
    $outOfStockCondition = $outOfStock?" and quantity=0":"";
    $modifiedUser = json_decode($json_str)->modifiedUser;
    

    if(!$storeName)
    {
        $storeName = $_POST["storeName"];
        $page = $_POST["page"];
        $limit = $_POST["limit"];
        $searchText = $_POST["searchText"];
        $outOfStock = $_POST["outOfStock"];
        $outOfStockCondition = $outOfStock?"and quantity=0":"";
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
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
   
    
    
    //*****************************

    //search process
    $search = trim($searchText);
    $search = mysqli_real_escape_string($con,$search);
    if($search == '')
    {
        $sql = "select false Animating, c.* from (select @row:=@row+1 as RowNum, b.* from (select Name, Quantity, Sku, MainImage, SpecialPrice FROM `mainproduct` WHERE STATUS = 'active' $outOfStockCondition order by ModifiedDate desc, Sku)b, (select @row:=0)t)c where c.RowNum > ($page-1)*$limit limit $limit";
        $productPage = executeQueryArray($sql);
    }
    else
    {
        $sql = "select false Animating, b.* from (select @row:=@row+1 as RowNum, a.* from (SELECT Name, Quantity, Sku, MainImage, SpecialPrice FROM `mainproduct` WHERE STATUS = 'active' $outOfStockCondition and match(Sku,Name) against ('$search') order by match(Sku,Name) against ('$search') desc, modifiedDate desc, sku)a,(select @row:=0)t)b where b.RowNum > ($page-1)*$limit limit $limit";
        $productPage = executeQueryArray($sql);
        
        if(sizeof($productPage) == 0)
        {
            $sql = "select false Animating, c.* from (select @row:=@row+1 as RowNum, b.* from (select Name, Quantity, Sku, MainImage, SpecialPrice from (select Name, Quantity, Sku, MainImage, SpecialPrice, ModifiedDate FROM `mainproduct` WHERE STATUS = 'active' $outOfStockCondition and sku like '%$search%' union select Name, Quantity, Sku, MainImage, SpecialPrice, ModifiedDate FROM `mainproduct` WHERE STATUS = 'active' $outOfStockCondition and name like '%$search%')a order by ModifiedDate desc, Sku)b, (select @row:=0)t)c where c.RowNum > ($page-1)*$limit limit $limit";
            $productPage = executeQueryArray($sql);
        }
    }

    //*****************************
    

    
    //set has sku in various channel
    for($i=0; $i<sizeof($productPage); $i++)
    {
        $product = $productPage[$i];
        $sku = $product->Sku;
        $sku = mysqli_real_escape_string($con,$sku);
        $sql = "select * from mapsku where sku = '$sku'";
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
    
    
    $data = array("products"=>$productPage);
    writeToLog("mainProductGetList: " . json_encode($data) );
    echo json_encode($data);
    
    
    //-----
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    
    
    exit();
?>
