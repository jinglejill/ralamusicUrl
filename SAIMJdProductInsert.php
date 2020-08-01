<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str)->storeName;
    $sku = json_decode($json_str)->sku;
    $insert = json_decode($json_str)->insert;
    $lazadaProduct = json_decode($json_str)->lazadaProduct;
    $modifiedUser = json_decode($json_str)->modifiedUser;
    
//    $storeName = json_decode($json_str,true)["storeName"];
//    $sku = json_decode($json_str,true)["sku"];
//    $insert = json_decode($json_str,true)["insert"];
//    $lazadaProduct = json_decode($json_str,true)["lazadaProduct"];
//    $modifiedUser = json_decode($json_str,true)["modifiedUser"];
 
    
    
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
    
  
    if(!$lazadaProduct)
    {
//        $fromApp = true;
        $sql = "select * from lazadaProductTemp where SellerSku = '$sku'";
        $lazadaProductList = executeQueryArray($sql);
        writeToLog("lazada product list: ".json_encode($lazadaProductList));
        if(sizeof($lazadaProductList) == 0)
        {
            $lazadaProductApi = getLazadaProduct($sku);
            if($lazadaProductApi)
            {
                $lazadaProduct = (object)array();
                $lazadaProduct->PrimaryCategory = mysqli_real_escape_string($con,$lazadaProductApi->primary_category);
                $lazadaProduct->name = mysqli_real_escape_string($con,$lazadaProductApi->attributes->name);
                $lazadaProduct->name_en = mysqli_real_escape_string($con,$lazadaProductApi->attributes->name_en);
                $lazadaProduct->short_description = mysqli_real_escape_string($con,$lazadaProductApi->attributes->short_description);
                $lazadaProduct->short_description_en = mysqli_real_escape_string($con,$lazadaProductApi->attributes->description_en);
                $lazadaProduct->video = mysqli_real_escape_string($con,$lazadaProductApi->attributes->video);
                $lazadaProduct->brand = mysqli_real_escape_string($con,$lazadaProductApi->attributes->brand);
                $lazadaProduct->SellerSku = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->SellerSku);
                $lazadaProduct->quantity = $lazadaProductApi->skus[0]->quantity;
                $lazadaProduct->price = $lazadaProductApi->skus[0]->price;
                $lazadaProduct->special_price = $lazadaProductApi->skus[0]->special_price;
                $lazadaProduct->package_weight = $lazadaProductApi->skus[0]->package_weight;
                $lazadaProduct->package_length = $lazadaProductApi->skus[0]->package_length;
                $lazadaProduct->package_width = $lazadaProductApi->skus[0]->package_width;
                $lazadaProduct->package_height = $lazadaProductApi->skus[0]->package_height;
                $lazadaProduct->MainImage = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[0]);
                $lazadaProduct->Image2 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[1]);
                $lazadaProduct->Image3 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[2]);
                $lazadaProduct->Image4 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[3]);
                $lazadaProduct->Image5 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[4]);
                $lazadaProduct->Image6 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[5]);
                $lazadaProduct->Image7 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[6]);
                $lazadaProduct->Image8 = mysqli_real_escape_string($con,$lazadaProductApi->skus[0]->Images[7]);
            }
        }
        else
        {
            $lazadaProduct = $lazadaProductList[0];
        }
    }
    writeToLog("source lazada:". json_encode($lazadaProduct));
    
    
//    if(sizeof($lazadaProductList) == 0)
    if(!$lazadaProduct)
    {
        if($insert)
        {
            $message = "เพิ่มสินค้าใน JD ไม่สำเร็จ";
        }
        else
        {
            $message = "แก้ไขสินค้าใน JD ไม่สำเร็จ";
        }
        sendNotiToAdmin($message);
        $ret = array();
        $ret["success"] = false;
        $ret["message"] = $message;
        mysqli_close($con);

        echo json_encode($ret);
        exit();
    }
    
    $sql = "select * from mainproduct where sku = '$sku'";
    $product = executeQueryArray($sql);
    $primaryCategory = $product[0]->PrimaryCategory;
    $productBrand = $product[0]->Brand;
    
    $jdBrandID = 0;
    $sql = "select * from categoryMappingJd where lazadaCategoryID = '$primaryCategory'";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) > 0)
    {
        for($i=0; $i<sizeof($selectedRow); $i++)
        {
            $jdCategoryID = $selectedRow[0]["JdCategoryID"];
            $brands = getJdBrands($jdCategoryID);
            for($j=0; $j<sizeof($brands); $j++)
            {
                $brand = $brands[$j];
//                writeToLog("stripos:".$brand->name.",".$productBrand);
                if(stripos($brand->name, $productBrand) !== false)
                {
                    $jdBrandID = $brand->brandId;
                    break;
                }
            }
            if($jdBrandID != 0)
            {
                break;
            }
        }
        if($jdBrandID == 0)
        {
            $jdCategoryID = $defaultJdCategoryID;
            $jdBrandID = $defaultJdBrandID;
        }
    }
    else
    {
        $jdCategoryID = $defaultJdCategoryID;
        $jdBrandID = $defaultJdBrandID;
    }
    
    
    $categoryId = intval($jdCategoryID);
    $brandId = intval($jdBrandID);
    $thName = $product[0]->Name;//name
    $thName = iconv_substr($thName, 0, 120,'UTF-8');
    $afterSales = "";//"Warranty by Seller - 2 Weeks";
    $price = floatval($product[0]->Price);//skus[0]->price
    $stock = intval($product[0]->Quantity);//skus[0]->quantity
    
    
//    if($fromApp)
//    {
//        $enName = $lazadaProduct->attributes->name_en?$lazadaProduct->attributes->name_en:"";
//        $appDescription = $lazadaProduct->attributes->short_description?$lazadaProduct->attributes->short_description:$lazadaProduct->attributes->name;
//        $pcDescription = $appDescription;
//        $packageWeight = intval($lazadaProduct->skus[0]->package_weight);//package_weight parseInt
//        $packageLength = intval($lazadaProduct->skus[0]->package_length);//package_length parseInt
//        $packageWidth = intval($lazadaProduct->skus[0]->package_width);//package_width parseInt
//        $packageHeight = intval($lazadaProduct->skus[0]->package_height);//package_height parseInt
//    }
//    else
    {
//        $enName = $lazadaProduct["name_en"];
//        $appDescription = $lazadaProduct["short_description"]?$lazadaProduct["short_description"]:$lazadaProduct["name"];
//        $pcDescription = $appDescription;
//        $packageWeight = intval($lazadaProduct["package_weight"]);
//        $packageLength = intval($lazadaProduct["package_length"]);
//        $packageWidth = intval($lazadaProduct["package_width"]);
//        $packageHeight = intval($lazadaProduct["package_height"]);
        
        
        $enName = $lazadaProduct->name_en;
        $appDescription = $lazadaProduct->short_description?$lazadaProduct->short_description:$lazadaProduct->name;
        $pcDescription = $appDescription;
        $packageWeight = intval($lazadaProduct->package_weight);
        $packageLength = intval($lazadaProduct->package_length);
        $packageWidth = intval($lazadaProduct->package_width);
        $packageHeight = intval($lazadaProduct->package_height);
        
        
//        $enName = $lazadaProduct["attributes"]["name_en"]?$lazadaProduct["attributes"]["name_en"]:"";
//        $appDescription = $lazadaProduct["attributes"]["short_description"]?$lazadaProduct["attributes"]["short_description"]:$lazadaProduct["attributes"]["name"];
//        $pcDescription = $appDescription;
//        $packageWeight = intval($lazadaProduct["skus"][0]["package_weight"]);//package_weight parseInt
//        $packageLength = intval($lazadaProduct["skus"][0]["package_length"]);//package_length parseInt
//        $packageWidth = intval($lazadaProduct["skus"][0]["package_width"]);//package_width parseInt
//        $packageHeight = intval($lazadaProduct["skus"][0]["package_height"]);//package_height parseInt
    }
    
    $saleAttrs = array();
    $saleAttr = array();
    $saleAttr["id"] = 0;
    $saleAttr["comAttId"] = 1;
    $saleAttr["Dimension"] = 1;
    $saleAttr["localeName"] = $sku;
    $saleAttr["required"] = 1;
    $saleAttr["orderSort"] = 0;
    $saleAttr["focus"] = true;
    $saleAttr["checked"] = true;
    $saleAttrs[] = $saleAttr;
    
    
    $skuList = array();
    $sku0 = array();
    $sku0["outerId"] = $sku;
    $sku0["jdPrice"] = $price;
    $sku0["stockNum"] = $stock;
//    $sku0["skuStatus"] = 1;
    $sku0["productCode"] = $sku;
    $sku0["upcCode"] = $sku;
    $sku0["length"] = strval($packageLength*10);
    $sku0["width"] = strval($packageWidth*10);
    $sku0["height"] = strval($packageHeight*10);
    $sku0["weight"] = strval($packageWeight);
    $sku0["saleAttrs"] = $saleAttrs;
    $skuList[] = $sku0;
    
    
    
    $imageList = array();
    $imageProduct = array();
    $imageSku = array();
    
    if($product[0]->MainImage != "")
    {
        $index = 1;
        $tmpFileName = $sku."-".$index.".jpg";
        $jdImageUrl = JdImageUpload($product[0]->MainImage,$tmpFileName);
        
        
        //image product
        $image = array();
        $image["colorId"] = "0000000000";
        $image["imgUrl"] = $jdImageUrl;
        $image["index"] = 1;
        $imageProduct[] = $image;
        
        
        //image sku
        $image = array();
        $image["colorId"] = $sku;
        $image["imgUrl"] = $jdImageUrl;
        $image["index"] = $index;
        $imageSku[] = $image;
    }
    if($product[0]->Image2 != "")
    {
        $index = 2;
        $tmpFileName = $sku."-".$index.".jpg";
        $jdImageUrl = JdImageUpload($product[0]->Image2,$tmpFileName);
        
        $image = array();
        $image["colorId"] = $sku;
        $image["imgUrl"] = $jdImageUrl;
        $image["index"] = $index;
        $imageSku[] = $image;
    }
    if($product[0]->Image3 != "")
    {
        $index = 3;
        $tmpFileName = $sku."-".$index.".jpg";
        $jdImageUrl = JdImageUpload($product[0]->Image3,$tmpFileName);
        
        $image = array();
        $image["colorId"] = $sku;
        $image["imgUrl"] = $jdImageUrl;
        $image["index"] = $index;
        $imageSku[] = $image;
    }
    if($product[0]->Image4 != "")
    {
        $index = 4;
        $tmpFileName = $sku."-".$index.".jpg";
        $jdImageUrl = JdImageUpload($product[0]->Image4,$tmpFileName);
        
        $image = array();
        $image["colorId"] = $sku;
        $image["imgUrl"] = $jdImageUrl;
        $image["index"] = $index;
        $imageSku[] = $image;
    }
    if($product[0]->Image5 != "")
    {
        $index = 5;
        $tmpFileName = $sku."-".$index.".jpg";
        $jdImageUrl = JdImageUpload($product[0]->Image5,$tmpFileName);
        
        $image = array();
        $image["colorId"] = $sku;
        $image["imgUrl"] = $jdImageUrl;
        $image["index"] = $index;
        $imageSku[] = $image;
    }
    if($product[0]->Image6 != "")
    {
        $index = 6;
        $tmpFileName = $sku."-".$index.".jpg";
        $jdImageUrl = JdImageUpload($product[0]->Image6,$tmpFileName);
        
        $image = array();
        $image["colorId"] = $sku;
        $image["imgUrl"] = $jdImageUrl;
        $image["index"] = $index;
        $imageSku[] = $image;
    }
    if($product[0]->Image7 != "")
    {
        $index = 7;
        $tmpFileName = $sku."-".$index.".jpg";
        $jdImageUrl = JdImageUpload($product[0]->Image7,$tmpFileName);
        
        $image = array();
        $image["colorId"] = $sku;
        $image["imgUrl"] = $jdImageUrl;
        $image["index"] = $index;
        $imageSku[] = $image;
    }
    if($product[0]->Image8 != "")
    {
        $index = 8;
        $tmpFileName = $sku."-".$index.".jpg";
        $jdImageUrl = JdImageUpload($product[0]->Image8,$tmpFileName);
        
        $image = array();
        $image["colorId"] = $sku;
        $image["imgUrl"] = $jdImageUrl;
        $image["index"] = $index;
        $imageSku[] = $image;
    }
    
    $imageList["0000000000"] = $imageProduct;
    $imageList[$sku] = $imageSku;
    
    
    
    //param
    $paramBody = array();
    $paramBody["categoryId"] = $categoryId;//1853;
    $paramBody["brandId"] = $brandId;//8237;
    $paramBody["thName"] = $thName;
    $paramBody["enName"] = $enName;
    $paramBody["appDescription"] = $appDescription;//html tag
    $paramBody["pcDescription"] = $pcDescription;//html tag
    $paramBody["sn"] = false;
    $paramBody["shelfLife"] = "0";
    $paramBody["payFirst"] = false;
    $paramBody["warranty"] = "1 year warranty";
    $paramBody["canUseJQ"] = true;
    $paramBody["canUseDQ"] = true;
    $paramBody["is15ToReturn"] = 1;
    $paramBody["vat"] = true;
    $paramBody["afterSales"] = "";
    $paramBody["unit"] = "piece";
    $paramBody["countryOfOrigin"] = "";
    
    $paramBody["skuList"] = $skuList;
    $paramBody["imageList"] = $imageList;
    $paramBody["locale"] = "th_TH";
    $paramBody["vat"] = true;
    
    if($insert)
    {
        $result = insertJdProduct($paramBody);
        
        if($result)
        {
            $productId = $result["productId"];//$result->data;
//            $jdProduct = getJdProduct($productId);
            $skuId = $result["skuId"];//$jdProduct->skuList[0]->skuId;
            
            
            //insert into shopeeProduct
            $sql = "insert into jdProduct (sku,productId,skuId,modifiedUser) values('$sku',$productId,$skuId,'$modifiedUser')";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                $message = "เพิ่ม JD sku ในแอปไม่สำเร็จ";
                sendNotiToAdmin($message);
                $ret["message"] = $message;
                mysqli_close($con);

                echo json_encode($ret);
                exit();
            }
        }
        else
        {
            //insert fail
            $message = "เพิ่มสินค้าใน JD ไม่สำเร็จ";
            sendNotiToAdmin($message);
            
            $ret = array();
            $ret["success"] = false;
            $ret["message"] = $message;
            mysqli_rollback($con);
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
    }
//    else
//    {
//        //update*********************************************************
//        //updateItem,
//        //updateItemImg,updatePrice,updateStock
//
//        $sql = "select * from shopeeProduct where sku = '$sku'";
//        $shopeeProduct = executeQueryArray($sql);
//        $itemID = $shopeeProduct[0]->ItemID;
//
//        $shopeeItem = getItemShopee($itemID);
//
//
//
//        $status = $shopeeItem->status;//"UNLIST";//NORMAL, UNLIST
//        $daysToShip = $shopeeItem->days_to_ship;
//        $isPreOrder = $shopeeItem->is_pre_order;
//        $condition = $shopeeItem->condition;
//        $sizeChart = $shopeeItem->size_chart;
//        $logistics = $shopeeItem->logistics;
//
//
//        $paramBody["item_id"] = intval($itemID);
//        unset($paramBody["price"]);
//        unset($paramBody["stock"]);
//        unset($paramBody["images"]);
//
//
//        $result = updateShopeeProduct($paramBody);
//        $obj = json_decode($result);
//
//        if($obj->item_id)
//        {
//            //update success
//
//            $failData = array();
//            $result = updateShopeeImages($itemID,$images);
//            if(!$result->item)
//            {
//                $fail = 1;
//                $failData[] = "รูปภาพ";
//            }
//
//            $result = updateShopeePrice($itemID,$price);
//            if(!$result->item)
//            {
//                $fail = 1;
//                $failData[] = "ราคา";
//            }
//
//            $result = updateShopeeStock($itemID,$quantity);
//            if(!$result->item)
//            {
//                $fail = 1;
//                $failData[] = "จำนวน";
//            }
//
//
//            //update fail
//            for($i=0; $i<sizeof($failData); $i++)
//            {
//                if($i==0)
//                {
//                    $failMessage = $failData[$i];
//                }
//                else
//                {
//                    $failMessage .= ", " . $failData[$i];
//                }
//            }
//
//            $message = "แก้ไข" . $failMessage . " ใน Shopee ไม่สำเร็จ";
//            sendNotiToAdmin($message);
//
//
//            $ret = array();
//            $ret["success"] = false;
//            $ret["message"] = $message;
//            mysqli_rollback($con);
//            mysqli_close($con);
//
//            echo json_encode($ret);
//            exit();
//        }
//        else
//        {
//            //update fail
//            $message = "แก้ไขสินค้าใน Shopee ไม่สำเร็จ";
//            sendNotiToAdmin($message);
//
//
//            $ret = array();
//            $ret["success"] = false;
//            $ret["message"] = $message;
//            mysqli_rollback($con);
//            mysqli_close($con);
//
//            echo json_encode($ret);
//            exit();
//        }
//    }
        
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>true));
    exit();
    
    
?>


