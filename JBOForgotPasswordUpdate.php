<?php
    $fullPath = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $posLastSlash = strripos($fullPath,'/');
?>
<?php
    include_once("dbConnect.php");
    setConnectionValue("");



    $content_type = $headers["content-type"];
    writeToLog("set contentType: " . $content_type);
    $handle = fopen("php://input", "rb");
    $raw_post_data = '';
    while (!feof($handle)) {
        $raw_post_data .= fread($handle, 8192);
    }
    fclose($handle);


    // parse it
    $data = json_decode($raw_post_data, true);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $data["modifiedUser"]);
    writeToLog("json data: " . json_encode($data));
    
    
//    $branchID = $data["branchID"];
    $username = $data["email"];
    
    $modifiedUser = $data["modifiedUser"];
    $modifiedDate = date("Y-m-d H:i:s");
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    
//    //get current dbName and set connection
//    $sql = "select * from $jummumOM.branch where branchID = '$branchID'";
//    $selectedRow = getSelectedRow($sql);
//    $dbName = $selectedRow[0]["DbName"];
//    setConnectionValue($dbName);
    
    
    
    
    //query statement
    $sql = "select * from $jummumOM.userAccount where username = '$username'";
    /* execute multi query */
    $dataJson = executeMultiQueryArray($sql);
    
    
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) > 0)
    {
        $requestDate = date('Y-m-d H:i:s', time());
        $randomString = generateRandomString();
        $codeReset = password_hash($username . $requestDate . $randomString, PASSWORD_DEFAULT);//
        $emailBody = file_get_contents('./HtmlEmailTemplateForgotPassword.php');
        $emailBody = str_replace("#codereset#",$codeReset,$emailBody);
        $emailBody = str_replace("#jummumFilePath#",substr($fullPath,0,$posLastSlash),$emailBody);
        $emailBody = str_replace("#jummumFilePathMasterFolder#",substr($fullPath,0,$posLastSlash) . "/../$masterFolder",$emailBody);
        
        
        
        $sql = "INSERT INTO $jummumOM.`forgotpassword`(`CodeReset`, `Email`, `RequestDate`, `Status`, `DbName`, `ModifiedUser`, `ModifiedDate`) VALUES ('$codeReset','$username','$requestDate','1','$dbName','$modifiedUser',now())";
        $ret = doQueryTask($sql);
        if($ret != "")
        {
            mysqli_rollback($con);
//            putAlertToDevice();
            echo json_encode($ret);
            exit();
        }
        
        
        sendEmail($username,"Reset password from JUMMUM BO",$emailBody);
    }
    
    
    
    
    //do script successful
    mysqli_commit($con);
    mysqli_close($con);
    
    
    
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
    $response = array('success' => true, 'data' => null, 'error' => null);
    echo json_encode($response);
    exit();
?>
