<?php include_once( wps_get_base_theme() . '/base-header.php' ); ?>

<style>
body {
	background-color: <?php echo wps_get_option( 'site_background_color' ); ?>;
	font-family: <?php echo wps_get_option( 'site_font' ); ?>;
}
.site-header {
	background-color: <?php echo wps_get_option( 'header_background_color' ); ?>;
	border-top-color: <?php echo wps_get_option( 'header_trim_color' ); ?>;
	color:  <?php echo wps_get_option( 'header_text_color' ); ?>;
}
.site-header a {
	color:  <?php echo wps_get_option( 'header_text_color' ); ?>;
}
.arrow-down {
	border-top-color: <?php echo wps_get_option( 'header_text_color' ); ?>;
	
}
</style>

<div id="page" class="site" data-role="page">

	<?php wps_banner() ?>
	
	<div id="main" data-role="content">
	
		<div id="main-content">
	