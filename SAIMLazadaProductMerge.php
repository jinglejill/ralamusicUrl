<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSIC");
    set_time_limit(1200);
    
    
    writeToLog("file: " . basename(__FILE__) . ", user: " . $data["modifiedUser"]);
    printAllPost();

    
    $sql = "delete from lazadaproducttemp;";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        
    }
    
    $sql = "INSERT INTO `lazadaproducttemp`( `PrimaryCategory`, `name`, `name_en`, `short_description`, `short_description_en`, `video`, `brand`, `SellerSku`, `quantity`, `price`, `special_price`, `package_weight`, `package_length`, `package_width`, `package_height`, `MainImage`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8`, `Status`) SELECT `PrimaryCategory`, `name`, `name_en`, `short_description`, `short_description_en`, `video`, `brand`, `SellerSku`, `quantity`, `price`, `special_price`, `package_weight`, `package_length`, `package_width`, `package_height`, `MainImage`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8`, `Status` from lazadaproduct1temp;";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        
    }
    
    $sql = "INSERT INTO `lazadaproducttemp`( `PrimaryCategory`, `name`, `name_en`, `short_description`, `short_description_en`, `video`, `brand`, `SellerSku`, `quantity`, `price`, `special_price`, `package_weight`, `package_length`, `package_width`, `package_height`, `MainImage`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8`, `Status`) SELECT `PrimaryCategory`, `name`, `name_en`, `short_description`, `short_description_en`, `video`, `brand`, `SellerSku`, `quantity`, `price`, `special_price`, `package_weight`, `package_length`, `package_width`, `package_height`, `MainImage`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8`, `Status` from lazadaproduct2temp;";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        
    }
    
    $sql = "INSERT INTO `lazadaproducttemp`( `PrimaryCategory`, `name`, `name_en`, `short_description`, `short_description_en`, `video`, `brand`, `SellerSku`, `quantity`, `price`, `special_price`, `package_weight`, `package_length`, `package_width`, `package_height`, `MainImage`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8`, `Status`) SELECT `PrimaryCategory`, `name`, `name_en`, `short_description`, `short_description_en`, `video`, `brand`, `SellerSku`, `quantity`, `price`, `special_price`, `package_weight`, `package_length`, `package_width`, `package_height`, `MainImage`, `Image2`, `Image3`, `Image4`, `Image5`, `Image6`, `Image7`, `Image8`, `Status` from lazadaproduct3temp;";
    $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
    if($ret != "")
    {
        
    }
    
    
    writeToLog("query commit, file: " . basename(__FILE__));
    exit();
?>


