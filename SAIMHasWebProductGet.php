<?php
//    include_once('dbConnect.php');
    $con;
    $con2;
    $modifiedUser="bot";
    $globalDBName="RALAMUSICWEB";
    $wordPressDB = "";
    setConnectionValue("RALAMUSICWEB");
    
    
    $json_str = file_get_contents('php://input');
    writeToLog("has web product json: " . $json_str);
    
    
    $json_obj = json_decode($json_str);
    
    $sku = $json_obj->sku;
//    $sku = $_GET["sku"];
    $ret = hasWebProductInRalaWeb($sku);
    echo json_encode(array("success"=>$ret));
    
    exit();

    function setConnectionValue($dbName)
    {
        global $con;
        global $con2;
        global $globalDBName;
        global $wordPressDB;
        $host = "localhost";
        $dbPassword = "123456";


        if($dbName == "")
        {
            $dbName = "MINIMALIST";
            $globalDBName = $dbName;
            $dbUser = $dbName;
        }
        else
        {
            $globalDBName = $dbName;
            $dbUser = $dbName;
            if($dbName == "RALAMUSIC")
            {
                //web
                $wordPressDB = "RALAMUSIC";


                //LAZADA
                $url = "https://api.lazada.co.th/rest";
                $appKey = "119433";
                $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
                $accessToken = "50000801a32kMPspe8DFUhLUVchHviwmyToiamtQhxo319b19c16qlna2Wwa6M8v";//ralaTokenStart: 16-08-2020 02:20
                $refreshToken = "50001800132cSOwTpBCUzHRjJ9pZOrah6GYiLTeTvGWj12d1cfe9bp4PyLkeT59x";//expire in 15544206 ประมาณ16 feb 2021


                //shopee
                $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
                $partnerID = 845652;
                $shopID = 1396523;


                //jd
                $appKeyJd = "4f167657105d3afd732653da83fb49a5";
                $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
                $accessTokenJd = "64514eb4d73290d0c9974b648058adec";

                $dbUser = "MINIMALIST";
            }
            else if( $dbName == "MINIMALISTTEST")
            {
                //shopee
                $key = "adfd6427d69ccda13459756d57acb7f93002e134882c1da29917afdfd094a193";
                $partnerID = 842613;
                $shopID = 215964291;
            }
            else if( $dbName == "RALAMUSICTEST")
            {
                //web
                $wordPressDB = "RALAMUSIC";


                //LAZADA
                $url = "https://api.lazada.co.th/rest";
                $appKey = "119433";
                $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
                $accessToken = "50000801a32kMPspe8DFUhLUVchHviwmyToiamtQhxo319b19c16qlna2Wwa6M8v";//ralaTokenStart: 16-08-2020 02:20
                $refreshToken = "50001800132cSOwTpBCUzHRjJ9pZOrah6GYiLTeTvGWj12d1cfe9bp4PyLkeT59x";//expire in 15544206 ประมาณ16 feb 2021


                //shopee
//                $key = " e11e4df1f0badd2d79fe2c1ca176a99de792d3b0456cef0dd97bfe6367f4f667";
//                $partnerID = 842997;
//                $shopID = 220004172;
                $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
                $partnerID = 845652;
                $shopID = 1396523;


                //jd
                $appKeyJd = "4f167657105d3afd732653da83fb49a5";
                $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
                $accessTokenJd = "64514eb4d73290d0c9974b648058adec";

                $dbUser = "MINIMALIST";
            }
            else if( $dbName == "RALAMUSICWEB")
            {
                //web
                $wordPressDB = "ralamusi_2020";


                //LAZADA
                $url = "https://api.lazada.co.th/rest";
                $appKey = "119433";
                $appSecret = "UXRPIrSZfCwKBhm9jR4rdgprOdMVHXKs";
                $accessToken = "50000600431yzdabrzgShKANzsTCB0f2kkPgssdBFdS137667fb7ny2CK7k0ATit";//tokenStart: 23-06-2020 20:00
                $refreshToken = "50001601a31kMPspeBeCQglRxiJGwivmyvjjxCpTEMR16b6facbzssvVaYFvVQXu";//expire in 23-12-2020  20:20


                //shopee
//                $key = " e11e4df1f0badd2d79fe2c1ca176a99de792d3b0456cef0dd97bfe6367f4f667";
//                $partnerID = 842997;
//                $shopID = 220004172;
                $key = "bc7b1996a69b9384eef86490a752d0c5388febc168b8aa0b3ed86066d5f0ba05";
                $partnerID = 845652;
                $shopID = 1396523;


                //jd
                $appKeyJd = "4f167657105d3afd732653da83fb49a5";
                $appSecretJd = "7789c6f973f9c3ad22c1206c97382663";
                $accessTokenJd = "64514eb4d73290d0c9974b648058adec";


                $dbName = "ralamusi_2020";
                $dbUser = "ralamusi_2020";
                $dbPassword = "4p8GzaN8j9";
                $host = "localhost";
//                $host = "ralamusic.com";
            }
        }


//        $dbUser = "FFD";

        // Create connection
        $con=mysqli_connect($host,$dbUser,$dbPassword,$dbName);
//        $con2=mysqli_connect($host,$dbUser,$dbPassword,$dbName);


        $timeZone = mysqli_query($con,"SET SESSION time_zone = '+07:00'");
        mysqli_set_charset($con, "utf8");
        $_POST["modifiedDate"] = date("Y-m-d H:i:s");
    }

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


    function hasWebProductInRalaWeb($sku)
    {
        global $wordPressDB;
        global $con;


        $productSku = mysqli_real_escape_string($con,$sku);

        //variation
        $sql = "SELECT product.ID FROM $wordPressDB.`wp_posts` as product LEFT JOIN $wordPressDB.wp_postmeta as product_sku ON product.ID = product_sku.post_ID WHERE product_sku.meta_key = '_sku' and product_sku.meta_value = '$productSku'";// and product.post_status = 'publish'
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function getSelectedRow($sql)
    {
        global $con;
        global $modifiedUser;
        $resultArray = array();
        $tempArray = array();
        
        if ($result = mysqli_query($con, $sql))
        {
            while($row = mysqli_fetch_array($result))
            {
                $tempArray = $row;
                array_push($resultArray, $tempArray);
            }
            mysqli_free_result($result);
        }
        if(sizeof($resultArray) == 0)
        {
            $error = "query: selected row count = 0, sql: " . $sql . ", modified user: " . $modifiedUser;
            writeToLog($error);
        }
        else
        {
            writeToLog("query success, sql: " . $sql . ", modified user: " . $modifiedUser);
        }
        
        return $resultArray;
    }
?>


