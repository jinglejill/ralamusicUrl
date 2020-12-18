<?php
    include_once('dbConnect.php');
    
    
//    $json_str = file_get_contents('php://input');
//    $storeName = json_decode($json_str)->storeName;
//    $modifiedUser = json_decode($json_str)->modifiedUser;
    
    $storeName = "RALAMUSIC";
    $modifiedUser = "bot";

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
    
  
    $sql = "select * from lazadaProductTemp where date_format(modifiedDate,'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) == 0)
    {
        $sucess = false;
    }
    else
    {
        $sucess = true;
    }
        
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    
    echo json_encode(array("success"=>$sucess));
    exit();
    

?>


