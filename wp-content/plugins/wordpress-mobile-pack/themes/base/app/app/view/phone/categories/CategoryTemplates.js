Ext.define("WP.view.phone.categories.CategoryTemplates", {
    extend: 'Ext.XTemplate',
	
	constructor: function(){
		
		this.callParent(
			this.t.concat([{
				config: this.config,
				getItemCls: function(){
					return this.config.itemCls;
				},
				getBoxWidth: function(percent){
					return Math.ceil(this.config.carousel.getInnerWidth())
				},
				getBoxHeight: function(percent){
					return Math.ceil(this.config.carousel.getInnerHeight())
				},
				getWidthByHeight: function(imgWidth, imgHeight, height){
					var width = (height*imgWidth) / imgHeight;
					return Math.ceil(width);
				},
				getHeightByWidth: function(imgWidth, imgHeight, width){
					var height = (width*imgHeight) / imgWidth;
					return Math.ceil(height);	
				},
				getShapeStyle: function(dimension, direction){
					direction = direction || "bottom";
					return "width:0px; height:0px; border-"+direction+"-width: 25px; border-right: "+parseInt(dimension)+"px solid transparent;";	
				},
				stripHtml: function(txt){
					var c = txt.replace(/\<br(|\/)\>\<br(|\/)\>/g, "<br/>");
						c = c.replace(/\<br(|\/)\>/g, "<br/><br/>");
						c = c.replace(/\<br\/\>\<br\/\>/g, "%br/%%br/%");
						c = c.replace(/\<p\>(|\s+)\<\/p\>/g, "");
						c = c.replace(/\<\/p\>/g, "%br/%%br/%");
					
					var newTxt = c.replace(/(<([^>]+)>)/ig,"");
					newTxt = newTxt.trim();
					newTxt = newTxt.replace(/\%br\/\%\%br\/\%/g, "<br/><br/>");
					return newTxt;	
				},
				hasSpaceForDescription: function(){
					var w = this.getBoxWidth();
					var h = this.getBoxHeight();
					var orientation = this.config.carousel.getOrientation();
					
					if (orientation == "landscape"){
						return w/h > 1.4;
					}
					else{
						return h/w > 1.4;	
					}
				}
			}])
		)
	}
});
