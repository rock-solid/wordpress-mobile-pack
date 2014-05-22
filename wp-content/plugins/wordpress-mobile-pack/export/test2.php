<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
<a href="http://dev.webcrumbz.co/~flori/blog/wp-content/plugins/wordpress-mobile-pack/export/test.php?content=savecomment&articleId=12">bla bla</a>

</html>


<?php

exit();
// jSON URL which should be requested
$json_url = 'http://dev.webcrumbz.co/~flori/blog/wp-content/plugins/wordpress-mobile-pack/export/test.php?content=savecomment&articleId=12';
$send_curl = curl_init($json_url);

// set curl options
curl_setopt($send_curl, CURLOPT_URL, $json_url);
curl_setopt($send_curl, CURLOPT_HEADER, true);
curl_setopt($send_curl, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($send_curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($send_curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($send_curl, CURLOPT_POSTFIELDS, '');     
//curl_setopt($send_curl, CURLOPT_HTTPHEADER,array('Accept: application/json', "Content-type: application/json"));
curl_setopt($send_curl, CURLOPT_FAILONERROR, FALSE);
curl_setopt($send_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($send_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
$json_response = curl_exec($send_curl);

// get request status
$status = curl_getinfo($send_curl, CURLINFO_HTTP_CODE);
curl_close($send_curl);

var_dump($json_response);exit();

if($status == 200) {
	// get response
	$response = json_decode($json_response, true);
	
	if(isset($response["news"]) && is_array($response["news"]) && !empty($response["news"]))
		// return response
		return $response["news"];
}

?>