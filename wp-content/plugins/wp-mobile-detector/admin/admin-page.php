<?php
//Make sure that we're displaying statistics according to the timezone
//set for each individual wordpress install
if(function_exists('date_default_timezone_set'))
	date_default_timezone_set(get_option('timezone_string'));
add_action('admin_head', 'websitez_admin_head');

function websitez_admin_head()
{
	echo "<link rel='stylesheet' id='mobiledetector-css'  href='".plugin_dir_url(__FILE__)."css/style.css' type='text/css' media='all' />";
}

/*
Register the link on the left sidebar in the administration interface
*/
function websitez_configuration_menu(){
	add_menu_page( __( WEBSITEZ_PLUGIN_NAME, 'Websitez' ), __( '<span style="font-size:12px;">'.__(WEBSITEZ_PLUGIN_NAME).'</span>', 'Websitez' ), 8, 'websitez_config', 'websitez_configuration_page',plugin_dir_url(__FILE__).'images/phone_icon_16x16.png');
	add_submenu_page( 'websitez_config', __('Settings', 'Websitez'), __('Settings', 'Websitez'), 8, 'websitez_config', 'websitez_configuration_page' );
	//add_submenu_page( 'websitez_config', __('Mobile Monetization', 'Websitez'), __('Mobile Monetization', 'Websitez'), 8, 'websitez_monetization', 'websitez_monetization_page' );
	add_submenu_page( 'websitez_config', __('Stats', 'Websitez'), __('Stats', 'Websitez'), 8, 'websitez_stats', 'websitez_stats_page' );
	add_submenu_page( 'websitez_config', __('Mobile Themes', 'Websitez'), __('Mobile Themes', 'Websitez'), 8, 'websitez_themes', 'websitez_themes_page' );
}

function websitez_stats_page(){
	global $wpdb, $websitez_plugin_description, $table_prefix, $websitez_free_version;
?>
	<div class="wrap">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top">
					<div class="wz_pro">
						<div class="head">
							<?php echo esc_html( __(WEBSITEZ_PLUGIN_NAME." - Statistics") ); ?>
							<ul class="nav">
								<li><?php echo sprintf(__( "%sUpgrade to WP Mobile Detector PRO%s", "wp-mobile-detector" ), '<a href="http://websitez.com/?utm_campaign=wp-admin-upgrade-link&utm_medium=web" target="_blank">','</a>'); ?></li>
								<li><?php echo sprintf(__( "%sRead User's Guide%s", "wp-mobile-detector" ), '<a href="http://websitez.com/wp-mobile-detector-guide/?utm_campaign=wp-admin-guide&utm_medium=web" target="_blank">','</a>'); ?></li>
								<li><?php echo sprintf(__( "%sWP Mobile Detector on Twitter%s", "wp-mobile-detector" ), '<a href="http://www.twitter.com/websitezcom" target="_blank">','</a>'); ?></li>
							</ul>
						</div>
						<div class="body">
							<a href="http://websitez.com/?utm_campaign=wp-admin-l-image&utm_medium=web" target="_blank"><img src="http://img.websitez.com/websitez-pro-pitch.png" border="0" class="desc"></a>
							<a href="http://websitez.com/?utm_campaign=wp-admin-r-image&utm_medium=web" target="_blank"><img src="http://img.websitez.com/websitez-pro-pitch-right.png" border="0" class="pic"></a>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	  <script type="text/javascript">
	    google.load("visualization", "1", {packages:["corechart"]});
	    google.setOnLoadCallback(drawChart);
	    function drawChart() {
	      var data = new google.visualization.DataTable();
	      data.addColumn('string', 'Date');
	      <?php
	      //Set some values
	      $total_googlebot_visits = 0;
	      $total_bing_bot_visits = 0;
	      $total_basic_unique_visits = 0;
	      $total_advanced_unique_visits = 0;
				$total_advanced_visits = 0;
				$total_basic_visits = 0;
				$visitors = array();
	      if(isset($_GET['type']) && $_GET['type'] == "mtd"){
	      	$report_title = "Mobile Visits Month To Date";
	      	$end_num = date("j");
	      	$length = $end_num-1;
	      	$begin_num = "1";
	      	$start_date = date("Y-m-1 00:00:00");
	      	$end_date = date("Y-m-j 23:59:59");
	      	for($i=$begin_num;$i<=$end_num;$i++){
	      		$chart_this[date("m")."/".$i] = array();
	      	}
	      }else if(isset($_GET['type']) && $_GET['type'] == "7day"){
	      	$report_title = "Mobile Visits Last 7 Days";
	      	$length = 6;
	      	$start_date = date("Y-m-j 00:00:00", strtotime("-".$length." days"));
	      	$end_date = date("Y-m-j 23:59:59");
	      	for($i=$length;$i>=0;$i--){
	      		$chart_this[date("m/j", strtotime("-".$i." days"))] = array();
	      	}
	      }else{
	      	$report_title = "Mobile Visits Today";
	      	$end_num = date("j");
	      	$length = 0;
	      	$begin_num = $end_num;
	      	$start_date = date("Y-m-j 00:00:00", strtotime("-".$length." days"));
	      	$end_date = date("Y-m-j 23:59:59");
	      	$chart_this[date("m")."/".$end_num] = array();
	      }

				$results = $wpdb->get_results("SELECT * FROM ".WEBSITEZ_STATS_TABLE." WHERE created_at BETWEEN '".$start_date."' AND '".$end_date."' ORDER BY created_at DESC");
				if(count($results) > 0){
					//Put each unique visitor into an array
					foreach($results as $ar):
						$data = unserialize($ar->data);
						if(array_key_exists($data['REMOTE_ADDR'],$visitors)){
							$visitors[$data['REMOTE_ADDR']]['visits'][] = $ar->created_at;
						}else{
							$visitors[$data['REMOTE_ADDR']] = array('type'=>$ar->device_type,'data'=>$data,'visits'=>array($ar->created_at));
						}
					endforeach;
				}
				//Put together an array to display in the chart below
				if(count($visitors) > 0){
					foreach($visitors as $unique_visit):
						$type = $unique_visit['type'];
						//Get visit total
						if($type==2)
							$total_basic_visits += count($unique_visit['visits']);
						else if($type==1)
							$total_advanced_visits += count($unique_visit['visits']);
					
						if(preg_match('/(googlebot\-mobile|googlebot mobile)/i',$unique_visit['data']['HTTP_USER_AGENT'])){
							$total_googlebot_visits++;
						}else if(preg_match('/(MSNBOT_Mobile|MSNBOT-Mobile|MSNBOT Mobile)/i',$unique_visit['data']['HTTP_USER_AGENT'])){
							$total_msnbot_visits++;
						}
						
						//Create the array to put into the chart
						if(count($unique_visit['visits']) > 0){
							foreach($unique_visit['visits'] as $unique_visit_date):
								$day = date("m/j", strtotime($unique_visit_date));
								if(!array_key_exists($day,$chart_this)){
									$chart_this[$day][$type] = 1;
									break;
								}else{
									$chart_this[$day][$type] = $chart_this[$day][$type] + 1;
									break;
								}
							endforeach;
						}
					endforeach;
				}
				//End visitor calculations
				?>
				data.addColumn('number', 'Advanced Mobile Device');
	      data.addColumn('number', 'Basic Mobile Device');
				data.addRows(<?php echo count($chart_this);?>);
				<?php
				$j=0;
				if(count($chart_this) > 0){
					foreach($chart_this as $day=>$day_data):
						echo "data.setValue(".$j.", 0,'".$day."');\n";
						if($day_data[2])
							echo "data.setValue(".$j.", 2, ".$day_data[2].");\n";
						else
							echo "data.setValue(".$j.", 2, 0);\n";
						if($day_data[1])
							echo "data.setValue(".$j.", 1, ".$day_data[1].");\n";
						else
							echo "data.setValue(".$j.", 1, 0);\n";
						$total_basic_unique_visits += $day_data[2];
						$total_advanced_unique_visits += $day_data[1];
						$j++;
					endforeach;
				}
	      ?>
				var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
	      //var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
	      chart.draw(data, {width: 1000, height: 340, title: ''});
	    }
	  </script>
	  <table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th class="manage-column" scope="col" style="text-align: center; font-size: 13px;">
						<?php _e('Showing mobile statistics for:')?>
						<select name="type" class="theme_template" style="width: 200px;" onchange="window.location='<?php echo $_SERVER['SCRIPT_NAME'];?>?page=<?php echo $_GET['page'];?>&type='+this.value">
							<option value="today" <?php if($_GET['type'] == "today") echo "selected";?>>Today</option>
							<option value="7day" <?php if($_GET['type'] == "7day") echo "selected";?>>Last 7 Days</option>
							<option value="mtd" <?php if($_GET['type'] == "mtd") echo "selected";?>>Month-To-Date</option>
						</select>
					</th>
				</tr>
			</thead>
			<tr valign="top" class="author-self status-publish iedit">
				<td>
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20" align="center" style="padding-top: 5px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/basic_phone_icon_16x16.gif"></td>
							<td><?php _e('<h3 style="margin: 0px 0px 10px;"><u>Basic Mobile Device</u></h3><p>Total Unique Visitors: '.$total_basic_unique_visits.'</p><p>Total Visits: '.$total_basic_visits.'</p>') ?></td>
							<td width="20" align="center" style="padding-top: 5px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/phone_icon_16x16.png"></td>
							<td><?php _e('<h3 style="margin: 0px 0px 10px;"><u>Advanced Mobile Device</u></h3><p>Total Unique Visitors: '.$total_advanced_unique_visits.'</p><p>Total Visits: '.$total_advanced_visits.'</p>') ?></td>
							<td width="20" align="center" style="padding-top: 5px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/icon_analytics_16x16.gif"></td>
							<td><?php _e('<h3 style="margin: 0px 0px 10px;"><u>Mobile Device Details</u></h3><p>Total Googlebot Mobile Visitors: '.$total_googlebot_visits.'</p><p>Total Bing Bot Mobile Visitors: '.$total_bing_bot_visits.'</p>') ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr valign="top" class="author-self status-publish iedit">
				<td>
					<div id="chart_div" style="text-align: center;"></div>
				</td>
			</tr>
		</table>
		<h2><?php _e('Unique Visitor Details')?></h2>
		<p><?php _e('Showing '.count($visitors).' visitors.')?></p>
		<table class="widefat post fixed" cellspacing="0" style="margin: 0px 0px;">
			<thead>
				<tr>
					<th width="50" class="manage-column" scope="col">
						<?php _e('Device')?>
					</th>
					<th width="50" class="manage-column" scope="col">
						<?php _e('Visits')?>
					</th>
					<th width="130" class="manage-column" scope="col">
						<?php _e('Last Visit')?>
					</th>
					<th width="130" class="manage-column" scope="col">
						<?php _e('IP')?>
					</th>
					<th class="manage-column" scope="col">
						<?php _e('User Agent')?>
					</th>
				</tr>
			</thead>
			<?php
			if(count($visitors) > 0){
				foreach($visitors as $v):
				?>
				<tr valign="top" class="author-self status-publish iedit">
					<td style="padding-top: 5px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/<?php if($v['type'] == "2") echo "basic_phone_icon_16x16.gif"; else echo "phone_icon_16x16.png";?>"></td>
					<td><?php _e('<p>'.count($v['visits']).'</p>') ?></td>
					<td><?php _e('<p>'.date("Y-m-d H:i:s", strtotime($v['visits'][(count($v['visits'])-1)])).'</p>') ?></td>
					<td><?php _e('<p>'.$v['data']['REMOTE_ADDR'].'</p>') ?></td>
					<td><?php _e('<p>'.$v['data']['HTTP_USER_AGENT'].'</p>') ?></td>
				</tr>
				<?php
				endforeach;
			}
			?>
		</table>
	</div>
	<div>
		<?php
		//Get dynamic footer
		_e(websitez_dynamic_footer());
		?>
	</div>
<?php
}

function websitez_configuration_page() 
{
	global $wpdb, $websitez_plugin_description, $table_prefix, $websitez_free_version;
?>
<div class="wrap">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="100%" valign="top">
				<div class="wz_pro">
					<div class="head">
						<?php echo esc_html( __(WEBSITEZ_PLUGIN_NAME) ); ?>
						<ul class="nav">
							<li><?php echo sprintf(__( "%sUpgrade to WP Mobile Detector PRO%s", "wp-mobile-detector" ), '<a href="http://websitez.com/?utm_campaign=wp-admin-upgrade-link&utm_medium=web" target="_blank">','</a>'); ?></li>
							<li><?php echo sprintf(__( "%sRead User's Guide%s", "wp-mobile-detector" ), '<a href="http://websitez.com/wp-mobile-detector-guide/?utm_campaign=wp-admin-guide&utm_medium=web" target="_blank">','</a>'); ?></li>
							<li><?php echo sprintf(__( "%sWP Mobile Detector on Twitter%s", "wp-mobile-detector" ), '<a href="http://www.twitter.com/websitezcom" target="_blank">','</a>'); ?></li>
						</ul>
					</div>
					<div class="body">
						<a href="http://websitez.com/?utm_campaign=wp-admin-l-image&utm_medium=web" target="_blank"><img src="http://img.websitez.com/websitez-pro-pitch.png" border="0" class="desc"></a>
						<a href="http://websitez.com/?utm_campaign=wp-admin-r-image&utm_medium=web" target="_blank"><img src="http://img.websitez.com/websitez-pro-pitch-right.png" border="0" class="pic"></a>
					</div>
				</div>
			</td>
		</tr>
	</table>
	<div id="plugin-description" class="widefat alternate" style="margin:10px 0; padding:5px;background-color:#FFFEEB;">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="20" align="center" style="padding-top: 5px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/basic_phone_icon_16x16.gif"></td>
				<td><?php _e('<h3 style="margin: 0px 0px 10px;"><u>Basic Mobile Device</u></h3><p>The WP Mobile Detector will remove all images and advanced HTML  from being displayed on basic devices.</p>') ?></td>
			</tr>
			<tr>
				<td width="20" align="center" style="padding-top: 10px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/phone_icon_16x16.png"></td>
				<td><?php _e('<h3 style="margin: 5px 0px 10px;"><u>Advanced Mobile Device</u></h3><p>The WP Mobile Detector will resize images that are too large to display on advanced mobile devices.</p>') ?></td>
			</tr>
		</table>
	</div>

<?php
	if(isset($_POST['action'])) {
		$field = $_POST['action'];
		$value = $_POST[$field];
		$url = $_POST['redirect_url'];
		$url_field = $_POST['url_field'];
		
		if(get_option($field)){
			if(update_option($field, $value)){
				$u = true;
			}else{
				$error_message = "The theme could not be saved.";
				$u = false;
			}
		}else{
			$error_message = "It appears that the plugin was not installed properly. The option to update was not found.";
			$u = false;
		}
		
		if($url != ''){
			if(update_option($url_field, $url)){
				$u = true;
			}else{
				$error_message = "The Redirect URL could not be updated.";
				$u = false;
			}
		}
		
		if($u)
			echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
		else
			echo '<div id="message" class="updated fade"><p><strong>Error saving settings.</strong></p><p>'.$error_message.'</p></div>';
	}else if(isset($_POST['record_stats'])){
		$value = $_POST['record_stats'];
		
		if(get_option(WEBSITEZ_RECORD_STATS_NAME)){
			if(update_option(WEBSITEZ_RECORD_STATS_NAME, $value)){
				$u = true;
			}else{
				$u = false;
			}
		}else{
			$u = false;
		}
		
		if($u)
			echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
		else
			echo '<div id="message" class="updated fade"><p><strong>Error saving settings.</strong></p></div>';
	}else if(isset($_POST['show_attribution'])){
		$value = $_POST['show_attribution'];
		
		if(get_option(WEBSITEZ_SHOW_ATTRIBUTION_NAME)){
			if(update_option(WEBSITEZ_SHOW_ATTRIBUTION_NAME, $value)){
				$u = true;
			}else{
				$u = false;
			}
		}else{
			$u = false;
		}
		
		if($u)
			echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
		else
			echo '<div id="message" class="updated fade"><p><strong>Error saving settings.</strong></p></div>';
	}else if(isset($_POST['show_dashboard_widget'])){
		$value = $_POST['show_dashboard_widget'];
		
		if(update_option(WEBSITEZ_SHOW_DASHBOARD_WIDGET_NAME, $value)){
			$u = true;
		}else{
			$u = false;
		}
		
		if($u)
			echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
		else
			echo '<div id="message" class="updated fade"><p><strong>Error saving settings.</strong></p></div>';
	}else if(isset($_POST['show_mobile_to_tablets'])){
		$value = $_POST['show_mobile_to_tablets'];
		
		if(update_option(WEBSITEZ_SHOW_MOBILE_TO_TABLETS_NAME, $value)){
			$u = true;
		}else{
			$u = false;
		}
		
		if($u)
			echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
		else
			echo '<div id="message" class="updated fade"><p><strong>Error saving settings.</strong></p></div>';
	}
	
	//Now that the settings are saved, get the themes
	$current_themes_installed = websitez_get_current_themes();
?>

		<?php
		$websitez_record_stats = get_option(WEBSITEZ_RECORD_STATS_NAME);
		$websitez_show_attribution = get_option(WEBSITEZ_SHOW_ATTRIBUTION_NAME);
		$websitez_show_dashboard_widget = get_option(WEBSITEZ_SHOW_DASHBOARD_WIDGET_NAME);
		$websitez_show_tablets_to_mobile = get_option(WEBSITEZ_SHOW_MOBILE_TO_TABLETS_NAME);
		?>
		<form action="" method="POST">
		<div style="margin:10px 0;">
			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th class="manage-column" scope="col" width="445">Record mobile statistics?</th>
						<th class="manage-column" scope="col">Operation</th>
					</tr>
				</thead>
				<tr valign="top" class="author-self status-publish iedit">
					<td>
						<select name="record_stats" class="theme_template" style="width: 100px;">
								<option value="true" <?php if($websitez_record_stats == "true") echo "selected";?>><?php _e('Yes'); ?></option>
								<option value="false" <?php if($websitez_record_stats == "false") echo "selected";?>><?php _e('No'); ?></option>
						</select>
					</td>
					<td>
						<input type="submit" class="button submit" value="Update">
					</td>
				</tr>
			</table>
		</div>
		</form>
		<form action="" method="POST">
		<div style="margin:10px 0;">
			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th class="manage-column" scope="col" width="445">Give credit to WP Mobile Detector with a footer link?</th>
						<th class="manage-column" scope="col">Operation</th>
					</tr>
				</thead>
				<tr valign="top" class="author-self status-publish iedit">
					<td>
						<select name="show_attribution" class="theme_template" style="width: 100px;">
								<option value="true" <?php if($websitez_show_attribution == "true") echo "selected";?>><?php _e('Yes'); ?></option>
								<option value="false" <?php if($websitez_show_attribution == "false") echo "selected";?>><?php _e('No'); ?></option>
						</select>
					</td>
					<td>
						<input type="submit" class="button submit" value="Update">
					</td>
				</tr>
			</table>
		</div>
		</form>
		<form action="" method="POST">
		<div style="margin:10px 0;">
			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th class="manage-column" scope="col" width="445">Show dashboard widget?</th>
						<th class="manage-column" scope="col">Operation</th>
					</tr>
				</thead>
				<tr valign="top" class="author-self status-publish iedit">
					<td>
						<select name="show_dashboard_widget" class="theme_template" style="width: 100px;">
								<option value="true" <?php if($websitez_show_dashboard_widget == "true") echo "selected";?>><?php _e('Yes'); ?></option>
								<option value="false" <?php if($websitez_show_dashboard_widget == "false") echo "selected";?>><?php _e('No'); ?></option>
						</select>
					</td>
					<td>
						<input type="submit" class="button submit" value="Update">
					</td>
				</tr>
			</table>
		</div>
		</form>
		<form action="" method="POST">
		<div style="margin:10px 0;">
			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th class="manage-column" scope="col" width="445">Show mobile to tablet devices?</th>
						<th class="manage-column" scope="col">Operation</th>
					</tr>
				</thead>
				<tr valign="top" class="author-self status-publish iedit">
					<td>
						<select name="show_mobile_to_tablets" class="theme_template" style="width: 100px;">
								<option value="true" <?php if($websitez_show_tablets_to_mobile == "true") echo "selected";?>><?php _e('Yes'); ?></option>
								<option value="false" <?php if($websitez_show_tablets_to_mobile == "false") echo "selected";?>><?php _e('No'); ?></option>
						</select>
					</td>
					<td>
						<input type="submit" class="button submit" value="Update">
					</td>
				</tr>
			</table>
		</div>
		</form>
		<div>
			<?php
			//Get dynamic footer
			_e(websitez_dynamic_footer());
			?>
		</div>
</div>
<?php
}

function websitez_monetization_page() 
{
	global $wpdb, $websitez_plugin_description, $table_prefix, $websitez_free_version;
	
	if(isset($_GET['hide']) && $_GET['hide'] == "true"):
		update_option(WEBSITEZ_MONETIZATION_MESSAGE, date("Y-m-d H:i:s"));
	endif;
	
	if($_GET['monetization'] == "true" || $_GET['monetization'] == "false"):
		update_option(WEBSITEZ_SHOW_MOBILE_ADS_NAME, $_GET['monetization']);
	endif;
	
	if($_POST):
		$monetization = $_POST['monetization'];
		if($monetization == "true" || $monetization == "false"):
			if(update_option(WEBSITEZ_SHOW_MOBILE_ADS_NAME, $monetization)):
				$u = true;
			else:
				$u = false;
			endif;
			
			if(!$u):
				echo '<div id="message" class="updated fade"><p><strong>An error occurred, please try again.</strong></p></div>';
			endif;
		endif;
	endif;
	
	$monetization = get_option(WEBSITEZ_SHOW_MOBILE_ADS_NAME);
?>
<div>

	<h1><?php echo esc_html( __(WEBSITEZ_PLUGIN_NAME." - Mobile Monetization") ); ?></h1>
	<p><?php _e('Would you like the WP Mobile Detector plugin to monetize your mobile traffic and <strong>send you a payout every month</strong>?') ?></p>
	<p><?php _e('By enabling monetization below, the WP Mobile Detector will run non-obtrusive mobile advertisements on your website. Any revenue generated from such advertisements will be shared with you on a monthly basis.') ?></p>
	<p><?php _e('Join over 32,000 websites that are <strong>already making money</strong>, it is as easy as clicking the enable button below!') ?></p>
	<style>
	table{
		border: 0px;
	}
	table td{
		border: 0px !important;
	}
	</style>
	<div class="" style="margin:10px 0; padding:20px;background-color:#FFFEEB;">
		<?php if($monetization != "false"): ?>
			<table width="100%">
				<tr>
					<td width="50%">
						<h2 style="padding: 0px; color: #3aac1b;">Monetization is currently enabled!</h2>
					</td>
					<td width="50%">
						<form action="" method="POST">
							<input type="hidden" name="monetization" value="false">
							<input type="submit" value="Disable" class="button button-large button-primary">
						</form>
					</td>
				</tr>
			</table>
		<?php else: ?>
			<table width="100%">
				<tr>
					<td width="50%">
						<h2 style="padding: 0px; color: #d23030;">Monetization is currently disabled!</h2>
					</td>
					<td width="50%">
						<form action="" method="POST">
							<input type="hidden" name="monetization" value="true">
							<input type="submit" value="Enable" class="button button-large button-primary">
						</form>
					</td>
				</tr>
			</table>
		<?php endif; ?>
	</div>

	<p><?php _e('<strong>Quick Links</strong>'); ?></p>
	<ul>
	<li><?php _e('<a href="http://websitez.com/monetization" target="_blank">How does it work?</a>'); ?></li>
	<li><?php _e('<a href="http://websitez.com/partners?partner_id='.get_option(WEBSITEZ_PLUGIN_AUTHORIZATION).'" target="_blank">Request A Payout</a>'); ?></li>
	<li><?php _e('<a href="mailto:support@websitez.com?subject=Monetization%20Support">Contact Support</a>'); ?></li>
	</ul>
</div>
<?php
}

/*
Get the dynamic footer remotely
*/
function websitez_dynamic_footer(){
	if(websitez_iscurlinstalled())
		$websitez_footer = websitez_remote_request("http://websitez.com/api/websitez-wp-mobile-detector/footer.php","");
		//$websitez_footer = file_get_contents("http://websitez.com/api/websitez-wp-mobile-detector/footer.php");
	else
		$websitez_footer = "";
	return $websitez_footer;
}

/*
Get dynamic offers for customers
*/
function websitez_dynamic_offers(){
	if(websitez_iscurlinstalled())
		$websitez_offers = websitez_remote_request("http://websitez.com/api/websitez-wp-mobile-detector/offers.php","");
		//$websitez_offers = file_get_contents("http://websitez.com/api/websitez-wp-mobile-detector/offers.php");
	else
		$websitez_offers = "";
	return $websitez_offers;
}

/*
Get dynamic offers for customers
*/
function websitez_dynamic_offers_stats(){
	if(websitez_iscurlinstalled())
		$websitez_offers = websitez_remote_request("http://websitez.com/api/websitez-wp-mobile-detector/offers-stats.php","");
		//$websitez_offers = file_get_contents("http://websitez.com/api/websitez-wp-mobile-detector/offers-stats.php");
	else
		$websitez_offers = "";
	return $websitez_offers;
}
?>