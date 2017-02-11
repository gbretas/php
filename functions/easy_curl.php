<?php
// Easy CURL requests
global $cookie;
$cookie = 'cookieTest.txt';
set_time_limit(0);

// Class cURL
class cURL{
    var $callback = false;
    function setCallback($func_name) {
        $this->callback = $func_name;
    }
    // doRequest, usefull to request mode POST & GET
    function doRequest($method, $url, $vars) {
        global $cookie;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/'.$cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'.$cookie);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data) {
            if ($this->callback) {
                $callback = $this->callback;
                $this->callback = false;
                return call_user_func($callback, $data);
            } else {
                return $data;
            }
        } else {
            return curl_error($ch);
        }
    }
    // Send GET function
    function get($url) {
        return $this->doRequest('GET', $url, 'NULL');
    }
    //Send POST function
    function post($url, $vars) {
        return $this->doRequest('POST', $url, $vars);
    }
}

// Mount the query paramenter by array
$http = http_build_query(array(
	'key' => 'value',
	'senha' => '123',
));

$requisition = new cURL();
$frist = $requisition->post(
    'https://github.com/',
    $http
);
// The var Frist gets the page html complete code, so u can use an web crawler to this :)
/*
Used to delete cookie file
unlink($cookie);
*/