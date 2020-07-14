<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    set_time_limit(600);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
//    Kazuki-KZ409CE-BK 2426062



//    $productIDs = array();
//    for($j=1; $j<=5; $j++)
//    {
//        $c = getApiManager();
//        $c->method = "com.productQueryApiService.queryProducts";
//        $c->param_json = '{"queryProductParam":{"saleState":"8","pageNum":"1000","page":"'.$j.'","locale":"th_TH"}}';
//        $resp = $c->call();
//        $openapi_data = json_decode($resp)->openapi_data;
//        $data = json_decode($openapi_data)->data;
//
//
//        for($i=0; $i<sizeof($data->datas); $i++)
//        {
//            $product = $data->datas[$i];
//            $productId = $product->productId;
//            $productIDs[] = $productId;
//        }
//
//        if(sizeof($data->datas)<1000)
//        {
//            break;
//        }
//    }
    
    
    $page = $_GET["page"];
    $limit = $_GET["limit"];
    $sql = "select ProductID from jdproductdetailtemp where jdproductdetailtempid>($page-1)*$limit order by jdproductdetailtempid limit $limit";
    $selectedRow = getSelectedRow($sql);
    
    
//    for($i=0; $i<sizeof($productIDs); $i++)
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
//        $productId = $productIDs[$i];
        $productId = $selectedRow[$i]["ProductID"];
        
        
        //***********
        $c2 = getApiManager();
        $c2->method = "com.productQueryApiService.queryProductById";
        $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
        $resp2 = $c2->call();
        $openapi_data2 = json_decode($resp2)->openapi_data;
        $data2 = json_decode($openapi_data2)->data;

//            echo json_encode($data2);
//            exit();

//            //***** back up product detail
//            $jsonData = json_encode($data2);
//            $jsonData = mysqli_real_escape_string($con,$jsonData);
////            exit();
//
//            $sql = "insert into jdproductdetailtemp (`ProductID`, `JsonData`) values ('$productId','$jsonData')";
//            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
//            if($ret != "")
//            {
////                mysqli_rollback($con);
//        //        putAlertToDevice($_POST["modifiedUser"]);
//                echo json_encode($ret);
//                exit();
//            }
//            //***** back up product detail
        
        
        
        //***** provide data before update
        $imageList = $data2->imageList;
        //**** remove key class
        foreach ($imageList as $key => $value)
        {
            for($m=0; $m<sizeof($value); $m++)
            {
                $image = $value[$m];
                $image->colorId = str_replace('®','',$image->colorId);
                unset($image->class);
            }
        }



        $skuList = $data2->skuList;
        for($k=0; $k<sizeof($skuList); $k++)
        {
            $sku = $skuList[$k];
            $sku->upcCode = str_replace('®','',$sku->upcCode);
            $sku->outerId = str_replace('®','',$sku->upcCode);
    //        $sku->skuStatus = 1;
    
    
    
    //*****test
//                echo "<br>".$data2->productId;
//                if($sku->outerId != $sku->upcCode)
//                {
//                    exit();
//                }
    // *****test




            $sku->outerId = $sku->upcCode;
        //    $sku->stockNum = 1;


            //unset sku****
            unset($sku->productCode);
            unset($sku->class);
            //unset sku****



            //unset saleAttrs****
            $saleAttrs = $sku->saleAttrs;
            for($l=0; $l<sizeof($saleAttrs); $l++)
            {
                $saleAttr = $saleAttrs[$l];
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




        $data2->appDescription = $data2->appdis;//"appDescription";
        $data2->pcDescription = $data2->dis;//"pcDescription";
        $data2->locale = "th_TH";
        unset($data2->wareQD);
        unset($data2->templateId);
        unset($data2->dis);
        unset($data2->applyId);
        unset($data2->promiseId);
        unset($data2->shelfLife);
        unset($data2->class);
        unset($data2->appdis);
        unset($data2->afterSales);
        unset($data2->unit);
        unset($data2->descriptionEditType);
        unset($data2->categoryStr);
        unset($data2->countryOfOrigin);
        //***** provide data before update



//            echo json_encode($data2);//2425992
//            exit();
    //
    //
        //update product new
        $c = getApiManager();
        $c->method = "com.productUpdateApiService.saveProduct";
        $param = array();
        $param["updateProductParam"] = $data2;
        $c->param_json = json_encode($param);
        $resp = $c->call();
        $openapi_data = json_decode($resp)->openapi_data;
        $code = json_decode($openapi_data)->code;
        echo "<br>".$code.";".$productId.";".$resp ;
        
        
    }
?>
