Ext.define("WP.view.LoadMask", {
    extend: 'Ext.LoadMask',
	xtype: 'custommask',
	   
	config: {
		
		// custom properties
		
		// css properties
		cls: '',
		
		// properties
		message: '',
		transparent: true,
		html: '',
		indicator: false
	},
	
	initialize: function(){
		
		var html = 	'<div class="spinner-box">';
			html += 	'<div class="loader">';
			html += 		'<div class="circle">';
			html += 			'<div class="circle">';
			html += 				'<div class="circle">';
			html += 					'<div class="circle">';
			html += 						'<div class="circle"></div>';
			html += 					'</div>';
			html += 				'</div>';
			html += 			'</div>';
			html += 		'</div>';
			html += 	'</div>';
			html += '</div>';
			
		this.setHtml(html);	 
		
        this.callParent(arguments);
		
		this.on("hide", this.onHideMask, this);
	},
	
	onHideMask: function(){
		var me = this;
		this.element.dom.innerHTML = "";
		Ext.defer(function(){
			me.destroy();
		}, 20);
	}
});
