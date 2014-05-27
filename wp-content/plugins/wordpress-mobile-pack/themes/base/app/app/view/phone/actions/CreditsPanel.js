Ext.define("WP.view.phone.actions.CreditsPanel", {
    extend: 'Ext.Panel',
	
	requires: [
		'Ext.TitleBar'
	],
	
	config: {
		
		itemId: "creditsPanel",
		
		// custom properties
		mask: null,												// the custom mask that appears behind this panel
		
		// css properties
		cls: 'credits-panel',
		width: '100%',
        height: '100%',
		zIndex: 100,
		layout: {
			type: 'vbox',
            pack: 'justify',
            align: 'stretch'
		},
		
		// properties
		modal: false,
        fullscreen: true,
        centered: true,
        items:[
            {
                xtype: 'titlebar',
                cls: 'top-bar',
				docked: 'top',
                title: 'Credits',
                height: 40,
                items: [
                    {
                        xtype: "button",
						itemId: "closeBtn",
						action: 'close-credits',
						cls: 'close-x',
                        pressedCls: 'pressed',
						iconCls: 'close-icon',
                        height: 60,
                        width: 60,
                        align: 'right',
                        html: '&nbsp;'
                    }
                ]
            },
            {
                xtype: 'container',
                itemId: 'contentBox',
                cls: 'content-container',
                flex: 1,
                scrollable:{
                    direction: 'vertical',
                    indicators: false
                }
            }
        ],
		hidden: true,
		showAnimation: {
			type: "slide",
			direction: "down",
			duration: 400,
			easing: "out"
		},
		hideAnimation: {
			type: "slideOut",
			direction: "up",
			duration: 400,
			easing: "in"
		}	
    },
	
	initialize: function(){
		
		this.on("addcontent", this.onAddContent, this);
		this.on("openpanel", this.onOpenPanel, this);
		this.on("closepanel", this.onClosePanel, this);
		
		var closeBtn = this.down("#closeBtn");
		closeBtn.on("tap", this.onCloseBtnTap, this);
		
		this.callParent(arguments);
	},
	
	
	onAddContent: function(content){
		this.down("#contentBox").setHtml("<div>"+content+"</div>");
	},
	
	
	onOpenPanel: function(){
		this.show();
	},
	
	onClosePanel: function(){
		this.hide();
		
		var me = this;
		Ext.defer(function(){
			me.destroy();	
		}, 400);	
	},
		
	onCloseBtnTap: function(){
		this.getMask().fireEvent("close");	
	},
});
