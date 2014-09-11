<?php 
require_once("export-class.php");
header("Content-Type: application/json; charset=UTF-8");

if(isset($_GET["content"])) {
    
	// export categories
	if($_GET["content"] == 'exportsettings' && isset($_GET['apiKey']) && $_GET['apiKey'] != '') { // export categories, optional param:  limit
		
		$export = new Export();
		echo $export->exportSettings();
		//echo $_GET['callback'] . '('.$export->exportSettings().')';
	
	} else
		echo '({"error":"No export requested"})';
}


?>