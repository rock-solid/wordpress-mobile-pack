// a hack used for Android (4+) browsers, to handle the Viewport's orientation change event

Ext.define('WP.util.Viewport', {
	override: 'Ext.viewport.Android',
	onReady: function() {
		if (this.getAutoRender()) {
			this.render();
			//this.updateSize(); // added this
		}
		if (Ext.browser.name == 'ChromeiOS') {
			this.setHeight('-webkit-calc(100% - ' + ((window.outerHeight - window.innerHeight) / 2) + 'px)');
		}
	}
});