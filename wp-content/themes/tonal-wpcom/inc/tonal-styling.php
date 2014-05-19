<?php
/**
 * Takes the background color and works out contrasts for text and borders
 *
 * This uses Jetpack class.color.php if JetPack exists, otherwise it uses the bundled color.php
 *
 */
function tonal_style_output() {

	// Check to see if JetPack class.color.php exists - we bundle one just incase
	if ( ! class_exists( 'Jetpack_Color' ) )
		require_once dirname( __FILE__ ) . '/class.color.php';

	// We are going to base everything from the background color so load that up
	$hexColor = get_background_color();

	if ( !isset( $hexColor ) ) {
		$hexColor = 'ffffff';
	}

	// We are going to next declare an new object from the Jetpack_Color class
	$color = new Jetpack_Color( $hexColor );

	// Will out put 000000 or ffffff so can use as a guide whether a dark or light background
	$contrastColor = $color->getMaxContrastColor();

	// If a dark background then load up the white background tones
	if ( $contrastColor == "#000000" ){
		$toneColor = $color->darken(10);
		$bodyColor = $color->getGrayscaleContrastingColor()->lighten(10);
		$headerColor = $color->getGrayscaleContrastingColor();

		tonal_tone('dark');
	}
	// As a default load up the light background defaults
	else {
		$toneColor = $color->lighten(10);
		$bodyColor = $color->getGrayscaleContrastingColor()->darken(10);
		$headerColor = $color->getGrayscaleContrastingColor()->darken();

		tonal_tone('light');
	}

	// Lets now output the CSS for creating the tonal effects
	?>
	<style type="text/css">
		body {
			background: #<?php echo $hexColor; ?>;
		}
		#page{
			z-index: 9999;
		}
		#page:before, #page:after {
    		background-color: <?php echo $toneColor; ?>;
			z-index: 9999;
		}
		#page {
	    	border-left: 20px solid <?php echo $toneColor; ?>;
    		border-right: 20px solid <?php echo $toneColor; ?>;
			z-index: 9999;
		}
		h1,
		h2,
		h3,
		h4,
		h5,
		h6,
		a,
		a:visited {
			color: <?php echo $headerColor; ?>;
		}
		body,
		button,
		input,
		select,
		textarea,
		a:hover {
			color: <?php echo $bodyColor; ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'tonal_style_output', 10 );

/**
 * This function loads either the light or dark tone CSS file
 */
function tonal_tone($type) {
	if ( $type == "light" ) {
		wp_enqueue_style( 'tonal-light', get_template_directory_uri() . '/css/tonal-light.css', array(), '20142102', null );
	}
	else {
		wp_enqueue_style( 'tonal-dark', get_template_directory_uri() . '/css/tonal-dark.css', array(), '20142102', null );
	}
}
add_action( 'wp_enqueue_scripts', 'tonal_tone' );