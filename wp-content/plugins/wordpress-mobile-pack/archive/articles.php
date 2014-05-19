<?php 

require_once("export-class.php");

/* // get params for extracting articles
$limit = 7;
if (isset($_GET["limit"]) && is_numeric($_GET["limit"]))
	$limit = $_GET["limit"];

$categoryId = 0;
if (isset($_GET["categoryId"]) && is_numeric($_GET["categoryId"]))
	$categoryId = $_GET["categoryId"];
	
$lastTimestamp = time();
if (isset($_GET["lastTimestamp"]) && is_numeric($_GET["lastTimestamp"]))
	$lastTimestamp = $_GET["lastTimestamp"];	


$export = new Export();

echo $export->exportArticles($categoryId,$lastTimestamp,$limit);

header("Content-Type: application/json; charset=UTF-8");
*/
$export = new Export();
echo $export->exportArticle();


?>