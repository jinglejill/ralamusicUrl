<?php
    
    include_once "./phpqrcode/phpqrcode.php";
    
    
    $text = $_GET["text"];
    QRcode::png($text);
?>
