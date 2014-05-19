
    <?php if (comments_open()) : ?>
    <div class="wrapper">
		<div class="ui-body ui-body-a ">

    <?php if ( get_option('comment_registration') && !$user_ID ) : ?>

    <p><font style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</font></p>

    <?php else : ?>
	
			<?php comment_form(); ?>

    <?php endif; ?>
		</div>
		</div>
    <?php endif; ?>
    
	<?php

		if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Please do not load this page directly. Thanks!');
    	if (!empty($post->post_password)) { 
        if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { 

    ?>

    <h2><?php _e('Password Protected'); ?></h2>
    <p><?php _e('Enter the password to view comments.'); ?></p>

    <?php 

		return;

			}
    	}

        $oddcomment = 'alt';

    ?>

    <?php if (have_comments()) : ?>

    <h3 style="padding: 15px 0px 5px;"><?php comments_number('No Comments', '1 Comment', '% Comments' );?> to &#8220;<?php the_title(); ?>&#8221;</h3>
    
    <div class="navigation">
  		<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
 		</div>
 		
		<?php
		wp_list_comments( array( 'callback' => 'websitez_comment' ) );
		?>

    <?php else : ?>
    
    <?php if ('open' == $post->comment_status) : ?>

    <?php else : ?>

    <p class="nocomments"><font style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">Comments are closed.</font></p>

    <?php endif; ?>

		<?php endif; ?>