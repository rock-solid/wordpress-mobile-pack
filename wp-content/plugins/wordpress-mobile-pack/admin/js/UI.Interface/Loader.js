/**
 *
 * @author Ardeleanu Ionut
 * @langversion JAVASCRIPT
 *
 * http://www.appticles.com
 * ionut@appticles.com
 * alexandra@appticles.com
 *
 */
 
 
/*****************************************************************************************************/
/*                                                                                                   */
/*                                         PRELOADER CLASS                                           */          
/*                                                                                                   */
/*****************************************************************************************************/ 
function Preloader(){
	
	var JSObject = this;
	
	this.defaultParams = {width: 320,
						height: 80,
						message: 'Please wait...'};
						
	
	/*****************************************************************************************/
	/*                                      START PRELOADER                                  */
	/*****************************************************************************************/
	/**
	 * start the loader
	 * method type: LOCAL
	 * params: @params : a JSON with new params like defaultParams
	 */
	this.start = function(params){
		
		this.defaultParams = jQuery.extend({}, this.defaultParams, params);
		
		jQuery('#preloader_container').remove();
		jQuery('body *:first',document).before('<div id="preloader_container" style="position:fixed; z-index:999998; display:none;"><div class="preloader"></div><div style="position:fixed; background: #000;"><div id="preloader_table" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFF; padding:10px;">'+this.defaultParams.message+'<br><br><img src="'+JSInterface.localpath+'includes/images/loading_animation.gif" /></div></div></div>');
		
		var preloader_container = jQuery('#preloader_container');
		var table = jQuery('#preloader_table',preloader_container);
		var preloading_bg = jQuery('.preloader',preloader_container);
		
		var w = this.defaultParams.width;
		var h = this.defaultParams.height;
		
		table.width(w-20);
		table.height(h-20);
		table.parent().width(w);
		table.parent().height(h);
		preloading_bg.width(w);
		preloading_bg.height(h);
		preloading_bg.parent().width(w);
		preloading_bg.parent().height(h);
				
		var newW = (-w/2)+'px';
		var newH = (-h/2)+'px';
		
		preloader_container.css({'top':'50%' , 'left':'50%' , 'margin-left': newW, 'margin-top':newH}).fadeIn(500);	
		
		preloader_container.css({'top':'50%' , 'left':'50%' , 'margin-left': newW, 'margin-top':newH}).fadeIn(500);	
	}
	
	
	/*****************************************************************************************/
	/*                                      UPDATE PRELOADER                                 */
	/*****************************************************************************************/
	/**
	 * update the preloader with a new message
	 * method type: LOCAL
	 * params: @msg : the new message to display
	 */
	this.update = function(msg){
		
		var preloader_container = jQuery('#preloader_container');
		var table = jQuery('#preloader_table',preloader_container);
		table.get(0).rows[0].cells[0].innerHTML = msg;
		
	}
	
	/*****************************************************************************************/
	/*                                      REMOVE PRELOADER                                 */
	/*****************************************************************************************/
	/**
	 * remove the loader from the stage
	 * method type: LOCAL
	 * params: @time : the time for the loader to disappear
	 */
	this.remove = function(time){
		
		if (jQuery('#loading_container') != null){
			jQuery('#loading_container').remove();	
		}
		
		if (time == null){
			time = 100;	
		}
		
        var preloader_container = jQuery('#preloader_container');
        preloader_container.stop();
        
        preloader_container.fadeOut({duration: time},function(){ preloader_container.remove(); });
	}
	
}