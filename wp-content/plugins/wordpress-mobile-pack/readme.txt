=== WordPress Mobile Pack ===
Contributors: jamesgpearce, andreatrasatti, edent, appticles.com
Tags: mobile, mobile web, mobile internet, smartphone, iphone, android, windows 8, webkit, chrome, safari, mobile web app, sencha touch
Requires at least: 3.5
Tested up to: 3.9.1
Stable tag: 2.0

With WordPress Mobile Pack, you can easily build your own stylish, cross-platform mobile web application out of your existing content.

== Description ==

**NEW in Version 2.0**: Completely rebuilt using a mobile web app for the frontend and new admin panel. 

**NEW in Version 1.2.2**: Support for WordPress v3.0

**NEW in Version 1.2.1**: Native integration with WP Super Cache. Read more at [Go mobile with WP Super Cache and the Mobile Pack](http://blog.trasatti.it/2010/05/go-mobile-with-wp-super-cache-and.html)

**NEW in Version 1.2.0**: The Mobile Pack now displays advanced themes for Nokia and WebKit devices. There are three grades of theme designed for low-, mid-, and high-end Nokia devices, the latter of which also provides full support for WebKit browsers, including those on Android, iPhone and Palm devices. The development of this feature has been kindly sponsored by Forum Nokia.

We enjoy writing and maintaining this plugin. If you like it too, please rate us. But if you don't, let us know how we can improve it.

Have fun on your mobile adventures.


== Installation ==

= Simple installation for WordPress v3.5 and later =

1.  Go to the 'Plugins' / 'Add new' menu
1.	Upload wordpress-mobile-pack.zip then press 'Install now'.
1.	Enjoy.

= Comprehensive setup =

A more comprehensive setup process and guide to configuration is as follows. If you are installing the plugin through the admin dashboard, you'll be able to skip most of the early steps.

1. Locate your WordPress install on the file system
1. Extract the contents of `wordpress-mobile-pack.zip` into `wp-content/plugins`
1. In `wp-content/plugins` you should now see a directory named `wordpress-mobile-pack`
1. Login to the WordPress admin panel at `http://yoursite.com/wp-admin`
1. Go to the 'Plugins' menu.
1. Click 'Activate' for the plugin.
1. Go to the 'WP Mobile Pack' admin panel.
1. Go to the 'Look & Feel' tab. Choose color schemes, fonts and add your own logo and app icon.
1. Go to the 'Content' tab. Disable or enable categories depending on what content you want to show in the mobile web app.
1. Go to the 'Settings' tab to choose a Display Mode and add your Google Analytics ID.
1. You're all done!

= Testing your installation =

Ideally, use a real mobile device to access your (public) site address and check that the switching and theme work correctly.

You can also download a number of mobile emulators that can run on a desktop PC and simulate mobile devices.

Please note that the mobile web app will be enabled only on supported devices: iOS, Android and Windows 8. Only Webkit browsers are compatible: Safari, Google Chrome, Android - Native Browser and Internet Explorer 10 (Firefox is not supported for now). 

If you use the Firefox Browser, the 'User-Agent Switcher' add-on can be configured to send mobile headers and crudely simulate a mobile device's request.

== Frequently Asked Questions ==


== Changelog ==

= 2.0 =
* Enterily rebuilt to use Sencha Touch for the mobile web application and a separate admin panel. No backwards compatibility with v1.2.5.

= 1.2.5 =
* Removed [PercentMobile](http://percentmobile.com) analytics and XSS issue.

= 1.2.4 =
* Fixed image transcoder callback bug

= 1.2.3 =
* Updated [PercentMobile](http://percentmobile.com) tracking code for mobile analytics

= 1.2.2 =
* Support for WordPress v3.0 RC2: custom menus and sidebars
* Primary custom menu will appear on mobile theme if it is enabled for the desktop theme
* Mobile widgets can be enabled from multiple sidebar locations (as in the default WP3.0 theme, twentyten)

= 1.2.1 =
* Native WP Super Cache integration - read more: [Go mobile with WP Super Cache and the Mobile Pack](http://blog.trasatti.it/2010/05/go-mobile-with-wp-super-cache-and.html)
* Fixed minor XHTML issues
* Improved management of embedded YouTube and Vimeo videos
* Minor change to PercentMobile code

= 1.2.0 =
* Advanced themes for Nokia and WebKit devices enabled by default. (The development of this feature has been kindly sponsored by Forum Nokia.)
* Mobile analytics and integration with [PercentMobile](http://percentmobile.com)
* Updated screenshots

= 1.2.0b2 =
* Shortened QR-code URLs and added alt attribute to img tag to be valid XHTML
Two major issues were introduced in beta 1, now solved:
* Solved mobile admin login error
* Restored compatibility with PHP4

= 1.2.0b =
* Full internationalisation and readiness for translation (see .pot file within installation)
* Automatic [mpexo](http://www.mpexo.com) listings (enabled in the settings menu)

= 1.1.92 =
* Prevented javascript being displayed in posts

= 1.1.91 =
* Fixed admin bug when using older themes

= 1.1.9 =
* Multi-device theming engine
* Metadata in post lists can be hidden
* More tolerance of installs on Windows servers
* Changes to comment status now generate emails
* Shortcodes filtered from teasers
* base theme patterns refactored, and any derived themes may need to be updated

= 1.1.3 =
* Ensure subdirectoried blogs work correctly with switcher
* Support object-oriented widgets in WP2.8
* Fixed empty and pre WP2.8 widgets causing invalid XHTML
* Switcher link now always appears in footer on admin pages
* Nokia N97 checkbox rendering fixed

= 1.1.2 =
* Tested to support WP v2.8.4
* Minor typos & theme credits
* Preparation for I18N

= 1.1.1 =
* Tested support for WP v2.8.1
* Improved tolerance of permissions issues at install
* Ability to force the upgrade of themes at install
* Deep-link QR-codes to the page you're on
* User can override detection-only switching
* Switcher race conditions avoided
* Mobile teaser now overrules 'more' break
* Support for Nintendo and Novarra mobile user agents
* PHP4 support
* Numerous minor bug fixes

[Full ticket list](http://www.assembla.com/spaces/wordpress-mobile-pack/milestones/95962)


= 1.0.8223 =
* Initial release


== Upgrade Notice ==

= 1.2.0 =
* Includes advanced themes for Nokia and WebKit devices, and mobile analytics and integration with [PercentMobile](http://percentmobile.com)



== Screenshots ==




== Documentation ==



= Mobile switcher =


= Base mobile theme =


= Transcoding and device adaptation =


= Mobile admin panel =


== Known issues ==

* On a WordPress MU installation, it is not possible to configure the favicon for each site independently within the single mobile theme. You are advised to create multiple derived themes from the mobile base theme, and configure the favicons separately for each.
* Note that if you use a desktop theme that provides unusual, additional page templates, the default mobile theme will not have the corresponding logic. The mobile theme will fall back to showing a default posting list. However, you are able to create new mobile templates just as for the desktop theme. (Copy archives.php as a simple example of an auxiliary page template.)
* If you are using a desktop domain and a mobile domain, it is not currently possible to host them on different sub-directory locations (eg `http://mysite.com/blog/` but `http://mysite.mobi/`). Both versions of the site must either be at the top-level of the domain or in the same sub-directory.
* W3 Total Cache does not play well with mobile plugins, you can read how to make it work at [Getting W3 Total cache to work with WordPress Mobile Pack](http://blog.trasatti.it/2010/04/getting-w3-total-cache-to-work-with.html) until a new release comes (very soon!)
