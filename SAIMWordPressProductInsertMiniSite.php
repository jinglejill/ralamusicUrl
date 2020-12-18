<?php
    require_once("../wp-load.php");
//    include_once('dbConnect.php');
//    setConnectionValue("RALAMUSICWEB");
    set_time_limit(300);
    ini_set('display_errors', 1);//not show error
//    ini_set('allow_url_include',1);
    $con;
    $con2;
    $modifiedUser="bot";
    $globalDBName="RALAMUSICWEB";//
    
        
    
    $json_str = file_get_contents('php://input');
    $product = json_decode($json_str)->product;
    $lazadaProduct = json_decode($json_str)->lazadaProduct;


    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
//    // Set autocommit to off
//    mysqli_autocommit($con,FALSE);
//    writeToLog("set auto commit to off");
    
    
    
    $sku = $product[0]->Sku;
    $title = $product[0]->Name;//name
    $price = floatval($product[0]->Price);//skus[0]->price
    $formatPrice = number_format($price, 2, '.', ',');
    
    $stock = intval($product[0]->Quantity);//skus[0]->quantity
    $brand = $product[0]->Brand;
    $model = str_ireplace($brand."-","",$sku);
    $commonTabTitle = str_replace("-"," ",$sku);
    
    
    $salePrice = $lazadaProduct->special_price;
    $formatSalePrice = number_format($salePrice, 2, '.', ',');
    
    $content = $lazadaProduct->short_description?$lazadaProduct->short_description:$lazadaProduct->name;
    $contentEn = $lazadaProduct->short_description_en;
    $video = $lazadaProduct->video;
    $packageWeight = intval($lazadaProduct->package_weight);
    $packageLength = intval($lazadaProduct->package_length);
    $packageWidth = intval($lazadaProduct->package_width);
    $packageHeight = intval($lazadaProduct->package_height);    
    $metaDesc = $title . " ราคาน่าฟัง " . $formatSalePrice . " บ. จากราคาปกติ " . $formatPrice. " บ.";
    
    
    
    
    //tag*****
    $tagList = array();
    $tagList[] = $brand . " " . $model;
    $tagList[] = $brand . " " . $model . " ราคา";
    $tagList[] = $model;
    $tagList[] = $model . " ราคา";
    if($webPrimaryCategory != "")
    {
        $tagList[] = $brand . " " . $webPrimaryCategory;
    }
    //tag*****
    
    $post = array(
        'post_author' => 1,//253
        'post_content' => $content,
        'post_status' => "pending",//publish
        'post_title' => $title,
        'post_parent' => '',
        'post_type' => "product",
        'post_name' => $sku
    );

    //Create post
    $post_id = wp_insert_post( $post, $wp_error );
    if($post_id == 0)
    {
//        mysqli_commit($con);
//        mysqli_close($con);
        writeToLog("wordpress insert post fail, sku: ".$sku);
        echo json_encode(array("success"=>false));
        exit();
    }
    else
    {
        writeToLog("wordpress insert post success, sku: ".$sku);
    }
//    if(sizeof($webCategoryNameList)>0)
//    {
//        wp_set_object_terms( $post_id,$webCategoryNameList , 'product_cat' );
//    }

    
    wp_set_object_terms($post_id, $tagList, 'product_tag');
    wp_set_object_terms( $post_id, 'simple', 'product_type');

    update_post_meta( $post_id, '_visibility', 'visible' );
    update_post_meta( $post_id, '_stock_status', 'instock');
    update_post_meta( $post_id, 'total_sales', '0');
//    update_post_meta( $post_id, '_downloadable', 'yes');
//    update_post_meta( $post_id, '_virtual', 'yes');
    update_post_meta( $post_id, '_regular_price', $price );
    update_post_meta( $post_id, '_sale_price', $salePrice );
    update_post_meta( $post_id, '_purchase_note', "" );
    update_post_meta( $post_id, '_featured', "no" );
    update_post_meta( $post_id, '_weight', $packageWeight );
    update_post_meta( $post_id, '_length', $packageLength );
    update_post_meta( $post_id, '_width', $packageWidth );
    update_post_meta( $post_id, '_height', $packageHeight );
    update_post_meta( $post_id, '_sku', $sku);
    update_post_meta( $post_id, '_product_attributes', array());
    update_post_meta( $post_id, '_sale_price_dates_from', "" );
    update_post_meta( $post_id, '_sale_price_dates_to', "" );
    update_post_meta( $post_id, '_price', $price );
    update_post_meta( $post_id, '_sold_individually', "" );
    update_post_meta( $post_id, '_manage_stock', "no" );
    update_post_meta( $post_id, '_backorders', "no" );
    update_post_meta( $post_id, '_stock', "" );
    update_post_meta( $post_id, '_basel_product_video', "$video" );
    update_post_meta( $post_id, 'common_tab_tab_custom_title', "$commonTabTitle" );
    update_post_meta( $post_id, 'common_tab', $contentEn );
    update_post_meta( $post_id, '_yoast_wpseo_metadesc', $metaDesc );
    
    wp_update_post( array('ID' => $post_id, 'post_excerpt' => $content ) );
    

    
    if($product[0]->MainImage != "")
    {
        attach_product_thumbnail($post_id, $product[0]->MainImage, 0);
        attach_product_thumbnail($post_id, $product[0]->MainImage, 1);
    }
    if($product[0]->Image2 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image2, 1);
    }
    if($product[0]->Image3 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image3, 1);
    }
    if($product[0]->Image4 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image4, 1);
    }
    if($product[0]->Image5 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image5, 1);
    }
    if($product[0]->Image6 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image6, 1);
    }
    if($product[0]->Image7 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image7, 1);
    }
    if($product[0]->Image8 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image8, 1);
    }
    
//    mysqli_commit($con);
//    mysqli_close($con);
    
    echo json_encode(array("success"=>true));
    writeToLog("query commit, file: " . basename(__FILE__));
    exit();

    
?>


<?php

    /**
     * Attach images to product (feature/ gallery)
     */
    function attach_product_thumbnail($post_id, $url, $flag)
    {

        /*
         * If allow_url_fopen is enable in php.ini then use this
         */
        $image_url = $url;
        $url_array = explode('/',$url);
        $image_name = $url_array[count($url_array)-1];
        $image_data = file_get_contents($image_url); // Get image data

      /*
       * If allow_url_fopen is not enable in php.ini then use this
       */


      // $image_url = $url;
      // $url_array = explode('/',$url);
      // $image_name = $url_array[count($url_array)-1];

      // $ch = curl_init();
      // curl_setopt ($ch, CURLOPT_URL, $image_url);

      // // Getting binary data
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      // curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

      // $image_data = curl_exec($ch);
      // curl_close($ch);



      $upload_dir = wp_upload_dir(); // Set upload folder
        $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); //    Generate unique name
        $filename = basename( $unique_file_name ); // Create image file name

        // Check folder permission and define file location
        if( wp_mkdir_p( $upload_dir['path'] ) ) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }







        // Create the image file on the server
        file_put_contents( $file, $image_data );

        // Check image file type
        $wp_filetype = wp_check_filetype( $filename, null );

        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name( $filename ),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        // Create the attachment
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

        // Include image.php
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

        // Assign metadata to attachment
        wp_update_attachment_metadata( $attach_id, $attach_data );

        // asign to feature image
        if( $flag == 0){
            // And finally assign featured image to post
            set_post_thumbnail( $post_id, $attach_id );
        }

        // assign to the product gallery
        if( $flag == 1 ){
            // Add gallery image to product
            $attach_id_array = get_post_meta($post_id,'_product_image_gallery', true);
            $attach_id_array .= ','.$attach_id;
            update_post_meta($post_id,'_product_image_gallery',$attach_id_array);
        }
            
    }
    
//
//
//
//    function setConnectionValue($dbName)
//    {
//        global $con;
//        global $con2;
//        global $globalDBName;
//        global $wordPressDB;
//        $host = "localhost";
//        $dbPassword = "123456";
//
//
//        global $url;
//        global $appKey;
//        global $appSecret;
//        global $accessToken;
//
//        global $key;
//        global $partnerID;
//        global $shopID;
//
//        global $appKeyJd;
//        global $appSecretJd;
//        global $accessTokenJd;
//
//
//        if($dbName == "")
//        {
//            $dbName = "MINIMALIST";
//            $globalDBName = $dbName;
//            $dbUser = $dbName;
//        }
//        else
//        {
//            $globalDBName = $dbName;
//            $dbUser = $dbName;
//            if($dbName == "RALAMUSIC")
//            {
//                //web
//                $wordPressDB = "RALAMUSIC";
//
//
//                //LAZADA
//                $url = "https://api.lazada.co.th/rest";
//                $appKey = "119433";
//                $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
//                $accessToken = "50000801a32kMPspe8DFUhLUVchHviwmyToiamtQhxo319b19c16qlna2Wwa6M8v";//ralaTokenStart: 16-08-2020 02:20
//                $refreshToken = "50001800132cSOwTpBCUzHRjJ9pZOrah6GYiLTeTvGWj12d1cfe9bp4PyLkeT59x";//expire in 15544206 ประมาณ16 feb 2021
//
//
//                //shopee
//                $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
//                $partnerID = 845652;
//                $shopID = 1396523;
//
//
//                //jd
//                $appKeyJd = "4f167657105d3afd732653da83fb49a5";
//                $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
//                $accessTokenJd = "64514eb4d73290d0c9974b648058adec";
//
//                $dbUser = "MINIMALIST";
//            }
//            else if( $dbName == "MINIMALISTTEST")
//            {
//                //shopee
//                $key = "adfd6427d69ccda13459756d57acb7f93002e134882c1da29917afdfd094a193";
//                $partnerID = 842613;
//                $shopID = 215964291;
//            }
//            else if( $dbName == "RALAMUSICTEST")
//            {
//                //web
//                $wordPressDB = "RALAMUSIC";
//
//
//                //LAZADA
//                $url = "https://api.lazada.co.th/rest";
//                $appKey = "119433";
//                $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
//                $accessToken = "50000801a32kMPspe8DFUhLUVchHviwmyToiamtQhxo319b19c16qlna2Wwa6M8v";//ralaTokenStart: 16-08-2020 02:20
//                $refreshToken = "50001800132cSOwTpBCUzHRjJ9pZOrah6GYiLTeTvGWj12d1cfe9bp4PyLkeT59x";//expire in 15544206 ประมาณ16 feb 2021
//
//
//                //shopee
////                $key = " e11e4df1f0badd2d79fe2c1ca176a99de792d3b0456cef0dd97bfe6367f4f667";
////                $partnerID = 842997;
////                $shopID = 220004172;
//                $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
//                $partnerID = 845652;
//                $shopID = 1396523;
//
//
//                //jd
//                $appKeyJd = "4f167657105d3afd732653da83fb49a5";
//                $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
//                $accessTokenJd = "64514eb4d73290d0c9974b648058adec";
//
//                $dbUser = "MINIMALIST";
//            }
//            else if( $dbName == "RALAMUSICWEB")
//            {
//                //web
//                $wordPressDB = "ralamusi_2020";
//
//
//                //LAZADA
//                $url = "https://api.lazada.co.th/rest";
//                $appKey = "119433";
//                $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
//                $accessToken = "50000600431yzdabrzgShKANzsTCB0f2kkPgssdBFdS137667fb7ny2CK7k0ATit";//tokenStart: 23-06-2020 20:00
//                $refreshToken = "50001601a31kMPspeBeCQglRxiJGwivmyvjjxCpTEMR16b6facbzssvVaYFvVQXu";//expire in 23-12-2020  20:20
//
//
//                //shopee
////                $key = " e11e4df1f0badd2d79fe2c1ca176a99de792d3b0456cef0dd97bfe6367f4f667";
////                $partnerID = 842997;
////                $shopID = 220004172;
//                $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
//                $partnerID = 845652;
//                $shopID = 1396523;
//
//
//                //jd
//                $appKeyJd = "4f167657105d3afd732653da83fb49a5";
//                $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
//                $accessTokenJd = "64514eb4d73290d0c9974b648058adec";
//
//
//                $dbName = "ralamusi_2020";
//                $dbUser = "ralamusi_2020";
//                $dbPassword = "4p8GzaN8j9";
//                $host = "localhost";
////                $host = "ralamusic.com";
//            }
//        }
//
//
////        $dbUser = "FFD";
//
//        // Create connection
//        $con=mysqli_connect($host,$dbUser,$dbPassword,$dbName);
////        $con2=mysqli_connect($host,$dbUser,$dbPassword,$dbName);
//
//
//        $timeZone = mysqli_query($con,"SET SESSION time_zone = '+07:00'");
//        mysqli_set_charset($con, "utf8");
//        $_POST["modifiedDate"] = date("Y-m-d H:i:s");
//    }
    
    function writeToLog($message)
    {
        $message = "pid: ".getmypid().", ".$message;
        global $globalDBName;
        $mday = getdate()["mday"];
        $day = sprintf("%02d", $mday);
        $mon = getdate()["mon"];
        $month = sprintf("%02d", $mon);
        $year = getdate()["year"];
        
        $fileName = 'saimTransactinLog' . $year . $month . $day . '.log';
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/SAIM/' . $globalDBName . '/TransactionLog/';
        if (!file_exists($filePath))
        {
            mkdir($filePath, 0777, true);
        }
        
        $filePath = $filePath . $fileName;
        
        
        if ($fp = fopen($filePath, 'at'))
        {
            $arrMessage = explode("\\n",$message);
            if(sizeof($arrMessage) > 1)
            {
                foreach($arrMessage as $eachLine)
                {
                    $newMessge .= PHP_EOL . $eachLine ;
                }
            }
            else
            {
                $newMessge = $message;
            }
            
            fwrite($fp, date('c') . ' ' . $newMessge . PHP_EOL);
            fclose($fp);
        }
    }
    
?>
