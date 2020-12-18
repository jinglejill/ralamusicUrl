<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
    ini_set("memory_limit","50M");
    set_time_limit(1200);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    $page = $_GET["page"];
    $limit = $_GET["limit"];
    $sql = "select * from (select @row:=@row+1 as row, ProductId from (select ProductId from jdproduct order by jdproductid desc)b,(select @row:=0)t)a where row > ($page-1)*$limit limit $limit";
    $selectedRow = getSelectedRow($sql);
    
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $productId = $selectedRow[$i]["ProductId"];
        
        $c2 = getApiManager();
        $c2->method = "com.productQueryApiService.queryProductById";
        $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
        $resp2 = $c2->call();
        $openapi_data2 = json_decode($resp2)->openapi_data;
        //    echo $openapi_data;
        $data2 = json_decode($openapi_data2)->data;
        
        if(sizeof($data2->skuList)==0)
        {
            //delete from jdProduct where productId = $productId;
            echo "<br>".$productId;
//            exit();
//            break;//test
        }
    }
?>


