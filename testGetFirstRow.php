<?php

    include_once('dbConnect.php');
    setConnectionValue("RALAMUSICTEST");
    set_time_limit(1200);
    
    
    writeToLog("file: " . basename(__FILE__) . ", user: " . $data["modifiedUser"]);
    printAllPost();

    
    $file = file("2020-07-22.csv");//file_get_contents('./2020-07-22.php');
    foreach($file as $line)
    {
        // do stuff here
        $columnNameList = explode(";",$line);
        break;
    }
    
    
    $strColumnNameList = "";
    $selectColumnNameList = ["name","brand","SellerSku","quantity","price","special_Price","MainImage","Image2","Image3","Image4","Image5","Image6","Image7","Image8","Status"];
    for($i=0; $i<sizeof($columnNameList); $i++)
    {
        $insertColumnName = "";
        $columnName = $columnNameList[$i];
        for($j=0; $j<sizeof($selectColumnNameList); $j++)
        {
            $selectColumnName = $selectColumnNameList[$j];
            if($columnName == $selectColumnName)
            {
                $insertColumnName = "@".$selectColumnName;
                break;
            }
        }
        if($insertColumnName == "")
        {
            $insertColumnName = "@dummy";
        }
        
        if($strColumnNameList == "")
        {
            $strColumnNameList = $insertColumnName;
        }
        else
        {
            $strColumnNameList = $strColumnNameList . "," . $insertColumnName;
        }
    }
    
    $strColumnNameList = "(".$strColumnNameList.")";
    echo $strColumnNameList;
    exit();
?>


