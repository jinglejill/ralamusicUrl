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
        $outOfStockCondition = $outOfStock?" and quantity=0":"";
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
//    printAllGet();
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
   
    
    
    //*****************************
    $sql = "select 0 as PressStatusView, Name, Sku, MainImage, ModifiedDate from mainproduct where status = 'active' $outOfStockCondition order by modifiedDate desc, sku";
    $allProducts = executeQueryArray($sql);
    
    
    //search process
    $search = trim($searchText);
    if($search != "")
    {
        $search = str_replace("-"," ",$search);
        $searchArray = explode(" ",$search);
        $searchArray[] = trim($searchText);
        $productFoundList = array();
        for($i=0; $i<sizeof($allProducts); $i++)
        {
            $product = $allProducts[$i];
            $product->Found = 0;
            $productName = $product->Name;
            $sku = $product->Sku;
            for($k=0; $k<sizeof($searchArray); $k++)
            {
                $searchItem = $searchArray[$k];
                if($searchItem == "")
                {
                    continue;
                }
                if(stripos($productName, $searchItem) !== false)
                {
                    $product->Found = $product->Found?$product->Found+1:1;
                }
                if(stripos($sku, $searchItem) !== false)
                {
                    $product->Found = $product->Found?$product->Found+1:1;
                }
                if($sku == $searchItem)
                {
                    $product->Found = $product->Found?$product->Found+10:1;
                }
            }
            if($product->Found > 0)
            {
                $productFoundList[] = $product;
            }
        }
        
        
        //sort by found, sku
        usort($productFoundList, function($a, $b) {
            $retval = $b->Found <=> $a->Found;
            if ($retval == 0)
            {
                $retval = $b->ModifiedDate <=> $a->ModifiedDate;
                if($retval == 0)
                {
                    return $a->Sku <=> $b->Sku;
                }
            }
            return $retval;
        });
    }
    else
    {
        $productFoundList = $allProducts;
    }
//    writeToLog("product found list:".json_encode($productFoundList, JSON_UNESCAPED_UNICODE ));
    
    
    //return product for page
    $productPage = array();
    $offset = ($page-1)*$limit;
    for($i=$offset; $i<$page*$limit && $i<sizeof($productFoundList); $i++)
    {
        $productPage[] = $productFoundList[$i];
    }
//    writeToLog("product page list:".json_encode($productPage, JSON_UNESCAPED_UNICODE));
    //*****************************
    

    
//    //set has sku in various channel
//    for($i=0; $i<sizeof($productPage); $i++)
//    {
//        $product = $productPage[$i];
//        $sku = $product->Sku;
//
//        //hasLazadaProduct
//        $hasProduct = hasLazadaProductInApp($sku);
//        $product->LazadaExist = $hasProduct?1:0;
//
//
//        //hasShopeeProduct
//        $hasProduct = hasShopeeProductInApp($sku);
//        $product->ShopeeExist = $hasProduct?1:0;
//
//
//        //hasJdProduct
//        $hasProduct = hasJdProductInApp($sku);
//        $product->JdExist = $hasProduct?1:0;
//
//
//        //hasWebProduct
//        $hasProduct = hasWebProduct($sku);
//        $product->WebExist = $hasProduct?1:0;
//    }
    
    //set stockSharing for product
    for($i=0; $i<sizeof($productPage); $i++)
    {
        $product = $productPage[$i];
        $product->StockSharing = 0;
        $sku = $product->Sku;
        $sql = "select * from stockSharing where sku = '$sku'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)>0)
        {
            $product->StockSharing = 2;
        }
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
