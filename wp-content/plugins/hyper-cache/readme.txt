=== Hyper Cache ===
Tags: cache,performance,staticizer,apache,htaccess,tuning,speed,bandwidth,optimization,tidy,gzip,compression,server load,boost
Requires at least: 2.5
Tested up to: 4.0
Stable tag: trunk
Donate link: http://www.satollo.net/donations
Contributors: satollo

Hyper Cache is flexible and easy to configure cache system for WordPress.

== Description ==

New! Version 3.0 has been rewritten. Be patient if some bugs are present and report
me any issue. Thank you.

Hyper Cache is a new cache system for WordPress, specifically written for
people which have their blogs on low resources hosting provider
(cpu and mysql). It works even with hosting based on Microsoft IIS (just tuning
the configuration). It has three invalidation method: all the cache, single post
based and nothing but with control on home and archive pages invalidation.

More can be read on the [official plugin page](http://www.satollo.net/plugins/hyper-cache).

Thanks to:

* Amaury Balmer
* Frank Luef
* HypeScience, Martin Steldinger, Giorgio Guglielmino for test and bugs submissions
* Ishtiaq
* Gene Steinberg
* Marcis Gasun
* Florian Höch
* Quentin
* Mckryak
* Tommy Tung


== Installation ==

1. Put the plugin folder into [wordpress_dir]/wp-content/plugins/
2. Go into the WordPress admin interface and activate the plugin
3. Optional: go to the options page and configure the plugin

== Frequently Asked Questions ==

See the [Hyper Cache official page](http://www.satollo.net/plugins/hyper-cache) or
the [Hyper Cache official forum](http://www.satollo.net/forums/forum/hyper-cache-plugin).

== Screenshots ==

1. The main configuration panel

2. Configuration of bypasses (things you want/not want to be cached)

3. Mobile devices configuration

== Changelog ==

= 3.1.2 =

* Fixed comment author cookie clean

= 3.1.1 =

* fixed a PHP warning on options panel when clearing an empty cache
* pot file added
* possible fix for after update messages that saving is needed

= 3.1.0 =

* Fixed the cookie bypass
* Removed a debug notice
* Added HTTPS separated cache
* Improved code performance

= 3.0.6 =

* readme.txt fix
* WP 4.0 compatibility check
* Fixed invalidation on draft saving

= 3.0.5 =

* Fixed analysis of URL with commas and dots
* Improved the categories invalidation with /%category% permalink

= 3.0.4 =

* Help texts fixed

= 3.0.3 =

* Fixed the autoclean when max cached page age is set to 0
* Changed a little the mobile agent list

= 3.0.2 =

* Added the browser caching option
* Fixed a cache header
* Fixed warning on cache size if empty

= 3.0.1 =

* Short description fix on plugin.php
* Forum link fix on readme.txt
* More help on comment authors option

= 3.0.0 =

* Totally rewritten to include the Lite Cache features

= To Do =

* Register an action to clean the cache by other plugin
* Separated cache for https
* Invalidation of categories paths when /%category%/%postname% is used

