=== WordPress Mobile Pack - Mobile Plugin for Progressive Web Apps & Hybrid Mobile Apps ===
Contributors: jamesgpearce, andreatrasatti, cborodescu, anghelalexandra
Tags: android, iOS, html5, iphone, mobile, mobile internet, mobile web, mobile web app, responsive ui, safari, sencha touch, smartphone, webkit, progressive web apps, app builder, apple, apps, convert to app, create blog app, ios app, ipad, make an app, mobile app plugin, mobile application, mobile blog app, mobile converter, mobile plugin, native app plugin, app theme, website to mobile app, WordPress android, WordPress app, WordPress iphone, WordPress mobile, WordPress mobile app
Requires at least: 3.6
Tested up to: 4.7.3
Stable tag: 3.2
License: GPLv2 or later

Mobile plugin to package your content into a progressive web app, build a hybrid mobile app and submit it to App Stores. Multiple mobile app themes.

== Description ==

**[WordPress Mobile Pack](https://wpmobilepack.com) is a mobile plugin that helps you transform your website's content into a progressive mobile web application. It comes with multiple mobile app themes that you can purchase individually or as a bundle.** 

WordPress Mobile Pack is **supported on** iOS and Android smartphones and tablets. **Compatible browsers**: Safari, Google Chrome, Android - Native Browser.

The pack has been tested on WordPress 3.6 and later. Please read the [Known issues and limitations](https://wordpress.org/plugins/wordpress-mobile-pack/other_notes/) list before installing.

What the WordPress Mobile Pack 3.0+ enables you to do:

* **Progressive Web Apps**. Some of the key features of progressive web apps are: 

 1. Apps load nearly instantly and are reliable, no matter what kind of network connection your user is on.
 1. Web app install banners give users the ability to quickly and seamlessly add your mobile app to their home screen, making it easy to launch and return to your app.
 1. Web push notifications makes it easy to re-engage with users by showing relevant, timely, and contextual notifications, even when the browser is closed.
 1. Smooth animations, scrolling, and navigations keep the experience silky smooth.
 1. Secured via HTTPS.
 1. Responsive UI.

* **Responsive UI**. The mobile web application is sensitive to various screen sizes and orientation changes: landscape, portrait. In other words, the look and feel of the mobile web app seamlessly morphs into the screen size of users' devices.

* **App Themes**. You can offer your users an exceptional reading experience by giving them a mobile web application with a native app-like look & feel. The default theme comes with 6 abstract covers that are randomly displayed on the loading screen to give the app a magazine flavour. More app themes available in [WordPress Mobile Pack PRO](https://wpmobilepack.com).

* **Customize appearance**. Once a favorite theme has been selected, you can customize the colors & fonts, add your logo and graphic elements that can relate to your blog's identity. More customization options available in [WordPress Mobile Pack PRO](https://wpmobilepack.com).

* **Posts Sync**. The articles/posts inside the mobile web application are organized into their corresponding categories, thus readers can simply swipe through articles and jump from category to category in a seamless way.

* **Pages Sync**. Choose what pages you want to display on your mobile web application. You can edit, show/hide different pages and order them according to your needs. 

* **Comments Sync**. All the comments that are displayed in the blog are also synchronized into the mobile web application. On top of that, comments that are posted from within the app are also displayed on the blog. Social media features are available in [WordPress Mobile Pack PRO](https://wpmobilepack.com).

* **Analytics**. WordPress Mobile Pack easily integrates with Google Analytics.

* **Add to Homescreen**. Readers can add the mobile web application to their homescreen and run it in full-screen mode.

WordPress Mobile Pack also comes with a  **PRO version** suitable for **professional bloggers, publishing companies with multiple publications** in their portfolio or web agencies. Some of the benefits of using [WordPress Mobile Pack PRO](https://wpmobilepack.com) are:

* **Customize your mobile web app's appearance** to resemble your brand identity. 

* Since mobile web apps don't have any shared revenue constraints, you can **take full control of your income**. [Wordpress Mobile Pack PRO](https://wpmobilepack.com) allows you to easily connect with your Google DFP & AdSense campaigns. 

* We take pride in offering fantastic [Wordpress Mobile Pack PRO](https://wpmobilepack.com) **maintenance and hands-on support**. Our team of friendly mobile web experts makes sure technology doesn't stand in your way. 

* **Access to multiple app themes** that can be purchased individually or as a bundle: BASE, MOSAIC, OBLIQ, ELEVATE, FOLIO, INVISION, POPSICLE, PULSE, GHOST, PHANTOM, LUCID, EXTRUDE, VEDI, BLEND, PURE, GOTHAM, FUTURE & PALM.

* Your mobile users will be able to benefit from a rich mobile reading experience on their favorite mobile device without needing to go through an App Store and install anything.

Here are some walkthrough videos that can get you started with WordPress Mobile Pack PRO:

[youtube https://www.youtube.com/watch?v=JSjhK8YI98M]

[youtube https://www.youtube.com/watch?v=elxjfdbAoqM]

For previous versions, check out the 'Change log' page.

We enjoy writing and maintaining this plugin. If you like it too, please rate us. But if you don't, let us know how we can improve it.

Have fun on your mobile adventures.


== Installation ==

= Simple installation for WordPress v3.6 and later =

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
1. Access your site in a mobile browser and check if the application is displayed. If the app is not loading properly, make sure that the file exporting the content - http://yoursite.com/{your plugins folder}/wordpress-mobile-pack/export/content.php - can be accessed in the browser and doesn't return a '404 Not Found' or '403 Forbidden' error.
1. You're all done!

= Testing your installation =

Ideally, use a real mobile device to access your (public) site address and check that the switching and mobile web app work correctly.

You can also download a number of mobile emulators that can run on a desktop PC and simulate mobile devices.

Please note that the mobile web app will be enabled only on supported devices: iPhones, Android smartphones, Windows Phone 8 and Firefox OS. Only the following browsers are compatible: Safari, Google Chrome, Android - Native Browser, Internet Explorer 10 and Firefox (as of 2.0.2).

= Connecting the API Key (Premium Cloud accounts) =
We have wrote a complete guide about [connecting your plugin with the hosted platform](http://support.appticles.com/connecting-your-website-with-your-companion-mobile-web-application/#apikey) by using the API Key.

== Frequently Asked Questions ==

= When I visit my website from a smartphone, I don't see any posts or pages =
Please make sure that the endpoint exporting the content can be accessed and doesn't show errors or notices. From a browser, go to the following address: http://yoursite.com/wp-content/plugins/wordpress-mobile-pack/export/content.php?content=exportcategories&limit=5&callback=Ext.data.JsonP.callback. You should see a text starting with "Ext.data.JsonP.callback". If the page displays a "403 forbidden" message or has any errors / notices, it means that the content will not be available to the mobile web app.

= I have enabled Wordpress Mobile Pack, but I still see the desktop theme on my smartphone =
If you are using a cache plugin, please check the [docs](http://support.appticles.com/optimizing-cache-plugins-wordpress-mobile-pack/). Some additional settings on the cache plugin might be required to correctly enable the mobile detection from Wordpress Mobile Pack.

= What can I use to replace contact forms? =
Most of the sites we come across use contact forms to allow users to get in touch or/and send messages. However, when targeting mobile users, forcing them to fill out a dull form (usually pretty long) is the worst UX you can offer to your mobile audience. Instead, here are [a couple of approaches](http://support.appticles.com/replacing-contact-forms-with-click-to-call-links-for-your-mobile-web-application/) you could try out in your mobile web application:

= What devices and operating systems are supported by my mobile web application?
WordPress Mobile Pack is supported on iOS and Android smartphones and tablets. Compatible browsers: Safari, Google Chrome, Android - Native Browser.

= How can my readers switch back to the desktop theme from my mobile web application? =
The side menu of the mobile web application contains a 'Switch to website' button that will take readers back to the desktop theme. Their option will be remembered the next time they visit your blog.

= How can my readers switch back to the mobile web application from the desktop theme? =
A link called 'Switch to mobile version' will be displayed in the footer of your desktop theme, only for readers that are viewing the site from a supported device and browser. Their option will be remembered the next time they visit your blog.

= I want to temporarily deactivate my mobile web application. What steps must I follow? =
The mobile web application can be deactivated from the "Settings" page of the admin panel. This option will not delete any settings that you have done so far, like customizing the look & feel of your application, but mobile readers will no longer be able to see it on their devices.

= What is the difference between my new mobile web application and a mobile friendly site? =
The short answer is that a mobile web application is an enriched version of a mobile-friendly site; it's not only about screen size, it's also about functionality (offline mode, for example). The long answer comes in a form of an article, you can check it out here: http://www.appticles.com/blog/2014/05/mobile-web-dying-shifting/.

= What is the difference between my mobile web application and a responsive theme? =
A responsive theme is all about screen-size: it loads the same styling as the desktop view, adjusting it to fit a smaller screen. On the other hand a mobile web application combines the versatility of the web with the functionality of touch-enabled devices and can support native app-like features such as: 

1. Apps load nearly instantly and are reliable, no matter what kind of network connection your user is on.
1.  Web app install banners give users the ability to quickly and seamlessly add your mobile app to their home screen, making it easy to launch and return to your app.
1.  Web push notifications makes it easy to re-engage with users by showing relevant, timely, and contextual notifications, even when the browser is closed.
1.  Smooth animations, scrolling, and navigations keep the experience silky Smooth.
1.  Secured via HTTPS.

= Am I able to use my own theme or customize the existing one? =
WordPress Mobile Pack uses a variety of open source JavaScript frameworks from Sencha Touch to Angular/Ionic or React, that mimics a native app interface. Because of that, the app themes we've developed are not regular PHP based themes that can be easily customized by editing the source. It is still possible to make small changes (not recommended), but changing the theme structure will require advance knowledge. We're happy to assist if you're looking for a custom type of application - [please get in touch](https://wpmobilepack.com/contact.html) with us. 

= Am I able to add Javascript code inside the theme? =
Adding tracking scripts in the source is possible if you place them in the section of the theme files. However, code that is placed inside the posts will not be executed. The theme is implemented enterily in JavaScript and that would mean JavaScript code inside another JavaScript code.

= Am I able to integrate my own advertisement? =
Google Ad Sense / Google Double Click for Publishers is supported on the PRO version as of v2.1. [Please get in touch](https://wpmobilepack.com/contact.html) if you want us to integrate with another 3rd party ad provider. 

= What is a progressive web app? = 
Please refer to this comprehensive article about it: https://www.appticles.com/blog/2016/09/progressive-web-apps-for-publishers/.

= Why some 3rd party plugins are not visible on any of the app themes? =
There are almost 50,000 plugins in the WordPress.org repository. It's impossible to support all of them. [Please get in touch](https://wpmobilepack.com/contact.html) if you want us to integrate with another 3rd party plugin. 


== Changelog ==

= 3.2 =
* Security fix, replaced Smart App Banner script with jQuery Noty plugin

= 3.1 =
* Implemented Add to Home Screen functionality
* Translated app in Bosnian (bs_BA)

= 3.0 =
* New Obliq mobile app theme, built on AngularJS & Ionic 1
* Refactor "App Themes" tab
* Refactor export settings method
* Add pagination params for exporting pages
* Deprecated Firefox and Windows Phone support
* Refactor mobile app styling compiler
* Remove integration with the Google AMP stylesheet

= 2.2.10 =
* Update MobileDetect library to 2.8.25
* Fix the mobile web app page details display on Firefox

= 2.2.9 =
* Add background color for the browser's address bar and splash screen (Progressive Web Apps features)
* Update HTMLPurifier library to 4.8.0 for compatibility with PHP7
* Fix notice from exporting posts and pages images
* Eliminate WP 3.6 conditions for custom fonts selects

= 2.2.8 =
* App theme - fix articles carousel on Firefox
* Exclude pages with inactive ancestors from exports, rel=alternate meta tags and smart app banner links
* Admin panel, 'Settings' tab - fix notices display

= 2.2.7 =
* Add opt-in for enabling mobile theme on tablets
* Optimize posts and pages featured images, use thumbnail if it exists
* Optimize pages loading, exclude pages that belong to an inactive parent page
* Fix display for images included in a div tag with fixed height
* Fix for bug that hides tiny-mce dropdown in post edit view

= 2.2.6 =		
* Add Facebook, Twitter and Google+ share buttons for posts. Social media settings are edited from the plugin's admin panel.
* Translated app in Japaneze (ja). A big thanks to Miru Yamashiro.
* Add pre-order options for Premium themes.
* Add order option for PRO themes bundle.

= 2.2.5 =		
* Integrate with the official [Google AMP plugin] (https://wordpress.org/plugins/amp/) 		
* Add Google Maps embedded iframe support		
* New API endpoints (export category, pagination params for export categories)		
* Premium - add support for connecting with new themes (Popsicle and Invision)		
* Premium - add support for embedding Google Tag Manager code

= 2.2.4 =
* Display smart app banner when the user reverts to the desktop theme
* Optimize app loading - check posts images size using post metadata
* Optimize app loading - load 3 posts per category instead of 9 at initial loading
* Optimize app loading - use HTMLPurifier only for exporting a post's or page's details
* Optimize app loading - use WordPress post excerpts when exporting posts or pages lists
* Optimize app loading - add responsive images attributes when exporting posts or pages
* Replace "What's New" admin tab with a quick start guide

= 2.2.3 =
* Add / edit categories images
* Increase basic font size for the mobile web app. Calculate headlines and subtitles font sizes depending on the base font size.
* Translated app in Chinese (zh_CN). A big thanks to Na LI.
* Added support for Youku embed code
* API - Don't export child pages if their parent pages are disabled.
* Fixed bug - the app was reverting to basic font settings when re-selecting a color scheme.
* Fixed bug - show the number of comments on the comments icon

= 2.2.2 =
* Added support for Spotify
* Translated app in Dutch. A big thanks to John Haverkate.
* Modify settings for Premium themes (new kits with comments, similar to the PRO plugin)
* Fixed disable desktop link for Premium themes

= 2.2.1 =
* Option to choose the number of posts per card
* Add support for Instagram and Spreaker embed code
* Added admin notice for PHP versions lower than 5.3
* Notify plugin users when a new PRO version is released
* Fixed comments order bug (use WordPress settings)

= 2.2 =
* Customize color scheme and fonts and compile SCSS theme file (similar to the PRO version)
* Added 6 new fonts options
* Select different font setting for headlines, subtitles and paragraphs
* Refactoring for all admin, core files and themes files (similar to the PRO version)
* Remove 'Monetize' preview page
* Added preview for the 5th app theme

= 2.1.5 =
* Select a single font option for headlines, subtitles and paragraphs. Generated CSS files for all color schemes / fonts combinations.
* Added browser caching for the app's static files, for improving loading time.
* Refactor and merge the application's CSS files, for improving loading time.
* Optimize cover images, for improving loading time.
* Remove integration with Zemanta and refactor content exports for the application
* Modified language files format and translated app in Portuguese (Brazil) and Italian. A big thanks to all contributors: Diogo Desiderati (Portuguese), Fabiola Sguassero, Emmanuel Andriulo (Italian).
* Added 'Monetize' page (as a preview for WordPress Mobile Pack PRO)
* Modified the 'More...' page and renamed it as 'PRO'
* Added preview for the 4th app theme
* Patch 05/11/2015 - Added new settings in the Premium theme index file - enable / disable Facebook and Twitter, language, ads interval
* Patch 25/11/2015 - Remember settings when the plugin is deactivated, delete them at uninstall

= 2.1.4 =
* Translated app in multiple languages. A big thanks to all contributors: Bernhard Steinbichler (German), Péter Ágoston (Hungarian), Sandra Gorgan (Romanian), Agnieszka Bugajska (Polish), Mathias Wideroth (Swedish).
* Partial support for multi-language plugins (to do - select language from the mobile web application)
* Fixed comments form display on IE (Windows Phone 8)
* Cleaned up admin CSS files

= 2.1.3 =
* Changed mobile detection library to [Mobile Detect](https://github.com/serbanghita/Mobile-Detect)
* Fixed detection issue for BlackBerry devices (BB will display the desktop theme)
* Fixed URL rewriting for non-latin languages for the categories menu, use category slug instead of name
* Added support for more click-to-call formats (Skype, SMS, WhatsApp)
* Added patch for swipe events on Chrome 43
* Added Google Webmaster Tools ID & deactivated status for Premium apps
* Fixed inactive categories and pages warning for sites that use multilanguage plugins
* Patch 03/06/2015 - Security bug fix for exporting a single post with a 'Private' status

= 2.1.2 =
* Added [rel="canonical" and rel="alternate" elements](https://developers.google.com/webmasters/mobile-sites/mobile-seo/configurations/separate-urls?hl=en) for SEO
* Fixed bug - category redirect for Premium themes
* Fixed bug - cleaning up transient when disconnecting the API key
* Fixed bug - navigating between categories with special chars (ex. French accents)
* Patch 21/04/2015 - Fixed bug, featured images were not displaying properly for the first 10 articles from the carousel

= 2.1.1 =
* Integrated with [Related Posts by Zemanta](https://wordpress.org/plugins/related-posts-by-zemanta/) and [Editorial Assistant by Zemanta](https://wordpress.org/plugins/zemanta/)
* Wrote docs about [how to set up the main cache plugins for WPMP](http://support.appticles.com/optimizing-cache-plugins-wordpress-mobile-pack/)
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
* Security bug fix for exporting password-protected posts, CVE-2014-5337

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

= 3.2 =
* WordPress Mobile Pack packages your content into a progressive web app or a hybrid mobile app that can be submitted to App Stores. The latest version comes with a security fix for the smart app banner.

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

If you find yourself in one of these situations, please read [the docs](http://support.appticles.com/optimizing-cache-plugins-wordpress-mobile-pack/) and make the appropriate settings on your cache plugin.

== Roadmap ==

Our roadmap currently includes:

* Fully customizable menus with categories, posts, pages and links
* Integrating with the most popular multi-language plugins
* Localization. Please contact us if you can help by translating the mobile web application's text into your language.
* Support for password protected posts.
* Integrating with the most popular forms plugins
* Integrating with Disqus

== Known issues and limitations for v2.0+ ==

* V2.0+ of the mobile web app doesn't include support for forms. We are looking for a way to recreate forms inside the mobile web application and integrate with various plugins.
* Iframes and embed codes are partially supported. Embed codes are allowed for YouTube, Vimeo, Daily Motion, Soundcloud, Instagram, Wistia, Flickrit and Spreaker. If you need support for other media content embed codes, please contact us.
* The mobile web app doesn't include user authentication. If your blog settings enable comments only for logged in users, they will be disabled in the mobile web app.
* For now, supported mobile browsers include Safari, Google Chrome, Android's native browser, Internet Explorer 10 and Firefox (as of 2.0.2).
* Only featured images from your blog posts are displayed as thumbnails in the list of posts. Images integrated in a post's content are displayed only on the details page for that post.

== Repositories ==

We currently have two Github development repositories:

* [https://github.com/appticles/wordpress-mobile-pack](https://github.com/appticles/wordpress-mobile-pack) - The plugin files, same as you will find for download on Wordpress.org, plus unit tests.
* [https://github.com/appticles/pwa-theme-obliq](https://github.com/appticles/pwa-theme-obliq) - Development files for the new version of theme Obliq (Javascript & CSS).

== Contributors ==

A big thanks to all contributors that helped us translate the mobile web application:

* Na LI (Chinese / zh_CN)
* John Haverkate (Dutch)
* Bernhard Steinbichler (German)
* Péter Ágoston (Hungarian)
* Fabiola Sguassero (Italian)
* Emmanuel Andriulo (Italian)
* Miru Yamashiro (Japanese)
* Sandra Gorgan (Romanian)
* Agnieszka Bugajska (Polish)
* Mathias Wideroth (Swedish)
* Diogo Desiderati (Portuguese)
