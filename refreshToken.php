<?php
    include_once "./lazada/LazopSdk.php";
    $globalDBName = "RALAMUSIC";
    $lineAdminToken = "nYfj6oMyVaJDSg8QQzGivPXDJMPzXwMI837Egr2gZED";
    
    
    
    $url = "https://api.lazada.co.th/rest";
    $appKey = "119433";
    $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
    $accessToken = "50000600319bSIoacUNFRjijr1QSCay162f4243o1fHwavpHAeDt6r0TH94dvlVr";//ralaTokenStart: 17-07-2020 11:45
    $refreshToken = "50001601a31kMPspeBeCQglRxiJGwivmyvjjxCpTEMR16b6facbzssvVaYFvVQXu";//expire in 23-12-2020  20:20


    $c = new LazopClient($url,$appKey,$appSecret);
    $request = new LazopRequest('/auth/token/refresh');
    $request->addApiParam('refresh_token',$refreshToken);
    $resp = $c->execute($request);

    $respObject = json_decode($resp);
    $newAccessToken = $respObject->access_token;
    $newRefreshToken = $respObject->refresh_token;
    $refreshExpiresIn = $respObject->refresh_expires_in;
    writeToLog("accessToken:" .  $respObject->access_token);
    writeToLog("refreshToken:" .  $respObject->refresh_token);
    
    
    $path_to_file = './dbConnect.php';
    $file_contents = file_get_contents($path_to_file);
    $file_contents = str_replace($accessToken,$newAccessToken,$file_contents);
    $file_contents = str_replace($refreshToken,$newRefreshToken,$file_contents);
    
    //ralaTokenStart: 19/06/2020 07:20
    $regEx = "/ralaTokenStart: [\\d]{2}-[\\d]{2}-[\\d]{4} [\\d]{2}:[\\d]{2}/";
    preg_match_all($regEx,$file_contents,$m);
    $founds = $m[0];
    $currentDateTime = date("d-m-Y H:i");
    $tokenComment = "ralaTokenStart: ".$currentDateTime;
//    echo sizeof($founds);
//    exit();
    for($i=0; $i<sizeof($founds); $i++)
    {
        $file_contents = str_replace($founds[$i],$tokenComment,$file_contents);
    }
    
    file_put_contents($path_to_file,$file_contents);
//    file_put_contents('./dbConnect2.php',$file_contents);
    
    
    
    if($refreshExpiresIn < 604800)
    {
        sendNotiToAdmin("refresh access token will expire in ".$refreshExpiresIn/3600 . " hours");
    }
    
    function sendNotiToAdmin($sMessage)
    {
        global $lineAdminToken;
        
        sendNotifyToDevice($lineAdminToken,$sMessage);
    }
    
    function sendNotifyToDevice($lineNotifyToken,$sMessage)
    {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(E_ALL);
        date_default_timezone_set("Asia/Bangkok");


        $sToken = $lineNotifyToken;
//        $sToken = "UHpcdJ6MfMVkN3FBpKEyiapJjuUkKLDB3SWdCQLS1DL";
//        $sMessage = "ทดสอบ reminder stock to testReminderStock Group....";

        
        $chOne = curl_init();
        curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt( $chOne, CURLOPT_POST, 1);
        curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage);
        $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
        curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec( $chOne );

        //Result error
        if(curl_error($chOne))
        {
            writeToLog('error:' . curl_error($chOne));
        }
        else {
            $result_ = json_decode($result, true);
            writeToLog( "status : ".$result_['status']);
            writeToLog( "message : ". $result_['message']);
        }
        curl_close( $chOne );
    }

    function writeToLog($message)
    {
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
