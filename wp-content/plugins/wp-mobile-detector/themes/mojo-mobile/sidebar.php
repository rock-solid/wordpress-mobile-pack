<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar() ) : else : ?>

<div class="wrapper">
	<div class="ui-body ui-body-f">
		<div data-role="collapsible" data-theme="e">
			<h3>Pages</h3>
			<ul data-role="listview" data-inset="true" data-theme="c">
			<?php wp_list_pages('title_li=&depth=1'); ?>
			</ul>
		</div><!-- /collapsible -->
	</div><!-- /themed container -->
</div>

<div class="wrapper">
	<div class="ui-body ui-body-f">
		<div data-role="collapsible" data-state="collapsed" data-theme="e">
			<h3>Archive</h3>
			<ul data-role="listview" data-inset="true" data-theme="c">
      <?php wp_get_archives('type=monthly'); ?>
      </ul>
		</div><!-- /collapsible -->
	</div><!-- /themed container -->
</div>

<div class="wrapper">
	<div class="ui-body ui-body-f">
		<div data-role="collapsible" data-state="collapsed" data-theme="e">
			<h3>Categories</h3>
			<ul data-role="listview" data-inset="true" data-theme="c">
      <?php wp_list_categories('show_count=1&title_li=&depth=1'); ?>
			</ul>
		</div><!-- /collapsible -->
	</div><!-- /themed container -->
</div>

<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>

<div class="wrapper">
	<div class="ui-body ui-body-f">
		<div data-role="collapsible" data-theme="e">
			<h3>Blogroll</h3>
			<ul data-role="listview" data-inset="true" data-theme="c">
      <?php wp_list_bookmarks(array('title_li'=>'','categorize'=>0)); ?>
			</ul>
		</div><!-- /collapsible -->
	</div><!-- /themed container -->
</div>

<div class="wrapper">
	<div class="ui-body ui-body-f">
		<div data-role="collapsible" data-state="collapsed" data-theme="e">
			<h3>Meta</h3>
			<ul data-role="listview" data-inset="true" data-theme="c">
          <?php
          if(is_user_logged_in()){
          	$register = wp_register('','',false);
          	if(strlen($register) > 0)
          		echo "<li><a href='/wp-admin/' rel='external'>Site Admin</a></li>\n";
          	echo "<li><a href='".wp_logout_url()."' rel='external'>Logout</a></li>\n";
          }else{
          	$register = wp_register('','',false);
          	if(strlen($register) > 0)
          		echo "<li><a href='/wp-login.php?action=register' rel='external'>Register</a></li>\n";
          	echo "<li><a href='".wp_login_url()."' rel='external'>Login</a></li>\n";
          }
          ?>
          <li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
          <li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
          <li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
          <?php wp_meta(); ?>
      </ul>
		</div><!-- /collapsible -->
	</div><!-- /themed container -->
</div>

<?php } ?>
<?php endif; ?>