<?php

$login_args = array(
	'form_id' => 'wptouch-login',
	'label_username' => '',
	'label_password' => '',
	'label_remember' => __( 'Remember Me', 'wptouch-pro' ),
	'value_remember' => true,
	'label_log_in' => __( 'Log In', 'wptouch-pro' )
);

?>

<div class="wptouch-login-wrap">
	<i class="login-close icon-remove-sign"></i>
	<div class='wptouch-login-inner'>

		<h3>
			<i class='icon-key'></i> 
			<?php _e( 'Login', 'wptouch-pro' ); ?>
		</h3>

		<?php wp_login_form( $login_args ); ?>

	</div>

	<?php if ( !wptouch_fdn_is_web_app_mode() && wptouch_fdn_show_login_links() ) { ?>
		<div class="login-links">
			<a class="sign-up tappable" href="<?php echo site_url( '/wp-login.php?action=register&redirect_to=' . $_SERVER['REQUEST_URI']); ?>"><?php _e( 'Sign-up', 'wptouch-pro' ); ?></a>
			<a class="forgot tappable" href="<?php echo site_url( '/wp-login.php?action=lostpassword&redirect_to=' . $_SERVER['REQUEST_URI']); ?>"><?php _e( 'Lost password?', 'wptouch-pro' ); ?></a>
		</div>
	<?php } ?>
</div>