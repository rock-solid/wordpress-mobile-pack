<div class="page-wrapper">

	<header id="header-title-logo">
		<?php if ( bauhaus_should_show_search() ) { ?>
			<div id="search-toggle" class="search-toggle tappable" role="button"><!--icon-search--></div>
		<?php } ?>
		<a href="<?php wptouch_bloginfo( 'url' ); ?>" class="header-center tappable">
			<?php if ( foundation_has_logo_image() ) { ?>
				<img id="header-logo" src="<?php foundation_the_logo_image(); ?>" alt="logo image" />
			<?php } else { ?>
				<h1 class="heading-font"><?php wptouch_bloginfo( 'site_title' ); ?></h1>
			<?php } ?>
		</a>
		<div id="menu-toggle" class="show-hide-toggle tappable" data-effect-target="menu" role="button"><!--icon-reorder--></div>
	</header>

	<?php if ( bauhaus_should_show_search() ) { ?>
	<div id="search-dropper">
		<div id="wptouch-search-inner">
			<form method="get" id="searchform" action="<?php wptouch_bloginfo( 'search_url' ); ?>/">
				<input type="text" name="s" id="search-text" placeholder="<?php _e( 'Search this website', 'wptouch-pro' ); ?>&hellip;" />
				<input name="submit" type="submit" id="search-submit" value="<?php _e( 'Search', 'wptouch-pro' ); ?>" class="button-dark" />
			</form>
		</div>
	</div>
	<?php } ?>

	<div id="menu" class="wptouch-menu show-hide-menu">

		<?php if ( wptouch_has_menu( 'primary_menu' ) ) { wptouch_show_menu( 'primary_menu' ); } ?>

		<?php if (  wptouch_fdn_show_login() ) { ?>
			<ul class="menu-tree login-link">
				<li>
				<?php if ( !is_user_logged_in() ) { ?>
				<a class="login-toggle tappable no-ajax" href="#">
					<i class="icon-key"></i> Login
				</a>
			<?php } else { ?>
				<a href="<?php echo wp_logout_url( $_SERVER['REQUEST_URI'] ); ?>" class="tappable" title="<?php _e( 'Logout', 'wptouch-pro' ); ?>">
					<i class="icon-user"></i>
					<?php _e( 'Logout', 'wptouch-pro' ); ?>
				</a>
			<?php } ?>
				</li>
			</ul>
		<?php } ?>

	</div>

	<?php do_action( 'wptouch_advertising_top' ); ?>

	<?php if ( is_home() ) { ?>
		<?php if ( function_exists( 'foundation_featured_slider' ) ) { ?>
			<?php foundation_featured_slider(); ?>
		<?php } ?>
	<?php } ?>

<?php if ( is_archive() ) { ?>
	<div class="post-page-head-area bauhaus">
		<?php wptouch_fdn_archive_title_text(); ?>
	</div>
<?php } ?>