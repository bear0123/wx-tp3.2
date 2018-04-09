<?php

/**
 *	检查请求是否来自微信
 */
function checkSignature(){
    $signature = I('get.signature');
    $timestamp = I('get.timestamp');
    $nonce = I('get.nonce');
    $token = C('TOKEN'); 		
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );
	
	if( $tmpStr == $signature ){
		return true;
	}else{
		return false;
	}
}


/**
 *	curl
 */
function request($url,$method="get",$data=null){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_HEADER, 0);					// 不输出头文件信息
	curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);			// 允许 @ 前缀在 CURLOPT_POSTFIELDS 中发送文件
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.82 Safari/537.36');			//设置"User-Agent: "头字符串。
	if($method == 'post'){
		curl_setopt($ch, CURLOPT_POST, true );				// post方法
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);		// post数据
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		// 不验证ssl
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);			// 返回字符串
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);					// 设置超时时间
	if(curl_exec($ch) === false){
		$result = curl_error($ch);							// 输出curl错误
	}else{
		$result = curl_exec($ch);							// 执行curl
	}
	curl_close($ch);
	return $result;
}

/**
 *	获取微信access_token
 */
function get_accesstoken(){
	if(!S('access_token')){
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.C('APPID').'&secret='.C('APPSECRET');
		$data = json_decode(request($url),true);
		S('access_token',$data['access_token'],7000);
	}
	return S('access_token');
}


/**
 *	PHP HMAC_SHA1 算法
 */
function hmac_sha1($str, $key){
	$signature = "";
	if (function_exists('hash_hmac')){
		$signature = base64_encode(hash_hmac("sha1", $str, $key, true));
	}else{
		$blocksize	= 64;
		$hashfunc	= 'sha1';
		if (strlen($key) > $blocksize)
		{
			$key = pack('H*', $hashfunc($key));
		}
		$key	= str_pad($key,$blocksize,chr(0x00));
		$ipad	= str_repeat(chr(0x36),$blocksize);
		$opad	= str_repeat(chr(0x5c),$blocksize);
		$hmac 	= pack(
			'H*',$hashfunc(
				($key^$opad).pack(
					'H*',$hashfunc(
						($key^$ipad).$str
					)
				)
			)
		);
		$signature = base64_encode($hmac);
	}
	return $signature;
}	









