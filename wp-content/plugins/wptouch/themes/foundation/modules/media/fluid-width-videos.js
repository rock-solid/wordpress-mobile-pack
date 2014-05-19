/*!
* Chris Coyier's Fluid Width Videos, as seen on CSS-tricks.com
* https://github.com/chriscoyier/Fluid-Width-Video
*
* Copyright Chris Coyier - http://css-tricks.com
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
* Modified by BraveNewCode for WPtouch Pro 3
*/

function coyierVids() {
	jQuery(function() {

		var $allVideos = jQuery( "iframe[src^='http://player.vimeo.com'], iframe[src^='https://player.vimeo.com'], iframe[src^='//player.vimeo.com'], iframe[src^='http://www.youtube.com'], iframe[src^='https://www.youtube.com'], iframe[src^='//www.youtube.com'], iframe[src^='http://www.kickstarter.com'], iframe[src^='http://www.funnyordie.com'], iframe[src^='http://media.mtvnservices.com'], iframe[src^='http://trailers.apple.com'], iframe[src^='http://www.brightcove.com'], iframe[src^='http://blip.tv'], iframe[src^='http://break.com'], iframe[src^='http://www.traileraddict.com'], iframe[src^='http://d.yimg.com'], iframe[src^='http://movies.yahoo.com'], iframe[src^='http://www.dailymotion.com'], iframe[src^='http://s.mcstatic.com'], iframe[src^='http://vine.co'], iframe[src^='https://vine.co']" ),

	    $fluidEl = jQuery( '#content .post, #content .page' );

		$allVideos.each(function() {

		  jQuery(this)
		    // jQuery .data does not work on object/embed elements
		    .attr('data-aspectRatio', this.height / this.width)
		    .removeAttr('height')
		    .removeAttr('width');

		});

		jQuery(window).resize(function() {

		  var newWidth = $fluidEl.width();
		  $allVideos.each(function() {

		    var $el = jQuery(this);
		    $el
		        .width(newWidth)
		        .height(newWidth * $el.attr('data-aspectRatio'));

		  });

		}).resize();
	});
}