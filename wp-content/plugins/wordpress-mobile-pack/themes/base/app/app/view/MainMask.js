Ext.define("WP.view.MainMask", {
    extend: 'Ext.Container',

	config: {
		
		// custom properties
		name: "mainMask",
		closeFn: Ext.emptyFn,									// a custom function that is called when the mask is closed
		spinner: false,
		
		// css properties
		cls: 'main-mask',
		top: 0,
        left: 0,
		
		// properties
		hidden: true,
		showAnimation: {
			type: "fadeIn",
			duration: 400	
		},
		hideAnimation: {
			type: "fadeOut",
			duration: 400	
		},
		layout: {
			type: "vbox",
			pack: "center",
			align: "center"	
		}
	},
	
	initialize: function(){

        this.callParent(arguments);

        this.element.on("tap", this.onClose, this);
        this.element.on("swipe", this.onClose, this);
		this.on("close", this.onClose, this);
		this.on("addspinner", this.onAddSpinner, this);
		this.on("removespinner", this.onRemoveSpinner, this);
		
		if (this.getSpinner()){
			this.fireEvent("addspinner");
		}
	},


    onClose: function(){
		
		// run the close callback function
		this.getCloseFn().call();
		
		this.hide();
		
		var me = this;
		Ext.defer(function(){
			me.destroy();	
		}, 400);
    },
	
	
	// add loading spinner
	onAddSpinner: function(){
		
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
		
		var spinner = Ext.create("Ext.Container", {
			itemId: "spinner",
			html: html
		});
		
		this.add(spinner);
	},
	
	
	// remove loading spinner
	onRemoveSpinner: function(){
		var spinner = this.down("#spinner");
		this.remove(spinner, true);
	}
	
});