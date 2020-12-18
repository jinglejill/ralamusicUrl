<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Initialise your autoloader (this example is using Composer)
    require './vendor/autoload.php';
//    require './WebPConvert/WebPConvert.php';
    use WebPConvert\WebPConvert;

    $source = __DIR__ . '/camera.png';
    $destination = $source . '.webp';
    $options = [];
    WebPConvert::convert($source, $destination, $options);
    ?>
