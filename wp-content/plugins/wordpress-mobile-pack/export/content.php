<?php 

header("Content-Type: application/json; charset=UTF-8");

// Disable error reporting because these methods are used as callbacks by the mobile web app
// error_reporting(0);

if (isset($_GET["content"]) && isset($_GET['callback'])) {
    
    require_once("export-class.php");
    
	// export categories
	if($_GET["content"] == 'exportcategories') { // export categories, optional param:  limit
		
		$export = new Export();
		echo $_GET['callback'] . '('.$export->exportCategories().')';
	
	} elseif($_GET["content"] == 'exportarticles') { //export articles, optional params: categoryId, lastTimestamp, limit
		
		$export = new Export();
		echo $_GET['callback'] . '('.$export->exportArticles().')';
		
	}	elseif($_GET["content"] == 'exportarticle' && isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
	   
		// export article details, mandatory param articleId
		$export = new Export();
		echo $_GET['callback'] . '('.$export->exportArticle().')';
		
	}	elseif($_GET["content"] == 'exportcomments' && isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
	   
		// export article details, mandatory param articleId
		$export = new Export();
		echo $_GET['callback'] . '('.$export->exportComments().')';
		
	}	elseif($_GET["content"] == 'savecomment' && isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
	
		// save comment, mandatory get param is articleId
		$export = new Export();
		echo $_GET['callback'] . '('.$export->saveComment().')';
	
	}	elseif($_GET["content"] == 'exportpages') {
	
		// export pages
		$export = new Export();		
		echo $_GET['callback'] . '('.$export->exportPages().')';
	
	}   elseif($_GET["content"] == 'exportpage' && isset($_GET["pageId"]) && is_numeric($_GET["pageId"])) {
	
		// save export page, manadatory param is pageId
		$export = new Export();
		echo $_GET['callback'] . '('.$export->exportPage().')';
	
	} else
		echo $_GET['callback'] . '({"error":"No export requested"})';
        
} else
    echo '({"error":"No export requested"})';
?>