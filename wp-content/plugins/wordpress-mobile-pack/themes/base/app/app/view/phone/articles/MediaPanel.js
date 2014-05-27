Ext.define("WP.view.phone.articles.MediaPanel", {
    extend: 'Ext.Panel',
	
	config: {
		
		// custom properties
		intro: null,									// the container (div object) used for creating the intro animation
		
		// css properties
		cls: 'media-panel',
		floatingCls: '',
		width: '100%',
		height: '100%',
		zIndex: 100,
		layout: {
			type: 'hbox',
			pack: 'center',
			align: 'center'
		},
		
		// properties
		modal: true,
		fullscreen: true,
		centered: true,
		items: [
			{
				xtype: 'container',
				itemId: 'mediaContainer',
                cls: 'media-container',
				tpl: new Ext.XTemplate(
						'<div>{[this.getContent(values.content, values.width, values.height)]}</div>',
					{
						getContent: function(content, width, height){
							var viewportWidth = Ext.Viewport.getWindowWidth();
							var viewportHeight = Ext.Viewport.getWindowHeight();
							
							if (this.getOrientation() == "landscape"){
								viewportWidth -= 20;
								viewportHeight -= 20;
							}
							else{
								viewportWidth -= 20;
								viewportHeight -= 20;	
							}
							
							var newW, newH;
							
							// resize the iframe to fit in the defined area (viewportWidth, viewportHeight) 
							if ((viewportWidth*height)/width <= viewportHeight){
								newW = viewportWidth;
								newH = parseInt((viewportWidth*height)/width);
							}
							else{
								newH = viewportHeight;
								newW = parseInt((viewportHeight*width)/height);	
							}
							
							// if the width isn't 100% then change the height
							if (new RegExp("width\=[\'|\"]100%[\'|\"]").test(content) == false){
								content = content.replace(/height\=[\'|\"]\d+(|\%)[\'|\"]/g, "height='"+newH+"'");	
							}
							
							content = content.replace(/width\=[\'|\"]\d+(|\%)[\'|\"]/g, "width='"+newW+"'");
							
							
							// add autoplay param to iframe src
							var d = content.match(/src\=[\'|\"]\S+[\'|\"]/);
							var src = src0 = d[0];
							src.replace(/\'/g, "\"");
							
							if (src.indexOf("autoplay") == -1){
								var prefix = src.indexOf("?") != -1 ? "&" : "?";	
								src = src.substr(0, src.length-1) + prefix + "rel=0&amp;autoplay=1\"";
							}
							content = content.replace(src0, src);
							
							return content;
						},
						
						/* on Android Tablets (al least on 4.0 version) "landscape" and "portrait" are reversed
						   because the detection is made on window orientation property and not on window screen sizes
						   the function fixes that */
						getOrientation: function(){
							if (!Ext.os.is.Android){
								return 	Ext.Viewport.getOrientation();
							}
							
							return Ext.Viewport.getWindowWidth() > Ext.Viewport.getWindowHeight() ? "landscape" : "portrait";
						},
					}
				),
			},
			{
				xtype: 'backbutton',
                cls: 'back-button',
                pressedCls: 'pressed'
			}
		]
    },
	
	
	initialize: function(){
		this.callParent(arguments);
		
		// add a handler for the orientationchange event of the viewport
		Ext.Viewport.on('orientationchange', 'handleOrientationChange', this, {buffer: 10 });
		
		// add tap event on back button
		var btn = this.down("backbutton");
		btn.on("tap", this.closePanel, this);
	},
	
	
	handleOrientationChange: function(){
		var mediaContainer = this.down("#mediaContainer");
			mediaContainer.setData(mediaContainer.getData())
	},
	
	
	closePanel: function(){
		this.destroy();
	},
});
