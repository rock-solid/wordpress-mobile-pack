<?php

try {

    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, 'https://pwa-cdn.baobabsuite.com'.$_SERVER['REQUEST_URI']);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Origin: '.$_SERVER['SERVER_NAME']));
    $page = curl_exec($curl_handle);
    $response = curl_getinfo($curl_handle);
    curl_close($curl_handle);

    if($page === false || $response[http_code] != 200) {
        throw new Exception('cannot load PWA');
    }
    echo $page;

} catch (Exception $e) {

    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    $queryVar = '?';

    if(strpos($_SERVER['REQUEST_URI'], '?')){
        $queryVar = '&';
    }
        
    $current_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']. $queryVar."noapp=true";

    header("Location: ".$current_link);
}

?>