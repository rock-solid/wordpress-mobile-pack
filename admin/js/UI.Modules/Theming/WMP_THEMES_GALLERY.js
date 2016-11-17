/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'THEMES GALLERY'		                                     */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_THEMES_GALLERY() {

  var JSObject = this;

  this.type = 'wmp_themes';

  this.DOMDoc;

  /*****************************************************************************************************/
  /*                                                                                                   */
  /*                              FUNCTION INIT - called from WMPJSInterface                           */
  /*                                                                                                   */
  /*****************************************************************************************************/
  this.init = function () {

    // save a reference to WMPJSInterface Object
    WMPJSInterface = window.parent.WMPJSInterface;

    this.initThemesPreview();
    this.initThemesGalleries();
  };

  /**
   * Preview themes - snapshots galleries
   */
  this.initThemesGalleries = function () {

    jQuery('.' + this.type + '_preview[data-snapshots]').each(function(index, object){

      var snapshots = jQuery(object).attr('data-snapshots').split(',');

      if (snapshots.length > 0){

        // reformat the snapshots list to fit the magnificPopup format: { 'src' : ... }
        var formattedSnapshots = snapshots.map(function(obj){
          var rObj = {};
          rObj['src'] = obj;
          return rObj;
        });

        jQuery(object).magnificPopup({
          items: formattedSnapshots,
          gallery: {
            enabled: true
          },
          type: 'image' // this is default type
        });
      }
    });
  };

  /**
   * Preview themes - demos
   */
  this.initThemesPreview = function () {

    jQuery('.' + this.type + '_preview[data-url]').click(function () {
      window.open(jQuery(this).attr('data-url'), 'previewApp', 'location=yes,width=375,height=667');
    });
  };
}
