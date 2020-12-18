<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSICTEST");
    ini_set("memory_limit","50M");
    writeToLog("file: " . basename(__FILE__));
    printAllPost();
    
//    sleep(10);
//    $str = exec('start /B c:\runNotepad.bat');
//    mysqli_close($con);
//    system('cmd.exe /c C:\Inetpub\vhosts\minimalist.co.th\httpdocs\SAIM\testRunBat.bat');
    shell_exec('start calc');
    exit();
?>


