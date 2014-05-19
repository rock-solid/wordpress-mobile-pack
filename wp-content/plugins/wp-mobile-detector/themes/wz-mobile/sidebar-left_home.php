<div id="lbMenu" style="display: none;">
	<div class="element">
		<h3><i class="icon-book icon-white"></i> <?php _e('Pages'); ?></h3>
		<ul>
		<?php wp_list_pages('title_li=&depth=1'); ?>
		</ul>
	</div>
	<div class="element">
		<h3><i class="icon-user icon-white"></i> <?php _e('Meta'); ?></h3>
		<ul>
	    <?php
	    if(is_user_logged_in()){
	    	$register = wp_register('','',false);
	    	if(strlen($register) > 0)
	    		echo "<li><a href='/wp-admin/' rel='external'>".__('Site Admin')."</a></li>\n";
	    	echo "<li><a href='".wp_logout_url()."' rel='external'>".__('Logout')."</a></li>\n";
	    }else{
	    	$register = wp_register('','',false);
	    	if(strlen($register) > 0)
	    		echo "<li><a href='/wp-login.php?action=register' rel='external'>".__('Register')."</a></li>\n";
	    	echo "<li><a href='".wp_login_url("/")."' rel='external'>".__('Login')."</a></li>\n";
	    }
	    ?>
	    <?php wp_meta(); ?>
		</ul>
	</div>
	<div class="element">
		<?php wp_footer(); ?>
	</div>
</div><!-- END LBMENU -->