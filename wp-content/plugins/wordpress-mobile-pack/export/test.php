<?php 

require_once("test2.php");

header("Content-Type: application/json; charset=UTF-8");

if(isset($_GET["content"])) {
	// export categories
	if($_GET["content"] == 'exportcategories') { // export categories, optional param:  limit
		
		$export = new Test();
		//echo $export->exportCategories();
	
		echo $_GET['callback'] . '('.$export->exportCategories().')';
	exit();
	} elseif($_GET["content"] == 'exportarticles') { //export articles, optional params: categoryId, lastTimestamp, limit
		
		$export = new Test();
		//echo $export->exportArticles();
		echo $_GET['callback'] . '('.$export->exportArticles().')';
		
	}	elseif($_GET["content"] == 'exportarticle' && isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
		// export article details, mandatory param articleId
		$export = new Test();
		//echo $export->exportArticle();
		echo $_GET['callback'] . '('.$export->exportArticle().')';
		
	}	elseif($_GET["content"] == 'exportcomments' && isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
		// export article details, mandatory param articleId
		$export = new Test();
		//echo $export->exportComments();
		echo $_GET['callback'] . '('.$export->exportComments().')';
		
	}	elseif($_GET["content"] == 'savecomment' && isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
	
		//var_dump($_SERVER['HTTP_REFERER']);
	
		//var_dump($_SERVER);exit();
		
		$token = WMobilePack::wmp_set_token();
		var_dump(WMobilePack::wmp_check_token($token));
		
		var_dump($token);exit();
		
		
		
		
		// generate token
		var_dump(base64_encode(get_bloginfo("wpurl").'_'));
		var_dump(base64_decode(base64_encode(get_bloginfo("wpurl"))));exit();
		/*$_POST["author"] = 'Flori';
		$_POST["email"] = 'florentina@webcrumbz.com';
		$_POST["url"] = 'http://appticles.com';
		$_POST["comment"] = 'I love the pohotos of the cats!!';
		$_POST["comment_parent"] = 0;*/
		
		
		// save comment, mandatory get param is articleId
		$export = new Test();
		//echo $export->saveComment();
		
		echo $_GET['callback'] . '('.$export->saveComment().')';
	
	} else
		echo '{"error"}:"No export requested"';
	
	
}

 



?>