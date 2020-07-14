<?php
    include_once('dbConnect.php');
    set_time_limit(300);


    $json_str = file_get_contents('php://input');
    $storeName = json_decode($json_str)->storeName;
    $searchText = json_decode($json_str)->searchText;
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $data["modifiedUser"]);
    writeToLog("post json: " . $json_str);
    writeToLog("userAgent: ".$_SERVER['HTTP_USER_AGENT']);
    printAllGet();
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
   
    
    
    //*****************************
    $sql = "select distinct Brand from mainproduct where brand like '$searchText%' order by modifiedDate desc limit 5";
    $brandList = executeQueryArray($sql);
    
    
    
    
    
    $data = array("brands"=>$brandList);
    writeToLog("brandSearchGetList: " . $data );
    echo json_encode($data);
    
    
    //-----
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    
    
    exit();
?>
