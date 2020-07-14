{
        //insert*********************************************************
        $lazadaProduct = getLazadaProduct($sku);
        
        
        $sql = "select * from mainproduct where sku = '$sku'";
        $product = executeQueryArray($sql);
        $primaryCategory = $product[0]->PrimaryCategory;
//        echo json_encode($product);
//        exit();
        
        $sql = "select * from categoryMapping where lazadaCategoryID = '$primaryCategory'";
        $selectedRow = getSelectedRow($sql);
        $attributesProduct = array();//id, value(options)
        if(sizeof($selectedRow) > 0)
        {
            $shopeeCategoryID = $selectedRow[0]["ShopeeCategoryID"];
            $attributes = getShopeeAttributes(intval($shopeeCategoryID));
//            echo json_encode($attributes);
//            exit();
            
            for($i=0; $i<sizeof($attributes); $i++)//rala ส่วนมากมี 1 attribute_id
            {
                $attribute = $attributes[$i];
                
                $foundAttribute = false;
                for($j=0; $j<sizeof($attribute->options); $j++)
                {
                    $option = $attribute->options[$j];
                    if(strpos($option, $lazadaProduct->attributes->brand) !== false)
                    {
                        $foundAttribute = true;
                        $attributeValue = $option;
                        break;
                    }
                }
                
                if($foundAttribute)
                {
                    $attribute1 = array("attributes_id"=>$attribute->attribute_id,"value"=>$attributeValue);//search brand มาใส่ หากไม่เจอ ให้เลือก no brand
                    $attributesProduct[] = $attribute1;
                }
                else
                {
                    $attribute1 = array("attributes_id"=>$attribute->attribute_id,"value"=>$attribute->options[0]);//search brand มาใส่ หากไม่เจอ ให้เลือก no brand
                    $attributesProduct[] = $attribute1;
                }
            }
        }
        else
        {
            $shopeeCategoryID = $defaultCategoryID;
            
            
            $attribute1 = array("attributes_id"=>$defaultAttributeID,"value"=>$defaultAttributeValue);//search brand มาใส่ หากไม่เจอ ให้เลือก no brand
            $attributesProduct[] = $attribute1;
        }

        
        //sku 3531-1575336982582-0
        
        
        
        
        $categoryId = intval($shopeeCategoryID);
        $name = $product[0]->Name;//name
        $description = $lazadaProduct->attributes->short_description?$lazadaProduct->attributes->short_description:$lazadaProduct->attributes->name;//short_description parse html tag out (<ul>,<li>,\r,\t)
        $description = str_replace('<ul>','',$description);
        $description = str_replace('<li>','',$description);
        $description = str_replace('\r','',$description);
        $description = str_replace('\t','',$description);
        $price = floatval($product[0]->Price);//skus[0]->price
        $stock = intval($product[0]->Quantity);//skus[0]->quantity
        $itemSku = $product[0]->Sku;//skus[0]->SellerSku
        $weight = intval($lazadaProduct->skus[0]->package_weight);//package_weight parseInt
        $packageLength = intval($lazadaProduct->skus[0]->package_length);//package_length parseInt
        $packageWidth = intval($lazadaProduct->skus[0]->package_width);//package_width parseInt
        $packageHeight = intval($lazadaProduct->skus[0]->package_height);//package_height parseInt
        $status = "UNLIST";//NORMAL, UNLIST
        $daysToShip = 2;
        $isPreOrder = false;
        $condition = "NEW";
        $sizeChart = "";
        $images = array();//url skus[0]->Images
        
        if($product[0]->MainImage != "")
        {
            $image1 = array("url"=>$product[0]->MainImage);
            $images[] = $image1;
        }
        if($product[0]->Image2 != "")
        {
            $image2 = array("url"=>$product[0]->Image2);
            $images[] = $image2;
        }
        if($product[0]->Image3 != "")
        {
            $image3 = array("url"=>$product[0]->Image3);
            $images[] = $image3;
        }
        if($product[0]->Image4 != "")
        {
            $image4 = array("url"=>$product[0]->Image4);
            $images[] = $image4;
        }
        if($product[0]->Image5 != "")
        {
            $image5 = array("url"=>$product[0]->Image5);
            $images[] = $image5;
        }
        if($product[0]->Image6 != "")
        {
            $image6 = array("url"=>$product[0]->Image6);
            $images[] = $image6;
        }
        if($product[0]->Image7 != "")
        {
            $image7 = array("url"=>$product[0]->Image7);
            $images[] = $image7;
        }
        if($product[0]->Image8 != "")
        {
            $image8 = array("url"=>$product[0]->Image8);
            $images[] = $image8;
        }
        
        
        $logistics = array();//logisticId, enabled
        $logistic = array("logistic_id"=>70021,"enabled"=>true,"estimated_shipping_fee"=>39,"is_free"=>true,"logistic_name"=>"Kerry");
        $logistics[] = $logistic;
   
       
    //    $variations = array();





        //create curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);


        //url
        $url = "https://partner.shopeemobile.com/api/v1/item/add";
        curl_setopt($ch, CURLOPT_URL, $url);


        //param
        $date = new DateTime();
        $timestamp = $date->getTimestamp();


        //payload
        $paramBody = array();
        $paramBody["partner_id"] = $partnerID;
        $paramBody["shopid"] = $shopID;
        $paramBody["timestamp"] = $timestamp;
        $paramBody["category_id"] = $categoryId;
        $paramBody["name"] = $name;
        $paramBody["description"] = $description;
        $paramBody["price"] = $price;
        $paramBody["stock"] = $stock;
        $paramBody["item_sku"] = $itemSku;
        $paramBody["weight"] = $weight;
        $paramBody["package_length"] = $packageLength;
        $paramBody["package_width"] = $packageWidth;
        $paramBody["package_height"] = $packageHeight;
        $paramBody["status"] = $status;
        $paramBody["days_to_ship"] = $daysToShip;
        $paramBody["is_pre_order"] = $isPreOrder;
        $paramBody["condition"] = $condition;
        $paramBody["size_chart"] = $sizeChart;
        $paramBody["images"] = $images;
        $paramBody["logistics"] = $logistics;
        $paramBody["attributes"] = $attributesProduct;
    //    $paramBody["variations"] = $variations;

//        echo json_encode($paramBody);
//        exit();

        $payload = json_encode($paramBody);
        writeToLog("payload:" . $payload);


        $contentLength = strlen($payload);
        $authorization = hash_hmac('sha256', $url . "|" .  $payload, $key);


        //header
        $header = array();
        $header[] = 'Host:' . $host;
        $header[] = 'Content-Type:' . $contentType;
        $header[] = 'Content-Length:' . $contentLength;
        $header[] = 'Authorization:' . $authorization;
        $header[] = 'charset:' . $charSet;
        writeToLog("header:" . json_encode($header));


        //set header and payload
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

    //    echo "test ";
    //    exit();

        //exec curl
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($ch);
        if ($http_status==503)
        {
            writeToLog( "HTTP Status == 503)");
        }

        if ($result === false)
        {
            print_r('Curl error: ' . curl_error($ch));
            writeToLog( "Curl Errno returned $curl_errno");
        }


        writeToLog("add shopee item result: " . $result);
//        echo $result;
        $obj = json_decode($result);
        if($obj->item_id)
        {
            $itemID = $obj->item_id;
            //insert into shopeeProduct
            $sql = "insert into shopeeProduct (itemID,sku,modifiedUser) values('$itemID','$sku','$modifiedUser')";
            $ret = doQueryTask($con,$sql,$_POST["modifiedUser"]);
            if($ret != "")
            {
                $message = "เพิ่ม Shopee sku ในแอปไม่สำเร็จ";
                sendNotiToAdmin($message);
                $ret["message"] = $message;
                mysqli_close($con);

                echo json_encode($ret);
                exit();
            }
        }
        else
        {
            //insert fail
            $message = "เพิ่มสินค้าใน Shopee ไม่สำเร็จ";
            sendNotiToAdmin($message);
            
            
            $ret["message"] = $message;
            mysqli_rollback($con);
            mysqli_close($con);
            
            echo json_encode($ret);
            exit();
        }
    }
