<?php
class ApiManager
{
	public $serverUrl = "https://open.jd.co.th/api";

	public $accessToken;

	public $connectTimeout = 0;

	public $readTimeout = 0;

	public $appKey;

	public $appSecret;
	
	public $version = "1.0";
	
	public $format = "json";
	public $signMethod = "md5";

	private $charset_utf8 = "UTF-8";
	
	public $method;

	public $param_json;
	public $param_file;

	protected function generateSign($params)
	{
		ksort($params);
		$stringToBeSigned = $this->appSecret;
		foreach ($params as $k => $v)
		{
			if("@" != substr($v, 0, 1))
			{
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		$stringToBeSigned .= $this->appSecret;
		
		//online env should comment this print
		//print("before sign raw: ".$stringToBeSigned."\n");
		return strtoupper(md5($stringToBeSigned));
	}

	public function curl($url, $postFields = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($this->readTimeout) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
		}
		if ($this->connectTimeout) {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
		}
		//https request
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		if (is_array($postFields) && 0 < count($postFields))
		{
			$postBodyString = "";
			$postMultipart = false;
			foreach ($postFields as $k => $v)
			{
				//if("@" != substr($v, 0, 1))//check whether file upload or not
				if ($k != 'param_file')
				{
					$postBodyString .= "$k=" . urlencode($v) . "&"; 
				}
				else// for file upload, set to multipart/form-data£¬else set to www-form-urlencoded
				{
					$postMultipart = true;
				}
			}
			unset($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart)
			{
				//There is a new Variable included with curl in PHP 5.5: 
				//CURLOPT_SAFE_UPLOAD this is set to false by default in PHP 5.5 and is switched to a default of true in PHP 5.6.
				curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}
			else
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			}
		}
		$reponse = curl_exec($ch);
		
		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch),0);
		}
		else
		{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode)
			{
				throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}

	
	public function getStandardOffsetUTC($timezone)
	{
		if($timezone == 'UTC') {
			return '+0000';
		} else {
			$timezone = new DateTimeZone($timezone);
			$transitions = array_slice($timezone->getTransitions(), -3, null, true);

			foreach (array_reverse($transitions, true) as $transition)
			{
				if ($transition['isdst'] == 1)
				{
					continue;
				}

				return sprintf('%+03d%02u', $transition['offset'] / 3600, abs($transition['offset']) % 3600 / 60);
			}

			return false;
		}
	}

	public function getCurrentTimeFormatted()
	{
		return  date("Y-m-d H:i:s").'.000'.$this->getStandardOffsetUTC(date_default_timezone_get());
	}
	
	public function call()
	{
		//construct system parameters
		$sysParams["app_key"] = $this->appKey;
		$sysParams["v"] = $this->version;
		$sysParams["format"] = $this->format;
		$sysParams["sign_method"] = $this->signMethod;
		$sysParams["method"] = $this->method;
		$sysParams["timestamp"] = $this->getCurrentTimeFormatted();
		$sysParams["access_token"] = $this->accessToken;
		

		//get business parameters
		if(null != $this->param_json && isset($this->param_json) && !empty($this->param_json))
		{
			$sysParams["param_json"] = $this->param_json;
		}
		else
		{
			$sysParams["param_json"] = "{}";
		}
		

		//sign
		$sysParams["sign"] = $this->generateSign($sysParams);
		//system parameter put into POST request string
		$requestUrl = $this->serverUrl;
		//$requestUrl = $this->serverUrl . "?";

		$arr4Post = array(); 
		foreach ($sysParams as $sysParamKey => $sysParamValue)
		{
			$arr4Post["$sysParamKey"] = $sysParamValue;
		}
		//send HTTP request
		try
		{
			$resp = $this->curl($requestUrl, $arr4Post);
			//print("\nfunction call resp:\n".$resp);
			return $resp;
		}
		catch (Exception $e)
		{
			print("\nfunction call error happened.\n".$e);
			$result->openapi_code = $e->getCode();
			$result->openapi_msg = $e->getMessage();
			return $result;
		}
	}
	
	public function call4BigData()
	{
		//construct system parameters
		$sysParams["app_key"] = $this->appKey;
		$sysParams["v"] = $this->version;
		$sysParams["format"] = $this->format;
		$sysParams["sign_method"] = $this->signMethod;
		$sysParams["method"] = $this->method;
		$sysParams["timestamp"] = $this->getCurrentTimeFormatted();
		$sysParams["access_token"] = $this->accessToken;
		

		//get business parameters
		if(null != $this->param_json && isset($this->param_json) && !empty($this->param_json))
		{
			$sysParams["param_json"] = $this->param_json;
		}
		else
		{
			$sysParams["param_json"] = "{}";
		}
		//get business file which would upload
		if(null != $this->param_file && isset($this->param_file) && !empty($this->param_file))
		{
			$sysParams["param_file_md5"] = strtoupper(md5_file($this->param_file));
		}

		//do sign
		$sysParams["sign"] = $this->generateSign($sysParams);
		//
		$requestUrl = $this->serverUrl;
		//curl function will check '@' flag to upload file using  multipart/form-data
		//curl function will check '@' flag to upload file using  multipart/form-data
		//$arr4File = array("param_file" => "@" . $this->param_file); 
		$arr4File = array("param_file" => new \CURLFile($this->param_file)); 
		foreach ($sysParams as $sysParamKey => $sysParamValue)
		{
			$arr4File["$sysParamKey"] = $sysParamValue;
		}
		
		//do http request
		try
		{
			$resp = $this->curl($requestUrl, $arr4File);
			//print("\nfunction call resp:\n".$resp);
			return $resp;
		}
		catch (Exception $e)
		{
			print("\nfunction call4BigData error happened.\n".$e);
			$result->openapi_code = $e->getCode();
			$result->openapi_msg = $e->getMessage();
			return $result;
		}
	}
	

}

//test demo
//$c = new ApiManager();
//minimalist
//$c->appKey = "222ffa678534712a816efc3535049e41";
//$c->appSecret = "25b12ddf8b66efe51ff35d38460b2bd1";
//$c->accessToken = "35f6a652e822627337db132c85e5b5c5";

////minimalist
//$c->appKey = "2712613104cf69a349c9394a801e97e5";
//$c->appSecret = "3ba6c386c12e68a718c87a2bcbae4bf0";
//$c->accessToken = "16599925199b8709e827049c469717ac";

//rala music
//$c->appKey = "10ace68345842bea18d78cdbeb8981b9";
//$c->appSecret = "d846c4ef9375d6dcd718569700faead2";
//$c->accessToken = "76ae0129bff9f0e64dbba2a0a81574cb";
//http:// only for test£¬ online env should use https://
//$c->serverUrl = "http://open.jd.co.th/api"; 

//business method & business params
//$c->method = "jingdong.PopAdminExport.queryListNew";
//$c->param_json = '{"productId": 5425402,"searchCondition": {"containBaseInfo": true,"containImageList": true,"containImageListAll": true,"containProductProperty": true,"containSkuProperty": true,"source": "SELF"}}';
//$resp = $c->call();
//print("\nDemo response: ".$resp);
//print("\n<br/>");
//~:end test demo

////test demo for big data
//$c = new ApiManager();
//$c->appKey = "---appKey---";
//$c->appSecret = "---appSecret---";
//$c->accessToken = "----accessToken----";
////http:// only for test£¬ online env should use https://
//$c->serverUrl = "http://open.jd.co.th/api_bigdata";
//
////business method & business params
//$c->method = "----jingdong.api.name----";
//$c->param_json = "";
//$c->param_file = "D://Desert.jpg";
//
//$resp = $c->call4BigData();
//print("<br/>\nDemo response for big data1: ".$resp);
////~:end test demo for big data

?>
