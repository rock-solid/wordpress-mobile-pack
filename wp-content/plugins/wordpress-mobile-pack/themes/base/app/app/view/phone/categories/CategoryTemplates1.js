Ext.define("WP.view.phone.categories.CategoryTemplates1", {
    extend: 'WP.view.phone.categories.CategoryTemplates',
	
	constructor: function(config){
		this.t = [];
		this.config = config;
		
		if (config.carousel){
			this.t = (config.carousel.getOrientation() == "landscape") ? this.getLandscapeTemplate(config.index) : this.getPortraitTemplate(config.index);	
		}
		
		this.callParent();
	},
	
	
	getLandscapeTemplate: function(index){
		
		var t;
		
		switch (index){
			
			// template 1
			default:
				t = [
					'<tpl for=".">',
						
						// article 1
						'<tpl if="xindex == 1">',
							'<tpl if="image &amp;&amp; image.src.length &gt; 0">',
								'<div class="{[this.getItemCls()]}" data-index="{#}" data-article-id="{id}">',
							'<tpl else>',
								'<div class="{[this.getItemCls()]} no-image" data-index="{#}" data-article-id="{id}">',
							'</tpl>',
								
									'<div class="inner">',
										
										// article with image
										'<tpl if="image &amp;&amp; image.src.length &gt; 0">',
											'<div class="image-container" style="background-image:url(\'{[values.image.src]}\'); background-size:cover; background-position:center;">',
												'<div class="overlay"><div class="shape bottom" style="{[this.getShapeStyle(this.getBoxWidth())]} "></div></div>',
											'</div>',
										
											'<div class="headline">',
												'<div class="over-shape"><span class="category-name" style="width:{[this.getBoxWidth()/2]}px">{category_name}</span></div>',
												'<h1>{title}</h1>',
												'<tpl if="date &amp;&amp; date.length &gt; 0">',
													"<h3>{date}</h3>",
												'</tpl>',
												'<tpl if="description &amp;&amp; description.length &gt; 0">',
													'<div class="description"><p>{[this.stripHtml(values.description)]}</p></div>',
												'</tpl>',
											'</div>',
											
										// article without image
										'<tpl else>',
											'<div class="headline">',
												'<h1>{title}</h1>',
												'<tpl if="date &amp;&amp; date.length &gt; 0">',
													'<h3 style="width:{[this.getBoxWidth()/2]}px">{date}</h3>', 
												'</tpl>',
												'<div class="over-shape">',
													'<span class="category-name">{category_name}</span>',
												'</div>',
											'</div>',
											
											'<div class="overlay">',
												'<div class="shape top" style="{[this.getShapeStyle(this.getBoxWidth(), "top")]}"></div>',
												'<tpl if="description &amp;&amp; description.length &gt; 0">',
													'<div class="description"><p>{[this.stripHtml(values.description)]}</p></div>',
													'<div class="category-inside-shadow"></div>',
												'</tpl>',
											'</div>',
										'</tpl>',	
											
									'</div>',
							'</div>',
						'</tpl>',
					'</tpl>',
				];
				break;		
		}
		
		return t;
	},
	/* End of getLandscapeTemplate() function */
	
	
	getPortraitTemplate: function(index){
		
		var t;
		
		switch (index){
			
			// template 1
			default:
				t = [
					'<tpl for=".">',
						
						// article 1
						'<tpl if="xindex == 1">',
							'<tpl if="image &amp;&amp; image.src.length &gt; 0">',
								'<div class="{[this.getItemCls()]}" data-index="{#}" data-article-id="{id}">',
							'<tpl else>',
								'<div class="{[this.getItemCls()]} no-image" data-index="{#}" data-article-id="{id}">',
							'</tpl>',
							
									'<div class="inner">',
										
										// article with image
										'<tpl if="image &amp;&amp; image.src.length &gt; 0">',
											'<div class="image-container" style="background-image:url(\'{[values.image.src]}\'); background-size:cover; background-position:center;">',
												'<div class="overlay"><div class="shape bottom" style="{[this.getShapeStyle(this.getBoxWidth())]} "></div></div>',
											'</div>',
										
											'<div class="headline">',
												'<div class="over-shape"><span class="category-name" style="width:{[this.getBoxWidth()/2]}px">{category_name}</span></div>',
												'<h1>{title}</h1>',
												'<tpl if="date &amp;&amp; date.length &gt; 0">',
													"<h3>{date}</h3>",
												'</tpl>',
												'<tpl if="description &amp;&amp; description.length &gt; 0">',
													'<div class="description"><p>{[this.stripHtml(values.description)]}</p></div>',
												'</tpl>',
											'</div>',
										
										// article without image
										'<tpl else>',
											'<div class="headline">',
												'<h1>{title}</h1>',
												'<tpl if="date &amp;&amp; date.length &gt; 0">',
													'<h3 style="width:{[this.getBoxWidth()/2]}px">{date}</h3>', 
												'</tpl>',
												'<div class="over-shape">',
													'<span class="category-name">{category_name}</span>',
												'</div>',
											'</div>',
											
											'<div class="overlay">',
												'<div class="shape top" style="{[this.getShapeStyle(this.getBoxWidth(), "top")]}"></div>',
												'<tpl if="description &amp;&amp; description.length &gt; 0">',
													'<div class="description"><p>{[this.stripHtml(values.description)]}</p></div>',
													'<div class="category-inside-shadow"></div>',
												'</tpl>',
											'</div>',
										'</tpl>',
										
									'</div>',
							'</div>',
						'</tpl>',
					'</tpl>',
				];
				break;	
		}
		
		return t;
	},
	/* End of getPortraitTemplate() function */
	
});
