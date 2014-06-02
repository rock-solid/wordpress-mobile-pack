=== WordPress Mobile Pack ===
Contributors: jamesgpearce, andreatrasatti, edent, cborodescu
Tags: mobile, mobile web, mobile internet, smartphone, iphone, android, windows 8, webkit, chrome, safari, mobile web app, html5, sencha touch, responsive ui
Requires at least: 3.5
Tested up to: 3.9.1
Stable tag: 2.0
License: GPLv2 or later

The NEW WordPress Mobile Pack allows you to 'package' your existing content into a cross-platform mobile web application.

== Description ==

**The WordPress Mobile Pack 2.0 has been completely rebuilt from the ground up to empower bloggers to go beyond responsiveness and 'appify' the content of their blog.**

WordPress Mobile Pack 2.0 is **supported on**: iPhones, Android smartphones, Windows Phone 8. **Compatible browsers**: Safari, Google Chrome, Android - Native Browser and Internet Explorer 10.

The pack has been tested on WordPress 3.5 and later. 

The WordPress Mobile Pack 2.0 eliminates the hassle of dealing with high development costs, approval processes with various app stores, poor discoverability due to the closed environment of native apps and finally, one of the biggest injustices aimed towards the publishing industry in general - the shared revenue constraint.

What the WordPress Mobile Pack 2.0 enables you to do: 

* **Cross-platform mobile web applications**. All it takes for a mobile web application to run is a modern mobile browser (HTML5 compatible), thus allowing readers to instantly have access to your content, without needing to go through an app store, download & install the app.
 
* **Responsive UI**. The mobile web application is sensitive to various screen sizes and orientation changes: landscape, portrait. In other words, the look and feel of the mobile web app seamlessly morphs into the screen size of users' devices.

* **Theming**. You can offer your users an exceptional reading experience by giving them a mobile web application with a native app-like look & feel. The default theme comes with 6 abstract covers that are randomly displayed on the loading screen to give the app a magazine flavour. 

* **Customize appearance**. Once a favourite theme has been selected, you can customize the colors & fonts, add your logo and graphic elements that can relate to your blog's identity.

* **Posts Sync**. The posts inside the mobile web application are organized into their corresponding categories, thus readers can simply swipe through articles and jump from category to category in a seamless way. 

* **Comments Sync**. All the comments that are displayed in the blog are also synchronized into the mobile web application. On top of that, comments that are posted from within the app are also displayed on the blog.

* **Analytics**. WordPress Mobile Pack 2.0 easily integrates with Google Analytics. 

* **Add to Homescreen**. Readers can add the mobile web application to their homescreen and run it in full-screen mode. 

For previous versions, check out the 'Change log' page.

We enjoy writing and maintaining this plugin. If you like it too, please rate us. But if you don't, let us know how we can improve it.

Have fun on your mobile adventures.


== Installation ==

= Simple installation for WordPress v3.5 and later =

1.  Go to the 'Plugins' / 'Add new' menu
1.	Upload wordpress-mobile-pack.zip then press 'Install now'.
1.	Enjoy.

= Comprehensive setup =

A more comprehensive setup process and guide to configuration is as follows.

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

Ideally, use a real mobile device to access your (public) site address and check that the switching and mobile web app work correctly.

You can also download a number of mobile emulators that can run on a desktop PC and simulate mobile devices.

Please note that the mobile web app will be enabled only on supported devices: iPhones, Android smartphones and Windows Phone 8. Only Webkit browsers are compatible: Safari, Google Chrome, Android - Native Browser and Internet Explorer 10 (Firefox is not supported for now). 

== Frequently Asked Questions ==

= What devices and operating systems are supported by my mobile web application? =
WordPress Mobile Pack 2.0 is supported on: iPhones, Android smartphones, Windows Phone 8. Compatible browsers: Safari, Google Chrome, Android - Native Browser and Internet Explorer 10.
Support for other mobile browsers such as Firefox will be added in later releases.

= How can my readers switch back to the desktop theme from my mobile web application? =
The side menu of the mobile web application contains a 'Switch to website' button that will take readers back to the desktop theme. Their option will be remembered the next time they visit your blog.

= How can my readers switch back to the mobile web application from the desktop theme? =
A link called 'Switch to mobile version' will be displayed in the footer of your desktop theme, only for readers that are viewing the site from a supported device and browser. Their option will be remembered the next time they visit your blog.

= I want to temporarily deactivate my mobile web application. What steps must I follow? =
The mobile web application can be deactivated from the "Settings" page of the admin panel. This option will not delete any settings that you have done so far, like customizing the look & feel of your application, but mobile readers will no longer be able to see it on their devices.

= What is the difference between my new mobile web application and a mobile friendly site? = 
The short answer is that a mobile web application is an enriched version of a mobile-friendly site; it's not only about screen size, it's also about functionality (offline mode, for example). The long answer comes in a form of an article, you can check it out here: http://www.appticles.com/blog/2014/05/mobile-web-dying-shifting/.

= What is the difference between my mobile web application and a responsive theme? =  
A responsive theme is all about design - it loads the same styling as the desktop view, adjusting it to fit a smaller screen. A mobile web application combines the versatility of the web with the functionality of touch-enabled devices and can contain features that your desktop website doesn't have (like offline mode for example). A mobile web app is similar to a native app in terms of look & feel, with the only difference being that it runs in the browser.

== Changelog ==

= 2.0 =
* Enterily rebuilt to use Sencha Touch for the mobile web application and a separate admin panel. NO backwards compatibility with v1.2.5.

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

= 1.0.8223 =
* Initial release


== Upgrade Notice ==

= 2.0 =
* Completely rebuilt from the ground up to empower you to go beyond responsiveness and 'appify' the content of your blog.


== Screenshots ==

1. Cover of the mobile web app. A default cover is used if your last blog entry doesn't contain a large enough featured image.
2. Articles within a category. 
3. Side menu with all the enabled categories. 
4. Article details.
5. Comments panel for an article.
6. "What's New" page from the admin panel. Displays latest updates and news.
7. "Look & Feel" page from the admin panel. Customize theme by choosing colors, fonts and adding your own app icon & logo.
8. "Content" page from the admin panel. Show/hide categories of articles in the mobile web app.
9. "Settings" page from the admin panel. Set display mode and add your Google Analytics Id.
10. "More" page from the admin panel. Other capabilities offered in the premium hosted platform.


== Known issues and limitations for v2.0 ==

* V2.0 of the mobile web app doesn't display the Pages from your blog, only categories and posts.
* The mobile web app doesn't include user authentication. If your blog settings enable comments only for logged in users, they will be disabled in the mobile web app.
* For now, supported mobile browsers include Safari, Google Chrome, Android's native browser and Internet Explorer 10. Firefox support will be added in later versions.
* Only featured images from your blog posts are used to generate the cover of the mobile web app and images for the list of posts. Images integrated in a post's content are displayed only on the details page for that post.