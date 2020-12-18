<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str)->storeName;
    $username = json_decode($json_str)->username;
    $modifiedUser = json_decode($json_str)->modifiedUser;
 
    
    
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
    
    
    
    $sql = "SELECT `UserAccountID`, `Username`, '' `Password`, '' `PasswordAgain`, `Email`, `Active`, `ModifiedUser`, `ModifiedDate` from useraccount where username = '$username'";
    $userList = executeQueryArray($sql);
    $user = $userList[0];
    
    $sql = "select role.*, case when a.RoleID is null then false else true end as Active from role LEFT JOIN (SELECT useraccount.UserAccountID, userrole.RoleID from useraccount LEFT JOIN userrole on useraccount.UserAccountID = userrole.UserAccountID where Username = '$username')a on role.RoleID = a.RoleID order by role.Name;";
    $roleList = executeQueryArray($sql);
    $user->RoleList = $roleList;
    
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>true, "user"=>$user));
    exit();
?>
