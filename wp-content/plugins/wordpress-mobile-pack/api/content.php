<?php 
require_once("export-class.php");
header("Content-Type: application/json; charset=UTF-8");

if(isset($_GET["content"])) {
    
	// export categories
	if($_GET["content"] == 'exportsettings' && isset($_POST['apiKey']) && $_POST['apiKey'] != '') { // export categories, optional param:  limit
		
		$export = new Export();
		echo $export->exportSettings();
	
	} else
		echo '{"error":"No export requested","status" : 0}';
} else
	echo '{"error":"No export requested","status" : 0}';


?>