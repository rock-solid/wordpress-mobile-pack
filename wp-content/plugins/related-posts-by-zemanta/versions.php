<?php

/* ************************* */
/*         Version 2.0       */
/* ************************* */

/* Settings:

	+ exclude_categories						string (comma-separated list of categories) : exclude these categories from recommendations, do not show related posts in them (similar to the old not_on_categories setting, also copied from it)
	(-) missing_rp_algorithm
	(-) missing_rp_title
	(-) not_on_categories

	// In order to preserve backwards compatibility, we won't be deleting the settings in this release.

*/

/* Meta options:

	+ show_statistics						bool : allow the user to show and hide the box with statistics on the Related Posts settings page

*/

/* ************************* */
/*         Version 1.8       */
/* ************************* */

/* Meta options:

	- Added Zemanta Editorial Assistant

*/

/* ************************* */
/*         Version 1.7       */
/* ************************* */

/* Settings:

	- scroll_up_related_posts
	- include_promotionail_link

*/

/* Meta options:

	+ show_blogger_network_form					bool : show a form for submitting email and blogger network
	+ remote_notifications						array : array of received remote notifications stored as (msg_id => text), sorted by msg_id
	+ turn_on_button_pressed					bool : whether the "Turn on advanced features" button has been pressed
	- show_invite_friends_form

	(+&-) show_enable_ads_form					(silent release)

*/

/* ************************* */
/*         Version 1.6       */
/* ************************* */

/* Settings: 

	+ scroll_up_related_posts					bool : display scroll up related posts

*/

/* Meta options:

	+ show_install_tooltip						bool : show a tooltip for new users, telling them to go to settings and enable features
	+ remote_recommendations					bool : query sre for recommendations
	+ show_turn_on_button						bool : show "Turn on statistics & thumbnails" button in settings
	+ name								string : the user's name ('' means not set)
	+ email								string : the user's email ('' means not set)
	+ show_invite_friends_form					bool : show the Invite Friends form in settings
	- show_ctr_banner
	- show_blogger_network

*/

/* ************************* */
/*        Version 1.5.2      */
/* ************************* */

/* Settings: 

	+ enable_themes							bool : themes are now disabled by default (to avoid tracking users)

*/

/* ************************* */
/*        Version 1.5.1      */
/* ************************* */

/* Settings: 

	ctr_dashboard_enabled						bool : show CTR statistics in dashboard
	+ include_promotionail_link					bool : show promotional link next to related articles

*/

/* Meta options:

	+ show_blogger_network						bool : show a banner for submitting email and blogger network

*/

/* ************************* */
/*        Version 1.5        */
/* ************************* */

/* Settings:

	wp_no_rp -> missing_rp_algorithm				"text" | "random" | "commented" | "popularity" : how to generate posts if there are no related posts found
	wp_no_rp_text -> missing_rp_title				string : the title used if there are no related posts found
	zem_rp_auto -> on_single_post					bool : include related posts on single post pages
	zem_rp_comments -> display_comment_count				bool : display comment count of related posts
	zem_rp_date -> display_publish_date				bool : display the publish date of related posts
	zem_rp_except -> display_excerpt					bool : display short excerpts of related posts
	zem_rp_except_number -> excerpt_max_length			int : the maximum length of the excerpt
	zem_rp_exclude -> not_on_categories				string (comma-separated list of categories) : do not display related articles on posts from these categories
	zem_rp_limit -> max_related_posts				int : the maximum number of related posts to display
	zem_rp_rss -> on_rss						bool : include related posts on rss feed
	zem_rp_theme -> theme_name					string : the name of the theme css file
	zem_rp_thumbnail -> display_thumbnail				bool : show thumbnails for related posts
	zem_rp_thumbnail_post_meta -> thumbnail_custom_field		string | `false` : either the name of a custom field that should be used for thumbnails, or `false`
	zem_rp_thumbnail_text -> thumbnail_display_title			bool : display title (and publish date and comment count) when thumbnails are on
	zem_rp_title -> related_posts_title				string : the title to display above related posts
	zem_rp_title_tag -> related_posts_title_tag			"h2" | "h3" | "h4" | "p" | "div" : the HTML tag to use for title
	zem_rp_default_thumbnail_path -> default_thumbnail_path		string | `false` : the path to the image to use as a default thumbnail, or `false` if not set
	zem_rp_thumbnail_extract -> thumbnail_use_attached		bool : if featured/custom image not found, use the first attached image as thumbnail
	zem_rp_thumbnail_featured -> thumbnail_use_custom		bool : `true` -> use image from custom field, `false` -> use featured image

	+ zem_rp_theme_custom_css -> theme_custom_css			string : user defined custom css for related posts
	+ ctr_dashboard_enabled						"yes" | false : whether to show CTR statistics in dashboard or not. We fucked up here, we must normalize this in the next version.

	- zem_rp_version -> meta.version
	- zem_rp_log_new_user -> meta.new_user

*/

/* Meta options:

	version								string : the current version of the settings
	first_version							string : the version which was first installed on this blog
	new_user							bool : set for new users, and unset when they are reported to GA
	blog_id								`false` | string : if the user has registered, the assigned blog_id
	access_token							`false` | string : if the user has registered, the assigned access token
	show_upgrade_tooltip						bool : show a tooltip for upgrades, informing the user of new features
	show_ctr_banner							bool : show a banner for the new CTR tracking option, until the user has first enable the CTR tracking

*/

/* ************************* */
/*        Version 1.4        */
/* ************************* */

/* Settings :

	+ zem_rp_default_thumbnail_path	the path to default image (on the server) to use as a default thumbnail for posts with no other thumbnail
	+ zem_rp_log_new_user		set for new users, and unset when they are reported as new users to GA
	+ zem_rp_thumbnail_extract	display the first attached image of the post as post's thumbnail
	+ zem_rp_thumbnail_featured	display the post's featured image as post's thumbnail
	+ zem_rp_version			the installed version of the plugin (to know when the code was upgraded) - this was well-meaning, but Tom fucked up and this parameter is not saved (i.e. it is erased as soon as the user modifies any settings)

	- zem_rp_template

*/


/* ************************* */
/*        Version 1.3        */
/* ************************* */

/* Settings:

	wp_no_rp			what to do if there are no related posts (`text`, `random`, most `commented` posts)
	wp_no_rp_text			title to show if there are no realted posts
	zem_rp_auto			auto insert related posts to single post pages
	zem_rp_comments			display number of comments
	zem_rp_date			display publish date
	zem_rp_except			display post excerpt
	zem_rp_except_number		maximum number of characters to display for excerpt
	zem_rp_exclude			categories to exclude
	zem_rp_limit			maximum number of related posts
	zem_rp_rss			display related posts in RSS feed
	zem_rp_template			unused
	zem_rp_theme			related posts display theme
	zem_rp_thumbnail			display thumbnail for related posts
	zem_rp_thumbnail_post_meta	meta field to use for thumbnail
	zem_rp_thumbnail_text		show post title under thumbnails
	zem_rp_title			related posts title
	zem_rp_title_tag			related posts title tag (e.g. h2, h3, div, ...)

*/
