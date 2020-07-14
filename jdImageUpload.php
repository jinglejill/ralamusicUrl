<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
//    setConnectionValue("MINIMALIST");
    set_time_limit(720);
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    $ret = JdImageUpload($_GET["url"],$_GET["fileName"]);
    echo $ret;
    
//    $filename = "camera.png";
//    $file = fopen($filename, "rb");
//    $contents = fread($file, filesize($filename));
//    fclose($file);
    
//    $handle = fopen("http://minimalist.co.th/saim/camera.png", "rb");
//    $contents = stream_get_contents($handle);
//    fclose($handle);
    
    
//    $currentFolder = getcwd();
////    $contents = $currentFolder."\\camera.png";
//    $contents = $_GET["contents"];
//    copy($contents, './tmp/file.jpg');
//    $contents = $currentFolder."\\tmp\\file.jpg";
//    $c = getApiManagerBigData();
//    $c->method = "jingdong.common.image.UploadFile";
//    $c->param_json = "";
//    $c->param_file = $contents;
//    $resp = $c->call4BigData();
////    $resp = $c->call();
//
//    echo $resp;
////    $openapi_data = json_decode($resp)->openapi_data;
////    $code = json_decode($openapi_data)->code;
////    echo "<br>".$code.";".$productId.";".$resp ;
//    exit();
    
    //jfs/t16/71/3073778329/31130/77a9758b/5ef1c7c8Nd0dc11f5.png
?>
