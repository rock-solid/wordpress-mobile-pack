=== WordPress Mobile Pack ===
Contributors: jamesgpearce, andreatrasatti, edent, cborodescu
Tags: mobile, mobile web, mobile internet, smartphone, iphone, android, windows 8, webkit, chrome, safari, mobile web app, html5, sencha touch, responsive ui
Requires at least: 3.5
Tested up to: 4.2
Stable tag: 2.1.2
License: GPLv2 or later

The NEW WordPress Mobile Pack allows you to 'package' your existing content into a cross-platform mobile web application.

== Description ==

**The WordPress Mobile Pack 2.0+ has been completely rebuilt from the ground up and repurposed to empower bloggers, publishers and other content creators to go beyond responsiveness and 'appify' the content of their blog.**

WordPress Mobile Pack 2.0+ is **supported on**: iPhones, Android smartphones, Windows Phone 8, Firefox OS. **Compatible browsers**: Safari, Google Chrome, Android - Native Browser, Internet Explorer 10 and Firefox.

The pack has been tested on WordPress 3.5 and later. Please read the [Known issues and limitations](https://wordpress.org/plugins/wordpress-mobile-pack/other_notes/) list before installing.

The WordPress Mobile Pack 2.0+ eliminates the hassle of dealing with high development costs, approval processes with various app stores, poor discoverability due to the closed environment of native apps and finally, one of the biggest injustices aimed towards the publishing industry in general - the shared revenue constraint.

What the WordPress Mobile Pack 2.0+ enables you to do: 

* **Cross-platform mobile web applications**. All it takes for a mobile web application to run is a modern mobile browser (HTML5 compatible), thus allowing readers to instantly have access to your content, without needing to go through an app store, download & install the app.
 
* **Responsive UI**. The mobile web application is sensitive to various screen sizes and orientation changes: landscape, portrait. In other words, the look and feel of the mobile web app seamlessly morphs into the screen size of users' devices.

* **Theming**. You can offer your users an exceptional reading experience by giving them a mobile web application with a native app-like look & feel. The default theme comes with 6 abstract covers that are randomly displayed on the loading screen to give the app a magazine flavour. 

* **Customize appearance**. Once a favourite theme has been selected, you can customize the colors & fonts, add your logo and graphic elements that can relate to your blog's identity.

* **Posts Sync**. The posts inside the mobile web application are organized into their corresponding categories, thus readers can simply swipe through articles and jump from category to category in a seamless way. 

* **Pages Sync**. Choose what pages you want to display on your mobile web application. You can edit, show/hide different pages and order them according to your needs.

* **Comments Sync**. All the comments that are displayed in the blog are also synchronized into the mobile web application. On top of that, comments that are posted from within the app are also displayed on the blog.

* **Analytics**. WordPress Mobile Pack 2.0 easily integrates with Google Analytics. 

* **Add to Homescreen**. Readers can add the mobile web application to their homescreen and run it in full-screen mode. 

WordPress Mobile Pack 2.0+ can be extended to its Premium version by connecting it with [Appticles.com](http://www.appticles.com).  Some of the benefits of going Premium:
 
* **Phablets & Tablets Support**. Turn your blog into an amazing tablet web application. The look and feel of the web app seamlessly morphs into the screen size of your users' device.

* **Unlimited Themes & Custom Appearance**. Dozens of themes to choose from. Customize the colors & fonts, add your logo and personalize your app to craft your brand identity. 

* **Unlimited Content Sources**. Repurpose your existing social content into a personalized mobile & tablet web application that tells a story: Your story.

* **Unlimited Applications**. Have as many mobile & tablet web applications as you need, supported on iOS, Android, Windows 8, FirefoxOS, Tizen. 

* **Monetization**. Start making money by connecting your ad units from Google Adsense and Double Click for Publishers.
 
* **Offline Mode**. Keep your mobile readers always in touch with your content, even in offline mode.

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
1. Go to the 'Content' tab. Disable, enable or order categories and pages depending on what content you want to show in the mobile web app.
1. Go to the 'Settings' tab to choose a Display Mode and add your Google Analytics ID.
1. Access your site in a mobile browser and check if the application is displayed. If the app is not loading properly, make sure that the file exporting the content - http://yoursite.com/{your plugins folder}/wordpress-mobile-pack/export/content.php - can be accessed in the browser and doesn't return a '404 Not Found' error.
1. You're all done!

= Testing your installation =

Ideally, use a real mobile device to access your (public) site address and check that the switching and mobile web app work correctly.

You can also download a number of mobile emulators that can run on a desktop PC and simulate mobile devices.

Please note that the mobile web app will be enabled only on supported devices: iPhones, Android smartphones, Windows Phone 8 and Firefox OS. Only the following browsers are compatible: Safari, Google Chrome, Android - Native Browser, Internet Explorer 10 and Firefox (as of 2.0.2). 

= Connecting the API Key (Premium accounts) =
We have wrote a complete guide about [connecting your plugin with the hosted platform](http://support.appticles.com/hc/en-us/articles/201681012-Connecting-Your-Website-with-Your-Mobile-Web-Application#apikey) by using the API Key.

== Frequently Asked Questions ==

= When I visit my website from a smartphone, I don't see any posts or pages =
Please make sure that the endpoint exporting the content can be accessed and doesn't show errors or notices. From a browser, go to the following address: http://yoursite.com/wp-content/plugins/wordpress-mobile-pack/export/content.php?content=exportcategories&limit=5&callback=Ext.data.JsonP.callback. You should see a text starting with "Ext.data.JsonP.callback". If the page displays a "403 forbidden" message or has any errors / notices, it means that the content will not be available to the mobile web app.

= I have enabled Wordpress Mobile Pack, but I still see the desktop theme on my smartphone =
If you are using a cache plugin, please check the [docs](http://support.appticles.com/hc/en-us/articles/201795202-Optimizing-Cache-Plugins-for-Wordpress-Mobile-Pack). Some additional settings on the cache plugin might be required to correctly enable the mobile detection from Wordpress Mobile Pack.

= What devices and operating systems are supported by my mobile web application? =
WordPress Mobile Pack 2.0 is supported on: iPhones, Android smartphones, Windows Phone 8 and Firefox OS. Compatible browsers: Safari, Google Chrome, Android - Native Browser, Internet Explorer 10 and Firefox.

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

= Am I able to use my own theme or customize the existing one? =  
V2.0+ is based on a Javascript framework called Sencha Touch, that mimics a native app interface. Because of that, the themes used are not regular PHP based themes that can be easily customized by editing the source. It is still possible to make small CSS changes, but changing the theme structure will not work.

= Am I able to add Javascript code inside the theme? =
Adding tracking scripts in the source is possible if you place them in the <head> section of the theme files. However, code that is placed inside the posts will not be executed. The theme is implemented enterily in Javascript and that would mean Javascript code inside another Javascript code.

= Am I able to integrate my own advertisement? =
Google Ad Sense / Google Double Click for Publishers is supported on the Premium version as of v2.1. Support for other ad networks will be added in future releases.

== Changelog ==

= 2.1.2 =
* Added [rel="canonical" and rel="alternate" elements](https://developers.google.com/webmasters/mobile-sites/mobile-seo/configurations/separate-urls?hl=en) for SEO
* Fixed bug - category redirect for Premium themes
* Fixed bug - cleaning up transient when disconnecting the API key
* Fixed bug - navigating between categories with special chars (ex. French accents)
* Patch 21/04/2015 - Fixed bug, featured images were not displaying properly for the first 10 articles from the carousel

= 2.1.1 =
* Integrated with [Related Posts by Zemanta](https://wordpress.org/plugins/related-posts-by-zemanta/) and [Editorial Assistant by Zemanta](https://wordpress.org/plugins/zemanta/)
* Wrote docs about [how to set up the main cache plugins for WPMP](http://support.appticles.com/hc/en-us/articles/201795202-Optimizing-Cache-Plugins-for-Wordpress-Mobile-Pack)
* Added manifest files for Android and FirefoxOS (handle 'Add to homescreen' url and icon) 
* Added SSL support
* Added support for HTML5 audio and video tags. Please note that not all mobile browsers are correctly handling these tags. The audio/video players are not controlled or modified by WPMP.
* Added opt-in for tracking for anonymous data (disabled by default)
* Fixed bug - Blank page when accessing a category with a single post from the menu, if the post is displayed on the cover.
* Premium version connect - Added support for custom color schemes and fonts
* Patch 11/02/2015 - Added support for tel: and callto: link attributes

= 2.1 =
* Added support for pages with basic HTML content (forms, tables and iframe tags are not supported)
* Categories and pages can be ordered from the admin panel
* Connect with Appticles.com through an API key for the Premium version
* Fixed PHP errors caused by STRICT standards
* Fixed url rewriting bug for categories with special characters in the title
* Patch 22/09/2014 - Fixed redirect loop bug for blogs that use a static page as their front page

= 2.0.2 = 
* Added support for Firefox and Firefox OS
* Added support for customizing the home page cover
* Added animation and arrow on the home page to suggest to readers that they have to swipe the screen to see the content
* Added menu button on the home page / cover
* Fixed bug - Filter content to remove script tags. The code from script tags was wrongly displayed as content.
* Fixed bug - Filter content to remove default image links added by Wordpress. The links were opening an image in a new window and messing up the scrolling of the content.
* Fixed bug - Using 'home_url' instead of 'site_url' for redirecting to the home page.
* Fixed bug - Switching from portrait to landscape on the native Android browser was not working properly on some smartphones (HTC One).

= 2.0.1 = 
* Hide comments button if the comments are disabled for an article
* Hide back and comments buttons at scroll within an article and display them at tap
* Hide 'Latest' category from the main menu
* Within a category, display a single article per page only if the article has a featured image.
* Fixed bug - verify if the curl library is installed before checking for updates 
* Fixed bug - url rewriting for categories with punctuation or other special characters

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

= 2.1.2 =
* WP Mobile Pack allows you to 'package' your existing content into a cross-platform mobile web application. The latest version includes support for rel="canonical" and rel="alternate" tags for better SEO and other bug fixes.


== Screenshots ==

1. Cover of the mobile web app. A default cover is used if your last blog entry doesn't contain a large enough featured image.
2. Articles within a category. 
3. Side menu with all the enabled categories.
4. Side menu with all the enabled pages.
5. Article details.
6. Comments panel for an article.
7. "What's New" page from the admin panel. Displays latest updates and news.
9. "Look & Feel" page from the admin panel. Customize theme by choosing colors, fonts and adding your own app icon, logo & cover.
9. "Content" page from the admin panel. Show/hide categories of articles and pages in the mobile web app.
10. "Settings" page from the admin panel. Set display mode, add your Google Analytics Id and enable the Premium version.
11. "More" page from the admin panel. Other capabilities offered in the premium hosted platform.


== Setting up cache plugins for working with Wordpress Mobile Pack ==

If your site uses a cache plugin, please note that a series of issues can occur if that plugin wasn't configured to work with Wordpress Mobile Pack. Some examples are:

* Loading the mobile web application on desktop browsers
* Loading the desktop theme on a supported mobile device (even though the Wordpress Mobile Pack plugin is active)
* Inconsistent switching between the desktop and the Wordpress Mobile Pack theme

If you find yourself in one of these situation, please read [the docs](http://support.appticles.com/hc/en-us/articles/201795202-Optimizing-Cache-Plugins-for-Wordpress-Mobile-Pack) and make the appropiate settings on your cache plugin.

== Roadmap ==

Our roadmap currently includes:

* Integrating with Disqus
* RTL support
* Localization. Please contact us if you can help by translating the mobile web application's text into your language.
* Integrating with the most popular forms plugins

== Known issues and limitations for v2.1 ==

* V2.1 of the mobile web app doesn't include support for forms. We are looking for a way to recreate forms inside the mobile web application and integrate with various plugins.
* Iframes are not currently supported because of scrolling issues on iPhone.
* The mobile web app doesn't include user authentication. If your blog settings enable comments only for logged in users, they will be disabled in the mobile web app.
* For now, supported mobile browsers include Safari, Google Chrome, Android's native browser, Internet Explorer 10 and Firefox (as of 2.0.2). Support for other mobile browsers such as Opera will be added in the following versions.
* Only featured images from your blog posts are displayed as thumbnails in the list of posts. Images integrated in a post's content are displayed only on the details page for that post.

== Repositories ==

We currently have two Github development repositories:

* [https://github.com/appticles/wordpress-mobile-pack-backend](https://github.com/appticles/wordpress-mobile-pack-backend) - The plugin files, same as you will find for download on Wordpress.org, plus unit tests.
* [https://github.com/appticles/wordpress-mobile-pack-app](https://github.com/appticles/wordpress-mobile-pack-app) - The mobile web application's development files (Javascript & CSS).