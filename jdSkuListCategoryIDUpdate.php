<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSICTEST");
//    setConnectionValue("MINIMALIST");
    set_time_limit(600);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
//    Kazuki-KZ409CE-BK 2426062


    
    $page = $_GET["page"];
    $limit = $_GET["limit"];
    $sql = "select ProductID from jdproduct where productID>($page-1)*$limit order by jdproductid limit $limit";
    $selectedRow = getSelectedRow($sql);
    
    
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $productId = $selectedRow[$i]["ProductID"];
        
        
        //***********
        $c2 = getApiManager();
        $c2->method = "com.productQueryApiService.queryProductById";
        $c2->param_json = '{"productId":"' . $productId . '","locale":"th_TH"}';
        $resp2 = $c2->call();
        $openapi_data2 = json_decode($resp2)->openapi_data;
        $data2 = json_decode($openapi_data2)->data;

        
        $brandId = $data2->brandId;
        $categoryId = $data2->categoryId;
        
        
        $sql = "update jdproduct set categoryID = '$categoryId', brandId = '$brandId', modifieduser = 'bot' where productID = '$productId'";
        $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
        if($ret != "")
        {
            $ret["message"] = "แก้ไข categoryId, brandId ไม่สำเร็จ (productId:$productId)";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
    }
    echo "success" . " (page:$page)";
?>
