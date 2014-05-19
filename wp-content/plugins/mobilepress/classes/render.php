<?php
if ( ! class_exists( 'Mobilepress_render' ) ) {
	/**
	 * Class that deals with all aspects of rendering the mobile website
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	class Mobilepress_render {

		private $blog_front_page;

		/**
		 * Constructor which sets up the variables we will need to use
		 *
		 * @package MobilePress
		 * @since 1.2
		 */
		public function __construct() {
			$this->blog_front_page	= mopr_get_option( 'front_page', 1 );
		}

		/**
		 * Initialize the rendering of the mobile website
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_render_theme() {
			// Apply theme filters
			add_filter( 'stylesheet', array( &$this, 'mopr_set_stylesheet' ) );
			add_filter( 'theme_root', array( &$this, 'mopr_set_theme_root' ) );
			add_filter( 'theme_root_uri', array (&$this, 'mopr_set_theme_uri' ) );
			add_filter( 'template', array( &$this, 'mopr_set_template' ) );

			// Apply settings filters
			add_filter( 'option_posts_per_page', array( &$this, 'mopr_set_page_posts' ) );

			if ( $this->blog_front_page != 'posts' ) {
				add_filter( 'option_show_on_front', array( &$this, 'mopr_set_front_page' ) );
				add_filter( 'option_page_on_front', array( &$this, 'mopr_set_front_page_id' ) );
			}
		}

		/**
		 * Sets the stylesheet to the themes mobile stylesheet
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_set_stylesheet() {
			return $_SESSION['MOPR_MOBILE_THEME'];
		}

		/**
		 * Sets the blogs template to the MobilePress template
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_set_template() {
			return $_SESSION['MOPR_MOBILE_THEME'];
		}

		/**
		 * Sets the theme root to the MobilePress theme directory
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_set_theme_root() {
			return WP_CONTENT_DIR . mopr_get_option( 'mobile_theme_root', 1 );
		}

		/**
		 * Sets the path to the themes directory
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_set_theme_uri() {
			return get_bloginfo('wpurl') . '/wp-content' . mopr_get_option( 'mobile_theme_root', 1 );
		}

		/**
		 * Sets the maximum number of posts per page
		 *
		 * @package MobilePress
		 * @since 1.2
		 */
		public function mopr_set_page_posts() {
			return mopr_get_option( 'page_posts', 1 );
		}

		/**
		 * Sets the front page type (posts or page)
		 *
		 * @package MobilePress
		 * @since 1.2
		 */
		public function mopr_set_front_page() {
			return 'page';
		}

		/**
		 * Sets the page id to be used as the front page
		 *
		 * @package MobilePress
		 * @since 1.2
		 */
		public function mopr_set_front_page_id() {
			return $this->blog_front_page;
		}
	}
}