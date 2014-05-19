<?php get_header(); ?>
<div class="main_body_mobile">           
	<div class="wrapper">
		<div class="ui-body ui-body-c">
		        		
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
		  <tbody>
		      <tr>
		      
		          <td valign="top" style="width:100%;">
		              <div id="container">
		  
		                  <?php if(have_posts()) : ?>
		                  <?php while(have_posts()) : the_post(); ?>
		  
		                      <div class="post_mobile" id="post-<?php the_ID(); ?>">
		  
		                          <div class="post_the_date">
		                              <?php the_time('F j, Y') ?>
		                          </div>
		                          <div class="post_the_title">
		                              <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
		                          </div>        
		                          
				<div class="entry">                        
					<?php the_content(); ?>
		                              <?php wp_link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
					<p class="postmetadata">
						<?php _e('Posted by'); ?> 
						<?php the_author(); ?>
						<?php edit_post_link('Edit', ' &#124; ', ''); echo '<br />'; ?>
						<?php _e('Category&#58; '); ?>
						<?php the_category(', '); echo '<br />'; ?> 
						<?php the_tags('Tags: ', ', ', '<br />'); echo '<br />'; ?>
					</p>
				</div>
		                          
		                          <div class="comments-template">
					<?php comments_template(); ?>
		                          </div>                                              	
		  
		                      </div>                    
		  
		                  <?php endwhile; ?>
		                  
		                     <div class="navigation">
		                          <?php previous_post_link('&laquo; %link') ?> &#124; <?php next_post_link(' %link &raquo;') ?>
		                     </div>               
		  
		                  <?php else : ?>
		  
		                      <div class="post" id="post-<?php the_ID(); ?>">
		                          <h2><?php _e('No posts are added.'); ?></h2>
		                      </div>
		  
		                  <?php endif; ?>
		                  
		              </div>
		          </td>
				</tr>
		  </tbody>
		  </table>  
	        
		</div>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>        