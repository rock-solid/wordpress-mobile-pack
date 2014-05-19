<?php


class TestTouch {
	
	
	
	function TestTouch() {
		
	}
	
	function wmp_install() {
		//...crickets
	}
	
	function wmp_admin_init() {
		
		//$this->wmp_admin_enqueue_files();
	}
	

	
	
	function wmp_plugin_uri()
	{
		return WP_PLUGIN_URL . '/testtouch-mobile';
	}
	
	function wmp_plugin_dir()
	{
		return WP_PLUGIN_DIR . '/testtouch-mobile';
	}
	
	function wmp_plugin_admin_uri()
	{
		return $this->wmp_plugin_uri() . '/admin';
	}
			
	
    
    
}
?>