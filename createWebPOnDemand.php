<?php
    
    // Initialise your autoloader (this example is using Composer)
    require 'vendor/autoload.php';

    use WebPConvert\WebPConvert;

    
    $globalDBName = "ralamusicweb";
    writeToLog($_GET['path']);
    $regEx = '/.(jpe?g|png)$/';
    preg_match_all($regEx,$path,$m);
    $founds = $m[0];
    if(sizeof($founds)>0)
    {
        $destination = str_replace($founds[0],'.webp',$path);
    }
    
    if(!file_exists($destination))
    {
        $options = [];
        WebPConvert::convert($path, $destination, $options);
    }
    writeToLog("destination:".$destination);
    
//    $source = ".".$_GET['path'];
//    $destination = $source . '.webp';
//    $options = [];
//    WebPConvert::convert($source, $destination, $options);
//
//    //writeToLog("path:".$_GET['path']);
//
    header('Content-Type: image/webp');
    readfile($destination);
    
    function writeToLog($message)
    {
        $message = "pid: ".getmypid().", ".$message;
        global $globalDBName;
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

