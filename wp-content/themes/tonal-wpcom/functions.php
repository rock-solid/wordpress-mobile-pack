<?php
/**
 * Tonal functions and definitions
 *
 * @package Tonal
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'tonal_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function tonal_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'tonal', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'featured-image', 1280, 9999 );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'tonal' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'tonal_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', ) );
}
endif; // _s_setup
add_action( 'after_setup_theme', 'tonal_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function tonal_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar One', 'tonal' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Sidebar Two', 'tonal' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Sidebar Three', 'tonal' ),
		'id'            => 'sidebar-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'tonal_widgets_init' );

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * The use of Oxygen by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @since Tonal 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function tonal_fonts() {
	/* translators: If there are characters in your language that are not supported
	   by Raleway, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Oxygen serif font: on or off', 'tonal' ) ) {

		wp_enqueue_style( 'tonal-font', "//fonts.googleapis.com/css?family=Oxygen:400,300,700", array(), null );

	}
}
add_action( 'init', 'tonal_fonts' );

/**
 * Enqueue Google Fonts for custom headers
 */
function tonal_admin_scripts( $hook_suffix ) {
	if ( 'appearance_page_custom-header' != $hook_suffix )
		return;

	wp_enqueue_style( 'tonal-font' );
}
add_action( 'admin_enqueue_scripts', 'tonal_admin_scripts' );

/**
 * Enqueue scripts and styles.
 */
function tonal_scripts() {
	wp_enqueue_style( 'tonal-style', get_stylesheet_uri() );

	wp_enqueue_script( 'tonal-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	wp_enqueue_script( 'tonal-scripts', get_template_directory_uri() . '/js/tonal.js', array('jquery'), '20142202', true );

	wp_enqueue_style( 'tonal-font' );

	// Add Genericons font, used in the main stylesheet.
	if ( wp_style_is( 'genericons', 'registered' ) )
		wp_enqueue_style( 'genericons' );
	else
		wp_enqueue_style( 'genericons', get_template_directory_uri() . '/css/genericons.css', array(), null );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'tonal_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Tonal style output to create the style from the background color.
 */
require get_template_directory() . '/inc/tonal-styling.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


// updater for WordPress.com themes
if ( is_admin() )
	include dirname( __FILE__ ) . '/inc/updater.php';
