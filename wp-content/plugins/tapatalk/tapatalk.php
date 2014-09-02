<?php
/*
Plugin Name: Tapatalk for WordPress
Description: Tapatalk for WordPress Plugin enables Tapatalk Community Reader to integrate WordPress Blogs and Forums into a single mobile app.
Version: 1.0.2
Author: Tapatalk
Author URI: http://www.tapatalk.com/
Plugin URI: http://www.tapatalk.com/activate_tapatalk.php
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/license-list.html#GPLCompatibleLicenses
*/

class Tapatalk {
	
	public $version    = '1.0.2';  //plugin's version
	public $method; //request method;
	
	/**
	 * Set some smart defaults to class variables. Allow some of them to be
	 * filtered to allow for early overriding.
	 *
	 * @since tapatalk
	 * @access private
	 * @uses plugin_dir_path() To generate Tapatalk blog api plugin path
	 * @uses plugin_dir_url() To generate Tapatalk blog api plugin url
	 */
	private function setup_globals() 
	{
		/** Paths *************************************************************/

        // Setup some base path and URL information
        $this->file       = __FILE__;
        $this->basename   = plugin_basename( $this->file );
        $this->plugin_dir = plugin_dir_path( $this->file );
        $this->wp_dir     = dirname(dirname(dirname(dirname($this->file))));

        // Includes
        $this->includes_dir = trailingslashit( $this->plugin_dir . 'includes' );
        $this->method       = isset($_REQUEST['tapatalk']) ? trim($_REQUEST['tapatalk']) : '';
    }

    /**
     * include plugin's file
     */
    private function includes()
    {
        /** Core **************************************************************/
        require( $this->includes_dir . 'common.php' );
        require( $this->includes_dir . 'functions.php' );
    }
    
    /**
     * Setup the default hooks and actions
     *
     * @since tapatalk
     * @access private
     * @uses add_action() To add various actions
     */
    public function steup_actions()
    {
        add_action('wp', array( $this, 'run' ));
    }

    /**
     * init the plugins
     */
    private function init()
    {
        $this->setup_globals();
        $this->includes();
    }

    /**
     * output json str
     * @since tapatalk
     * @access private
     */
    public function run()
    {
        if(!isset($_REQUEST['tapatalk']))
        {
            return ;
        }
        
        header('Content-type: application/json; charset=UTF-8');
        
        $this->init();

        if (function_exists('ttwp_'.$this->method))
        {
            call_user_func('ttwp_'.$this->method);
        }
        else
        {
            tt_json_error(-32601);
        }

        exit();
    }
}

/*execute plugin*/
$tapatalk = new Tapatalk();
$tapatalk->steup_actions();


