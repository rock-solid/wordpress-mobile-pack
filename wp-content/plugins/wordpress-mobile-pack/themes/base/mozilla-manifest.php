<?php
$arr = array(
	'name' 			=> get_bloginfo("name"), 
	'launch_path' 	=> '/~ionut/wp/dev/app1/', 
	'icons'			=> array(
    	  					"152"		=> "http://dev.webcrumbz.co/~ionut/wp/dev/app1/resources/icons/192x192.png",
         				),
	'developer'		=> array(
    	  					"name"		=> "Appticles.com",
							"url"		=> "http://www.appticles.com"
         				)
);

echo json_encode($arr);
?>