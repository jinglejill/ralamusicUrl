<?php
     // How to Generate CSV File from Array in PHP Script       
     $results = array (
          "0" => array(
               "name"           => "Anna Smith",
               "email_id"      => "annabsmith@inbound.plus"
          ),
          "1" => array(
               "name"           => "Johnny Huck",
               "email_id" => "johnnyohuck@inbound.plus"
          )
     );
    $fp = fopen('file.csv', 'w');
     $header = array_keys($results[0]);
     fputcsv($fp, $header);
     foreach($results as $row)
     {
          fputcsv($fp, $row);
     }
     fclose($fp);
?>
