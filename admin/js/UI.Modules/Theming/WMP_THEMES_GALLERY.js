/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'THEMES GALLERY'		                                     */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_THEMES_GALLERY(){

    var JSObject = this;

    this.type = "wmp_themes";

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
    /*                              FUNCTION INIT SNAPSHOTS ARRAY                                        */
    /*                                                                                                   */
    /*****************************************************************************************************/

    this.initSnapshots = function(){

        var snapshots = {

            'app1' : [
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

            'app2': [
                {
                    src: WMPJSInterface.localpath + 'admin/images/app2/1-preview-cover.png',
                    title: 'Home page cover'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app2/2-preview-categories.png',
                    title: 'View posts'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app2/3-preview-menu.png',
                    title: 'Categories menu'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app2/4-preview-article.png',
                    title: 'Post details'
                }
            ],

            'app3': [
                {
                    src: WMPJSInterface.localpath + 'admin/images/app3/1-preview-cover.png',
                    title: 'Home page cover'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app3/2-preview-categories.png',
                    title: 'Categories menu'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app3/3-preview-menu.png',
                    title: 'View posts'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app3/4-preview-article.png',
                    title: 'Post details'
                }
            ],

            'app4': [
                {
                    src: WMPJSInterface.localpath + 'admin/images/app4/1-homepage.png',
                    title: 'View posts'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app4/2-menu-opened.png',
                    title: 'Menu'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app4/3-categories-opened.png',
                    title: 'Categories menu'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app4/4-others-opened.png',
                    title: 'Other options menu'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app4/5-article-details.png',
                    title: 'Article details'
                }
            ],

            'app5' : [
                {
                    src: WMPJSInterface.localpath + 'admin/images/app5/1-preview-homepage.png',
                    title: 'Home page'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app5/2-preview-articles.png',
                    title: 'View posts'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app5/3-preview-menu.png',
                    title: 'Menu'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app5/4-preview-menu-categories.png',
                    title: 'Categories menu'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app5/5-preview-article-details.png',
                    title: 'Article details'
                },
                {
                    src: WMPJSInterface.localpath + 'admin/images/app5/6-preview-article-details-menu.png',
                    title: 'Share article'
                }
            ]
        }

        return snapshots;

    }
    
     /*****************************************************************************************************/
    /*                                                                                                   */
    /*                                  FUNCTION INIT GALLERIES                                          */
    /*                                                                                                   */
    /*****************************************************************************************************/
    this.initThemesGalleries = function(){

        var snapshots = this.initSnapshots();

        for (var i = 1; i <= 5; i++) {

            jQuery('#' + this.type + '_preview_' + String(i)).magnificPopup({
                items: snapshots['app' + String(i)],
                gallery: {
                    enabled: true
                },
                type: 'image' // this is default type
            });
        }
    }
}