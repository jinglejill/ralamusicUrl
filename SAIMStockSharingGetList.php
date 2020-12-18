<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $page = json_decode($json_str)->page;
    $limit = json_decode($json_str)->limit;
    $searchText = json_decode($json_str)->searchText;
    $skuSelected = json_decode($json_str)->sku;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    
    
    if(!$storeName)
    {
        $storeName = $_POST["storeName"];
        $page = $_POST["page"];
        $limit = $_POST["limit"];
        $searchText = $_POST["searchText"];
        $skuSelected = $_POST["sku"];
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
    printAllGet();
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
   
    
    
    //*****************************
    $sql = "select Sku, Name, MainImage, ModifiedDate from mainproduct where status = 'active'";
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

    $sql = "select stocksharing.Sku,mainproduct.MainImage from stocksharing left join mainproduct on stocksharing.sku = mainproduct.Sku where StockSharingGroupID in (SELECT StockSharingGroupID FROM `stocksharing` WHERE sku = '$skuSelected')";
    $stockSharingList = executeQueryArray($sql);
        
    for($i=0; $i<sizeof($productPage); $i++)
    {
        $eachSku = $productPage[$i]->Sku;
        $productPage[$i]->StockSharing = 0;//0=white
        for($j=0; $j<sizeof($stockSharingList); $j++)
        {
            $stockSharingSku = $stockSharingList[$j]->Sku;
            if($stockSharingSku == $eachSku)
            {
                $productPage[$i]->StockSharing = 1;//1=green
                break;
            }
        }
        
        if($productPage[$i]->StockSharing == 0)
        {
            $sql = "select * from stockSharing where sku = '$eachSku'";
            $selectedRow = getSelectedRow($sql);
            $productPage[$i]->StockSharing = sizeof($selectedRow)>0?2:0;//2=grey color
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
