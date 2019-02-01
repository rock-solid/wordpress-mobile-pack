=== Publisher's Toolbox PWA ===
Contributors: jamesgpearce, andreatrasatti, cborodescu, anghelalexandra, publisherstoolbox
Tags: pwa, progressive web app, android, iOS, html5, iphone, mobile, mobile internet, mobile web, mobile web app, responsive ui, safari, sencha touch, smartphone, webkit, progressive web apps, app builder, apple, apps, convert to app, create blog app, ios app, ipad, make an app, mobile app plugin, mobile application, mobile blog app, mobile converter, mobile plugin, native app plugin, app theme, website to mobile app, WordPress android, WordPress app, WordPress iphone, WordPress mobile, WordPress mobile app
Requires at least: 4.6
Tested up to: 5.0.3
Stable tag: 1.0
License: GPLv2 or later

== Description ==

**Publisher's Toolbox PWA is a mobile plugin that helps you transform your website's content into a progressive mobile web application. It comes with multiple mobile app themes from which to choose from.** 

Publisher's Toolbox PWA is supported on iOS and Android smartphones and tablets. **Compatible browsers**: Safari, Google Chrome, Android - Native Browser.

Publisher's Toolbox PWA provides the following:

* **Progressive Web App**. Some of the key features of progressive web apps are: 

 1. Apps load nearly instantly and are reliable, no matter what kind of network connection your user is on.
 1. Web app install banners give users the ability to quickly and seamlessly add your mobile app to their home screen, making it easy to launch and return to your app.
 1. Web push notifications makes it easy to re-engage with users by showing relevant, timely, and contextual notifications, even when the browser is closed.
 1. Smooth animations, scrolling, and intuitive navigation keep the experience silky smooth.
 1. Secured via HTTPS.
 1. Responsive UI.

* **Responsive UI**. The mobile web application is sensitive to various screen sizes and orientation changes: landscape, portrait. In other words, the look and feel of the mobile web app seamlessly morphs into the screen size of users' devices.

* **App Themes**. You can offer your users an exceptional reading experience by giving them a mobile web application with a native app-like look & feel. The default theme comes with 6 abstract covers that are randomly displayed on the loading screen to give the app a magazine flavour.

* **Customize appearance**. Once a favorite theme has been selected, you can customize the colors & fonts, add your logo and graphic elements that can relate to your blog's identity.

* **Posts Sync**. The articles/posts inside the mobile web application are organized into their corresponding categories, thus readers can simply swipe through articles and jump from category to category in a seamless way.

* **Pages Sync**. Choose what pages you want to display on your mobile web application. You can edit, show/hide different pages and order them according to your needs. 

* **Comments Sync**. All the comments that are displayed in the blog are also synchronized into the mobile web application. On top of that, comments that are posted from within the app are also displayed on the blog.

* **Analytics**. Publisher's Toolbox PWA easily integrates with Google Analytics.

* **Add to Homescreen**. Readers can add the mobile web application to their homescreen and run it in full-screen mode.

We enjoy writing and maintaining this plugin. If you like it too, please rate us. But if you don't, let us know how we can improve it.

Have fun on your mobile adventures.

== 3rd Party Services and Applications ==

This plugin uses the Publisher's Toolbox PWA Script to fetch the required HTML to display your website's PWA. The content is loaded via the WordPress Rest API, no user data is exposed or transmitted at this point. The service simply fetches the required markup for your content to populate. No account is required. More about our PWA solution can be found here: https://www.publisherstoolbox.com/websuite/


== Installation ==

= Simple installation for WordPress v4.6 and later =

1.  Go to the 'Plugins' / 'Add new' menu
1.	Upload wordpress-pwa.zip then press 'Install now'.
1.	Enjoy.

= Comprehensive setup =

A more comprehensive setup process and guide to configuration is as follows.

1. Locate your WordPress install on the file system
1. Extract the contents of `wordpress-pwa.zip` into `wp-content/plugins`
1. In `wp-content/plugins` you should now see a directory named `publishers-toolbox-pwa`
1. Login to the WordPress admin panel at `http://yoursite.com/wp-admin`
1. Go to the 'Plugins' menu.
1. Click 'Activate' for the plugin.
1. Go to the 'PT PWA' admin panel.
1. Go to the 'Look & Feel' tab. Choose color schemes, fonts and add your own logo and app icon.
1. Go to the 'Content' tab. Disable, enable or order categories and pages depending on what content you want to show in the mobile web app.
1. Go to the 'Settings' tab to choose a Display Mode and add your Google Analytics ID.
1. Access your site in a mobile browser and check if the application is displayed. If the app is not loading properly, make sure that the file exporting the content - http://yoursite.com/{your plugins folder}/wordpress-pwa/export/content.php - can be accessed in the browser and doesn't return a '404 Not Found' or '403 Forbidden' error.
1. You're all done!

= Testing your installation =

Ideally, use a real mobile device to access your (public) site address and check that the switching and mobile web app work correctly.

You can also download a number of mobile emulators that can run on a desktop PC and simulate mobile devices.

Please note that the mobile web app will be enabled only on supported devices: iPhones, Android smartphones, Windows Phone 8 and Firefox OS. Only the following browsers are compatible: Safari, Google Chrome, Android - Native Browser, Internet Explorer 10 and Firefox.

== Frequently Asked Questions ==

= When I visit my website from a smartphone, I don't see any posts or pages =
Please make sure that the endpoint exporting the content can be accessed and doesn't show errors or notices. From a browser, go to the following address: http://yoursite.com/wp-content/plugins/publishers-toolbox-pwa/export/content.php?content=exportcategories&limit=5&callback=Ext.data.JsonP.callback. You should see a text starting with "Ext.data.JsonP.callback". If the page displays a "403 forbidden" message or has any errors / notices, it means that the content will not be available to the mobile web app.

= I have enabled Publisher's Toolbox PWA, but I still see the desktop theme on my smartphone =
If you are using a caching plugin, please ensure that it is disabled or configured correctly. Some additional settings on the cache plugin might be required to correctly enable the mobile detection from Publisher's Toolbox PWA.

= What devices and operating systems are supported by my mobile web application?
Publisher's Toolbox PWA is supported on iOS and Android smartphones and tablets. Compatible browsers: Safari, Google Chrome, Android - Native Browser.

= How can my readers switch back to the desktop theme from my mobile web application? =
The side menu of the mobile web application contains a 'Switch to website' button that will take readers back to the desktop theme. Their option will be remembered the next time they visit your blog.

= How can my readers switch back to the mobile web application from the desktop theme? =
A link called 'Switch to mobile version' will be displayed in the footer of your desktop theme, only for readers that are viewing the site from a supported device and browser. Their option will be remembered the next time they visit your blog.

= I want to temporarily deactivate my mobile web application. What steps must I follow? =
The mobile web application can be deactivated from the "Settings" page of the admin panel. This option will not delete any settings that you have done so far, like customizing the look & feel of your application, but mobile readers will no longer be able to see it on their devices.

= What is the difference between my mobile web application and a responsive theme? =
A responsive theme is all about screen-size: it loads the same styling as the desktop view, adjusting it to fit a smaller screen. On the other hand a mobile web application combines the versatility of the web with the functionality of touch-enabled devices and can support native app-like features such as: 

1. Apps load nearly instantly and are reliable, no matter what kind of network connection your user is on.
1.  Web app install banners give users the ability to quickly and seamlessly add your mobile app to their home screen, making it easy to launch and return to your app.
1.  Web push notifications makes it easy to re-engage with users by showing relevant, timely, and contextual notifications, even when the browser is closed.
1.  Smooth animations, scrolling, and navigations keep the experience silky Smooth.
1.  Secured via HTTPS.

