<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="initial-scale=1" />
	<title><?php echo wps_get_option( 'site_title' ) ?></title>

	<?php wps_enqueue_header(); wp_head(); ?>
	
	<script type="text/javascript">var $wpsmart = jQuery.noConflict();</script>
</head>

<body>