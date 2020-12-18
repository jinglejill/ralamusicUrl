<?php

    include_once('dbConnect.php');
//    setConnectionValue("RALAMUSIC");
    setConnectionValue("MINIMALIST");
    set_time_limit(1200);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    

    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");


    //truncate temp
    $sql = "truncate `jdproducttemp`;";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        mysqli_close($con);
        
        echo json_encode($ret);
        sendNotiToAdmin($dbName.": Cannot fetch jd sku");
        exit();
    }
    
    
    for($j=1; $j<=60; $j++)
    {
        $c = getApiManager();
        $c->method = "com.productQueryApiService.queryProducts";
        $c->param_json = '{"queryProductParam":{"saleState":"-1","pageNum":"100","page":"'.$j.'","locale":"en_US"}}';
        $resp = $c->call();
        //    echo $resp;
        $openapi_data = json_decode($resp)->openapi_data;
        //    echo $openapi_data;
        $data = json_decode($openapi_data)->data;
        //            echo json_encode($data->datas);
        //            exit();
//        echo $resp;
//        exit();


        for($i=0; $i<sizeof($data->datas); $i++)
        {
            $product = $data->datas[$i];
            $productName = $product->productName;
            $productId = $product->productId;

        //            echo $productId;

            //***********
            $c2 = getApiManager();
            $c2->method = "com.productQueryApiService.queryProductById";
            $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
            $resp2 = $c2->call();
            $openapi_data2 = json_decode($resp2)->openapi_data;
            //    echo $openapi_data;
            $data2 = json_decode($openapi_data2)->data;

        //                echo json_encode($data2);
        //                exit();
            $skuList = $data2->skuList;

            $sku = $skuList[0];
            $outerId = mysqli_real_escape_string($con,$sku->outerId);
            $skuId = $sku->skuId;
            $upcCode = mysqli_real_escape_string($con,$sku->upcCode);
            $modifiedUser = "bot";
            
            
            
        //            echo $productId.";".$sku->skuId.";".$sku->upcCode.";".$sku->outerId."<br>";
            $sql = "INSERT INTO `jdproducttemp`(`Sku`, `ProductId`, `SkuId`, `UpcCode`, `OuterId`, `ModifiedUser`) values('$outerId','$productId','$skuId','$upcCode','$outerId','$modifiedUser')";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_rollback($con);
        //        putAlertToDevice($_POST["modifiedUser"]);
                echo json_encode($ret);
                exit();
            }
                //***********
        }

        $size += sizeof($data->datas);
        if(sizeof($data->datas)<100)
        {
            //truncate backup
            //move current to backup
            $sql = "truncate jdProductBackup;";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_close($con);

                echo json_encode($ret);
                sendNotiToAdmin($dbName.": Cannot fetch jd sku");
                exit();
            }

            $sql = "insert into jdproductbackup(`Sku`, `ProductId`, `SkuId`, `UpcCode`, `OuterId`, `ModifiedUser`) select `Sku`, `ProductId`, `SkuId`, `UpcCode`, `OuterId`, `ModifiedUser` from jdProduct";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_close($con);

                echo json_encode($ret);
                sendNotiToAdmin($dbName.": Cannot fetch jd sku");
                exit();
            }


            //truncate current
            //move from temp to current
            $sql = "truncate jdProduct;";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_close($con);

                echo json_encode($ret);
                sendNotiToAdmin($dbName.": Cannot fetch jd sku");
                exit();
            }

            $sql = "insert into jdproduct(`Sku`, `ProductId`, `SkuId`, `UpcCode`, `OuterId`, `ModifiedUser`) select `Sku`, `ProductId`, `SkuId`, `UpcCode`, `OuterId`, `ModifiedUser` from jdProductTemp";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                mysqli_close($con);

                echo json_encode($ret);
                sendNotiToAdmin($dbName.": Cannot fetch jd sku");
                exit();
            }

            mysqli_commit($con);
            mysqli_close($con);
            
            
            break;
        }
    
    }
    echo json_encode(array("success"=>true));
    exit();
?>
