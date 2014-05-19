<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
  <meta name="description" content="<?php bloginfo('description'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<script src="<?php bloginfo('template_url'); ?>/jquery-1.4.4.min.js" type="text/javascript"></script>
	<script type="application/x-javascript">
  addEventListener("load", function(){
      setTimeout(updateLayout, 0);
  }, false);

  var currentWidth = 0;
  
  function updateLayout(){
    if (window.innerWidth != currentWidth){
      currentWidth = window.innerWidth;

      var orient = currentWidth == 320 ? "profile" : "landscape";
      document.body.setAttribute("orient", orient);
      setTimeout(function(){
      	window.scrollTo(0, 1);
      }, 100);            
    }
  }
  
  $(document).ready(function() {
		setInterval(updateLayout, 400);
	});
	</script>
	<?php wp_head() ?>
</head>
<body <?php body_class(); ?>>
	<a name="top"></a>
	<div class="websitez-header">
		<?php
		if("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] != get_bloginfo('url')."/"){
		?>
		<a href="" onClick="history.go(-1); return false;"><div class="websitez-header-left"></div></a>
		<?php
		}
		?>
		<a href="<?php bloginfo('url'); ?>" class="logo"><?php bloginfo('name'); ?></a>
		<a href="#" onClick="websitez_extendMenu(); return false;" class="websitez-header-right"></a>
	</div>
	<div class="websitez-menu">
		<div class="websitez-menu-content">
			<?php get_search_form(); ?>
			<?php 
			$menu = wp_nav_menu( array('container'=>false,'echo'=>false) );
			if(strlen($menu) > 0){
				echo "<div class='websitez-sidebar'>";
				echo "<h3>Menu</h3>";
				echo $menu;
				echo "</div>";
			}
			?>
			<?php get_sidebar(); ?>
			<div style="clear: both;"></div>
		</div>
		<a onClick="$('.websitez-menu-content').toggle('slow'); $('.hid').toggle(); return false;" href="#"><div class="websitez-menu-button hid"><img src="<?php bloginfo('template_url'); ?>/images/small-down-arrow.png" border="0"></div></a>
		<a onClick="$('.websitez-menu-content').toggle('slow'); $('.hid').toggle();" href="#top"><div class="websitez-menu-button hid hidden"><img src="<?php bloginfo('template_url'); ?>/images/small-up-arrow.png" border="0"></div></a>
	</div>