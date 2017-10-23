WordPress Mobile Pack
=================
[![GitHub release](https://img.shields.io/github/release/appticles/wordpress-mobile-pack.svg)](https://github.com/appticles/wordpress-mobile-pack )
[![GitHub closed issues](https://img.shields.io/github/issues-closed/appticles/wordpress-mobile-pack.svg)](https://github.com/appticles/wordpress-mobile-pack)

[![WordPress](https://img.shields.io/wordpress/v/wordpress-mobile-pack.svg)](https://wordpress.org/plugins/wordpress-mobile-pack/)
[![WordPress](https://img.shields.io/wordpress/plugin/dt/wordpress-mobile-pack.svg)](https://wordpress.org/plugins/wordpress-mobile-pack/)
[![WordPress rating](https://img.shields.io/wordpress/plugin/r/wordpress-mobile-pack.svg)](https://wordpress.org/plugins/wordpress-mobile-pack/)
[![Open Source](https://badges.frapsoft.com/os/v1/open-source.png?v=103)](https://github.com/appticles/wordpress-mobile-pack)

 &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp; ![demo](http://d3oqwjghculspf.cloudfront.net/github/wordpress-mobile-pack/FYwy5UH.gif) &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; ![demo](http://d3oqwjghculspf.cloudfront.net/github/wordpress-mobile-pack/hOTltV9.gif) &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; ![demo](http://d3oqwjghculspf.cloudfront.net/github/wordpress-mobile-pack/uhfONuq.gif) &nbsp; &nbsp;  &nbsp; &nbsp;


Mobile [WordPress](https://wordpress.org/) plugin to package your content into a [Progressive Web App](https://wpmobilepack.com/), build a hybrid mobile app and submit it to App Stores.


### Description 

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

WordPress Mobile Pack also comes with a  **PRO version** suitable for **professional bloggers, publishing companies with multiple publications** in their portfolio or web agencies. 

We enjoy writing and maintaining this plugin. If you like it too, please star us. But if you don't, let us know how we can improve it.

Have fun on your mobile adventures.

### Frequently Asked Questions 

**When I visit my website from a smartphone, I don't see any posts or pages**

Please make sure that the endpoint exporting the content can be accessed and doesn't show errors or notices. From a browser, go to the following address: 


http://yoursite.com/wp-content/plugins/wordpress-mobile-pack/export/content.php?content=exportcategories&limit=5&callback=Ext.data.JsonP.callback


You should see a text starting with "Ext.data.JsonP.callback". If the page displays a "403 forbidden" message or has any errors / notices, it means that the content will not be available to the mobile web app.

**I have enabled Wordpress Mobile Pack, but I still see the desktop theme on my smartphone**

If you are using a cache plugin, please check the [docs](http://support.appticles.com/optimizing-cache-plugins-wordpress-mobile-pack/). Some additional settings on the cache plugin might be required to correctly enable the mobile detection from Wordpress Mobile Pack.

**What can I use to replace contact forms?**
Most of the sites we come across use contact forms to allow users to get in touch or/and send messages. However, when targeting mobile users, forcing them to fill out a dull form (usually pretty long) is the worst UX you can offer to your mobile audience. Instead, here are [a couple of approaches](http://support.appticles.com/replacing-contact-forms-with-click-to-call-links-for-your-mobile-web-application/) you could try out in your mobile web application:

**What devices and operating systems are supported by my mobile web application?**

WordPress Mobile Pack is supported on iOS and Android smartphones and tablets. Compatible browsers: Safari, Google Chrome, Android - Native Browser.

 **How can my readers switch back to the desktop theme from my mobile web application?**
 
The side menu of the mobile web application contains a 'Switch to website' button that will take readers back to the desktop theme. Their option will be remembered the next time they visit your blog.

**How can my readers switch back to the mobile web application from the desktop theme?**

A link called 'Switch to mobile version' will be displayed in the footer of your desktop theme, only for readers that are viewing the site from a supported device and browser. Their option will be remembered the next time they visit your blog.

**I want to temporarily deactivate my mobile web application. What steps must I follow?**

The mobile web application can be deactivated from the "Settings" page of the admin panel. This option will not delete any settings that you have done so far, like customizing the look & feel of your application, but mobile readers will no longer be able to see it on their devices.

**What is the difference between my new mobile web application and a mobile friendly site?**

The short answer is that a mobile web application is an enriched version of a mobile-friendly site; it's not only about screen size, it's also about functionality (offline mode, for example). The long answer comes in a form of an article, you can check it out here: http://www.appticles.com/blog/2014/05/mobile-web-dying-shifting/.

**What is the difference between my mobile web application and a responsive theme?**

A responsive theme is all about screen-size: it loads the same styling as the desktop view, adjusting it to fit a smaller screen. On the other hand a mobile web application combines the versatility of the web with the functionality of touch-enabled devices and can support native app-like features such as:

1. Apps load nearly instantly and are reliable, no matter what kind of network connection your user is on.
1.  Web app install banners give users the ability to quickly and seamlessly add your mobile app to their home screen, making it easy to launch and return to your app.
1.  Web push notifications makes it easy to re-engage with users by showing relevant, timely, and contextual notifications, even when the browser is closed.
1.  Smooth animations, scrolling, and navigations keep the experience silky Smooth.
1.  Secured via HTTPS.

**Am I able to use my own theme or customize the existing one?**

WordPress Mobile Pack uses a variety of open source JavaScript frameworks from Sencha Touch to Angular/Ionic or React, that mimics a native app interface. Because of that, the app themes we've developed are not regular PHP based themes that can be easily customized by editing the source. It is still possible to make small changes (not recommended), but changing the theme structure will require advance knowledge. We're happy to assist if you're looking for a custom type of application - [please get in touch](https://wpmobilepack.com/contact.html) with us.

**Am I able to add Javascript code inside the theme?**

Adding tracking scripts in the source is possible if you place them in the section of the theme files. However, code that is placed inside the posts will not be executed. The theme is implemented enterily in JavaScript and that would mean JavaScript code inside another JavaScript code.

**Am I able to integrate my own advertisement?**

Google Ad Sense / Google Double Click for Publishers is supported on the PRO version as of v2.1. [Please get in touch](https://wpmobilepack.com/contact.html) if you want us to integrate with another 3rd party ad provider.

**What is a progressive web app?**

Please refer to this comprehensive article about it: https://www.appticles.com/blog/2016/09/progressive-web-apps-for-publishers/.

**Why some 3rd party plugins are not visible on any of the app themes?**

There are almost 50,000 plugins in the WordPress.org repository. It's impossible to support all of them. [Please get in touch](https://wpmobilepack.com/contact.html) if you want us to integrate with another 3rd party plugin.

### Contributors 

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
