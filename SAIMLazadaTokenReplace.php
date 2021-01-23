
<?php
    include('dbConnect.php');
    setConnectionValue("RALAMUSIC");

    $ret = replaceLazadaAccessToken();
    $expiredDate = date('Y/m/d h:i:s a', time() + $ret->refresh_expires_in);
    
    $time = $ret->refresh_expires_in;
    $hours = floor($time / (60*60));
    $minutes = ($time % (60*60));
    $days = floor($hours / 24);
    
    if($days > 7)
    {
        echo 'expire in '.$days. ' days';
    }
    else
    {
        echo '************ expire in '.$days. ' days ************';
    }
    
    exit();
?>
