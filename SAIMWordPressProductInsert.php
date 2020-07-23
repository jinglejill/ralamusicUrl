<?php
    require_once("../wp-load.php");
    include_once('dbConnect.php');
    setConnectionValue("RALAMUSICWEB");
    set_time_limit(300);

        
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
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    
    $sku = $product[0]->Sku;
    $title = $product[0]->Name;//name
    $content = $lazadaProduct->attributes->short_description?$lazadaProduct->attributes->short_description:$lazadaProduct->attributes->name;
    $price = floatval($product[0]->Price);//skus[0]->price
    $salePrice = $lazadaProduct->skus[0]->special_price;
    $stock = intval($product[0]->Quantity);//skus[0]->quantity
    $packageWeight = intval($lazadaProduct->skus[0]->package_weight);//package_weight parseInt
    $packageLength = intval($lazadaProduct->skus[0]->package_length);//package_length parseInt
    $packageWidth = intval($lazadaProduct->skus[0]->package_width);//package_width parseInt
    $packageHeight = intval($lazadaProduct->skus[0]->package_height);//package_height parseInt
    $brand = $product[0]->Brand;
//    $model = $lazadaProduct->attributes->Model;
    $model = str_ireplace($brand."-","",$sku);
    $video = $lazadaProduct->attributes->video;
    $commonTabTitle = str_replace("-"," ",$sku);
    
    $formatSalePrice = number_format($salePrice, 2, '.', ',');
    $formatPrice = number_format($price, 2, '.', ',');
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
        'post_author' => 253,
        'post_content' => $content,
        'post_status' => "publish",
        'post_title' => $title,
        'post_parent' => '',
        'post_type' => "product",
    );

    //Create post
    $post_id = wp_insert_post( $post, $wp_error );
    if($post_id == 0)
    {
        echo json_encode(array("success"=>false));
        exit();
    }
    if(sizeof($webCategoryNameList)>0)
    {
        wp_set_object_terms( $post_id,$webCategoryNameList , 'product_cat' );
    }

    
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
    update_post_meta( $post_id, 'common_tab', $content );
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

    
?>
