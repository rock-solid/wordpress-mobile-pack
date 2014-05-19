<?php 

require_once("content.php");

 // get params for extracting articles
$limit = 7;
if (isset($_GET["limit"]) && is_numeric($_GET["limit"]))
	$limit = $_GET["limit"];
	
$export = new Export();

echo $export->exportCategories($limit);

header("Content-Type: application/json; charset=UTF-8");


?>