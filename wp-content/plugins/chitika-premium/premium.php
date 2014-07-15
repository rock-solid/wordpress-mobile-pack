<?php
/*
Plugin Name: Chitika
Version: 2.1.2
Plugin URI: http://chitika.com/
Description: Enables you to quickly add and modify your use of Chitika within Wordpress! <a href="options-general.php?page=premium/premium.php">Edit your Chitika configuration settings</a>. Please report bugs, questions and concerns by <a href="http://support.chitika.com/customer/portal/emails/new">submitting a support ticket to Chitika</a>.
Author: Chitika Inc.
Author URI: http://chitika.com/
*/

$PREMIUM_DEFAULTS['plugin-version']     =  '2.1.2';

$PREMIUM_DEFAULTS['template'] ="<!-- Chitika - WordPress Plugin {%plugin-version%}--><div class='chitika-adspace {%placement%}'>
<script type='text/javascript'>
  ( function() {
    if (window.CHITIKA === undefined) {
      window.CHITIKA = { 'units' : [] };
    };
    var unit = {
      'publisher'       : {%client%},
      'width'           : {%width%},
      'height'          : {%height%},
      'sid'             : {%channel%},
      'color_site_link' : {%titlecolor%},
      'color_title'     : {%titlecolor%},
      'color_text'      : {%textcolor%},
      'color_bg'        : {%background%},
      'font_title'      : {%font%},
      'font_text'       : {%font%},
      'impsrc'          : 'wordpress',
      'calltype'        : 'async[2]'
    };
    var placement_id = window.CHITIKA.units.length;
    window.CHITIKA.units.push(unit);
    var x = \"<di\" + \"v id='chitikaAdBlock-\"+placement_id+\"'></di\"+\"v>\";
    document.write(x);
}());
</script>
<script type=\"text/javascript\" src=\"//cdn.chitika.net/getads.js\" async></script>
</div>";

class chitikaPremium {
    var $chpremium_options;

	function chitikaPremium() {
        global $chpremium_options;

		add_filter('the_content', array(&$this, 'chpremium_filter_content'));
		add_action('admin_menu', array(&$this, 'chpremium_add_options_page'));

		$this->chpremium_install();
        $this->chpremium_update();

        $chpremium_options = get_option('chpremium_options');

		if (((!$chpremium_options || $chpremium_options['client'] == 'demo' ) && !isset($_POST['chpremium_update']) ) ||
			 ((empty($_POST['client']) || $_POST['client'] == 'demo') && isset($_POST['chpremium_update'])) ) {
			add_action('admin_notices', array(&$this, 'chpremium_warning'));
		}
	}

	function chpremium_warning() {
		echo '<div id="chitika-warning" class="error"><p style="font-size:15px"><strong>The Chitika Plugin is almost
        ready to place ads on your site!</strong> You need to update your <a href="options-general.php?page=premium/premium.php#username">Chitika
        account username</a>.<br /><br />Don\'t have a Chitika account? <a href="https://chitika.com/publishers/apply?refid=wordpressplugin"
        target="_blank">Sign Up Today</a>! Note: Until your Chitika Account is approved, you will not be able to start earning revenue from your
        Chitika Ads.</p></div>';
	}

	function chpremium_install() {
        global $chpremium_options;

        if (!get_option("chpremium_options")) {
            $chpremium_defaults = array(
                'client'		=>	'demo',
                'password'      =>  '',
                'size'			=>	'468x120',
                'channel'		=>	'wordpress-plugin',
                'background'	=>	'ffffff',
                'titlecolor'	=>	'0000CC',
                'textcolor'	    =>	'000000',
                'display'		=>	'all',
                'placement'		=>	'top',
                'append'		=>	'true',
                'font'			=>	'',
            );

            update_option("chpremium_options", $chpremium_defaults);
            $chpremium_options = $chpremium_defaults;
        }
	}

    function chpremium_update() {
        global $PREMIUM_DEFAULTS, $chpremium_options;
        $changed = false;

        if (get_option('chitikap_plugin-version') != $PREMIUM_DEFAULTS['plugin-version']) {
            $options = array('titlecolor', 'textcolor', 'channel', 'client', 'background', 'display', 'placement', 'font', 'size', 'append', 'password');
            foreach ($options as $option) {
                $old_option = get_option("chitikap_{$option}");
                if ($old_option && !empty($old_option)) {
                    $changed = true;
                    $chpremium_options[$option] = $old_option;
                    delete_option("chitikap_{$option}");
                }
            }

            if ($changed) {
                update_option("chpremium_options", $chpremium_options);
            }
            update_option('chitikap_plugin-version', $PREMIUM_DEFAULTS['plugin-version']);
        }
    }

    function chpremium_uninstall() {
        delete_option("chpremium_options");
    }

	function chpremium_filter_content($text) {
		global $PREMIUM_DEFAULTS, $chpremium_options;

		$textContainsTag = preg_match_all("/(<\!--NO-ChitikaPremium-->)/is", $text, $matches);

		if($textContainsTag || is_feed() || ($chpremium_options['display'] == 'not_frontpage' && is_front_page())
                || ($chpremium_options['display'] == 'only_post_index' && is_home())) {
			return $text;
		}

        $vars = array();
		foreach ($chpremium_options as $option => $value) {
            $vars[$option] = "'" . $value . "'";
        }

        $vars['plugin-version'] = $PREMIUM_DEFAULTS['plugin-version'];
        list($vars['width'], $vars['height']) = explode('x', $chpremium_options['size']);

		// Get the chitikaPremium template
		$template = $PREMIUM_DEFAULTS['template'];

		// Put the chitikaPremium template into the post, replacing the user's tag
		$placement = $chpremium_options['placement'];

		if ($placement == 'bottom') {
			$text = $text . "\n" . $this->_chpremium_apply_template($template, $vars, 'below');
		} elseif($placement == 'both') {
			$text = $this->_chpremium_apply_template($template, $vars, 'above - both') . "\n" . $text. "\n" . $this->_chpremium_apply_template($template, $vars, 'below - both');
        } elseif ($placement == 'bp1p2') {
            $text_array = explode('</p>', $text, 2);
            $text = $text_array[0] . '</p>' . "\n" . $this->_chpremium_apply_template($template, $vars, 'above') . "\n" . $text_array[1];
		} else {
			$text = $this->_chpremium_apply_template($template, $vars, 'above') . "\n" . $text;
		}

		return $text;
	}

	function chpremium_options_page() {
        global $chpremium_options;

		if (isset($_POST['chpremium_update'])) {
            $options = array('titlecolor', 'textcolor', 'channel', 'client', 'background', 'display', 'placement', 'font', 'size', 'append', 'password');
            foreach ($options as $option) {
				$chpremium_options["{$option}"] = $_POST["{$option}"];
			}

            // Verify username
			$usr_verify = $this->chpremium_test_username(
                stripslashes($_POST['client']),
                stripslashes($_POST['password'])
            );

			if(!$usr_verify || $usr_verify == 'connection error' || $usr_verify == 'curl error'){
				$_usr_class = 'usralert';
                if (!$usr_verify) {
                    $chpremium_options['password'] = '';
                }
			} else {
                $chpremium_options['client'] = stripslashes($usr_verify);
            }

            update_option("chpremium_options", $chpremium_options);

			echo '<div class="updated"><p><strong>Your Chitika settings have been saved.</strong></p></div>';
            if (!$usr_verify){
                echo '<div class="updated"><p><strong><font color="red">
                    Username & password combination not correct. Please update username and password to start earning revenue from your Chitika Ads.
                </font></strong></p></div>';
            } else if ($usr_verify == 'connection error') {
                echo '<div class="updated"><p><strong><font color="red">
                Error connecting to validation server to verify your username, please try again later. You can manually verify that you have entered the correct username by logging into the
                 <a target="_blank" href="https://publishers.chitika.com">Chitika Publisher Panel</a> and matching the name in the top right of the navigation bar,
                 near support and account. Please note that your email is not your username. </font></strong></p></div>
                </font></strong></p></div>';
            } else if ($usr_verify == 'curl error') {
                 echo '<div class="updated"><p><strong><font color="red">IMPORTANT NOTICE!<br />We were unable to verify your username because your
                 host has disabled the use of Curl, our authentication method. You can manually verify that you have entered the correct username by logging into the
                 <a target="_blank" href="https://publishers.chitika.com">Chitika Publisher Panel</a> and matching the name in the top right of the navigation bar,
                 near support and account. Please note that your email is not your username. </font></strong></p></div>';
            }

            if (($usr_verify == 'connection error' || $usr_verify == 'curl error') && filter_var($chpremium_options['client'], FILTER_VALIDATE_EMAIL)) {
                echo '<div class="updated"><p><strong><font color="red">
                Your username should not be an email!
                </font></strong></p></div>';
            }
		}

        ?>
        <div class="wrap">
            <h2>Chitika Settings</h2>
            <fieldset class="options">
                <legend>Customize Your Chitika Ad Display Settings</legend>
                <h3>What is Chitika?</h3>
                <p>
                    <a href="http://chitika.com">Chitika</a> is a CPC advertising solution. The ads are search-targeted, meaning that they will
                    show relevant ads to your search visitors based on what they are searching for. Chitika ads will also show ads to non-search
                    users. The ads can be run on the same page as Google AdSense, or on their own as an AdSense Alternative.
                </p>

                <?php if((float)get_bloginfo('version') >= 2.2){ ?>
                    <div>
                        <h3>How Do I Preview Chitika on my Blog?</h3>
                        <p>Enter the URL of the page you want to preview your Chitika Ads on, and add the keyword to display ads for and click preview.</p>
                        <p>For additional help, <a href="http://support.chitika.com/customer/portal/emails/new">send us an email</a>.</p>

                        <div style="background-color:#EAF3FA; margin-left:10px; width:500px; padding:15px; line-height:1.6em;">
                            <form name="previewtool" id="previewtool" method="get">
                                <fieldset>
                                    <legend style="font-size:1.3em; font-weight:bold;">Chitika Preview Tool</legend>
                                    <label for="chpremium_url"><strong>URL</strong>  (For preview purposes only)</label><br />
                                    <input name="chpremium_url" type="text" id="chpremium_url" value="<?php echo bloginfo('url') ?>" size="45" /><br />
                                    <label for="chpremium_keywords"><strong>Keyword(s)</strong>  (For preview purposes only)</label><br />
                                    <input name="chpremium_keywords" type="text" id="chpremium_keywords" value="powered generators" size="45" />
                                    <p class="submit" style="border-top-width: 0pt; padding-top:0">
                                        <input type="button" onclick="var uri = jQuery('#chpremium_url').val() + '#chitikatest=' + jQuery('#chpremium_keywords').val();
                                        window.open( uri ,'chpremiumreview','width=600,height=500,status=1,toolbar=1,resizable=1,location=1,scrollbars=1');"
                                        name="chpremium_preview" value="Preview (in new window)" />
                                    </p>
                                </fieldset>

                                <p>
                                    This tool is for previewing only - it will append <code>#chitikatest=keywords</code> to the URL of your choosing to preview the ad unit.
                                    Only the form below will be saved to change the Chitika display options on your blog.
                                </p>
                            </form>
                        </div>
                    </div>
                <?php } else { ?>
                    <h3>How Do I Preview Chitika on my Blog?</h3>
                    <p>
                        Since Chitika ads will only display to your US and Canada search engine traffic, you need to append `#chitikatest=keywords`
                        to the end of your URL to preview Chitika in your blog. For additional help please view the
                        <a href="https://chitika.com/support/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=138&nav=0,13">preview support documentation</a>.
                    </p>
                <?php } ?>

                <br style="clear:both;" />
                <style type="text/css">
                    .usralert { font-weight:bold; }
                    input.usralert { font-weight:normal; font-style:italic; }
                </style>

                <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <h3>Settings</h3>

                    <table width="100%" cellspacing="2" cellpadding="5" class="form-table">
                        <tr valign="top">
                            <th width="33%" scope="row">Display</th>
                            <td>
                                <input name="display" type="radio" id="display_all" value="all" <?php if($chpremium_options['display'] == 'all' || empty($chpremium_options['display'])){ echo "checked='checked'";} ?>/>
                                <label for="display_all">Display Ads Everywhere</label><br />
                                <input name="display" type="radio" id="display_not_frontpage" value="not_frontpage" <?php if($chpremium_options['display'] == 'not_frontpage'){ echo "checked='checked'";} ?>/>
                                <label for="display_not_frontpage">Display Ads Everywhere but the Front Page</label><br />
                                <input name="display" type="radio" id="display_only_post_index" value="only_post_index" <?php if($chpremium_options['display'] == 'only_post_index'){ echo "checked='checked'";} ?>/>
                                <label for="display_only_post_index">Only Display Ads on the Post's Index Page</label><br />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th width="33%" scope="row">Placement</th>
                            <td>
                                <?php
                                    $placement = $chpremium_options['placement'];
                                    if( $placement == 'bottom'){
                                        $placement_put[1] = ' checked="checked"'; $placement_put[0] = ''; $placement_put[2] = ''; $placement_put[3] = '';
                                    } else if ($placement == 'both'){
                                        $placement_put[1] = ''; $placement_put[0] = ''; $placement_put[2] = ' checked="checked"'; $placement_put[3] = '';
                                                } else if ($placement == 'bp1p2') {
                                                        $placement_put[1] = ''; $placement_put[0] = ''; $placement_put[2] = ''; $placement_put[3] = ' checked="checked"';
                                    } else {
                                        $placement_put[1] = ''; $placement_put[0] = ' checked="checked"'; $placement_put[2] = ''; $placement_put[3] = '';
                                    }
                                ?>

                                <input name="placement" type="radio" id="placement" value="top" <?php echo $placement_put[0]; ?>/>
                                <label for="placement_top">Above Posts <em>(Recommended!)</em></label><br />
                                <input name="placement" type="radio" id="placement" value="bottom" <?php echo $placement_put[1]; ?>/>
                                <label for="placement_bottom">Below Posts</label><br />
                                <input name="placement" type="radio" id="placement" value="both" <?php echo $placement_put[2]; ?>/>
                                <label for="placement_both">Above and Below Posts</label><br />
                                <input name="placement" type="radio" id="placement" value="bp1p2" <?php echo $placement_put[3]; ?>/>
                                <label for="placement_bp1p2">Between First and Second Paragraph</label><br />

                                <p>Placing the code <code>&lt;!--no-chitikaPremium--&gt;</code> within a post will stop Chitika from displaying with that specific post.</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th width="33%" scope="row" id="username">Chitika Account Username</th>
                            <td>
                                <?php
                                    $_style_client =  $_style_password = $_stylep = '';
                                    if ( (!$chpremium_options['client'] || $chpremium_options['client'] == 'demo' ) && !isset($_POST['submit']) ) {
                                        $_style_client = 'style="background-color:#FFFBCC; border-color:#D54E21;"';
                                    }
                                    if ( (!$chpremium_options['password'] || $chpremium_options['password'] == '' ) && !isset($_POST['submit']) ) {
                                        $_style_password = 'style="background-color:#FFFBCC; border-color:#D54E21;"';
                                    }
                                    if(isset($_usr_class)){
                                        $_style .= ' class="' . $_usr_class.'"';
                                        $_stylep = ' class="' . $_usr_class.'"';
                                    }
                                ?>

                                Username <input name="client" type="text" id="client" value="<?php echo $chpremium_options['client'] ?>" <?php echo $_style_client; ?> size="50" /><br />
                                Password <input name="password" type="password" id="password" value="<?php echo $chpremium_options['password'] ?>" <?php echo $_style_password; ?> size="50" /><br />

                                <p <?php echo $_stylep; ?>>
                                    Please enter your Chitika username (found at the top right of your <a target="_blank" href="https://publishers.chitika.com">Chitika Publisher Panel</a>, near support and account). <br />
                                    Or, enter your email associated with your Chitika account. (Note: Your email will be replaced with your username after succesful login). <br /><br />
                                    If you don't have a Chitika account, please <a target="_blank" href="https://chitika.com/publishers/apply?refid=wordpressplugin">sign up</a> for one.<br />
                                    Note: Until your Chitika Account is approved, you will not be able to start earning revenue from your Chitika Ads.
                                </p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th width="33%" scope="row">Size</th>
                            <td>
                                <fieldset>
                                    <legend class="hidden">Chitika Size</legend>
                                    <?php
                                        $_font = $chpremium_options['size'];
                                        $put_size = array_fill(0, 26, '');
                                        switch($_font){
                                            case '728x90' :
                                                $put_size[0] = ' selected="selected"';		break;
                                            case '120x600' :
                                                $put_size[1] = ' selected="selected"';		break;
                                            case '160x600' :
                                                $put_size[2] = ' selected="selected"';		break;
                                            case '468x180' :
                                                $put_size[3] = ' selected="selected"';		break;
                                            case '468x90' :
                                                $put_size[5] = ' selected="selected"';		break;
                                            case '468x60' :
                                                $put_size[6] = ' selected="selected"';		break;
                                            case '550x120' :
                                                $put_size[7] = ' selected="selected"';		break;
                                            case '550x90' :
                                                $put_size[8] = ' selected="selected"';		break;
                                            case '450x90' :
                                                $put_size[9] = ' selected="selected"';		break;
                                            case '430x90' :
                                                $put_size[10] = ' selected="selected"';	break;
                                            case '400x90' :
                                                $put_size[11] = ' selected="selected"';	break;
                                            case '300x250' :
                                                $put_size[12] = ' selected="selected"';	break;
                                            case '300x150' :
                                                $put_size[13] = ' selected="selected"';	break;
                                            case '300x125' :
                                                $put_size[14] = ' selected="selected"';	break;
                                            case '300x70' :
                                                $put_size[15] = ' selected="selected"';	break;
                                            case '250x250' :
                                                $put_size[16] = ' selected="selected"';	break;
                                            case '200x200' :
                                                $put_size[17] = ' selected="selected"';	break;
                                            case '160x160' :
                                                $put_size[18] = ' selected="selected"';	break;
                                            case '336x280' :
                                                $put_size[19] = ' selected="selected"';	break;
                                            case '336x160' :
                                                $put_size[20] = ' selected="selected"';	break;
                                            case '334x100' :
                                                $put_size[21] = ' selected="selected"';	break;
                                            case '180x300' :
                                                $put_size[22] = ' selected="selected"';	break;
                                            case '180x150' :
                                                $put_size[23] = ' selected="selected"';	break;
                                            case '550x250' :
                                                $put_size[24] = ' selected="selected"';	break;
                                            case '500x250' :
                                                $put_size[25] = ' selected="selected"';	break;
                                            default:
                                                $put_size[4] = ' selected="selected"';		break;
                                        }
                                    ?>

                                    <select name="size" id="size">
                                        <option value="550x250"<?php echo $put_size[24]; ?>>550 x 250 *New!* MEGA-Unit</option>
                                        <option value="500x250"<?php echo $put_size[25]; ?>>500 x 250 *New!* MEGA-Unit</option>
                                        <option value="468x180"<?php echo $put_size[3]; ?>>468 x 180 Blog Banner</option>
                                        <option value="468x120"<?php echo $put_size[4]; ?>>468 x 120 Blog Banner</option>
                                        <option value="" disabled="disabled"></option>
                                        <option value="468x90"<?php echo $put_size[5]; ?>>468 x 90 Small Blog Banner</option>
                                        <option value="468x60"<?php echo $put_size[6]; ?>>468 x 60 Mini Blog Banner</option>
                                        <option value="" disabled="disabled"></option>
                                        <option value="728x90"<?php echo $put_size[0]; ?>>728 x 90 Leaderboard</option>
                                        <option value="120x600"<?php echo $put_size[1]; ?>>120 x 600 Skyscraper</option>
                                        <option value="160x600"<?php echo $put_size[2]; ?>>160 x 600 Wide Skyscraper</option>
                                        <option value="" disabled="disabled"></option>
                                        <option value="550x120"<?php echo $put_size[7]; ?>>550 x 120 Content Banner</option>
                                        <option value="550x90"<?php echo $put_size[8]; ?>>550 x 90 Content Banner</option>
                                        <option value="450x90"<?php echo $put_size[9]; ?>>450 x 90 Small Content Banner</option>

                                        <option value="430x90"<?php echo $put_size[10]; ?>>430 x 90 Small Content Banner</option>
                                        <option value="400x90"<?php echo $put_size[11]; ?>>400 x 90 Small Content Banner</option>
                                        <option value="" disabled="disabled"></option>
                                        <option value="300x250"<?php echo $put_size[12]; ?>>300 x 250 Rectangle</option>
                                        <option value="300x150"<?php echo $put_size[13]; ?>>300 x 150 Rectangle, Wide</option>
                                        <option value="300x125"<?php echo $put_size[14]; ?>>300 x 125 Mini Rectangle, Wide</option>

                                        <option value="300x70"<?php echo $put_size[15]; ?>>300 x 70 Mini Rectangle, Wide</option>
                                        <option value="" disabled="disabled"></option>
                                        <option value="250x250"<?php echo $put_size[16]; ?>>250 x 250 Square</option>
                                        <option value="200x200"<?php echo $put_size[17]; ?>>200 x 200 Small Square</option>
                                        <option value="160x160"<?php echo $put_size[18]; ?>>160 x 160 Small Square</option>
                                        <option value="" disabled="disabled"></option>
                                        <option value="336x280"<?php echo $put_size[19]; ?>>336 x 280 Rectangle</option>

                                        <option value="336x160"<?php echo $put_size[20]; ?>>336 x 160 Rectangle, Wide</option>
                                        <option value="" disabled="disabled"></option>
                                        <option value="334x100"<?php echo $put_size[21]; ?>>334 x 100 Small Rectangle, Wide</option>
                                        <option value="180x300"<?php echo $put_size[22]; ?>>180 x 300 Small Rectangle, Tall</option>
                                        <option value="180x150"<?php echo $put_size[23]; ?>>180 x 150 Small Rectangle</option>
                                    </select>
                                </fieldset>

                                <p>Recommended sizes are the MEGA-Unit (550x250) which has an awesome CTR or the 468 wide x 120 high which fits well in most WordPress templates.</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th width="33%" scope="row">Font</th>
                            <td>
                                <?php
                                    $_font = $chpremium_options['font'];
                                    $put_font = array_fill(0, 8, '');
                                    switch($_font){
                                        case 'Arial' :
                                            $put_font[1] = ' selected="selected"';		break;
                                        case 'Comic Sans MS' :
                                            $put_font[2] = ' selected="selected"';		break;
                                        case 'Georgia' :
                                            $put_font[3] = ' selected="selected"';		break;
                                        case 'Tahoma' :
                                            $put_font[4] = ' selected="selected"';		break;
                                        case 'Times' :
                                            $put_font[5] = ' selected="selected"';		break;
                                        case 'Verdana' :
                                            $put_font[6] = ' selected="selected"';		break;
                                        case 'Courier' :
                                            $put_font[7] = ' selected="selected"';		break;
                                        default:
                                            $put_font[0] = ' selected="selected"';		break;
                                    }
                                ?>

                                <select name="font" id="font">
                                    <option value="" <?php echo $put_font[0]; ?>>-- Default Font --</option>
                                    <option value="Arial"<?php echo $put_font[1]; ?>>Arial</option>
                                    <option value="Comic Sans MS"<?php echo $put_font[2]; ?>>Comic Sans MS</option>
                                    <option value="Georgia"<?php echo $put_font[3]; ?>>Georgia</option>
                                    <option value="Tahoma"<?php echo $put_font[4]; ?>>Tahoma</option>
                                    <option value="Times"<?php echo $put_font[5]; ?>>Times</option>
                                    <option value="Verdana"<?php echo $put_font[6]; ?>>Verdana</option>
                                    <option value="Courier"<?php echo $put_font[7]; ?>>Courier</option>
                                </select>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th width="33%" scope="row">Channel Tracking</th>
                            <td>
                                <input name="channel" type="text" id="channel" value="<?php echo $chpremium_options['channel'] ?>" size="50" /><br />
                                <p><a href="http://support.chitika.com/customer/portal/articles/62580-tracking-the-performance-of-each-ad-unit" target="_blank">What are Channels?</a></p>
                                <?php
                                    $_append = $chpremium_options['append'];
                                    if( $_append == 'true'){
                                        $append_put = ' checked="checked"';
                                    } else {
                                        $append_put = '';
                                    }
                                ?>

                                <input name="append" type="checkbox" id="append" <?php echo $append_put; ?> value="true" /> Append top / bottom to channel name depending on ad placement?
                            </td>
                        </tr>

                        <tr valign="top">
                            <th width="33%" scope="row">Background Color</th>
                            <td>
                                #<input name="background" type="text" id="background" value="<?php echo $chpremium_options['background'] ?>" size="25" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th width="33%" scope="row">Link Color</th>
                            <td>
                                #<input name="titlecolor" type="text" id="titlecolor" value="<?php echo $chpremium_options['titlecolor'] ?>" size="25" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th width="33%" scope="row">Text Color</th>
                            <td>
                                #</strong><input name="textcolor" type="text" id="textcolor" value="<?php echo $chpremium_options['textcolor'] ?>" size="25" />
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <input type="submit" name="chpremium_update" id="chpremium_update" value="Update Settings &raquo;" style="font-weight:bold;" />
                    </p>
                </form>
            </fieldset>
        </div>
        <?php
	}

	function chpremium_add_options_page() {
		add_options_page('Chitika Settings', 'Chitika', 10, 'premium/premium.php', array(&$this, 'chpremium_options_page'));
	}

	function _chpremium_prepare_template_var(&$item, $key) {
		$item = '{%' . $item . '%}';
	}

	function _chpremium_apply_template($str, $replace = 0, $position = 'top') {
		global $chpremium_options;

		if($chpremium_options['append'] == 'true'){
			$replace['channel'] = '"' . trim($replace['channel'], "'") . ' ' .$position .'"';
		}
		$replace['placement'] = str_replace(array(' ', '-'),'', $position);

	    if ( is_array($replace) ) {
			$from = array_keys($replace);
			array_walk($from, array(&$this, '_chpremium_prepare_template_var'));

			$to = array_values($replace);
			return str_replace($from, $to, $str);
		}
		return $str;
	}

    function chpremium_test_username($user, $password){ // username verification
        if (!function_exists("curl_exec") || !function_exists("curl_init")) {
            return 'curl error';
        }

        $curl = curl_init();
        if ($curl) {
            curl_setopt($curl, CURLOPT_URL, 'https://publishers.chitika.com/login?output=json');
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl, CURLOPT_SSLVERSION,3);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, true);

            $data = curl_exec($curl);
            if ($data == false || empty($data)) {
                return 'connection error';
            }

            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($data, 0, $header_size);
            $body = substr($data, $header_size);
            $body = json_decode($body);
            curl_close($curl);

            preg_match_all('|Set-Cookie: (.*);|U', $data, $matches);
            $cookies = implode('; ', $matches[1]);
            $tn = $body->{'csrf_token'}->{'token_name'};
            $h = $body->{'csrf_token'}->{'hash'};
            $post_fields = array('password' => $password, 'username' => $user, $tn => $h);
        } else {
            return 'connection error';
        }
        $curl = curl_init();
        if ($curl) {
            curl_setopt($curl, CURLOPT_URL, 'https://publishers.chitika.com/validate');
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl, CURLOPT_SSLVERSION,3);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_COOKIE, $cookies);

            $data = curl_exec($curl);
            if ($data == false || empty($data)) {
                return 'connection error';
            }

            $data = json_decode($data);
            return $data->{'client'};
        } else {
            return 'connection error';
        }
    }
}

$chpremium_plugin =	new chitikaPremium();

register_uninstall_hook( __FILE__, 'chpremium_uninstall' );
