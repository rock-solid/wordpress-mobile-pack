<?php

add_action('plugins_loaded', 'widget_sidebar_zemanta_related_posts');

function widget_zemanta_related_posts($args) {
	if(is_single()) {
		extract($args);
		echo $before_widget;
		
		//echo $before_title . $options["related_posts_title"] . $after_title;
		$output = zem_rp_get_related_posts($before_title, $after_title);
		echo $output;
		echo $after_widget;
	}
}

function widget_sidebar_zemanta_related_posts() {
	wp_register_sidebar_widget('zemanta_related_posts_widget', 'Related Posts', 'widget_zemanta_related_posts');
}
