<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSICTEST");
    ini_set("memory_limit","50M");
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
    
    writeToLog("pid: ".getmypid());
    sleep(30000);
    exit();
?>


