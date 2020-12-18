<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str,true)["storeName"];
    $username = json_decode($json_str,true)["username"];
    $modifiedUser = json_decode($json_str,true)["modifiedUser"];
 
    
    
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
    
    
    
    $sql = "SELECT distinct menu.* FROM `useraccount` inner join userrole on useraccount.UserAccountID = userrole.UserAccountID inner JOIN rolemenu on userrole.RoleID = rolemenu.RoleID left JOIN menu ON rolemenu.MenuID = menu.MenuID WHERE useraccount.active=1 and useraccount.Username = '$username' and menu.MenuID not in (9) order by menu.orderNo";
    $menuList = executeQueryArray($sql);
    
    
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>true, "menuList"=>$menuList));
    exit();
?>
