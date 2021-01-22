<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str,true)["storeName"];
    $sku = json_decode($json_str,true)["sku"];
    $index = json_decode($json_str,true)["index"];
    $modifiedUser = json_decode($json_str,true)["modifiedUser"];
    
    
    
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
    
    
    //main product
    $escapeSku = mysqli_real_escape_string($con,$sku);
    $sql = "select * from mainProduct where sku = '$escapeSku'";
    $productList = executeQueryArray($sql);
    $product = $productList[0];
    
    
    if($index == 1)
    {
        $filename = $product->MainImage;
    }
    else if($index == 2)
    {
        $filename = $product->Image2;
    }
    else if($index == 3)
    {
        $filename = $product->Image3;
    }
    else if($index == 4)
    {
        $filename = $product->Image4;
    }
    else if($index == 5)
    {
        $filename = $product->Image5;
    }
    else if($index == 6)
    {
        $filename = $product->Image6;
    }
    else if($index == 7)
    {
        $filename = $product->Image7;
    }
    else if($index == 8)
    {
        $filename = $product->Image8;
    }
//    $filename = 'https://th-live.slatic.net/p/bdf3889ba05b10ed5d55b93a05675a55.png';
    $percent = 0.8;


    // Get new sizes
    list($width, $height) = getimagesize($filename);
    $newwidth = $width * $percent;
    $newheight = $height * $percent;

    // Load
    $newImage = imagecreatetruecolor($newwidth, $newheight);
    $imageType = getImageType($filename);
    $newFileName = '.\\'.$storeName.'\\Images\\'.$sku.'-'.$index.'.'.$imageType;
    $newImageUrl = $appImageUrl .'/' . $storeName . '/Images/'.$sku.'-'.$index.'.'.$imageType;
    if($imageType == 'png')
    {
        // integer representation of the color black (rgb: 0,0,0)
        $background = imagecolorallocate($newImage , 0, 0, 0);
        // removing the black from the placeholder
        imagecolortransparent($newImage, $background);

        // turning off alpha blending (to ensure alpha channel information
        // is preserved, rather than removed (blending with the rest of the
        // image in the form of black))
        imagealphablending($newImage, false);

        // turning on alpha channel information saving (to ensure the full range
        // of transparency is preserved)
        imagesavealpha($newImage, true);
    }
    
    if($imageType == "png")
    {
        $source = imagecreatefrompng($filename);
        // Resize
        imagecopyresized($newImage, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // Output
        imagepng($newImage,$newFileName);
    }
    else if($imageType == "jpg" || $imageType == "jpeg")
    {
        $source = imagecreatefromjpeg($filename);
        // Resize
        imagecopyresized($newImage, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // Output
        imagejpeg($newImage,$newFileName);
    }
    else if($imageType == "gif")
    {
        $source = imagecreatefromgif($filename);
        // Resize
        imagecopyresized($newImage, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // Output
        imagegif($newImage,$newFileName);
    }

    
    if($index == 1)
    {
        $updateImageUrl = "MainImage = '$newImageUrl'";
    }
    else
    {
        $updateImageUrl = "Image" . $index . " = '$newImageUrl'";
    }
    
    $sql = "update mainproduct set $updateImageUrl where sku = '$escapeSku'";
    $ret = doQueryTask($con,$sql,$modifiedUser);
    if($ret != "")
    {
        $ret["message"] = "ลดขนาดรูปภาพไม่สำเร็จ";
        mysqli_close($con);
        
        echo json_encode($ret);
        exit();
    }
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>true,"index"=>$index,"imageUrl"=>$newImageUrl));
    exit();
?>
