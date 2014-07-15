=== TinyMCE Advanced ===
Contributors: azaozz
Tags: wysiwyg, formatting, tinymce, write, editor
License: GPL v2
Requires at least: 3.7
Tested up to: 3.8
Stable tag: 3.5.9

Enables the advanced features of TinyMCE, the WordPress WYSIWYG editor. 

== Description ==

This plugin will let you add, remove and arrange the buttons that are shown on the Visual Editor toolbar.
It includes 16 plugins for [TinyMCE](http://tinymce.com/) that are automatically enabled or disabled depending on what buttons are chosen.

= Some of the features added by this plugin =

* Support for creating and editing tables.
* More options when inserting lists.
* Search and Replace in the editor.
* Editing in-line css styles.
* Advanced image dialog that offer a lot of options.
* Adding and removing HTML tag attributes.

Included TinyMCE plugins: Advanced HR, Advanced Image, Advanced Link, Advanced List, Context Menu, Emotions (Smilies), Date and Time, IESpell, Layer, Nonbreaking, Print, Search and Replace, Style, Table, Visual Characters and XHTML Extras.

= Language Support =

The plugin includes several translations for the TinyMCE components: German, French, Italian, Spanish, Portuguese, Russian, Chinese and Japanese.

More translations are available as a separate plugin: [TinyMCE Advanced Language Pack](http://wordpress.org/plugins/tinymce-advanced-language-pack/).


== Installation ==

Best is to install directly from WordPress. If manual installation is required, please make sure that the plugin files are in a folder named "tinymce-advanced" (not two nested folders) in the WordPress plugins folder, usually "wp-content/plugins".

== Changelog ==

= 3.5.9.1 =
Updated for WordPress 3.8, fixed auto-embedding of single line URLs when not removing paragraph tags.

= 3.5.9 =
Updated for WordPress 3.7 and TinyMCE 3.5.9.

= 3.5.8 =
Updated for WordPress 3.5 and TinyMCE 3.5.8.

= 3.4.9 =
Updated for WordPress 3.4 and TinyMCE 3.4.9.

= 3.4.5.1 =
Fixed a bug preventing TinyMCE from importing CSS classes from editor-style.css.

= 3.4.5 =
Updated for WordPress 3.3 or later and TinyMCE 3.4.5.

= 3.4.2.1 =
Fix the removal of the *media* plugin so it does not require re-saving the settings.

= 3.4.2 =
Compatibility with WordPress 3.2 and TinyMCE 3.4.2, removed the options for suport for iframe and HTML 5.0 elements as they are supported by default in WordPress 3.2, removed the *media* plugin as it is included by default.

= 3.3.9.1 =
Added advanced options: stop removing iframes, stop removing HTML 5.0 elements, moved the support for custom editor styles to editor-style.css in the current theme.

Attention: if you have a customized tadv-mce.css file and your theme doesn't have editor-style.css, please download tadv-mce.css, rename it to editor-style.css and upload it to your current theme directory. Alternatively you can add there the editor-style.css from the Twenty Ten theme. If your theme has editor-style.css you can add any custom styles there.

= 3.3.9 =
Compatibility with WordPress 3.1 and TinyMCE 3.3.9, improved P and BR tags option.

= 3.2.7 =
Compatibility with WordPress 2.9 and TinyMCE 3.2.7, several minor bug fixes.

= 3.2.4 =
Compatibility with WordPress 2.8 and TinyMCE 3.2.4, minor bug fixes.

= 3.2 =
Compatibility with WordPress 2.7 and TinyMCE 3.2, minor bug fixes.

= 3.1 =
Compatibility with WordPress 2.6 and TinyMCE 3.1, keeps empty paragrarhs when disabling the removal of P and BR tags, the buttons for MCImageManager and MCFileManager can be arranged (if installed).

= 3.0.1 =
Compatibility with WordPress 2.5.1 and TinyMCE 3.0.7, added option to disable the removal of P and BR tags when saving and in the HTML editor (autop), added two more buttons to the HTML editor: autop and undo, fixed the removal of non-default TinyMCE buttons.

= 3.0 =
Support for WordPress 2.5 and TinyMCE 3.0.

= 2.2 =
Deactivate/Uninstall option page, font size drop-down menu and other small changes.

= 2.1 =
Improved language selection, improved compatibility with WordPress 2.3 and TinyMCE 2.1.1.1, option to override some of the imported css classes and other small improvements and bugfixes.

= 2.0 =
Includes an admin page for arranging the TinyMCE toolbar buttons, easy installation, a lot of bugfixes, customized "Smilies" plugin that uses the built-in WordPress smilies, etc. The admin page uses jQuery and jQuery UI that lets you "drag and drop" the TinyMCE buttons to arrange your own toolbars and enables/disables the corresponding plugins depending on the used buttons.


== Frequently Asked Questions ==

= No styles are imported in the Styles drop-down menu. =

These styles are imported from your current theme editor-style.css file. However some themes do not have this functionality. For these themes TinyMCE Advanced has the option to let you add a customized editor-style.css and import it into the editor.

= I have just installed this plugin, but it does not do anything. =

Log out of WordPress, clear your browser cache, quit and restart the browser and try again. If that does not work, there may be a caching proxy or network cache somewhere between you and your host. You may need to wait for a few hours until this cache expires.

= When I add "Smilies", they do not show in the editor. =

The "Emotions" button in TinyMCE adds the codes for the smilies. The actual images are added by WordPress when viewing the Post. Make sure the checkbox "Convert emoticons to graphics on display" in "Options - Writing" is checked.

= The plugin does not add any buttons. =

Make sure the "Disable the visual editor when writing" checkbox under "Users - Your Profile" is **not** checked.

= I still see the "old" buttons in the editor =

Click the "Remove Settings" button on the plugin settings page and then set the buttons again and save.

= Other questions? More screenshots? =

Please post on the support forum or visit the homepage for [TinyMCE Advanced](http://www.laptoptips.ca/projects/tinymce-advanced/). 


== Screenshots ==

1. The TinyMCE Advanced options page
