<?php
    
    require 'vendor/autoload.php';

    use WebPConvert\WebPConvert;

    $source = 'image.jpg';//__DIR__ . '/logo.jpg';
    $destination = $source . '.webp';
    $options = [];
    WebPConvert::convert($source, $destination, $options);

    
    echo "jill";
    
?>
