=== Chitika ===
Author: chitikainc
Contributors: chitikainc
Donate link: http://chitika.com/
Tags: seo, google, adsense, adsense alternative, chitika, monetization, advertising, ads, post, posts, ad, income, money, monetize
Requires at least: 3.0
Tested up to: 3.9
Stable tag: 2.1.2

This plugin will automate adding Chitika ads to your blog posts. Chitika Ads will show your blog viewers targeted ads and are compatible with AdSense.

== Description ==

= Access Top-Tier Advertisers =

Relax -- our ads work for you! [Chitika](htttp://chitika.com/ "Chitika, Inc.") provides its network of over 350,000 publishers with online monetization solutions for both web and mobile sites. We specialize in delivering highly relevant, search-targeted ads, drawn from an extensive network of quality advertising partners including Yahoo!, SuperPages and Yellowbook.

= Customize Your Ads to Maximize Earnings =
Choose the ad unit that best fits your site’s content and audience. Choose traditional search-targeted text and display ads, optimize for your mobile users, or choose from our family of apps that includes Chitika Linx (in-text ads) and Hover (“sticky” scroll ads).

Don't have a Chitika account? [Click here to sign up and get started](http://chitika.com/publishers/apply?refid=wordpressplugin "Apply for a Chitika account here!"). Note: Until your Chitika account is approved, you will not be able to start earning revenue from your Chitika Ads.

== Installation ==

Installation and customization is easy and takes fewer than five minutes.

1. Upload `/chitika-premium/` directory to the `/wp-content/plugins/` directory
2. Activate the plugin *Chitika* through the 'Plugins' menu in WordPress
3. Go to 'Settings' > 'Chitika' to activate the display and add your Chitika username and password and change any display settings.
*If you are using a version of WordPress earlier than 2.5 your configuration screen will be in 'Options' > 'Chitika'*

= Updates =
* **New in 2.1.2** - Updated ad code
* **New in 2.1.1** - Fix for ads not showing up in some cases(hopefully). Restructured code.
* **New in 2.1.0** - Fixed/Updated account validation.
* **New in 2.0.9** - Added Display options for hiding ads on the front page and only showing ads on the post's index page.
* **New in 2.0.8** - Fix for missing </div> tag.
* **New in 2.0.7** - Updated ad code
* **New in 2.0.6** - Permalink bug fix
* **New in 2.0.5** - New ways for publishers to maximize revenue with Chitika's Wordpress plug in
* **New in 2.0.4** - Update to username and Chitika Account verification
* **New in 2.0.3** - Updated Plugin Tracking
* **New in 2.0.2** - Note: Until your Chitika Account is approved, you will not be able to start earning revenue from your Chitika Ads.
* **New in 2.0.1** - Updated plugin admin panel.
* **New in 2.0** - Updated features and ad technology.
* **New in 1.3** - Choose whether or not show ads only on permalink pages (and not just any page that displays the full post)
New Option to append 'Above', 'Below' to channel names for advanced channel tracking
* **New in 1.3.1** - Style options expanded
* **New in 1.3.2** - Alert error bug fix
* **New in 1.3.4** - Username bug fix
* **New in 1.3.5** - Added the high CTR MEGA-Unit as a unit size option
* **New in 1.3.6** - Added dashboard news, corrected admin UI bugs
* **New in 1.3.7** - Added MEGA-Unit 500x250 size and fixed UI confusion
* **New in 1.3.8** - Mega Unit Bug Fix
* **New in 1.4.0** - User verification and text updates
* **New in 1.4.1** - Fix feed breaking (ie. stop ads from trying to show in feeds). Special thanks to [Stephan from DigitalProductsReview.com](http://www.digitalproductsreview.net/blog/how-the-chitika-plugin-broke-my-feed/)

== Screenshots ==

1. Chitika ads sample on a WordPress Blog

== Frequently Asked Questions ==

= Can I use Chitika ads on the same page as Google AdSense? =
Yes! Many of our publishers have actually found that Chitika ads complement their Google AdSense ads. Publishers combining the ad networks have seen a 30% boost in revenue.
= When do I get paid? =

Payments are based on a 'Net 30' schedule. That means you will receive January's payment at the end of February, and February's payment at the end of March (and so on). Learn more about how our payment process works [here](http://chitika.com/chitika-blog/2011/08/its-august-1st-so-why-havent-i-received-my-july-payment-yet “What are Net 30 payments?”)
= When Installing this Plugin do I need to Upload the Entire Directory? =

Yes, when installing the plugin it is recommended you upload the `/chitika/` directory.  Your directory structure should look something like this:

    - wp-content/
        - plugins/
            - akismet/
            - chitika-premium/
                - README.txt
                - premium.php
                - index.php
                - screenshot-1.jpg
            - hello.php


= Is it compatible with WP Cache and WP Super Cache? =

Chitika uses javascript to serve up ads. As long as the javascript call is on the page, your ads will continue to display correctly for your traffic.

= How can I preview Chitika ads once I sign up and install this plugin? =

Easy! There's a tool built into the settings page, for WordPress versions greater than 2.2, where you can enter the URL of the page you want to test and the keyword(s) you want to test and it will open up a new window displaying that page with the Chitika ad.

= How do I Stop Chitika ads from displaying on specific posts =

If you don't want Chitika ads to display on specific posts just add `<!--NO-ChitikaPremium-->` anywhere in the body of the post.
The `<!-- -->` means it's an HTML comment, so you don't need to worry about the `<!--NO-ChitikaPremium-->` ever displaying in your posts. You can put it at the top, bottom or anywhere in between. You can [read more about HTML comments here](http://www.w3schools.com/tags/tag_comment.asp "W3C HTML Comment Tags").

= The ads aren’t showing up on my posts. Why? =

There are a few different things that could be going on:

1. Make sure you viewing you page with `#chitikatest=camera` at the end of the url. For example: `http://yourwebsite.com/blog/i-like-camera-stuff/#chitikatest=camera`. Make sure that you hit the refresh button after doing so. This will show you a preview of the ads that site visitors will see on the page.
2. The Chitika ads will only display above/below (depending on your settings) full posts, not excerpts. Therefore, be sure to check on a permalink page!
3. Are you using the most up-to-date version of the Chitika plugin? If not, make sure to update!
4. Address any concerns by submitting a support ticket to support@chitika.com.

== Upgrade Notice ==

= 2.0.4 =
This version adds a new username and account verification system. Upgrade recommended.
