<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    set_time_limit(1200);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    

    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");


    $dataList = array();
    for($j=1; $j<=1; $j++)
    {
        $c = getApiManager();
        $c->method = "com.productQueryApiService.queryProducts";
        $c->param_json = '{"queryProductParam":{"saleState":"-1","pageNum":"5","page":"'.$j.'","locale":"th_TH"}}';
        $resp = $c->call();
        //    echo $resp;
        $openapi_data = json_decode($resp)->openapi_data;
        //    echo $openapi_data;
        $data = json_decode($openapi_data)->data;
        //            echo json_encode($data->datas);
        //            exit();



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

            $dataList[] = $data2;
            

            
            
                //***********
        }

        
    
    }
    
    echo json_encode($dataList);
    exit();
    

?>
