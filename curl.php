<?php
	function http_curl_request($url,$data=null){
		// 1. 初始化
		$ch = curl_init();
		// 2. 设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		
		if(!empty($data)){
			curl_setopt($ch, CURLOPT_POST,1	);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 3. 执行并获取HTML文档内容
		$output = curl_exec($ch);
		// 4. 释放curl句柄
		curl_close($ch);
		
		return $output;
	}
	
	function get_access_token(){
		$appid = "wx6dc24dcf3adb8d84";
		$appsecret = "1a8ace30681e9a6035e926a2662b30cb";
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
		$json =  http_curl_request($url);
		$arr = json_decode($json,true);
		return $arr;
	}
	function time_access_token(){
		$tokenFile = "access_token.txt";
		$access_token = "";
		//缓存文件名
		$data = json_decode(file_get_contents($tokenFile));
		if($data -> expire_time < time() or !$data -> expire_time){
			$access_token = get_access_token();
			$data_new['expire_time'] = time() + 7200;
			$data_new['access_token'] = $access_token['access_token'];
			$fp = fopen($tokenFile, "w");
			fwrite($fp, json_encode($data_new));
			fclose($fp);
		}else{
			$access_token = $data->access_token;
		}
		return $access_token;
	}
	
	//微信数组转json串中文编码 
	function my_json_encode($type,$p){
		if(PHP_VERSION >= '5.4'){
			$str = json_encode($p,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		}else{
			switch($type){
				case "text":
				isset($p['text']['content']) && ($p['text']['content'] == urlencode($p['text']['content']));
				break;
			}
			$str = urlencode($json_encode($p));
		}
		return $str;
	}
	
