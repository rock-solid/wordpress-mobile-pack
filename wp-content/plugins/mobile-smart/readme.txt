=== Plugin Name ===
Contributors: dolby_uk
Donate link: http://www.mobile-smart.co.uk/
Tags: iphone, mobile, theme switcher, mobile theme, mobile device, series 60, ipad, blackberry, android, tablet
Requires at least: 3.1
Tested up to: 3.7
Stable tag: trunk

The Mobile Smart plugin allows your Wordpress site to switch your theme if a user visits it using a mobile device,
plus adds template tags to help you customise your theme based on the device viewing it. Also contains a sample mobile theme
for developers to start with, based on Mobile Boilerplate and HTML 5 Reset.

== Description ==

Mobile Smart (http://www.mobile-smart.co.uk/), using detection from the MobileESP project (http://www.mobileesp.com) allows the following:

* Switch your theme to a mobile-ready theme if a mobile device is detected (you can now enable/disable iPad/tablets)
* Manual Switcher - to allow your user to manually switch between desktop and mobile versions. Available in 3 versions: widget, option to automatically insert into footer, or template tag.
* Template functions to help determine which tier of mobile device (touch/smartphone/other) is viewing your site, to allow conditional content inclusion.
* Adds device and tier specific CSS selectors to the body_class, to allow conditional CSS (e.g. so in the same way you have ".single" that you can target ".iphone" or ".mobile-tier-touch".)
* Image transcoding - rescale images to fit their device

Check out the Mobile Smart Pro plugin http://www.mobile-smart.co.uk for support for:
* Domain switching - use a mobile theme to detect mobile devices, and redirect to appropriate theme URL (e.g. m.yoursite.com)
* Mobile Pages - mobile versions of posts and pages with the same URL
* Mobile Menus - mobile versions of menus for mobile-specific navigation

See the Frequently Asked Questions for guidance on how to use the plugin.

Device support includes iPhone, iPad, Android, Blackberry, Windows Phone 6 & 7, Symbian, and many more.


= Mobile Theme =
Mobile Smart comes with a basic barebones ('boilerplate') theme that can be used by theme developers to develop for mobile devices.

The Mobile Smart boilerplate theme is based on two projects: http://html5boilerplate.com/mobile/ and http://html5reset.org/#wordpress
with some additional Mobile Smart modifications.

Note: The theme is not designed to be used without developer modification.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the `mobile-smart` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Settings->Mobile Smart and choose your theme to display when a mobile device encounters your page.

Go to http://www.mobile-smart.co.uk/installation/ for more details.

== Frequently Asked Questions ==

See http://www.mobile-smart.co.uk/frequently-asked-questions/ for more.

= Does this work with other mobile plugins =

It would be advisable not to use other mobile theme switching functionality with this unless theme switching is turned off (go to Settings->Mobile Smart to disable).

This has been tested with the Wordpress Mobile Pack transcoder and is noted to be compatible, though the list of mobile devices are different between the two.

= How to switch and style for Tablets =

Go to the Mobile Theme tab in the Settings->Mobile Smart admin page, and enable / disable tablet support.

If you wish to serve up different styles for tablets, you'll have to modify your template to use the template tags to detect:

`<?php
/* add additional stylesheet for certain mobile types */
global $mobile_smart;
// add stylesheets dependent on header
if ($mobile_smart->isTierTablet())
{
  wp_enqueue_style('mobile-tablet', get_bloginfo('stylesheet_directory')."/css/tablet.css");
}
?>`

You can use the same template tag (isTierTablet()) to display additional content (such as sidebars, etc).

= How do I use the Manual Switcher? =

You have the option of the following:
* Mobile Smart Manual Switcher Widget - go to Appearance->Widgets and drop the widget in an appropriate sidebar. If you're
  a theme developer, you can create a new 'sidebar' in the appropriate location, e.g. the footer bar, if you don't want
  this option in the standard sidebar.
* Enable Manual Switcher in footer - if this option is enabled (via the Options->Mobile Smart page), this adds
  the Manual Switcher link into the wp_footer() call, which means it will be displayed at the bottom of your page.
* Template tag, see below:

`<?php
  // get global instance of Mobile Smart class
  global $mobile_smart;

  // display manual switcher link - requires Manual Switching to be enabled
  $mobile_smart->addSwitcherLink();
?>`

The Manual Switcher displays the switcher link in a div with an id of *mobilesmart_switcher*

= Do you do domain switching =

Go to http://www.mobile-smart.co.uk/ for more information on the Mobile Smart Pro plugin.

= How do I enable unique handset body classes =

To enable the CSS body classes, ensure that in your mobile theme you have the body_class() function included:

 `<?php body_class(); ?>`


= How do I change stylesheets dependent on device tier =

How do I use the body classes?

If you have a style that you only want a specific tier of device (e.g. touch handsets like the iPhone) to use, then use the body class CSS selector in your CSS file as follows:

(Example: 

/* for all links */
a {
  color: black;

  }

/* increase padding on anchors on touch handsets to allow for big fingers
.mobile-tier-touch li a {
  padding: 20px;
}


= How do I change stylesheets dependent on device tier =

You would do this if you prefer to split out each device tier CSS into separate files. Be aware that this creates an extra function call though.

Use the following PHP code:

`<?php
/* add additional stylesheet for certain mobile types */
global $mobile_smart;
// add stylesheets dependent on header
if ($mobile_smart->isTierTouch())
{
  wp_enqueue_style('mobile-touch', get_bloginfo('stylesheet_directory')."/css/touch.css");
}
else if ($mobile_smart->isTierSmartphone())
{
  wp_enqueue_style('mobile-smartphone', get_bloginfo('stylesheet_directory')."/css/smartphone.css");
}
?>`

Note: these functions do not test for the Manual Switcher. To test for the manual switcher (in case you are using
these template tag functions in a desktop theme), you should call:

`<?php
/* add additional stylesheet for certain mobile types */
global $mobile_smart;
// find out manual switching state
$is_manual_switched_to_mobile = $mobile_smart->switcher_isMobile();
?>`

= Can you add xxxx-device? =

Please email me with details of the device that is not yet supported by Mobile Smart, and I will add it in, and endeavour to release an updated version within the week (if timescales allow).

= Where can I get a mobile theme from? =

Try the Mobile Smart boilerplat theme if you're a developer. Also check out the Wordpress Mobile Pack
for a good example of a theme that is compatible with XHTML-MP.


== Changelog ==

= 0.1 =
Initial release, containing mobile device detection, body classes, and mobile tier template tags.

= 0.2 =
Added Manual Mobile Switcher - widget, link, and template tag.

= 1.0 =
Based detector on Mobile ESP project, meaning device detection will stay up to date with latest mobile devices

= 1.1 =
Includes sample mobile theme - based on Mobile Boilerplate and HTML 5 Reset projects

= 1.1.1 =
Bug fix where plugin was calling non existent DetectTierTouch() function.

= 1.2 =
Added support for child themes

= 1.2.1 =
Vital Security patch (timthumb.php). Please upgrade immediately.

= 1.3 =
Major upgrade:
- Upgrade of MobileESP detection engine - better support for Opera Mini on Android (see http://blog.mobileesp.com/ for latest updates)
- Admin upgrade to tabs
- You can now select to switch for Ipad/tablet or not
- Debugged image transcoding

== Upgrade Notice ==

= 0.1 =
Initial release.

= 0.2 =

= 1.0 =

= 1.1 =

= 1.2 =
If you're switching to a child theme, you will need to save your Mobile Smart settings before use.

= 1.2.1 =
Security patch to timthumb.php

= 1.3 
Improved admin, enable/disable iPad/tablet switching, updated MobileESP device detection engine

= 1.3.5
- Fix for cookie path
- Updated Mobile ESP to latest version
- Improved tablet tier checking for Android tablets
