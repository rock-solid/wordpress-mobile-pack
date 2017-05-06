/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'CONNECT WITH APPTICLES'                                     */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_CONNECT() {

  var JSObject = this;

  this.type = "wmp_connect";

  this.form;
  this.DOMDoc;

  this.send_btn;

  this.submitURL;
  this.redirectTo;

  /*****************************************************************************************************/
  /*                                                                                                   */
  /*                              FUNCTION INIT - called from WMPJSInterface                           */
  /*                                                                                                   */
  /*****************************************************************************************************/
  this.init = function () {

    // save a reference to WMPJSInterface Object
    WMPJSInterface = window.parent.WMPJSInterface;

    // save a reference to "SEND" Button
    this.send_btn = jQuery('#' + this.type + '_send_btn', this.DOMDoc).get(0);

    // save a reference to the FORM and remove the default submit action
    this.form = this.DOMDoc.getElementById(this.type + '_form');

    // add actions to send, cancel, ... buttons
    this.addButtonsActions();

    if (this.form == null) {
      return;
    }

    // custom validation for FORM's inputs
    this.initValidation();
  }




  /*****************************************************************************************************/
  /*                                                                                                   */
  /*                                  FUNCTION INIT VALIDATION                                         */
  /*                                                                                                   */
  /*****************************************************************************************************/
  this.initValidation = function () {

    /*******************************************************/
    /*                    VALIDATION RULES                 */
    /*******************************************************/

    // this is the object that handles the form validations
    this.validator = jQuery("#" + this.form.id, this.DOMDoc).validate({

      rules: {
        wmp_connect_apikey: {
          required: true,
          alphanumeric: true
        },

      },

      messages: {
        wmp_connect_apikey: {
          required: "This field is required."
        }
      },

      // the errorPlacement has to take the table layout into account
      // all the errors must be handled by containers/divs with custom ids: Ex. "error_fullname_container"
      errorPlacement: function (error, element) {
        var split_name = element[0].id.split("_");
        var id = (split_name.length > 1) ? split_name[split_name.length - 1] : split_name[0];
        var errorContainer = jQuery("#error_" + id + "_container", JSObject.DOMDoc);
        error.appendTo(errorContainer);
      },

      errorElement: 'span'
    });
  }


  /*****************************************************************************************************/
  /*                                                                                                   */
  /*                                  FUNCTION ADD BUTTONS ACTIONS                                     */
  /*                                                                                                   */
  /*****************************************************************************************************/
  this.addButtonsActions = function () {

    /*******************************************************/
    /*                     SEND "BUTTON"                   */
    /*******************************************************/
    jQuery(this.send_btn).unbind("click");
    jQuery(this.send_btn).bind("click", function () {
      JSObject.disableButton(this);
      JSObject.validate();
    })
    JSObject.enableButton(this.send_btn);

    jQuery("#" + JSObject.form.id, JSObject.DOMDoc).bind("keypress", function (e) {
      if (e.keyCode == 13) return false;
    });

  }


  /*****************************************************************************************************/
  /*                                                                                                   */
  /*                                 FUNCTION ENABLE BUTTON                                            */
  /*                                                                                                   */
  /*****************************************************************************************************/
  this.enableButton = function (btn) {
    jQuery(btn).css('cursor', 'pointer');
    jQuery(btn).animate({ opacity: 1 }, 100);
  }


  /*****************************************************************************************************/
  /*                                                                                                   */
  /*                                 FUNCTION DISABLE BUTTON                                           */
  /*                                                                                                   */
  /*****************************************************************************************************/
  this.disableButton = function (btn) {
    jQuery(btn).unbind("click");
    jQuery(btn).animate({ opacity: 0.4 }, 100);
    jQuery(btn).css('cursor', 'default');
  }


  /*****************************************************************************************************/
  /*                                                                                                   */
  /*                                 FUNCTION SCROLL TO FIRST ERROR                                    */
  /*                                                                                                   */
  /*****************************************************************************************************/
  this.scrollToError = function (yCoord) {

    var container = jQuery('html,body', JSObject.DOMDoc);
    var scrollTop = parseInt(jQuery('html,body').scrollTop()) || parseInt(jQuery('body').scrollTop());
    var containerHeight = container.get(0).clientHeight;
    var top = parseInt(container.offset().top);

    if (yCoord < scrollTop) {
      jQuery(container).animate({ scrollTop: yCoord - 20 }, 1000);
    }
    else if (yCoord > scrollTop + containerHeight) {
      jQuery(container).animate({ scrollTop: scrollTop + containerHeight }, 1000);
    }
  }


  /*****************************************************************************************************/
  /*                                                                                                   */
  /*                                 FUNCTION VALIDATE INFORMATION                                     */
  /*                                                                                                   */
  /*****************************************************************************************************/
  this.validate = function () {
    jQuery(this.form).validate().form();

    // y coordinates of error inputs
    var arr_errorsYCoord = [];

    // find the y coordinate for the errors
    for (var name in this.validator.invalid) {
      var $input = jQuery(this.form[name]);
      arr_errorsYCoord.push($input.offset().top);
    }

    // if there are no errors from syntax point of view, then send data
    if (arr_errorsYCoord.length == 0) {
      this.sendData();
    }
    //move container(div) scroll to the first error
    else {
      arr_errorsYCoord.sort(function (a, b) { return (a - b); });
      JSObject.scrollToError(arr_errorsYCoord[0]);

      // add actions to send, cancel, ... buttons. At this moment the buttons are disabled.
      JSObject.addButtonsActions();
    }
  }



  /*****************************************************************************************************/
  /*                                                                                                   */
  /*                          FUNCTION SEND DATA   								                     */
  /*                                                                                                   */
  /*****************************************************************************************************/
  this.sendData = function () {

    WMPJSInterface.Preloader.start();

    // Make request to save the API Key in the options table (will be used by the API to retrieve settings)
    jQuery.post(
      ajaxurl,
      {
        'action': 'wmp_premium_save',
        'api_key': jQuery("#" + JSObject.type + "_apikey", JSObject.DOMDoc).val()
      },
      function (response) {

        // Call Appticles API
        jQuery.ajax({
          url: JSObject.submitURL,
          type: 'get',
          data: {
            'apiKey': jQuery("#" + JSObject.type + "_apikey", JSObject.DOMDoc).val()
          },
          dataType: "jsonp",
          success: function (responseJSON) {

            WMPJSInterface.Preloader.remove(100);

            var JSON = eval(responseJSON);
            var status = Boolean(Number(String(JSON.status)));

            if (status == true) {

              // Make request to save config settings in the db and enable premium theme
              jQuery.post(
                ajaxurl,
                {
                  'action': 'wmp_premium_connect',
                  'api_key': jQuery("#" + JSObject.type + "_apikey", JSObject.DOMDoc).val(),
                  'valid': 1,
                  'config_path': JSON.config_path
                },
                function (response1) {

                  var response1 = Boolean(Number(String(response1)));

                  if (response1 == true) {

                    // reload the page - redirect to premium
                    window.location.href = JSObject.redirectTo;

                  } else {

                    var message = 'We were unable to verify your API Key. Please contact support.';
                    WMPJSInterface.Loader.display({ message: message });

                    // reset form
                    JSObject.form.reset();

                    //enable form elements
                    setTimeout(function () {
                      var aElems = JSObject.form.elements;
                      nElems = aElems.length;
                      for (j = 0; j < nElems; j++) {
                        aElems[j].disabled = false;
                      }
                    }, 300);

                    //enable buttons
                    JSObject.addButtonsActions();
                  }
                }
              );

            } else {

              // Display error message returned by the API or a default message
              if (JSON.message != undefined)
                WMPJSInterface.Loader.display({ message: JSON.message });
              else
                WMPJSInterface.Loader.display({ message: "We were unable to verify your API Key. Please contact support." });

              // reset form
              JSObject.form.reset();

              //enable form elements
              setTimeout(function () {
                var aElems = JSObject.form.elements;
                nElems = aElems.length;
                for (j = 0; j < nElems; j++) {
                  aElems[j].disabled = false;
                }
              }, 300);

              //enable buttons
              JSObject.addButtonsActions();

            }

          },
          error: function (responseJSON) {

            // API endpoint is turned off
            WMPJSInterface.Preloader.remove(100);
            WMPJSInterface.Loader.display({ message: "Verification endpoint is unreachable. Please contact support." });
          }
        });

      }
    );
  }
}
