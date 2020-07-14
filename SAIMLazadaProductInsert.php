<?php
    include_once('dbConnect.php');
    
    
    $json_str = file_get_contents('php://input');


    $storeName = json_decode($json_str,true)["storeName"];
    $sku = json_decode($json_str,true)["sku"];
    $insert = json_decode($json_str,true)["insert"];
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
    
  
    if(!$insert)
    {
        $sql = "select * from mainproduct where sku = '$sku'";
        $product = executeQueryArray($sql);
        
        
        $lazadaProduct = getLazadaProduct($sku);
        if(!$lazadaProduct)
        {
            $message = "แก้ไขสินค้าใน Lazada ไม่สำเร็จ";
            sendNotiToAdmin($message);
            
            
            $ret["success"] = false;
            $ret["message"] = $message;
            mysqli_rollback($con);
            mysqli_close($con);

            echo json_encode($ret);
            exit();
        }
        
        //attributes
        $lazadaProduct->Attributes = $lazadaProduct->attributes;
        unset($lazadaProduct->attributes);
        
        $lazadaProduct->Attributes->name = $product[0]->Name;
        $lazadaProduct->Attributes->brand = $product[0]->Brand;
        
        
        //image
        $images = array();//url skus[0]->Images
        
        if($product[0]->MainImage != "")
        {
            $images[] = $product[0]->MainImage;
        }
        if($product[0]->Image2 != "")
        {
            $images[] = $product[0]->Image2;
        }
        if($product[0]->Image3 != "")
        {
            $images[] = $product[0]->Image3;
        }
        if($product[0]->Image4 != "")
        {
            $images[] = $product[0]->Image4;
        }
        if($product[0]->Image5 != "")
        {
            $images[] = $product[0]->Image5;
        }
        if($product[0]->Image6 != "")
        {
            $images[] = $product[0]->Image6;
        }
        if($product[0]->Image7 != "")
        {
            $images[] = $product[0]->Image7;
        }
        if($product[0]->Image8 != "")
        {
            $images[] = $product[0]->Image8;
        }
        
        
        
        
        //skus
        unset($lazadaProduct->skus[0]);
        $lazadaProduct->skus = array("sku"=>array("SellerSku" => $sku,"quantity"=>$product[0]->Quantity,"price"=>$product[0]->Price,"Images"=>$images));
        
        
        $lazadaProduct->Skus = $lazadaProduct->skus;
        unset($lazadaProduct->skus);
        
        
        $lazadaProductAddNode = array("Product"=>$lazadaProduct);
        $xmlPayload = array2xml(json_decode(json_encode($lazadaProductAddNode),true),false);
        
        
        //replace for image
        $xmlPayload = str_replace("<0>","<Image>",$xmlPayload);
        $xmlPayload = str_replace("</0>","</Image>",$xmlPayload);
        $xmlPayload = str_replace("<1>","<Image>",$xmlPayload);
        $xmlPayload = str_replace("</1>","</Image>",$xmlPayload);
        $xmlPayload = str_replace("<2>","<Image>",$xmlPayload);
        $xmlPayload = str_replace("</2>","</Image>",$xmlPayload);
        $xmlPayload = str_replace("<3>","<Image>",$xmlPayload);
        $xmlPayload = str_replace("</3>","</Image>",$xmlPayload);
        $xmlPayload = str_replace("<4>","<Image>",$xmlPayload);
        $xmlPayload = str_replace("</4>","</Image>",$xmlPayload);
        $xmlPayload = str_replace("<5>","<Image>",$xmlPayload);
        $xmlPayload = str_replace("</5>","</Image>",$xmlPayload);
        $xmlPayload = str_replace("<6>","<Image>",$xmlPayload);
        $xmlPayload = str_replace("</6>","</Image>",$xmlPayload);
        $xmlPayload = str_replace("<7>","<Image>",$xmlPayload);
        $xmlPayload = str_replace("</7>","</Image>",$xmlPayload);
        
        
        
//        echo $xmlPayload;
        
        
        $c = new LazopClient($url,$appKey,$appSecret);
        $request = new LazopRequest('/product/update','POST');
        $request->addApiParam('payload',$xmlPayload);
        $resp = $c->execute($request, $accessToken);
        $respObject = json_decode($resp);
        if($respObject->code != 0)
        {
            $message = "แก้ไขสินค้าใน Lazada ไม่สำเร็จ";
            sendNotiToAdmin($message);
            
            $ret["success"] = false;
            $ret["message"] = $message;
            mysqli_rollback($con);
            mysqli_close($con);

            echo json_encode($ret);
            exit();
        }
//        echo $resp;
        
        
        mysqli_commit($con);
        mysqli_close($con);
        writeToLog("query commit, file: " . basename(__FILE__));
        
        echo json_encode(array("success"=>true));
        exit();
    }

    
    
    function array2xml($array, $xml = false)
    {
        if($xml === false)
        {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Request/>');
        }

        foreach($array as $key => $value){
            if(is_array($value)){
                array2xml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml->asXML();
    }

?>


