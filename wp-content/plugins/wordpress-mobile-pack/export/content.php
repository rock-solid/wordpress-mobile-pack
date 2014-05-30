<?php 

//Disable error reporting becasue these methods are used as callbacks by the mobile web app
error_reporting(0);

require_once("export-class.php");

header("Content-Type: application/json; charset=UTF-8");

if(isset($_GET["content"])) {
	// export categories
	if($_GET["content"] == 'exportcategories') { // export categories, optional param:  limit
		
		$export = new Export();
		//echo $export->exportCategories();
	
		echo $_GET['callback'] . '('.$export->exportCategories().')';
	
	} elseif($_GET["content"] == 'exportarticles') { //export articles, optional params: categoryId, lastTimestamp, limit
		
		$export = new Export();
		//echo $export->exportArticles();
		echo $_GET['callback'] . '('.$export->exportArticles().')';
		
	}	elseif($_GET["content"] == 'exportarticle' && isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
		// export article details, mandatory param articleId
		$export = new Export();
		//echo $export->exportArticle();
		echo $_GET['callback'] . '('.$export->exportArticle().')';
		
	}	elseif($_GET["content"] == 'exportcomments' && isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
		// export article details, mandatory param articleId
		$export = new Export();
		//echo $export->exportComments();
		echo $_GET['callback'] . '('.$export->exportComments().')';
		
	}	elseif($_GET["content"] == 'savecomment' && isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
	
		/*$_GET["author"] = 'Flori';
		$_GET["email"] = 'florentina@webcrumbz.com';
		$_GET["url"] = 'http://appticles.com';
		$_GET["comment"] = 'Must be approved?';
		$_GET["comment_parent"] = 0;
		$_GET["code"] = 'M2I4YTViOTI4NzRiMmI2Y2ExOGZiY2I1ZDU4ZThiOTFfMTQwMDc3MTc1NA==';*/
		
		
		// save comment, mandatory get param is articleId
		$export = new Export();
		//echo $export->saveComment();
		
		echo $_GET['callback'] . '('.$export->saveComment().')';
	
	} else
		echo $_GET['callback'] . '({"error":"No export requested"})';
	
	
}

 



?>