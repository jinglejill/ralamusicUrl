<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str)->storeName;
    $item = json_decode($json_str)->item;
    $insert = json_decode($json_str)->insert;
    $modifiedUser = json_decode($json_str)->modifiedUser;
 
    
    
    setConnectionValue($storeName);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $modifiedUser);
    writeToLog("post json: " . $json_str);
    
    
    
    $userAccountID = $item->UserAccountID;
    $username =  mysqli_real_escape_string($con,$item->Username);
    $password = $item->Password;
    $passwordAgain = $item->PasswordAgain;
    $email = $item->Email;
    $active = $item->Active;
    $roleList = $item->RoleList;
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    if($username == "")
    {
        mysqli_close($con);
        
        echo json_encode(array("success"=>false,"message"=>"กรุณาใส่ Username"));
        exit();
    }
    
    if($insert)
    {
        if($password == "")
        {
            mysqli_close($con);
            
            echo json_encode(array("success"=>false,"message"=>"กรุณาใส่ Password"));
            exit();
        }
    }
    
    if($email == "")
    {
        mysqli_close($con);
        
        echo json_encode(array("success"=>false,"message"=>"กรุณาใส่ Email"));
        exit();
    }
    
    
    
    if($insert)
    {
        //insert
        $sql = "select * from useraccount where username = '$username'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)>0)
        {
            mysqli_close($con);
            
            echo json_encode(array("success"=>false,"message"=>"Username นี้ มีผู้ใช้แล้ว"));
            exit();
        }
        
        if($password != $passwordAgain)
        {
            mysqli_close($con);
            
            echo json_encode(array("success"=>false,"message"=>"Password ทั้ง 2 ตัวไม่ตรงกัน"));
            exit();
        }
        
        $sql = "select * from useraccount where email = '$email'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)>0)
        {
            mysqli_close($con);
            
            echo json_encode(array("success"=>false,"message"=>"Email นี้ มีผู้ใช้แล้ว"));
            exit();
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
          mysqli_close($con);
          
          echo json_encode(array("success"=>false,"message"=>"รูปแบบ Email ไม่ถูกต้อง"));
          exit();
        }
        
        
        $hashPassword = hash('SHA256',$password.$salt);
        $sql = "INSERT INTO `useraccount`(`Username`, `Password`, `Email`, `Active`, `ModifiedUser`) VALUES ('$username','$hashPassword','$email','$active','$modifiedUser')";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $ret["message"] = "เพิ่มผู้ใช้ไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
        $userAccountID  = mysqli_insert_id($con);
        
        
        for($i=0; $i<sizeof($roleList); $i++)
        {
            $roleID = $roleList[$i]->RoleID;
            if($roleList[$i]->Active)
            {
                $sql = "INSERT INTO `userrole`(`UserAccountID`, `RoleID`, `ModifiedUser`) VALUES ('$userAccountID', '$roleID','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $ret["message"] = "เพิ่มผู้ใช้ไม่สำเร็จ";
                    mysqli_close($con);
                    
                    echo json_encode($ret);
                    exit();
                }
            }
        }
    }
    else
    {
        $sql = "select * from useraccount where email = '$email' and userAccountID != '$userAccountID'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)>0)
        {
            mysqli_close($con);
            
            echo json_encode(array("success"=>false,"message"=>"Email นี้ มีผู้ใช้แล้ว"));
            exit();
        }
        
        if($password != "")
        {
            if($password != $passwordAgain)
            {
                mysqli_close($con);
                
                echo json_encode(array("success"=>false,"message"=>"Password ทั้ง 2 ตัวไม่ตรงกัน"));
                exit();
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
              mysqli_close($con);
              
              echo json_encode(array("success"=>false,"message"=>"รูปแบบ Email ไม่ถูกต้อง"));
              exit();
            }
            
            $hashPassword = hash('SHA256',$password.$salt);
            $sql = "update `useraccount` set `Password` = '$hashPassword', `Email` = '$email', `Active`='$active', `ModifiedUser`='$modifiedUser' where username = '$username'";
            $ret = doQueryTask($con,$sql,$modifiedUser);
            if($ret != "")
            {
                $ret["message"] = "แก้ไขผู้ใช้ไม่สำเร็จ";
                mysqli_close($con);
                
                echo json_encode($ret);
                exit();
            }
        }
        else
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
              mysqli_close($con);
              
              echo json_encode(array("success"=>false,"message"=>"รูปแบบ Email ไม่ถูกต้อง"));
              exit();
            }
            
            $sql = "update `useraccount` set `Email` = '$email', `Active`='$active', `ModifiedUser`='$modifiedUser' where username = '$username'";
            $ret = doQueryTask($con,$sql,$modifiedUser);
            if($ret != "")
            {
                $ret["message"] = "แก้ไขผู้ใช้ไม่สำเร็จ";
                mysqli_close($con);
                
                echo json_encode($ret);
                exit();
            }
        }
        
        $sql = "delete from userrole where userAccountID = '$userAccountID'";
        $ret = doQueryTask($con,$sql,$modifiedUser);
        if($ret != "")
        {
            $ret["message"] = "แก้ไขผู้ใช้ไม่สำเร็จ";
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
        
        
        for($i=0; $i<sizeof($roleList); $i++)
        {
            $roleID = $roleList[$i]->RoleID;
            if($roleList[$i]->Active)
            {
                $sql = "INSERT INTO `userrole`(`UserAccountID`, `RoleID`, `ModifiedUser`) VALUES ('$userAccountID', '$roleID','$modifiedUser')";
                $ret = doQueryTask($con,$sql,$modifiedUser);
                if($ret != "")
                {
                    $ret["message"] = "แก้ไขผู้ใช้ไม่สำเร็จ";
                    mysqli_close($con);
                    
                    echo json_encode($ret);
                    exit();
                }
            }
        }
    }
    
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__));
    
    echo json_encode(array("success"=>true));
    exit();
?>
