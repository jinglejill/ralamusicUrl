<?php

    include_once('dbConnect.php');
//    setConnectionValue("RALAMUSIC");
    setConnectionValue("MINIMALIST");
    set_time_limit(720);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    
    
    //multiple images
    $imageList = array();
    $image0 = array();
    $image = array();
    $image["colorId"] = "0000000000";
    $image["imgUrl"] = "jfs/t16/71/3073778329/31130/77a9758b/5ef1c7c8Nd0dc11f5.png";
    $image["index"] = 1;
    $image0[] = $image;
    $imageList["0000000000"] = $image0;
    
    
    $saleAttrs = array();
    $saleAttr = array();
    $saleAttr["id"] = 0;
    $saleAttr["comAttId"] = 1;
    $saleAttr["Dimension"] = 1;
    $saleAttr["localeName"] = "AT200D-FBAT";//AT200D-FBAT
    $saleAttr["required"] = 1;
    $saleAttr["orderSort"] = 0;
    $saleAttrs[] = $saleAttr;
    
    
    $skuList = array();
    $sku = array();
    $sku["outerId"] = "AROMA-AT200D-FBAT";
    $sku["jdPrice"] = 10;
    $sku["stockNum"] = 20;
//    $sku["skuStatus"] = 1;
    $sku["productCode"] = "AROMA-AT200D-FBAT";//AROMA-AT200D-FBAT
    $sku["upcCode"] = "AROMA-AT200D-FBAT";
    $sku["length"] = "1";
    $sku["width"] = "2";
    $sku["height"] = "3";
    $sku["weight"] = "4";
    $sku["saleAttrs"] = $saleAttrs;
    $skuList[] = $sku;
        

    $data2 = array();
    $data2["categoryId"] = 2655;//1853;
    $data2["brandId"] = 2651;//8237;
    $data2["thName"] = "test products";
    $data2["enName"] = "test products en";
    $data2["appDescription"] = "app";//html tag
    $data2["pcDescription"] = "pc";//html tag
    $data2["sn"] = false;
    $data2["shelfLife"] = "0";
    $data2["payFirst"] = false;
    $data2["warranty"] = "No warranty";
    $data2["canUseJQ"] = true;
    $data2["canUseDQ"] = true;
    $data2["is15ToReturn"] = 4;
    $data2["vat"] = true;
    $data2["afterSales"] = "Warranty by Seller - 2 Weeks";
    $data2["unit"] = "piece";
    $data2["countryOfOrigin"] = "";
    
    $data2["skuList"] = $skuList;
    $data2["imageList"] = $imageList;
    $data2["locale"] = "th_TH";
    
    
    
    
//    echo json_encode($data2);
//    exit();
    $c = getApiManager();
    $c->method = "com.productUpdateApiService.saveProduct";
    $param = array();
    $param["updateProductParam"] = $data2;
    $c->param_json = json_encode($param);
    $resp = $c->call();
    
    echo $resp;
//    $openapi_data = json_decode($resp)->openapi_data;
//    $code = json_decode($openapi_data)->code;
//    echo "<br>".$code.";".$productId.";".$resp ;
//    exit();
    
    
?>
