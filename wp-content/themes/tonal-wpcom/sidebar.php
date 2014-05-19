<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Tonal
 */
?>
	<nav id="site-navigation" class="main-navigation" role="navigation">
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'tonal' ); ?></a>
		<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
	</nav><!-- #site-navigation .main-navigation -->

	<div class="widget-areas">
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div><!-- .widget-ara -->
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div><!-- .widget-ara -->
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar-3' ); ?>
			</div><!-- .widget-ara -->
		<?php endif; ?>
	</div><!-- .widgets-areas -->