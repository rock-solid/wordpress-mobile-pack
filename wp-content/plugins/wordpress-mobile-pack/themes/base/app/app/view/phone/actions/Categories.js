Ext.define("WP.view.phone.actions.Categories", {
  	extend: 'Ext.List',
	
	requires: [
		
	],
	
	config: {
		
		itemId: 'categoriesList',
		
		// custom properties
		
		// css properties
		cls: 'categories-list',
		itemCls: 'item',
		selectedCls: '',
		pressedCls: 'item-pressed',
		itemHeight: 85,
				
		// properties
		scrollable: {
			direction: 'none',
        },
		disableSelection: true,
		emptyText: 'There are no categoies',
		useSimpleItems: false,
		variableHeights: true,
		itemTpl: new Ext.XTemplate(
			'<tpl if="image &amp;&amp; image.width &amp;&amp; image.width &gt; 0">',
				'<div class="category-container" style="width: {[ this.itemWidth ]}px; height: {[ this.itemHeight ]}px; {[ this.getBackgroundProperties(values.image) ]}">',
					'<div class="gradient-bg" style="width: 100%; height: 100%">',
						'<h1>{name}</h1>',
					'</div>',
				'</div>',
			'<tpl else>',
				'<div class="category-container" style="width: {[ this.itemWidth ]}px; height: {[ this.itemHeight ]}px;">',
					'<div class="gradient-bg" style="width: 100%; height: 100%">',
						'<h1>{name}</h1>',
					'</div>',
				'</div>',
			'</tpl>',
			{
				itemWidth: 225,
				itemHeight: 85,
				getImageSize: function(imageWidth, imageHeight){
                    var w = this.itemWidth / imageWidth;
					var h = this.itemHeight / imageHeight;
					var max = Math.max(w,h);
					
					var newWidth = Math.floor(imageWidth*max);
					var newHeight = Math.floor(imageHeight*max);
					return newWidth+"/"+newHeight;
                },

                getBackgroundSize: function(imageWidth, imageHeight){
					var w = this.itemWidth / imageWidth;
					var h = this.itemHeight / imageHeight;
					var max = Math.max(w,h);
					
					var newWidth = Math.floor(imageWidth*max);
					var newHeight = Math.floor(imageHeight*max);
					return newWidth+"px "+newHeight+"px";
                },
				
				getBackgroundProperties: function(image){
                    
					if (image.src.length > 0 && image.width > 0 && image.height > 0){
					   
						return  'background-image: url(\'' + image.src + '\');' +
						'-webkit-background-size: ' + this.getBackgroundSize( image.width, image.height) + ';' +
						'-moz-background-size: ' + this.getBackgroundSize( image.width, image.height) + ';' +
						'-o-background-size: ' + this.getBackgroundSize( image.width, image.height) + ';' +
						'background-size: ' + this.getBackgroundSize( image.width, image.height) + ';';
					} 
					else {
                        return '';
                    }
                }
			}
		)
    },
	
	initialize: function(){
		
		this.setStore(Ext.create("WP.store.Categories"));
		
		this.callParent(arguments);
	}
});
