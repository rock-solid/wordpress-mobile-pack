
    <?php if (comments_open()) : ?>
    <div class="wrapper">
		<div class="ui-body ui-body-a ">
    <h2 id="respond">Leave a Comment</h2>

    <?php if ( get_option('comment_registration') && !$user_ID ) : ?>

    <p><font style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</font></p>

    <?php else : ?>

    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

   		<?php if ( $user_ID ) : ?>

   		<p>
        	<font style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?loggedout=true" title="Log out of this account">Logout &raquo;</a></font>
      	</p>

    	<?php else : ?>
    	
    	<div data-role="fieldcontain">
				<label for="name">Your Name<?php if ($req) echo " (required)"; ?>:</label>
			  <input type="text" name="author" id="author" tagindex="1" value=""  />
			</div>
			
			<div data-role="fieldcontain">
				<label for="name">Your Email<?php if ($req) echo " (required)"; ?>:</label>
			  <input type="text" name="email" id="email" tagindex="2" value=""  />
			</div>

        <?php endif; ?>

        <!--<?php _e('You can use these tags&#58;'); ?> <?php echo allowed_tags(); ?></small></p>-->
				
			<div data-role="fieldcontain">
				<label for="textarea">Your Comment<?php if ($req) echo " (required)"; ?>:</label>
				<textarea cols="40" rows="8" name="comment" id="comment"></textarea>
			</div>

        <p>
          <input type="submit" data-inline="true" value="Submit Comment" />
        	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
        </p>

    	<?php do_action('comment_form', $post->ID); ?>

    </form>

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