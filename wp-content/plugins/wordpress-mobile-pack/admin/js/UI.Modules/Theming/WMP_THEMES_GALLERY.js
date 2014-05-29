/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'EDIT DISPLAY MODE'		                                     */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_THEMES_GALLERY(){

    var JSObject = this;

    this.type = "wmp_themes_gallery";

    this.DOMDoc;
    this.baseThemeUrl;
	
    /*****************************************************************************************************/
    /*                                                                                                   */
    /*                              FUNCTION INIT - called from WMPJSInterface                           */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.init = function(){
        
        // save a reference to WMPJSInterface Object
        WMPJSInterface = window.parent.WMPJSInterface;
        
        this.initThemesGalleries();
    }
    
     /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION INIT GALLERIES                                          */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.initThemesGalleries = function(){
        
        jQuery('#'+this.type+'_base').magnificPopup({
            items: [
              {
                src: JSObject.baseThemeUrl + '/snapshots/1-preview-phone-cover.png',
                title: 'Home page cover'
              },
              {
                src: JSObject.baseThemeUrl + '/snapshots/2-preview-phone-categories.png',
                title: 'View posts'
              },
              {
                src: JSObject.baseThemeUrl + '/snapshots/3-preview-phone-menu.png',
                title: 'Categories menu'
              },
              {
                src: JSObject.baseThemeUrl + '/snapshots/4-preview-phone-article.png',
                title: 'Post details'
              },
              {
                src: JSObject.baseThemeUrl + '/snapshots/5-preview-phone-comments.png',
                title: 'Post comments'
              }
            ],
            gallery: {
              enabled: true
            },
            type: 'image' // this is default type
        });
        
        jQuery('#'+this.type+'_business').magnificPopup({
            items: [
              {
                src: WMPJSInterface.localpath + '/admin/images/businesstheme/1-preview-phone-cover.png',
                title: 'Home page cover'
              },
              {
                src: WMPJSInterface.localpath + '/admin/images/businesstheme/2-preview-phone-categories.png',
                title: 'View posts'
              },
              {
                src: WMPJSInterface.localpath + '/admin/images/businesstheme/3-preview-phone-menu.png',
                title: 'Categories menu'
              },
              {
                src: WMPJSInterface.localpath + '/admin/images/businesstheme/4-preview-phone-article.png',
                title: 'Post details'
              },
              {
                src: WMPJSInterface.localpath + '/admin/images/businesstheme/5-preview-phone-social.png',
                title: 'Social media menu for a post'
              }
            ],
            gallery: {
              enabled: true
            },
            type: 'image' // this is default type
        });
        
        jQuery('#'+this.type+'_lifestyle').magnificPopup({
            items: [
              {
                src: WMPJSInterface.localpath + '/admin/images/lifestyletheme/1-preview-phone-cover.png',
                title: 'Home page cover'
              },
              {
                src: WMPJSInterface.localpath + '/admin/images/lifestyletheme/2-preview-phone-categories.png',
                title: 'View posts'
              },
              {
                src: WMPJSInterface.localpath + '/admin/images/lifestyletheme/3-preview-phone-menu.png',
                title: 'Side menu'
              },
              {
                src: WMPJSInterface.localpath + '/admin/images/lifestyletheme/4-preview-phone-article.png',
                title: 'Post details'
              },
              {
                src: WMPJSInterface.localpath + '/admin/images/lifestyletheme/5-preview-phone-social.png',
                title: 'Social media menu for a post'
              }
            ],
            gallery: {
              enabled: true
            },
            type: 'image' // this is default type
        });
    }
}