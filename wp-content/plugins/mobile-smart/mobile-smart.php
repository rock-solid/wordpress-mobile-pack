<?php
/*
Plugin Name: Mobile Smart
Plugin URI: http://www.dansmart.co.uk/downloads/
Version: v1.3.5
Author: <a href="http://www.dansmart.co.uk/">Dan Smart</a>
Description: Mobile Smart contains helper tools for mobile devices +  switching mobile themes. <a href="/wp-admin/options-general.php?page=mobile-smart.php">Settings</a>
             determination of mobile device type or tier in CSS and PHP code, using
             detection by Mobile ESP project.
 */

/*  Copyright 2011 Dan Smart  (email : dan@dansmart.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 * Attributation:
 *  - Detection performed by MobileESP project code (www.mobileesp.com)
 * - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */


// -------------------------------------------------------------------------
// Defines
// -------------------------------------------------------------------------
define('MOBILESMART_DOMAIN', 'mobilesmart');
define('MOBILESMART_PLUGIN_PATH', WP_PLUGIN_DIR . '\mobile-smart');

// MAIN DEVICES (for more, see lib/mdetect.php which can be detected directly)
define ('MOBILE_DEVICE_OPERA_MINI', 'operamini');
define ('MOBILE_DEVICE_IPHONE', 'iphone');
define ('MOBILE_DEVICE_IPAD', 'ipad');
define ('MOBILE_DEVICE_IPOD', 'ipod');
define ('MOBILE_DEVICE_ANDROID', 'android');
define ('MOBILE_DEVICE_ANDROID_WEBKIT', 'android_webkit');
define ('MOBILE_DEVICE_ANDROID_TABLET', 'android table');
define ('MOBILE_DEVICE_SERIES60', 'series_60');
define ('MOBILE_DEVICE_SYMBIAN_OS', 'symbian_os');
define ('MOBILE_DEVICE_WINDOWS_MOBILE', 'windows_mobile');
define ('MOBILE_DEVICE_WINDOWS_PHONE_7', 'windows_phone_7');
define ('MOBILE_DEVICE_BLACKBERRY', 'blackberry');
define ('MOBILE_DEVICE_BLACKBERRY_TABLET', 'blackberry_tablet');
define ('MOBILE_DEVICE_BLACKBERRY_WEBKIT', 'blackberry_webkit');
define ('MOBILE_DEVICE_BLACKBERRY_TOUCH', 'blackberry_touch');
define ('MOBILE_DEVICE_PALM_OS', 'palm_os');
define ('MOBILE_DEVICE_OTHER', 'other_mobile');

// TIERS
define ('MOBILE_DEVICE_TIER_TOUCH', 'mobile-tier-touch');
define ('MOBILE_DEVICE_TIER_TABLET', 'mobile-tier-tablet');
define ('MOBILE_DEVICE_TIER_RICH_CSS', 'mobile-tier-rich-css');
define ('MOBILE_DEVICE_TIER_SMARTPHONE', 'mobile-tier-smartphone');
define ('MOBILE_DEVICE_TIER_OTHER', 'mobile-tier-other-mobile');

// MANUAL SWITCHING
define ('MOBILESMART_SWITCHER_GET_PARAM', 'mobile_switch');
define ('MOBILESMART_SWITCHER_MOBILE_STR', 'mobile');
define ('MOBILESMART_SWITCHER_DESKTOP_STR', 'desktop');
define ('MOBILESMART_SWITCHER_COOKIE', 'mobile-smart-switcher');
define ('MOBILESMART_SWITCHER_COOKIE_EXPIRE', 3600); // 3600
define ('MOBILESMART_SWITCHER_DOMAIN_SWITCH', 'domain_switch');
define ('MOBILESMART_SWITCHER_DOMAIN_SWITCH_DOMAIN', 'mobile_domain');


// SOME DUMMY TIER SCREEN DIMENSIONS FOR TRANSCODING IMAGES
define ('MOBILE_DEVICE_TIER_TOUCH_MAX_WIDTH', 300);
define ('MOBILE_DEVICE_TIER_TOUCH_MAX_HEIGHT', 400);
define ('MOBILE_DEVICE_TIER_TABLET_MAX_WIDTH', 1024);
define ('MOBILE_DEVICE_TIER_TABLET_MAX_HEIGHT', 768);
define ('MOBILE_DEVICE_TIER_RICH_CSS_MAX_WIDTH', 300);
define ('MOBILE_DEVICE_TIER_RICH_CSS_MAX_HEIGHT', 400);
define ('MOBILE_DEVICE_TIER_SMARTPHONE_MAX_WIDTH', 200);
define ('MOBILE_DEVICE_TIER_SMARTPHONE_MAX_HEIGHT', 250);
define ('MOBILE_DEVICE_TIER_OTHER_MAX_WIDTH', 100);
define ('MOBILE_DEVICE_TIER_OTHER_MAX_HEIGHT', 150);

// -------------------------------------------------------------------------
// Includes
// -------------------------------------------------------------------------
require_once('lib/mdetect.php');
require_once('mobile-smart-switcher-widget.php');

// -------------------------------------------------------------------------
// Plugin Class
// -------------------------------------------------------------------------
if (!class_exists("MobileSmart"))
{
  class MobileSmart extends uagent_info
  {
    // -------------------------------------------------------------------------
    // Attributes
    // -------------------------------------------------------------------------
    var $admin_optionsName = "MobileSmartOptions";
    var $admin_options = array('mobile_theme'=>'default',
                               'enable_theme_switching'=>true);

    var $device = ''; // current device
    var $deviceTier = ''; // current device tier

    var $switcher_cookie = null;
    
    var $detectmobile = false;
    var $detect_from_domain = false;
    var $detect_from_cookie = false;

    // -------------------------------------------------------------------------
    // Methods
    // -------------------------------------------------------------------------

    // -------------------------------------------------------------------------
    // Method: Constructor
    // -------------------------------------------------------------------------
    // PHP 4 version
    function MobileSmart()
    {
      // init parent constructor
      parent::uagent_info();
      
      // translations
      load_plugin_textdomain(MOBILESMART_DOMAIN);

      if (isset($_COOKIE[MOBILESMART_SWITCHER_COOKIE]))
      {
        $this->switcher_cookie = $_COOKIE[MOBILESMART_SWITCHER_COOKIE];
        //echo "Construct cookie: $this->switcher_cookie<br/><br/>";
      }
    }

    // -------------------------------------------------------------------------
    // Method: initialisePlugin
    // Description: WP initialisation of the plugin
    // -------------------------------------------------------------------------
    function initialisePlugin()
    {
      // initialise the admin options
      $this->addAdminOptions();
    }

    // -------------------------------------------------------------------------
    // Method: addAdminOptions
    // Description: add the options
    // -------------------------------------------------------------------------
    function addAdminOptions()
    {
      add_option($this->admin_optionsName, $this->admin_options);
    }

    // -------------------------------------------------------------------------
    // Method: getAdminOptions
    // Description: gets the admin panel options
    // -------------------------------------------------------------------------
    function getAdminOptions()
    {
      // get the options from WP
      $wp_options = get_option($this->admin_optionsName);

      // if already existing data
      if (!empty($wp_options))
      {
        // populate our adminOptions with wp options
        foreach($wp_options as $key=>$wp_option)
        {
          $this->admin_options[$key] = $wp_option;
        }
      }

      // update WP
      update_option($this->admin_optionsName, $this->admin_options);

      return $this->admin_options;
    }
    
    
    /**
     * Set meta data option from a checkbox in the admin
     * @param array $options
     * @param type $meta_key
     * @param type $label
     * @return array status message array
     */
    private function adminSetOptionFromCheckbox(&$options, $meta_key, $label)
    {
      $status_message = array();
      if (isset($_POST[$meta_key]))
      {
        // enable theme switching
        if ($options[$meta_key] != true)
        {
          $options[$meta_key] = true;

          $status_message = array('updated', $label.' : '.__('enabled', MOBILESMART_DOMAIN));
        }
      }
      else
      {
        // disable theme switching
        if ($options[$meta_key] != false)
        {
          $options[$meta_key] = false;

          $status_message = array('updated', $label.' : '.__('disabled', MOBILESMART_DOMAIN));
        }
      }

      return $status_message;
    }
    
    /**
     * Set meta data option from a checkbox in the admin
     * @param array $options
     * @param type $meta_key
     * @param type $label
     * @return array status message array
     */
    private function adminSetOptionFromTextboxURL(&$options, $meta_key, $label)
    {
      $status_message = array();
      if (isset($_POST[$meta_key]))
      {
        $options[$meta_key] = filter_var($_POST[$meta_key], FILTER_SANITIZE_URL);

        $status_message = array('updated', $label.' : '.__('saved', MOBILESMART_DOMAIN));
      }
      else
      {
        $options[$meta_key] = '';

        $status_message = array('updated', $label.' : '.__('saved', MOBILESMART_DOMAIN));
      }

      return $status_message;
    }

    // -------------------------------------------------------------------------
    // Method: displayAdminOptions
    // Description: displays the admin panel
    // -------------------------------------------------------------------------
    function displayAdminOptions()
    {
      $options = $this->getAdminOptions();
      
      $themes = get_themes();
      
      $current_tab = (isset($_GET['tab']) ? $_GET['tab'] : 1);
      
      /*echo '<pre>';
      print_r($_POST);
      echo '</pre>';*/

      if (isset($_POST['submit']))
      {
        $status_messages = array();
        
        switch ($current_tab)
        {
          case 1:
            // Enable / Disable theme switching
            $status_messages[] = $this->adminSetOptionFromCheckbox($options, 'enable_theme_switching', __('Theme switching', MOBILESMART_DOMAIN));
            
            // Get choice of mobile theme
            if ($options['mobile_theme'] != $_POST['theme'])
            {
              $theme_name = $_POST['theme'];

              if (array_key_exists($theme_name, $themes))
              {
                $options['mobile_theme'] = $themes[$theme_name]['Template'];
                $options['mobile_theme_stylesheet'] = $themes[$theme_name]['Stylesheet'];

                $status_messages[] = array('updated', __('Mobile theme updated to: ', MOBILESMART_DOMAIN) . $_POST['theme']);
              }
            }
            
            // Enable / Disable switching for tablets
            $status_messages[] = $this->adminSetOptionFromCheckbox($options, 'switch_for_tablets', __('Switching for Tablets', MOBILESMART_DOMAIN));
            break;
          case 2:
            // Enable / Disable domain switching
            $status_messages[] = $this->adminSetOptionFromCheckbox($options, 'enable_domain_switching', __('Domain Switching', MOBILESMART_DOMAIN));
            
            // Save Domain Switching URL
            $status_messages[] = $this->adminSetOptionFromTextboxURL($options, 'mobile_domain', __('Mobile Domain', MOBILESMART_DOMAIN));
            break;
          case 3:
            // Enable / Disable manual switching
            $status_messages[] = $this->adminSetOptionFromCheckbox($options, 'enable_manual_switch', __('Manual theme switching', MOBILESMART_DOMAIN));
            
            // Enable / Disable footer manual switching
            $status_messages[] = $this->adminSetOptionFromCheckbox($options, 'enable_manual_switch_in_footer', __('Manual theme switching in footer', MOBILESMART_DOMAIN));

            // Enable / Disable desktop manual switching
            $status_messages[] = $this->adminSetOptionFromCheckbox($options, 'allow_desktop_switcher', __('Manual theme switching on desktop', MOBILESMART_DOMAIN));
            break;
          case 4:
            // Enable / Disable image transcoding
            $status_messages[] = $this->adminSetOptionFromCheckbox($options, 'enable_image_transcoding', __('Image transcoding', MOBILESMART_DOMAIN));
            break;
          case 5:
            // Enable / Disable mobile pages
            $status_messages[] = $this->adminSetOptionFromCheckbox($options, 'enable_mobile_pages', __('Mobile Pages', MOBILESMART_DOMAIN));
        }

        // output status messages
        if (!empty($status_messages))
        {
          ?>
            <div class="updated">
              <?php foreach ($status_messages as $message) : ?>
                <p><strong><?php echo $message[1] ?></strong></p>
              <?php endforeach; ?>
            </div>
          <?php
        }

        // update the options
        update_option($this->admin_optionsName, $options);
      }

      // Display the admin page
      ?>
      <script type="text/javascript">
      </script>
      <div class="wrap clearfix">
        <style type="text/css" media="all">
          
          #mobilesmart_infobox {
            border: 1px solid #999;
            padding: 10px; margin: 10px;
            background-color: #efefef;
            float: right;
            width: 200px;
          }
          
          #mobilesmart_infobox .subsection {
            border: 1px solid #cdcdcd;
            padding: 10px; margin: 10px 0;
          }
        </style>
          <h2>Mobile Smart</h2>
          <div id="mobilesmart_infobox">
            <div class="subsection clearfix">
              <h3>Mobile Smart Pro</h3>
              <p>The ultimate mobile plugin for WordPress:</p>
              <ul>
                <li><strong>Domain Switching</strong> - redirect to a mobile domain (e.g. m.domain.com)</li>
                <li><strong>Mobile Pages</strong> - mobile specific content direct from your posts &amp; pages</li>
                <li><strong>Mobile Menus</strong> - mobile versions of your menus</li>
                <li><strong>Device Detection</strong> - mobileESP or DeviceAtlas support</li>
              </ul>
              <a href="http://www.mobile-smart.co.uk/">Find out more</a>
            </div>
            <div class="subsection clearfix">
              <h3>Mobile Smart Newsletter</h3>
              <!-- Begin MailChimp Signup Form -->
              <div id="mc_embed_signup">
              <form action="http://dansmart.us2.list-manage.com/subscribe/post?u=d2059b426acf8c7232bd417a2&amp;id=eddd2b41ad" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
                  <p><label for="mce-EMAIL">Sign up for Mobile Smart updates, plus articles on developing websites for mobile devices and WordPress. </label>
                  <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required/></p>
                  <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
              </form>
              </div>

              <!--End mc_embed_signup-->
            </div>
          </div>
          <div id="mobilesmart_main_container">
            <p><em><strong>Mobile theme switching and more</strong></em></p>
            <p><strong>Tabs overview:</strong><br/><em>Mobile Theme: Set the mobile theme to be displayed when viewed on a mobile device</br>
                Domain Switching (PRO only): Redirect to a mobile domain (e.g. m.yourdomain.com) when viewed on a mobile device</em><br/>
                Manual Switching: Add a link in footer (or widget) allowing user to switch between mobile and desktop versions<br/>
                Transcoding: Resize images to mobile scale<br/>
                Mobile Pages (PRO only): Mobile versions of normal page content</em>
            </p>
            <?php
              function display_active_tab($tab, $current_tab)
              { 
                if ($current_tab == $tab) {
                  echo 'nav-tab-active';
                }
              }
            ?>
            <h3 class="nav-tab-wrapper">
              <a href="<?php echo add_query_arg('tab', 1); ?>" class="nav-tab <?php display_active_tab(1, $current_tab); ?>">Mobile Theme</a>
              <a href="<?php echo add_query_arg('tab', 2); ?>" class="nav-tab <?php display_active_tab(2, $current_tab); ?>">Domain Switching (PRO)</a>
              <a href="<?php echo add_query_arg('tab', 3); ?>" class="nav-tab <?php display_active_tab(3, $current_tab); ?>">Manual Switching</a>
              <a href="<?php echo add_query_arg('tab', 4); ?>" class="nav-tab <?php display_active_tab(4, $current_tab); ?>">Transcoding</a>
              <a href="<?php echo add_query_arg('tab', 5); ?>" class="nav-tab <?php display_active_tab(5, $current_tab); ?>">Mobile Pages (PRO)</a>
            </h3>
            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
            <?php
              switch ($current_tab)
              {
                case 1: $this->displayAdminTabMobileTheme($options, $themes); break;
                case 2: $this->displayAdminTabDomainSwitching($options); break;
                case 3: $this->displayAdminTabManualSwitching($options); break;
                case 4: $this->displayAdminTabTranscoding($options); break;
                case 5: $this->displayAdminTabMobilePages($options); break;
                default: $this->displayAdminTabMobileTheme($options, $themes); break;
              }
            ?>

              <div class="submit">
                <input type="submit" name="submit" value="<?php _e('Update Settings', 'MobileSmart'); ?>"/>
              </div>
            </form>
          </div>
      </div>
      <?php
    }
    
    /**
     * display mobile theme admin tab
     * @param type $options
     * @param type $themes 
     */
    function displayAdminTabMobileTheme($options, $themes)
    {
      ?>
      <h3>Mobile Theme</h3>
      
      <h4>Mobile Switching</h4>
      <p>Enable switching via user agent detection:</p>
      <label for="enable_theme_switching">Enable <input type="checkbox" name="enable_theme_switching" id="enable_theme_switching" <?php if ($options['enable_theme_switching']) { echo "checked"; } ?>/></label>
      
      <p>Choose the mobile theme that will be displayed when a mobile device is detected.</p>
        <label for="theme">Mobile theme: <select id="theme" name="theme">
            <?php
              foreach ($themes as $theme_name => $theme)
              {
                ?>
                <option value="<?php echo $theme_name; ?>" <?php if ($theme['Template'] == $options['mobile_theme']) { echo "selected"; } ?>><?php echo $theme['Name']; ?></option>
                <?php
              }
            ?>
          </select></label>
      
      <h4>Tablets</h4>
      <p>
        <em>Most people choose to show the desktop theme on tablets such as iPads. You may wish to enable your mobile theme and pull
        in a tablet specific stylesheet and/or other content via the mobile theme.</em></p>
      <p>
        <label for="switch_for_tablets">Enable theme switching for tablets (e.g. iPad):  <input type="checkbox" name="switch_for_tablets" id="switch_for_tablets" <?php if ($options['switch_for_tablets']) { echo "checked"; } ?>/></label>
      </p>
      <?php
    }
    
    /**
     * Display domain switching tab
     * @param type $options 
     */
    function displayAdminTabDomainSwitching($options)
    {
      ?>
      <h3>Domain Switching (PRO)</h3>
      
      <?php $this->displayProNotice(); ?>
      
      <div style="color: #999">
      <p>If a user arrives at your mobile subdomain, you can automatically switch to the mobile theme by enabling domain switching.</p>
      <p>If you also have manual switching enabled, manual switching will take priority - so they will be redirected to the desktop version of the site
         until they switch back.</p>
      <p>
        <label for="enable_domain_switching"><strong>Enable domain switching: </strong> <input type="checkbox" disabled="disabled" name="enable_domain_switching" id="enable_domain_switching" <?php if ($options['enable_domain_switching']) { echo "checked"; } ?>/></label>
      </p>
      
      <p>
        <label for="mobile_domain"><strong>Your mobile domain: </strong> <input type="text" name="mobile_domain" disabled="disabled" value="<?php echo $options['mobile_domain']; ?>"/></label><em> You must enable domain switching and have a subdomain for this to function correctly.</em>
      </p>
      <br/>
      <h4><em>Notes on subdomains and DNS</em></h4>
      <p><em>To use a mobile subdomain, you'll need to go to your DNS control panel (domain name), and create either an A record or a CNAME record
         pointing to the same location. An A record would point the to the same IP address, a CNAME record would point to the main domain.</em></p>
      <p><em>If you're on shared hosting, you may need to get your hosting provider to add your mobile subdomain as a 'parked domain'
         to your account so that the server points you to your existing account.</em></p>
      </div>
      <?php
    }
    
    /**
     * display Transcoding admin tab
     * @param type $options 
     */
    function displayAdminTabTranscoding($options)
    {
      ?>
      <h3>Transcoding</h3>
      
      <h4>In development: Enable image transcoding</h4>
      
      <p>Do not enable this unless you want to try the TimThumb powered image transcoding. Make sure you enable your cache folder to 777.</p>
      <p><em>Manual switching (above) must be enabled for this to work properly.</em></p>
      <label for="enable_image_transcoding">Enable image transcoding <input type="checkbox" name="enable_image_transcoding" id="enable_image_transcoding" <?php if ($options['enable_image_transcoding']) { echo "checked"; } ?>/>
      </label>
      <?php
    }
    
    /**
     * Display Manual Switching admin tab
     * @param type $options 
     */
    function displayAdminTabManualSwitching($options)
    {
      ?>
      <h3>Manual Switching</h3>
      
      <h4>Enable Manual Switcher</h4>
      <p>You can add a link to your pages which will allow the user to manually select the version
       (desktop or mobile) that they want. Once you enable Manual Switching, you can use either the
       footer link or the Mobile Smart Manual Switcher widget.</p>
      <label for="enable_manual_switch"><strong>Enable Manual Switcher:</strong> <input type="checkbox" name="enable_manual_switch" id="enable_manual_switch" <?php if ($options['enable_manual_switch']) { echo "checked"; } ?>/>
      </label><br/>

      <h4>Enable a Manual Switcher link in the footer</h4>
      <p><em>Manual switching (above) must be enabled for this to work properly.</em></p>
      <label for="enable_manual_switch_in_footer"><strong>Enable Manual Switcher in footer:</strong> <input type="checkbox" name="enable_manual_switch_in_footer" id="enable_manual_switch_in_footer" <?php if ($options['enable_manual_switch_in_footer']) { echo "checked"; } ?>/>
      </label><br/>

      <h4>Allow manual switching on desktop</h4>
      <p>This is most useful for debugging your themes. You probably
      do not want to allow your users to switch to the mobile version whilst viewing on a desktop in other cases.</p>
      <p><em>Manual switching (above) must be enabled for this to work properly.</em></p>
      <label for="allow_desktop_switcher"><strong>Enable Manual Switcher Link whilst on Desktop</strong> <input type="checkbox" name="allow_desktop_switcher" id="allow_desktop_switcher" <?php if ($options['allow_desktop_switcher']) { echo "checked"; } ?>/>
      </label>
      <?php
    }
    
    /**
     * Display Mobile Pages admin tab
     * @param type $options 
     */
    function displayAdminTabMobilePages($options)
    {
      ?>
      <h3>Mobile Pages</h3>
      
      <?php $this->displayProNotice(); ?>
      
      <div style="color: #999">
      <p>It can be beneficial to have mobile versions of your content, specifically targeted at the smaller mobile pages.</p>
      <p>
        <label for="enable_mobile_pages"><strong>Enable Mobile Pages</strong> <input type="checkbox" disabled="disabled" name="enable_mobile_pages" <?php if ($options['enable_mobile_pages']) { echo "checked"; } ?>/></label>
      </p>
      </div>
      <?php
    }
    
    function displayProNotice()
    {
      ?>
      <p>Coming soon: Mobile Smart PRO - sign up to the newsletter to get news of when it will be released.</p>
      <?php
    }

    // ---------------------------------------------------------------------------
    // Function: getUserAgentString
    // Description: gets the user agent string
    // ---------------------------------------------------------------------------
    function getUserAgentString()
    {
      return $this->Get_Uagent();
    }

    // ---------------------------------------------------------------------------
    // Function: getAcceptString
    // Description: gets the accept string
    // ---------------------------------------------------------------------------
    function getAcceptString()
    {
      return $this->Get_HttpAccept();
    }

    // ---------------------------------------------------------------------------
    // Function: getCurrentDevice
    // Description: gets the current device
    // ---------------------------------------------------------------------------
    function getCurrentDevice()
    {
      if ($this->device == '')
      {
        if ($this->DetectOperaMini())
        {
          $this->device = MOBILE_DEVICE_OPERA_MINI;
        }
        else if ($this->DetectIpad())
        {
          $this->device = MOBILE_DEVICE_IPAD;
        }
        else if ($this->DetectIphone())
        {
          $this->device = MOBILE_DEVICE_IPHONE;
        }
        else if ($this->DetectIpod())
        {
          $this->device = MOBILE_DEVICE_IPOD;
        }
        else if ($this->DetectAndroid())
        {
          $this->device = MOBILE_DEVICE_ANDROID;
        }
        else if ($this->DetectAndroidTablet())
        {
          $this->device = MOBILE_DEVICE_ANDROID_TABLET;
        }
        else if ($this->DetectAndroidWebkit())
        {
          $this->device = MOBILE_DEVICE_ANDROID_WEBKIT;
        }
        else if ($this->DetectSeries60())
        {
          $this->device = MOBILE_DEVICE_SERIES60;
        }
        else if ($this->DetectSymbianOS())
        {
          $this->device = MOBILE_DEVICE_SYMBIAN_OS;
        }
        else if ($this->DetectWindowsMobile())
        {
          $this->device = MOBILE_DEVICE_WINDOWS_MOBILE;
        }
        else if ($this->DetectWindowsPhone7())
        {
          $this->device = MOBILE_DEVICE_WINDOWS_PHONE_7;
        }
        else if ($this->DetectBlackBerry())
        {
          $this->device = MOBILE_DEVICE_BLACKBERRY;
        }
        else if ($this->DetectBlackBerryTablet())
        {
          $this->device = MOBILE_DEVICE_BLACKBERRY_TABLET;
        }
        else if ($this->DetectBlackBerryWebkit())
        {
          $this->device = MOBILE_DEVICE_BLACKBERRY_WEBKIT;
        }
        else if ($this->DetectBlackBerryTouch())
        {
          $this->device = MOBILE_DEVICE_BLACKBERRY_TOUCH;
        }
        else if ($this->DetectPalmOS())
        {
          $this->device = MOBILE_DEVICE_PALM_OS;
        }
        else if ($this->DetectIsMobile())
        {
          $this->device = MOBILE_DEVICE_OTHER;
        }
        // To do...add the rest
      }
      return $this->device;
    }

    // ---------------------------------------------------------------------------
    // Function: getCurrentDeviceTier
    // Description: gets the current device tier
    // ---------------------------------------------------------------------------
    function getCurrentDeviceTier()
    {
      if ($this->deviceTier == '')
      {
        if ($this->DetectTierTablet())
        {
          $this->device_tier = MOBILE_DEVICE_TIER_TABLET;
        }
        if ($this->DetectTierIphone())
        {
          $this->device_tier = MOBILE_DEVICE_TIER_TOUCH;
        }
        if ($this->DetectTierRichCSS())
        {
          $this->device_tier = MOBILE_DEVICE_TIER_RICH_CSS;
        }
        if ($this->DetectTierRichCss())
        {
          $this->device_tier = MOBILE_DEVICE_TIER_SMARTPHONE;
        }
        if ($this->DetectTierOtherPhones())
        {
          $this->device_tier = MOBILE_DEVICE_TIER_OTHER;
        }
      }

      return $this->device_tier;
    }


    // ---------------------------------------------------------------------------
    // Function: filter_add_body_classes
    // Description: adds device specific CSS class to the body
    // - Filter: see add_filter('body_class'...)
    // ---------------------------------------------------------------------------
    function filter_addBodyClasses($classes)
    {
      $options = $this->getAdminOptions();

      // if theme switching enabled
      if ($options['enable_theme_switching'] == true)
      {
        // if is a mobile device
        if ($this->DetectIsMobile())
        {
          $classes[] .= "mobile" ;
        }

        // add current device string to body class
        $classes[] .= $this->getCurrentDevice();

        // add the tier of device also to body class
        $classes[] .= $this->getCurrentDeviceTier();
      }

      return $classes;
    }

    // ---------------------------------------------------------------------------
    // Function: filter_switchTheme
    // Description: switches the theme if it's a mobile device to the specified theme
    // - Filter: see add_filter('template'...)
    // ---------------------------------------------------------------------------
    function filter_switchTheme($theme)
    {
      // get options
      $options = $this->getAdminOptions();

      // if theme switching enabled
      if ($options['enable_theme_switching'] == true)
      { 
        // if is a mobile device or is mobile due to cookie switching
        if ($this->switcher_isMobile())
        { 
          $theme = $options['mobile_theme'];
        }
      }

      return $theme;
    }
    
    // ---------------------------------------------------------------------------
    // Function: filter_switchTheme_stylesheet
    // Description: switches the theme if it's a mobile device to the specified theme - stylesheet - for child themes
    // - Filter: see add_filter('template'...)
    // ---------------------------------------------------------------------------
    function filter_switchTheme_stylesheet($theme)
    {
      // get options
      $options = $this->getAdminOptions();

      // if theme switching enabled
      if ($options['enable_theme_switching'] == true)
      {
        // if is a mobile device or is mobile due to cookie switching
        if ($this->switcher_isMobile())
        {
          $theme = $options['mobile_theme_stylesheet'];
        }
      }

      return $theme;
    }

     //---------------------------------------------------------------------------
     // Function: switcher_isMobile
     // Description: determines whether the mode is mobile or switched
     // ---------------------------------------------------------------------------
     function switcher_isMobile()
     {
        $is_mobile = false;

        // get the mobile detect value
        $detectmobile = $this->DetectIsMobile();

        // check the switcher cookie
        $is_mobile = $this->switcher_getMobileCookieDetect($detectmobile);

        //echo "Is Mobile: ".($is_mobile ? "true" : "false")."<br/><br/>";

        return $is_mobile;
     }
     
     /**
      * Detect if it's mobile from the cookie - overrides mobile status
      * @param boolean $detectmobile
      * @return boolean
      */
     function switcher_getMobileCookieDetect($detectmobile)
     {
       if (!$this->detect_from_cookie)
       {
          // check the switcher cookie
        if ($detectmobile && $this->switcher_cookie)
        {
          if (($this->switcher_cookie == MOBILESMART_SWITCHER_DESKTOP_STR))
          {
            $is_mobile = false;
          }
          else
          {
            $is_mobile = true;
          }
        }
        // if we're not a mobile, then we invert the check string
        else if (!$detectmobile)
        {
          if (($this->switcher_cookie == MOBILESMART_SWITCHER_MOBILE_STR))
          {
            $is_mobile = true;
          }
          else
          {
            $is_mobile = false;
          }
        }
        else
        {
          $is_mobile = $detectmobile;
        }
          
          $this->detect_from_cookie = $is_mobile;
       }

        //echo "Is Mobile: ".($is_mobile ? "true" : "false")."<br/><br/>";

        return $this->detect_from_cookie;
     }
     
     /**
      * is it a mobile device (including iPad)
      * @return boolean
      */
     function DetectIsMobile()
     {
       if (!$this->detectmobile)
       {
         $options = $this->getAdminOptions();
         $is_mobile =  false;

         if ($options['switch_for_tablets'])
         {
           $is_mobile =  $this->DetectMobileQuick() || $this->DetectIpad() || $this->DetectAndroidTablet();
         }
         else
         {
           $is_mobile = $this->DetectMobileQuick();
         }
         
         $this->detectmobile = $is_mobile;
       }
       
       return $this->detectmobile;
     }

     // ---------------------------------------------------------------------------
     // Function: addSwitcherLink
     // Description: checks if the plugin option is enabled and if so adds the html switcher
     // ---------------------------------------------------------------------------
     function addSwitcherLink()
     {
        // get options
        $options = $this->getAdminOptions();

        // if theme switching enabled
        if ($options['enable_manual_switch'] == true)
        {
          // if is a mobile device or cookie switcher allows it.
          $is_mobile = $this->switcher_isMobile();
          if ($is_mobile || $options['allow_desktop_switcher'])
          {
            ?>
      <!-- START MobileSmart - Switcher - http://www.dansmart.co.uk/ -->
      <div id="mobilesmart_switcher">
        <?php if ($is_mobile) : ?>
          <a href="<?php echo $this->get_switcherLink(MOBILESMART_SWITCHER_DESKTOP_STR); ?>"><?php _e('Switch to desktop version', MOBILESMART_DOMAIN); ?></a>
        <?php else : ?>
          <a href="<?php echo $this->get_switcherLink(MOBILESMART_SWITCHER_MOBILE_STR); ?>"><?php _e('Switch to mobile version', MOBILESMART_DOMAIN); ?></a>
        <?php endif; ?>
      </div>
      <!-- END MobileSmart - Switcher - http://www.dansmart.co.uk/ -->
            <?php
          }
        }
     }

     // ---------------------------------------------------------------------------
     // Function: action_addSwitcherLinkInFooter
     // Description: action call for too add link into wp_footer
     // ---------------------------------------------------------------------------
     function action_addSwitcherLinkInFooter()
     {
        // get options
        $options = $this->getAdminOptions();

        // if theme switching enabled
        if ($options['enable_manual_switch'] == true && $options['enable_manual_switch_in_footer'] == true)
        {
          // display the link
          $this->addSwitcherLink();
        }
     }
     
     /**
      * Run init action
      */
     function action_init()
     {
        // get options
        $options = $this->getAdminOptions();

        // if theme switching enabled
        if ($options['enable_theme_switching'] == true)
        { 
          $is_mobile = $this->switcher_isMobile();

          
        }
     }

    // ---------------------------------------------------------------------------
    // Function: get_switcherLink
    // Description: gets the link to display the switcher
    // Parameters: version - should be 'mobile' or 'desktop'
    // ---------------------------------------------------------------------------
    function get_switcherLink($version)
    {
      $switcher_str = add_query_arg (array (MOBILESMART_SWITCHER_GET_PARAM => $version));

      return $switcher_str;
    }

    // ---------------------------------------------------------------------------
    // Function: action_addSwitcherLink
    // Description: checks if the html switcher link has been called and acts appropriately
    // ---------------------------------------------------------------------------
    function action_handleSwitcherLink()
    {
      if (isset($_GET[MOBILESMART_SWITCHER_GET_PARAM]))
      {
        // get the version
        $version = $_GET[MOBILESMART_SWITCHER_GET_PARAM];

        // set the cookie to say which version it is
        setcookie(MOBILESMART_SWITCHER_COOKIE,
                  $version,
                  time()+MOBILESMART_SWITCHER_COOKIE_EXPIRE,
                  COOKIEPATH,
                  str_replace(array('http://www','http://'),'',get_bloginfo('url')));

        // save version in class for viewing the page before a refresh
        $this->switcher_cookie = $version;

        //echo "Version to set: $version<br/>";
        //echo "Set version: $this->switcher_cookie<br/><br/>";
      }
    }
    
    // ---------------------------------------------------------------------------
    // Function: isTierTablet
    // Description: is the current device tier - table
    // ---------------------------------------------------------------------------
    function isTierTablet()
    {
      return $this->getCurrentDeviceTier() == MOBILE_DEVICE_TIER_TABLET;
    }

    // ---------------------------------------------------------------------------
    // Function: isTierTouch
    // Description: is the current device tier - touch
    // ---------------------------------------------------------------------------
    function isTierTouch()
    {
      return $this->getCurrentDeviceTier() == MOBILE_DEVICE_TIER_TOUCH;
    }
    
    // ---------------------------------------------------------------------------
    // Function: isTierRichCSS
    // Description: is the current device tier - Rich CSS
    // ---------------------------------------------------------------------------
    function isTierRichCSS()
    {
      return $this->getCurrentDeviceTier() == MOBILE_DEVICE_TIER_RICH_CSS;
    }

    // ---------------------------------------------------------------------------
    // Function: isTierSmartphone
    // Description: is the current device tier - smartphone
    // ---------------------------------------------------------------------------
    function isTierSmartphone()
    {
      return $this->getCurrentDeviceTier() == MOBILE_DEVICE_TIER_SMARTPHONE;
    }

    // ---------------------------------------------------------------------------
    // Function: isTierOtherMobile
    // Description: is the current device tier - other mobile devices (non-smartphone / non-touch)
    // ---------------------------------------------------------------------------
    function isTierOtherMobile()
    {
      return $this->getCurrentDeviceTier() == MOBILE_DEVICE_TIER_OTHER;
    }

     /**
      * Magic function - to catch old naming scheme of method with decapitalised first character. Change was caused by inclusion of mdetect.php
      * @param type $name
      * @param type $arguments 
      */
     function __call($name, $arguments)
     {
       $old_naming_scheme = ucwords($name);
       
       // check for method with capitalised first character - for backwards compatibility, as previous plugin had lowercase first characters in method name
       if (method_exists($this, $old_naming_scheme))
       {
         $name($arguments);
       }
     }

     // ------------------------------------------------------------------------
     // Function: filter_processContent
     // Description: processes the post's content and transcodes the post's images
     // Credits: idea and regexp taken from wpmp_transcoder.php, but brought into
     //          MobileSmart domain with improvements
     // ------------------------------------------------------------------------
     function filter_processContent($the_content)
     {
       $options = $this->getAdminOptions();
       
       // only process the content if we're in mobile mode
      if (!$this->switcher_isMobile() || !$options['enable_image_transcoding'])
        return $the_content;
     
       preg_match_all("/\<img.* src=((?:'[^']*')|(?:\"[^\"]*\")).*\>/Usi", $the_content, $images);

       foreach ($images[0] as $images_key=>$image)
       {
        $img_src = $images[1][$images_key];

        // remove the site url
        $site_url = str_replace('/', '\/', get_bloginfo('siteurl'));
        $img_src = preg_replace("/[\"|']".$site_url."(.*)[\"|']/", '\1', $img_src);

        // get the width and height
        preg_match_all("/(width|height)[=:'\"\s]*(\d+)(?:px|[^\d])/Usi", $image, $img_dimensions);

        $width = 0; $height = 0;
        foreach ($img_dimensions[2] as $dim_index=>$dim_val)
        {
          if ($img_dimensions[1][$dim_index] == 'height')
            $height = $dim_val;
          else if ($img_dimensions[1][$dim_index] == 'width')
            $width = $dim_val;
        }

        // * * * * * * *
        // to do: get max dimensions of images for each device / tier from somewhere like WURFL
        switch ($this->deviceTier)
        {
          case MOBILE_DEVICE_TIER_TOUCH: $max_width = MOBILE_DEVICE_TIER_TOUCH_MAX_WIDTH; $max_height = MOBILE_DEVICE_TIER_TOUCH_MAX_HEIGHT; break;
          case MOBILE_DEVICE_TIER_TABLET: $max_width = MOBILE_DEVICE_TIER_TABLET_MAX_WIDTH; $max_height = MOBILE_DEVICE_TIER_TABLET_MAX_HEIGHT; break;
          case MOBILE_DEVICE_TIER_SMARTPHONE: $max_width = MOBILE_DEVICE_TIER_SMARTPHONE_MAX_WIDTH; $max_height = MOBILE_DEVICE_TIER_SMARTPHONE_MAX_HEIGHT; break;
          case MOBILE_DEVICE_TIER_RICH_CSS: $max_width = MOBILE_DEVICE_TIER_RICH_CSS_MAX_WIDTH; $max_height = MOBILE_DEVICE_TIER_RICH_CSS_MAX_HEIGHT; break;
          case MOBILE_DEVICE_TIER_OTHER: $max_width = MOBILE_DEVICE_TIER_OTHER_MAX_WIDTH; $max_height = MOBILE_DEVICE_TIER_OTHER_MAX_HEIGHT; break;
          default: $max_width = 100; $max_height = 100; break;
        }
        // * * * * * * *

        // rescale image
        if ($width > $max_width)
        {
          $height = floor($width / $max_width) * $height;
          $width = $max_width;
        }

        if ($height > $max_height)
        {
          $width = floor($height / $max_height) * $width;
          $height = $max_height;
        }

        // create new rescaled image
        $rescaled_image = '<img src="'.MOBILESMART_PLUGIN_URL.'/includes/timthumb.php?src='.$img_src.'&w='.$width.'&h='.$height.'&zc=0"'
                          .' width="'.$width.'"'.' height="'.$height.'"'.'/>';

        // replace the entire text of the old image with the text of the resized image
        $the_content = str_replace($image, $rescaled_image, $the_content);
       }

       return $the_content;
     }
     
     /**
      * Add mobile pages post type
      */
     function mobilePages_init()
     {
       $options = $this->getAdminOptions();
       
       if ($options['enable_mobile_pages'])
       {
          add_meta_box( 
             'mobileSmart_mobilePage'
            ,__( 'Mobile Version', MOBILESMART_DOMAIN )
            ,array( &$this, 'mobilePages_displayMetaBox' )
            ,'post' 
            ,'normal'
            ,'high'
          );
          
          add_meta_box( 
             'mobileSmart_mobilePage'
            ,__( 'Mobile Version', MOBILESMART_DOMAIN )
            ,array( &$this, 'mobilePages_displayMetaBox' )
            ,'page' 
            ,'normal'
            ,'high'
          );
       }
     }
     
  } // MobileSmart
}

// -------------------------------------------------------------------------
// Instantiate class
// -------------------------------------------------------------------------
if (class_exists("MobileSmart"))
{
  $mobile_smart = new MobileSmart();
}


// -------------------------------------------------------------------------
// Actions and Filters
// -------------------------------------------------------------------------
if (isset($mobile_smart))
{
  // Activation
  register_activation_hook(__FILE__, array(&$mobile_smart, 'initialisePlugin'));

  // Switcher {
    // Actions
    add_action('admin_menu', 'MobileSmart_ap');
    add_action('setup_theme', array($mobile_smart, 'action_handleSwitcherLink'));
    add_action('wp_footer', array($mobile_smart, 'action_addSwitcherLinkInFooter'));
    add_action('init', array($mobile_smart, 'action_init'));

    // Filters
    add_filter('body_class', array(&$mobile_smart, 'filter_addBodyClasses'));
    add_filter('template', array(&$mobile_smart, 'filter_switchTheme'));
    add_filter('stylesheet', array(&$mobile_smart, 'filter_switchTheme_stylesheet'));
 // } End Switcher

  // Content transformation {
    // Filters
    add_filter('the_content', array(&$mobile_smart, 'filter_processContent'));
  // } End Content transformation
 
}

// -------------------------------------------------------------------------
// initialise the Admin Panel
// -------------------------------------------------------------------------
if (!function_exists("MobileSmart_ap"))
{
  function MobileSmart_ap()
  {
    global $mobile_smart;

    if (!isset($mobile_smart)) return;

    // add the options page
    if (function_exists('add_options_page'))
    {
      add_options_page("Mobile Smart", "Mobile Smart", 9, basename(__FILE__),
                       array(&$mobile_smart, 'displayAdminOptions'));
    }
  }
}

?>
