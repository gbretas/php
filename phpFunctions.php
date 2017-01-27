<?php

// Sanatize strings for databases & security
function clean($str) { 
  $str = @trim($str);
	if(get_magic_quotes_gpc()) {
		$str = stripslashes($str);
	}
	return mysqli_real_escape_string($str);
}

//---------------------------------------------------------------------------
// Generate an 8-character random password/string 
//Usage: genPassword(NumberOfChars);

function genPassword($length = 8) { 
    $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ1234567890";
    $validCharNumber = strlen($validCharacters);
    $result = "";
    
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }
 
    return $result;
}

//---------------------------------------------------------------------------
// Function to send POST datas to website more easier
// Full array go also return status of http
//echo get_remote_data('http://example.com/');                                   //simple request
//echo get_remote_data('http://example.com/', "var2=something&var3=blabla" );    //POST request 										

function get_remote_data($url, $post_paramtrs=false,$return_full_array=false)	{
	$c = curl_init();curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

	if($post_paramtrs){curl_setopt($c, CURLOPT_POST,TRUE);curl_setopt($c, CURLOPT_POSTFIELDS, $post_paramtrs );}
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST,false);                  
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0"); 
	curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
	curl_setopt($c, CURLOPT_MAXREDIRS, 10); 
	$follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false:true;  if ($follow_allowed){curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);}
	curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
	curl_setopt($c, CURLOPT_REFERER, $url);    
	curl_setopt($c, CURLOPT_TIMEOUT, 60);
	curl_setopt($c, CURLOPT_AUTOREFERER, true);  
	curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');

	$data = curl_exec($c);
	$status = curl_getinfo($c);
	curl_close($c);

	preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si',  $status['url'],$link);	
	$data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si','$1=$2'.$link[0].'$3$4$5', $data);     
	$data=preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si','$1=$2'.$link[1].'://'.$link[3].'$3$4$5', $data);   
	if($status['http_code']==301 || $status['http_code']==302) { 
		if (!$follow_allowed){
			if(empty($redirURL)){if(!empty($status['redirect_url'])){$redirURL=$status['redirect_url'];}}
			if(empty($redirURL)){preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
			if (!empty($m[2])){ $redirURL=$m[2]; } }
			if(empty($redirURL)){preg_match('/moved\s\<a(.*?)href\=\"(.*?)\"(.*?)here\<\/a\>/si',$data,$m); if (!empty($m[1])){ $redirURL=$m[1]; } }
			if(!empty($redirURL)){$t=debug_backtrace(); return call_user_func( $t[0]["function"], trim($redirURL), $post_paramtrs);}
		}
	}
	elseif ( $status['http_code'] != 200 ) { $data =  "ERRORCODE22 with $url<br/><br/>Last status codes:".json_encode($status)."<br/><br/>Last data got:$data";}
	return ( $return_full_array ? array('data'=>$data,'info'=>$status) : $data);
}


//---------------------------------------------------------------------------
// Uses PHP's filter_var to check for valid URL

function isUrl($url) {
	$validation = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
	if ($validation){
		$output = true;
	}else{
		$output = false;
	}
	return $output;
}

//---------------------------------------------------------------------------
//Security Level: AES 256
//Encrypt the string
//$encryptedString = encode("Encrypted");
//Decrypt the string
//$decryptedString = decode($string);

// The key to encrypting and decrypting your string
define('SALT', 'SuPeRsEcReT');

function encode($text, $salt = SALT){ 
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
}

function decode($text, $salt = SALT){
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
}

//---------------------------------------------------------------------------
// Make hyperlinks by url
// Usage: echo url_to_href('http://gbretas.com'); outputs <a href="http://gbretas.com">http://gbretas.com</a>
function url_to_href($str) {
    $pattern = '/((?:http|https)(?::\\/{2}[\\w]+)(?:[\\/|\\.]?)(?:[^\\s"]*))/is';
    $replace = '<a target="blank" href="$1">$1</a>';
    return preg_replace($pattern, $replace, $str);
}

//---------------------------------------------------------------------------
// Check if email is valid
// Usage: valid_email($email, $test_mx = Optional); 
// TestMX is a function to test if Hostname exists :)
function valid_email($email, $test_mx = false) { /* checks if email address is valid */
	if(eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)){
		if($test_mx){
			list($username, $domain) = split("@", $email);
			return getmxrr($domain, $mxrecords);
		}else
			return true;
		}
	}else{
		return false;
	}
}

//---------------------------------------------------------------------------