<?php
    require_once("../wp-load.php");
    set_time_limit(300);
    ini_set('display_errors', 0);
    $modifiedUser="bot";
    $globalDBName="RALAMUSICWEB";
    


    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    

//    $post_id = 70045;
    $sku = "Tc-Electronic-Go-Guitar-Pro";
    $sql = "select * from mainproduct where productid in (6679)";
    $product = executeQueryArray($sql);

    
    $imageDataCorrupt = 0;
    $imageName = preg_replace("/[^\w\-\.]/", '',$sku);
    if($product[0]->MainImage != "")
    {
        attach_product_thumbnail($post_id, $product[0]->MainImage, 0, $imageName);
        attach_product_thumbnail($post_id, $product[0]->MainImage, 1, $imageName.'_1');
    }
    if($product[0]->Image2 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image2, 1, $imageName.'_2');
    }
    if($product[0]->Image3 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image3, 1, $imageName.'_3');
    }
    if($product[0]->Image4 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image4, 1, $imageName.'_4');
    }
    if($product[0]->Image5 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image5, 1, $imageName.'_5');
    }
    if($product[0]->Image6 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image6, 1, $imageName.'_6');
    }
    if($product[0]->Image7 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image7, 1, $imageName.'_7');
    }
    if($product[0]->Image8 != "")
    {
        attach_product_thumbnail($post_id, $product[0]->Image8, 1, $imageName.'_8');
    }
    
    echo $imageDataCorrupt."<br>";
    
//    mysqli_commit($con);
//    mysqli_close($con);
    
    echo json_encode(array("success"=>true));
    writeToLog("query commit, file: " . basename(__FILE__));
    exit();

    
?>


<?php
    
    function executeQueryArray($sql)
    {
        $modifiedUser = 'jill';
        $host = 'localhost';
        $dbUser = 'ralamusi_2020';
        $dbPassword = '4p8GzaN8j9';
        $dbName = 'ralamusi_2020';
        
        $con=mysqli_connect($host,$dbUser,$dbPassword,$dbName);
        
        if ($result = mysqli_query($con, $sql)) {
            $resultArray = array();

            while ($row = mysqli_fetch_object($result)) {
                array_push($resultArray, $row);
            }
            mysqli_free_result($result);
            
            $rowCount = sizeof($resultArray);
            writeToLog("query: row count = $rowCount, sql: " . $sql . ", modified user: " . $modifiedUser);
            return $resultArray;
        }
        else
        {
            writeToLog( "executeQueryArray fail:" . $sql);
        }
        return null;
    }

    /**
     * Attach images to product (feature/ gallery)
     */
    function attach_product_thumbnail($post_id, $url, $flag, $imageName)
    {
        global $imageDataCorrupt;
        /*
         * If allow_url_fopen is enable in php.ini then use this
         */
        $image_url = $url;
        $url_array = explode('/',$url);
        $image_name = $url_array[count($url_array)-1];
        $dataList = explode('.',$image_name);
//        $image_ext = $dataList[count($dataList)-1];
        $image_ext = getImageType($image_url);
        $image_data = file_get_contents($image_url); // Get image data
        for($i=0; $i<10; $i++)
        {
            if(!$image_data)
            {
                $image_data = file_get_contents($image_url);
            }
            else
            {
                break;
            }
        }


        $upload_dir = wp_upload_dir(); // Set upload folder
        $unique_file_name = wp_unique_filename( $upload_dir['path'], $imageName.'.'.$image_ext ); //    Generate unique name
        $filename = basename( $unique_file_name ); // Create image file name

        // Check folder permission and define file location
        if( wp_mkdir_p( $upload_dir['path'] ) ) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
        
        if(!$image_data)
        {
            $imageDataCorrupt++;
        }
        
        

        // Create the image file on the server
        file_put_contents( $file, $image_data );

//        // Check image file type
//        $wp_filetype = wp_check_filetype( $filename, null );
//
//        // Set attachment data
//        $attachment = array(
//            'post_mime_type' => $wp_filetype['type'],
//            'post_title' => sanitize_file_name( $filename ),
//            'post_content' => '',
//            'post_status' => 'inherit'
//        );
//
//        // Create the attachment
//        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
//
//        // Include image.php
//        require_once(ABSPATH . 'wp-admin/includes/image.php');
//
//        // Define attachment metadata
//        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
//
//        // Assign metadata to attachment
//        wp_update_attachment_metadata( $attach_id, $attach_data );
//
//        // asign to feature image
//        if( $flag == 0){
//            // And finally assign featured image to post
//            set_post_thumbnail( $post_id, $attach_id );
//        }
//
//        // assign to the product gallery
//        if( $flag == 1 ){
//            // Add gallery image to product
//            $attach_id_array = get_post_meta($post_id,'_product_image_gallery', true);
//            $attach_id_array .= ','.$attach_id;
//            update_post_meta($post_id,'_product_image_gallery',$attach_id_array);
//        }
    }
  
    
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
  
    function getImageType($filename)
    {
        $image_info = getimagesize($filename);
        if($image_info[2] == 1)
        {
            return "gif";
        }
        else if($image_info[2] == 2)
        {
            return "jpg";
        }
        else if($image_info[2] == 3)
        {
            return "png";
        }
        return "jpg";
    }
?>
