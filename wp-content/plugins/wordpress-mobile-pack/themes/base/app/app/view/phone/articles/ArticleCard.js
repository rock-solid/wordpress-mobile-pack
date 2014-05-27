Ext.define("WP.view.phone.articles.ArticleCard", {
    extend: 'Ext.Panel',
	requires: ['WP.view.phone.articles.MediaPanel'],
		
	config: {
		
		// custom properties
		record: null,
		isFilled: false,								// a flag indicates if the card was filled with content
		
		// css properties
		cls: 'article-card',
		
		// properties
		scrollable: {
			direction: 'vertical',
			indicators: false	
		},
		tpl: null
    },
	
	
	initialize: function(){
		this.callParent(arguments);
		
		// add events
		this.on("repaint", this.onPainted, this);
		this.on("addcontent", this.onAddContent, this);
	},
	
	
	onPainted: function(){
		
		if (this.getIsFilled()) return;
		
		var articlesPanel = this.getParent();
		var orientation = articlesPanel.getOrientation();
		
		if (articlesPanel.getInnerWidth() == 0){
			var width = this.element.dom.offsetWidth;
			articlesPanel.setInnerWidth(width);
					
			var height = this.element.dom.offsetHeight;
			articlesPanel.setInnerHeight(height);
		}
		else{
			var width = articlesPanel.getInnerWidth();
			var height = articlesPanel.getInnerHeight();
		}
					
		
		var image = this.getRecord().get("image") || null;
		var imageWidth = Math.floor(width),
			imageHeight;
		
		// if there is a default image
		if (image){
			imageHeight = Math.floor(imageWidth * image.height / image.width);
			if (isNaN(imageHeight)) imageHeight = 0;
		}
		else{
			imageHeight = 0;	
		}
		
		
		// update template
		this.setTpl(new Ext.XTemplate(
			
			'<div>',
				'<tpl if="'+imageWidth+' &gt; 0 &amp;&amp; '+imageHeight+' &gt; 0 ">',
					'<div class="article-image" style="width: ' + imageWidth + 'px; height: ' + imageHeight + 'px;" >',
						'<div class="image-container" style="background-image: url(\'{imageSrc}\'); width: ' + imageWidth + 'px; height: ' + imageHeight + 'px; background-size:cover; background-position:center">',
							'<div class="headline">',
								'<span class="category-name">{category}</span>',
								'<h1 class="article-title">{title}</h1>',
								'<tpl if="author.length &gt; 0">',
									"<h3>by <span>{author}</span>{[this.getDate(values.date, values.author)]}</h3>",
								'<tpl elseif="date.length &gt; 0">',
									"<h3>{[this.getDate(values.date, values.author)]}</h3>",
								'</tpl>',
							'</div>',
						'</div>',
					'</div>',
                
				'<tpl else>', 
					'<div class="headline no-image">',
						'<span class="category-name">{category}</span>',
						'<h1 class="article-title">{title}</h1>',
						'<tpl if="author.length &gt; 0">',
							"<h3>by <span>{author}</span>{[this.getDate(values.date, values.author)]}</h3>",
						'<tpl elseif="date.length &gt; 0">',
							"<h3>{[this.getDate(values.date, values.author)]}</h3>",
						'</tpl>',
					'</div>',
				'</tpl>',
			'</div>',
			
			'<div class="content">',
				'{[this.parseContent(values.content, '+imageWidth+')]}',
			'</div>',
			{
				getDate: function(date, author){
					var strDate = "";
					if (author && author.length > 0 && date && date.length > 0){
						strDate += ", ";	
					}
					if (date && date.length > 0){
						strDate += date;	
					}
					return strDate;
				},	
				parseContent: function(content, boxWidth){
					boxWidth = boxWidth - 30;
					var newContent = content.replace(/\<img\s/g, "<img style='max-width: "+boxWidth+"px;' ");
					newContent = newContent.replace(/\<iframe\s/g, "<iframe width='"+boxWidth+"' ");
					newContent = newContent.replace(/\<video\s/g, "<video width='"+boxWidth+"' ");
					newContent = newContent.replace(/\<table\s/g, "<table width='"+boxWidth+"' ");
					newContent = newContent.replace(/\<object\s/g, "<object width='"+boxWidth+"' ");
					newContent = newContent.replace(/\<canvas\s/g, "<canvas width='"+boxWidth+"' ");
					return newContent;
				}
            }
		));
	},
	
			
	onAddContent: function(){
		
		var articlesPanel = this.getParent(),
			image = this.getRecord().get("image") || null,
			content = this.getRecord().get("content"),
			html = content;
		
		this.setData({
			category	: this.getRecord().get("category_name"),
			title		: this.getRecord().get("title"),
			author		: this.getRecord().get("author"),
			date		: this.getRecord().get("date"),
            image       : image,
			imageSrc	: (image) ? image.src : null,
			content		: html
		})
		
		
		// mark the isFilled flag
		this.setIsFilled(true);
	}
});