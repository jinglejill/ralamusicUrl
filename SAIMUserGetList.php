<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str,true)["storeName"];
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
    
    
    
    $sql = "select * from (select Username, ifnull(Role,'') as Role from useraccount left join (select case when @currentUserAccountID != a.UserAccountID then @role:= '' end as RoleVariable , @role:=case when @role = '' then a.Name else concat(@role,', ',a.Name) end as Role,@currentUserAccountID:=a.UserAccountID as CurrentUserAccountID, a.UserAccountID from (select Name, userrole.UserAccountID from userrole LEFT JOIN role on userrole.RoleID = role.RoleID order by userrole.UserAccountID,role.RoleID)a,(select @role:='',@currentUserAccountID:=0)b)c on c.userAccountID = useraccount.UserAccountID order by Username, Role desc)d GROUP by Username;;";
    $userList = executeQueryArray($sql);
    
    
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>true, "userList"=>$userList));
    exit();
?>
