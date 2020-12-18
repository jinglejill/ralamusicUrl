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

    //search process
    $search = trim($searchText);
    $search = mysqli_real_escape_string($con,$search);
    $skuSelected = mysqli_real_escape_string($con,$skuSelected);
    if($search == '')
    {
        $sql = "select c.* from (select @row:=@row+1 as RowNum, b.* from (select Name, Sku, MainImage FROM `mainproduct` WHERE STATUS = 'active' and sku != '$skuSelected' order by ModifiedDate desc, Sku)b, (select @row:=0)t)c where c.RowNum > ($page-1)*$limit limit $limit";
        $productPage = executeQueryArray($sql);
    }
    else
    {
        $sql = "select b.* from (select @row:=@row+1 as RowNum, a.* from (SELECT Name, Sku, MainImage FROM `mainproduct` WHERE STATUS = 'active' and sku != '$skuSelected' and match(Sku,Name) against ('$search') order by match(Sku,Name) against ('$search') desc, modifiedDate desc, sku)a,(select @row:=0)t)b where b.RowNum > ($page-1)*$limit limit $limit";
        $productPage = executeQueryArray($sql);
        
        if(sizeof($productPage) == 0)
        {
            $sql = "select c.* from (select @row:=@row+1 as RowNum, b.* from (select Name, Sku, MainImage from (select Name, Sku, MainImage, ModifiedDate FROM `mainproduct` WHERE STATUS = 'active' and sku != '$skuSelected' and sku like '%$search%' union select Name, Sku, MainImage, ModifiedDate FROM `mainproduct` WHERE STATUS = 'active' and sku != '$skuSelected' and name like '%$search%')a order by ModifiedDate desc, Sku)b, (select @row:=0)t)c where c.RowNum > ($page-1)*$limit limit $limit";
            $productPage = executeQueryArray($sql);
        }
    }
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
