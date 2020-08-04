<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSICTEST");
    ini_set("memory_limit","50M");
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
//    $sku = $_GET["sku"];
//
//
//    //get from db
//    $sql = "select * from lazadaproducttemp where sellersku = '$sku'";
//    $ret = executeQueryArray($sql);
//    echo $ret[0]->short_description;
//
//    echo "<br><br>";
//
//
//    //get from api
//    $ret = getLazadaProduct($sku);
//    echo $ret->attributes->short_description;
//
//
//
//    exit();
    
    
    
    
    //shopee getallsku and id
//    $variations = getAllSkuShopee();
    
    
    
////  shopee find itemID
//    $sku = $_GET["sku"];
//    $variations = getAllVariationsShopee($sku);
//    writeToLog("all variations:" . json_encode($variations));
//    if(sizeof($variations) > 0)
//    {
//        $variation = $variations[0];
//        $itemID = $variation["item_id"];
//        $variationID = $variation["variation_id"];
//        echo $itemID;
////        $quantity = getStockShopee($itemID,$variationID);
////        return $quantity;
//    }
//    exit();
    
    
    //jd get product detail by productId
    $productId = $_GET["productId"];
    $c2 = getApiManager();
    $c2->method = "com.productQueryApiService.queryProductById";
    $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
    $resp2 = $c2->call();
    $openapi_data2 = json_decode($resp2)->openapi_data;
    //    echo $openapi_data;
    $data2 = json_decode($openapi_data2)->data;
//    echo $resp2;
    echo json_encode($data2);
    exit();
    
    
    
    //jd search by sku
    //get productid
    $sku = $_GET["sku"];
    $c = getApiManager();
    $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
    $param = array();
    $outerId = array();
    $outerId["outerId"] = $sku;
    $param["searchSkusByOuterIdParam"] = $outerId;
//    echo json_encode($param);
//    exit();
    $c->param_json = json_encode($param);
    $resp = $c->call();

    echo $resp;
    writeToLog("get product jd skuId result:" . $resp);
    $openapi_data = json_decode($resp)->openapi_data;
    $objs = json_decode($openapi_data)->objs;

    exit();



    
    
    //prepare for delete selected sku in jd and shopee
    $variations = getAllSkuShopee();
    
    $sql = "SELECT Sku FROM `mainproducttest`";
    $selectedRow = getSelectedRow($sql);
//    for($i=3; $i<sizeof($selectedRow); $i++)
    for($i=20; $i<40; $i++)
    {
        $sku = $selectedRow[$i]["Sku"];
        writeToLog("delete [i,sku]:[$i,$sku]");
        
        //get productid
//        $sku = $_GET["sku"];
        $c = getApiManager();
        $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
        $param = array();
        $outerId = array();
        $outerId["outerId"] = $sku;
        $param["searchSkusByOuterIdParam"] = $outerId;
    //    echo json_encode($param);
    //    exit();
        $c->param_json = json_encode($param);
        $resp = $c->call();
        
    //    echo $resp;
        writeToLog("get product jd skuId result:" . $resp);
        $openapi_data = json_decode($resp)->openapi_data;
        $objs = json_decode($openapi_data)->objs;
         
        
        $productSkuIds = array();
        for($j=0; $j<sizeof($objs); $j++)
        {
            $productSkuId = array();
            $productSkuId["productId"] = json_decode($objs[$j])->productId;
            $productSkuId["skuId"] = json_decode($objs[$j])->skuId;
            $productSkuIds[] = $productSkuId;
            
            
            //jd delete by product id
            $skuId = $productSkuId["skuId"];
            $c = getApiManager();
            $c->method = "com.jd.oversea.api.ProductUpdateApiService.deleteSku";
            $c->param_json = '{"skuId":"' . $skuId . '","locale":"th"}';
            $resp = $c->call();

            $openapi_data = json_decode($resp)->openapi_data;
            $code = json_decode($openapi_data)->code;
            if($code != 200)
            {
                echo "[i,sku]:[$i,$sku] delete jd fail";
            }
            writeToLog("delete sku jd result:" . $resp);
        }
        
        
        
        
        
        
        
        //shopee delete product by id
        for($j=0; $j<sizeof($variations); $j++)
        {
            $variation = $variations[$j];
            if($variation["item_sku"] == $sku)
            {
                $itemID = $variation["item_id"];
                
                //delete by id
                $ret = deleteShopeeItemByItemID($itemID);
                if(!$ret)
                {
                    echo "[i,sku]:[$i,$sku] delete shopee fail";
                }
                
//                break;
            }
        }
    }
    

    
//    echo json_encode($productSkuIds);
    
    exit();
    
   
    
    
//    SELECT *  FROM `wp_kpcode_url_posts` WHERE `kpcode_post_name` LIKE 'slugtest';
//    DELETE from wp_kpcode_url_posts where id in (11901,11902);
//    insert INTO wp_kpcode_url_posts (kpcode_post_id,kpcode_post_name,kpcode_type) values(19995,'testja','posts');
    
//    $productId = 9074566;
//    $productStatus = 1;
//    $skuId = 9074567;
//    $skuStatus = 1;
//
//    $ret = updateSkuStatus($productId,$productStatus,$skuId,$skuStatus);
////    $ret = getJdProduct($productId);
//
//
//    echo json_encode($ret);
//    exit();
//    $sku = $_GET["sku"];
//    $variations = getAllVariationsShopee($sku);
//    echo json_encode($variations);
//    exit();
    
    
//    $orders = getPendingOrdersLazada();
//    echo json_encode($orders);
    
//    $orders = getPendingOrdersJd();
//    echo json_encode($orders);
//    exit();
    
    
//    $ret = getJdProduct(2428150);
//    echo json_encode($ret);
//    exit();
    
//    $c = getApiManager();
//    $c->method = "com.productQueryApiService.querycategory";
//    $param = array();
//    $param["categoryId"] = 1846;//$_GET["categoryId"];
//    $param["locale"] = "th_TH";
//    $c->param_json = json_encode($param);
//    $resp = $c->call();
//
//    $openapi_data = json_decode($resp)->openapi_data;
//    $data = json_decode($openapi_data)->data;
//
//    echo json_encode($data->brands);
//    exit();

//
//
//    $c = getApiManager();
//    $c->method = "jingdong.stock.batchQueryStock";
//    $c->param_json = '{"skuIds":[2428151]}';
//    $resp = $c->call();
//
//    echo $resp;
//    exit();
    
    
//    $c = getApiManager();
//    $c->method = "com.jd.th.pop.open.OrderInformationFacade.listOrder";
//    $data = array();
//    $data["locale"] = "th_TH";
//    $data["page"] = 1;
//    $data["pageSize"] = 100;
//    $data["orderStatusType"] = 2;
//
//    $param["param"] = $data;
//    $c->param_json = json_encode($param);
//    $resp = $c->call();
//
//    writeToLog("getPendingOrdersJd result:" . $resp);
//    $openapi_data = json_decode($resp)->openapi_data;
//    $data = json_decode($openapi_data)->data;
//
//    echo json_encode($data->itemList);
//
//    exit();
    
    
    
    
//    Echoslap-VC201-US 7844318159 9311081
//    Kirlin-Y-362PR-0.3M 6744318638 9311185
//    Presonus-Eris-HD9 5644314361 9311305
//    Echoslap-GFX-TG 6144314454 9311307
//    Echoslap-GFX-TB 5744318953 9311300
//
//    The-One-KBTO-TOM1BK 5444307843 9310803
//    The-One-KBTO-TOM1WH 5844306732 9310791
//    Echoslap-VC201-MEX 4844312820 9310824
//    Echoslap-VC201-UK 6544311303 9310813
    
    
//
    
    
//    $openapi_data = json_decode($resp)->openapi_data;
//    $objs = json_decode($openapi_data)->objs;
   
//    setConnectionValue("RALAMUSICWEB");
//    $dbName = "ralamusi_2018a";
//    $dbUser = "ralamusi_2018a";
//    $dbPassword = "rala*2018";
////    $host = "27.254.86.35";
//    $host = "ralamusic.com";
//    $con=mysqli_connect($host,$dbUser,$dbPassword,$dbName);
//    if($con)
//    {
//        echo "con is not null";
//    }
//    else
//    {
//        echo "con is null";
//    }
//    exit();
//    $timeZone = mysqli_query($con,"SET SESSION time_zone = '+07:00'");
//    mysqli_set_charset($con, "utf8");
//
//
////    setConnectionValue("MINIMALIST");
//    set_time_limit(720);
//    writeToLog("file: " . basename(__FILE__));
//    printAllPost();
//
//
//    $sku = $_GET["sku"];
//    $quantity = $_GET["quantity"];
//
////    $ret = updateStockWordPressSku($sku,$quantity);
////    echo $ret;
//    $ret = getStockWordPressSku($sku);
//    echo $ret;
//    exit();
    
    
    
//    //3531-1575336982582-0
//    $page = 1;//$_GET["page"];
//    $limit = 10;//$_GET["limit"];
//    $sql = "select ProductID from jdproduct where productID>($page-1)*$limit order by jdproductid limit $limit";
//    $selectedRow = getSelectedRow($sql);
//
//
//    for($i=0; $i<sizeof($selectedRow); $i++)
//    {
//        $productId = $selectedRow[$i]["ProductID"];
//
//
//        //***********
//        $c2 = getApiManager();
//        $c2->method = "com.productQueryApiService.queryProductById";
//        $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
//        $resp2 = $c2->call();
//        $openapi_data2 = json_decode($resp2)->openapi_data;
//        $data2 = json_decode($openapi_data2)->data;
//
//        echo json_encode($data2);
//        exit();
//
//        $brandId = $data2->brandId;
//        $categoryId = $data2->categoryId;
//
//
//        $sql = "update jdproduct set categoryID = '$categoryId', brandId = '$brandId', modifieduser = 'bot' where productID = '$productId'";
//        $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
//        if($ret != "")
//        {
//            $ret["message"] = "แก้ไข categoryId, brandId ไม่สำเร็จ (productId:$productId)";
//            mysqli_close($con);
//
//            echo json_encode($ret);
//            exit();
//        }
//    }
//    exit();

//    $mainProductQuantity = 3;
//    $sku = "Daddario-PWPS10-Pin-Black";
//
//
//    //update product in lazada
//        $ret = updateStockQuantityLazadaInApp($sku,$mainProductQuantity);
//        if(!$ret)
//        {
//    //        mysqli_close($con);
//            $failMarketplace[] = "Lazada";
//        }
//
//    //update product in jd
//        $ret = updateStockQuantityJdInApp($sku,$mainProductQuantity);
//        if(!$ret)
//        {
//    //        mysqli_close($con);
//            $failMarketplace[] = "JD";
//        }
//
//    exit();

    
    $sku = $_GET["sku"];
    echo "<br>quantity lazada: ".getStockQuantityLazada($sku);
    echo "<br>quantity shopee: ".getStockQuantityShopee($sku);
    echo "<br>quantity jd: ".getStockQuantityJd($sku);
    exit();



////    productid 3943894 audreyantiquegold40
//    $productSkuIds = getJdProductSkuIds("audreyantiquegold40");
//    echo json_encode($productSkuIds);
//    exit();

    
    //3257 salestate =8,
//    echo json_encode(getJdProduct(2424722));
//    exit();
//

//    $sku = "Ernie-Ball-Prodigy-Black-Mini";
//    $c = getApiManager();
//    $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
//    $c->param_json = '{"searchSkusByOuterIdParam":{"outerId":"' . $sku . '"}}';
//    $resp = $c->call();
//
//    writeToLog("has product search jd result:" . $resp);
//    $openapi_data = json_decode($resp)->openapi_data;
//    $objs = json_decode($openapi_data)->objs;
//    echo json_encode($objs);
//    exit();
//


//    $c = getApiManager();
//    $c->method = "jingdong.gms.ItemModelGlobalService.getItemModelById";
//    $c->param_json = '{"productId": 2426102,"searchCondition": {"containBaseInfo": true,"containImageList": true,"containImageListAll": true,"containProductProperty": true,"containSkuProperty": true,"source": "SELF"}}';
//    $resp = $c->call();
//    $openapi_data = json_decode($resp)->openapi_data;
//    echo $openapi_data;
//    exit();




//    2424722 color หาย appdis,dis หาย
//    2426102 color หาย  'Kazuki-KZ41C-BK', 'KZ41CBK', ''
//4943243 ปกติ อัพเดตได้แล้ว โดยการเอา image ออก แล้วใส่เข้าไปใหม่
    //Ernie-Ball-Prodigy-Large-Shield-Black-P09332 stock 6; 2424722 stock 1  , 4942920
    //product detail new
    $c = getApiManager();
    $c->method = "com.productQueryApiService.queryProductById";
    $c->param_json = '{"productId":"2424722","locale":"th_TH"}';
    $resp = $c->call();
    $openapi_data = json_decode($resp)->openapi_data;
    $data = json_decode($openapi_data)->data;
    echo json_encode($data);
    exit();
    
    
    $imageList = $data->imageList;
    //**** remove key class
    foreach ($imageList as $key => $value)
    {
        for($i=0; $i<sizeof($value); $i++)
        {
            $image = $value[$i];
            $image->colorId = str_replace('®','',$image->colorId);
            unset($image->class);
        }
    }

    
    
    $skuList = $data->skuList;
    for($i=0; $i<sizeof($skuList); $i++)
    {
        $sku = $skuList[$i];
//        $sku->skuStatus = 1;
        $sku->upcCode = str_replace('®','',$sku->upcCode);
        $sku->outerId = str_replace('®','',$sku->upcCode);
    //    $sku->stockNum = 1;
        
        
        //unset sku****
        unset($sku->productCode);
        unset($sku->class);
        //unset sku****
        
        
        
        //unset saleAttrs****
        $saleAttrs = $sku->saleAttrs;
        for($j=0; $j<sizeof($saleAttrs); $j++)
        {
            $saleAttr = $saleAttrs[$j];
            $saleAttr->required = 1;
            $saleAttr->localeName = str_replace('®','',$saleAttr->localeName);
            unset($saleAttr->isEditting);
            unset($saleAttr->checked);
            unset($saleAttr->focus);
            unset($saleAttr->comAttId);
            unset($saleAttr->class);
        }
        //unset saleAttrs****
    }
    
    
    
//    //unset****
    $data->appDescription = $data->appdis;
    $data->pcDescription = $data->dis;
    $data->locale = "th_TH";
    unset($data->wareQD);
    unset($data->templateId);
    unset($data->dis);
    unset($data->applyId);
    unset($data->promiseId);
    unset($data->shelfLife);
    unset($data->class);
    unset($data->appdis);
    unset($data->afterSales);
    unset($data->unit);
    unset($data->descriptionEditType);
    unset($data->categoryStr);
    unset($data->countryOfOrigin);
//    //unset****
    

//    echo json_encode($data);
//    exit();
//
    //update product new
    $c = getApiManager();
    $c->method = "com.productUpdateApiService.saveProduct";
    $param = array();
    $param["updateProductParam"] = $data;

    $c->param_json = json_encode($param);
    $resp = $c->call();

    echo $resp;
    exit();
//
    

    
    
    
    
    
    
    
//    //product list
//    $searchText = $_GET["searchText"];
//    $searchArray = explode(" ",$searchText);
//
////    $productFoundList = array();
////    $sql = "select * from lazadaProduct";
////    $selectedRow = getSelectedRow($sql);
////    for($k=0; $k<sizeof($selectedRow); $k++)
//    {
////        $sellerSku = $selectedRow[$k]["SellerSku"];
//        for($j=3; $j<=4; $j++)
//        {
//            $c = getApiManager();
//            $c->method = "com.productQueryApiService.queryProducts";
//            $c->param_json = '{"queryProductParam":{"saleState":"8","pageNum":"1000","page":"'.$j.'","locale":"th_TH"}}';
//            $resp = $c->call();
//        //    echo $resp;
//            $openapi_data = json_decode($resp)->openapi_data;
//        //    echo $openapi_data;
//            $data = json_decode($openapi_data)->data;
////            echo json_encode($data->datas);
////            exit();
//
//
//            $product = $data->datas[$i];
//
//            for($i=0; $i<sizeof($data->datas); $i++)
//            {
//                $product = $data->datas[$i];
//                $productName = $product->productName;
//                $productId = $product->productId;
//
//                echo $productId;
//
//                //***********
//                $c2 = getApiManager();
//                $c2->method = "com.productQueryApiService.queryProductById";
//                $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
//                $resp2 = $c2->call();
//                $openapi_data2 = json_decode($resp2)->openapi_data;
//                //    echo $openapi_data;
//                $data2 = json_decode($openapi_data2)->data;
//
////                echo json_encode($data2);
////                exit();
//                $skuList = $data2->skuList;
//
//                $sku = $skuList[0];
//                echo $productId.";".$sku->skuId.";".$sku->upcCode.";".$sku->outerId."<br>";
//                //***********
//
//
////                if($sku->upcCode != "")
////                {
////                    echo $productId.";".$sku->upcCode.";".$sku->outerId.";".$productName."<br>";
////                    exit();
////                }
////
////
////
////                $productFound = array();
////                for($k=0; $k<sizeof($searchArray); $k++)
////                {
////                    $search = $searchArray[$k];
////                    if(stripos($productName, $search) !== false)
////                    {
////                        $productFound["productName"] = $productName;
////                        $productFound["found"] = $productFound["found"]?$productFound["found"]+1:1;
////                        $productFound["productId"] = $product->productId;
////                        $productFound["mainSkuId"] = $product->mainSkuId;
////                    }
////                }
////                if($productFound["found"]>0)
////                {
////                    $productFoundList[] = $productFound;
////                }
//            }
//        }
//    }
    
//    usort($productFoundList, function($a, $b) {
//        return $b['found'] <=> $a['found'];
//    });
//
////    for($i=0; $i<sizeof($productFoundList); $i++)
//    for($i=0; $i<10; $i++)
//    {
//        $productFound = $productFoundList[$i];
//        echo $productFound["found"].":".$productFound["productId"].":".$productFound["mainSkuId"].":".$productFound["productName"]."<br>";
//    }

    
        
        
        
        
        
        
        
        
//
//    $sku = $_GET["sku"];
//    $variations = getAllVariationsShopee($sku);
//    echo json_encode($variations);
//    
//    
//    exit();
//    $orders = getShopeeOrders("READY_TO_SHIP");
//    echo json_encode($orders);
//    $k=1;
//    $products = getLazadaProducts();
//    for($i=0; $i<sizeof($products); $i++)
//    {
//        $product = $products[$i];
//        for($j=0; $j<sizeof($product->skus); $j++)
//        {
//            $sku = $product->skus[$j];
//            echo $k.";".$product->attributes->name.";"."".";".$sku->SellerSku.";".$sku->quantity.";".$sku->Images[0].";"."bot".";"."2020-05-25 18:00:00"."<br>";
//            $k++;
//        }
//
//    }
//    echo "quantity".getStockQuantityLazada("audreyantiquegold40");
//    echo updateStockJd("audreyantiquegold40",4);


//    $sku = $_GET["sku"];
//    $c = getApiManager();
//    $c->method = "jingdong.gms.ItemModelGlobalService.searchSkusByOuterId";
//    $c->param_json = '{"searchSkusByOuterIdParam":{"outerId":"' . $sku . '"}}';
//    $resp = $c->call();
//
//    $openapi_data = json_decode($resp)->openapi_data;
//    $objs = json_decode($openapi_data)->objs;
//    echo "<br>objs:".json_encode($objs);
//    exit();
//    echo "<br>skuId:".json_decode($objs[0])->skuId;
    
////    //update stock
//    $skuId = 3943885;
//    $quantity = 4;
//    $sid = 0;
//    writeToLog("skuID:".$skuId.", quantity:".$quantity. ", sid:".$sid);
//    $c = getApiManager();
//    $c->method = "jingdong.stock.updateSkuStock";
//    $c->param_json = '{"updateSkuStockParam":{"skuId":"' . $skuId . '","sid":"' . $sid . '","stockNum":"' . $quantity . '"}}';
//    $resp = $c->call();
//    writeToLog($resp);
//    $respObj = json_decode($resp);
//    $dataObj = json_decode($respObj->openapi_data);
//    $resultCode = $dataObj->resultCode;
//    echo $resp;
//    echo "<br>resultCode:".$resultCode;


    
//    $c = getApiManager();
//    $c->method = "jingdong.stock.batchQueryStock";
//    $c->method = "jingdong.stock.getStoreList";
//    $c->param_json = '{"skuIds":[3943876,3943877,3943878,3943879,3943884,3943885]}';//,3943896
//    $resp = $c->call();
//    echo $resp;
//    echo "<br>";
//    echo "<br>";
//    echo "data:" . json_decode($resp)->openapi_data;

?>


