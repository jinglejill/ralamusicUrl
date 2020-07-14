<?php
    include_once("dbConnect.php");
    setConnectionValue("RALAMUSIC");
    writeToLog("file: " . basename(__FILE__));
    
    $code = $_GET["code"];
    writeToLog("lazada callback authorization code: " . $code);
    writeToLog("end of lazada callback");
?>
