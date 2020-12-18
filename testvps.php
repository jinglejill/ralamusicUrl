<?php
    ini_set("memory_limit","50M");
    set_time_limit(3000);

    $startRow = $_GET["startRow"];
    $endRow = $_GET["endRow"];
    $sql = "select Sku from websku where webskuid between $startRow and $endRow";
    $webSku = executeQueryArray($sql);
    
    
    
    $listFileCorrupt = $_GET["listFileCorrupt"];
//    $filenameList = ["Yamaha-F600"];
    $filenameList = array();
    for($i=0; $i<sizeof($webSku); $i++)
    {
//        $filenameList[] = "Paramount-NBK";
        $filenameList[] = $webSku[$i]->Sku;
    }
    
    $monthFolder = $_GET["monthFolder"];
    $dir = "./wp-content/uploads/2020/$monthFolder/";
    $files1 = scandir($dir);
    
    
    $imageFileList = array();
    for($i=0; $i < sizeof($filenameList); $i++)
    {
        $findme = $filenameList[$i];
        for($j=2; $j < sizeof($files1); $j++)
        {
            if(strpos($files1[$j], $findme) !== false)
            {
                if($listFileCorrupt)
                {
                    //list file corrupt
                    $filename = $dir.$files1[$j];
                    $image_info = getimagesize($filename);
                    if(!$image_info)
                    {
                        $imageFileList[] = $files1[$j];
                    }
                    //*****
                }
                else
                {
                    //list all file in sku
                    $imageFileList[] = $files1[$j];
                }
            }
        }
    }
    echo sizeof($imageFileList) . "<br>";
    echo json_encode($imageFileList) . "<br>";
    if($listFileCorrupt)
    {
        if($_GET["removeFileCorrupt"])
        {
            for($i=0; $i < sizeof($imageFileList); $i++)
            {
                $filename = $dir.$imageFileList[$i];
                unlink($filename);
            }
        }
        
    }
    else
    {
        //remove imageFile
        for($i=0; $i < sizeof($imageFileList); $i++)
        {
            $filename = $dir.$imageFileList[$i];
            unlink($filename);
        }
        
    }
    
    if($_GET["listCorruptSku"])
    {
        //    //list sku that corrupt
        for($i=0; $i < sizeof($imageFileList); $i++)
        {
            $imageFileList[$i] = str_replace("_1.jpg","",$imageFileList[$i]);
            $imageFileList[$i] = str_replace("_2.jpg","",$imageFileList[$i]);
            $imageFileList[$i] = str_replace("_3.jpg","",$imageFileList[$i]);
            $imageFileList[$i] = str_replace("_4.jpg","",$imageFileList[$i]);
            $imageFileList[$i] = str_replace("_5.jpg","",$imageFileList[$i]);
            $imageFileList[$i] = str_replace("_6.jpg","",$imageFileList[$i]);
            $imageFileList[$i] = str_replace("_7.jpg","",$imageFileList[$i]);
            $imageFileList[$i] = str_replace("_8.jpg","",$imageFileList[$i]);
            $imageFileList[$i] = str_replace(".jpg","",$imageFileList[$i]);
        }
        $corruptSku = array_values( array_unique($imageFileList));
        echo sizeof($corruptSku) . "<br>";
        echo json_encode($corruptSku);
    }
    exit();
    
    
    
    
    
    

    
    

    
    
    //list filenames that corrupt
    $dir = "./wp-content/uploads/2020/10/";
    $files1 = scandir($dir);
    
    echo sizeof($files1) . "<br>";
//    exit();
    
    $start = $_GET["start"];
    $skip = $_GET["skip"];
    $filenameList = array();
    for($i=2; $i<sizeof($files1) ; $i++)//
    {
        if(startsWith($files1[$i],$start))
        {
            $filename = $dir.$files1[$i];
            $image_info = getimagesize($filename);
            if(!$image_info)
            {
                $filenameList[] = $files1[$i];
            }
         
        }
        
//        else if(startsWith($files1[$i],$skip))
//        {
//            break;
//        }
    }
    echo sizeof($filenameList). "<br>";
//    echo json_encode($filenameList);
//    exit();
    
    
    //327240
//    327320

    
    //    //list sku that corrupt
    for($i=0; $i < sizeof($filenameList); $i++)
    {
        $filenameList[$i] = str_replace("_1.jpg","",$filenameList[$i]);
        $filenameList[$i] = str_replace("_2.jpg","",$filenameList[$i]);
        $filenameList[$i] = str_replace("_3.jpg","",$filenameList[$i]);
        $filenameList[$i] = str_replace("_4.jpg","",$filenameList[$i]);
        $filenameList[$i] = str_replace("_5.jpg","",$filenameList[$i]);
        $filenameList[$i] = str_replace("_6.jpg","",$filenameList[$i]);
        $filenameList[$i] = str_replace("_7.jpg","",$filenameList[$i]);
        $filenameList[$i] = str_replace("_8.jpg","",$filenameList[$i]);
        $filenameList[$i] = str_replace(".jpg","",$filenameList[$i]);
    }
    echo json_encode(array_values( array_unique($filenameList)));
    exit();
    
    
    function startsWith($string, $startString) {
      $len = strlen($startString);
      return (substr($string, 0, $len) === $startString);
    }
?>


<?php
    
    function executeQueryArray($sql)
    {
        $modifiedUser = 'jill';
        $host = 'localhost';
        $dbUser = 'ralamusi_2020';
        $dbPassword = '4p8GzaN8j9';
        $dbName = 'ralamusi_2020';
        
        $con=mysqli_connect($host,$dbUser,$dbPassword,$dbName);
        
        if ($result = mysqli_query($con, $sql)) {
            $resultArray = array();

            while ($row = mysqli_fetch_object($result)) {
                array_push($resultArray, $row);
            }
            mysqli_free_result($result);
            
            $rowCount = sizeof($resultArray);
            writeToLog("query: row count = $rowCount, sql: " . $sql . ", modified user: " . $modifiedUser);
            return $resultArray;
        }
        else
        {
            writeToLog( "executeQueryArray fail:" . $sql);
        }
        return null;
    }
    
    
    function writeToLog($message)
    {
        $message = "pid: ".getmypid().", ".$message;
        $globalDBName = "RALAMUSICWEB";
        $mday = getdate()["mday"];
        $day = sprintf("%02d", $mday);
        $mon = getdate()["mon"];
        $month = sprintf("%02d", $mon);
        $year = getdate()["year"];
        
        $fileName = 'saimTransactinLog' . $year . $month . $day . '.log';
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/SAIM/' . $globalDBName . '/TransactionLog/';
        if (!file_exists($filePath))
        {
            mkdir($filePath, 0777, true);
        }
        
        $filePath = $filePath . $fileName;
        
        
        if ($fp = fopen($filePath, 'at'))
        {
            $arrMessage = explode("\\n",$message);
            if(sizeof($arrMessage) > 1)
            {
                foreach($arrMessage as $eachLine)
                {
                    $newMessge .= PHP_EOL . $eachLine ;
                }
            }
            else
            {
                $newMessge = $message;
            }
            
            fwrite($fp, date('c') . ' ' . $newMessge . PHP_EOL);
            fclose($fp);
        }
    }
    ?>


