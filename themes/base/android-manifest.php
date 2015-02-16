<?php
$arr = array(
	'name' 			=> 'Web Application 2', 
	'start_url' 	=> 'http://dev.webcrumbz.co/~ionut/wp/dev/app1/', 
	'display' 		=> 'standalone',
	'icons'			=> array(
    	  					array(
								"src"		=> "http://dev.webcrumbz.co/~ionut/wp/dev/app1/resources/icons/192x192.png",
          						"sizes"		=> "192x192"
         					)
						)
);

echo json_encode($arr);
?>