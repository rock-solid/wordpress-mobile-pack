/*****************************************************************************************************/
/*                                                                                                   */
/*                                    	'JOIN WAITLIST'				                             	 */
/*                                                                                                   */
/*****************************************************************************************************/

function WMP_WAITLIST(){

	var JSObject = this;

	this.type = "wmp_waitlist";

	this.container;
	this.form;
	this.actionBox;
	this.DOMDoc;

	this.send_btn;
	this.display_btn;

	this.submitURL;
	this.listType;  // from where the form is used - content, settings, lifestyletheme or businesstheme

	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                              FUNCTION INIT - called from WMPJSInterface                              */
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.init = function(){

		// save a reference to WMPJSInterface Object
		WMPJSInterface = window.parent.WMPJSInterface;

		// save references to buttons
		this.send_btn = jQuery('#'+this.type+'_send_btn',this.container).get(0);
		this.display_btn = jQuery('#'+this.type+'_display_btn',this.container).get(0);

		// save a reference to actions container that displays the form
		this.actionBox = jQuery('#'+this.type+'_action',this.container).get(0);

		// save a reference to the FORM and remove the default submit action
		this.form = jQuery('#'+this.type+'_form',this.container).get(0);

		// add actions to send, cancel, ... buttons
		this.addButtonsActions();

		if (this.form == null){
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
	this.initValidation = function(){

		// this is the object that handles the form validations
		this.validator = jQuery("#"+this.form.id, this.container).validate({

			rules: {
				wmp_waitlist_emailaddress: {
					required	: true,
					email		: true
				}
			},

			messages: {
				wmp_waitlist_emailaddress: {
					email		: "Invalid e-mail address"
				}
			},

			// the errorPlacement has to take the table layout into account
			// all the errors must be handled by containers/divs with custom ids: Ex. "error_fullname_container"
			errorPlacement: function(error, element) {
				var split_name = element[0].id.split("_");
				var id = (split_name.length > 1) ? split_name[ split_name.length - 1] : split_name[0];
				var errorContainer = jQuery("#error_"+id+"_container",JSObject.DOMDoc);
				error.appendTo( errorContainer );
			},

			errorElement: 'span'
		});


		/*************  PLACEGOLDERS *************/

		var $Email = jQuery('#'+this.type+'_email',this.container);
		$Email.data('holder',$Email.attr('placeholder'));
		$Email.focusin(function(){jQuery(this).attr('placeholder','');}).focusout(function(){jQuery(this).attr('placeholder',jQuery(this).data('holder'));});

		/*******************************************/
	}


	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                                  FUNCTION ADD BUTTONS ACTIONS                                     */
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.addButtonsActions = function(){

		/*******************************************************/
		/*                     SEND "BUTTON"                   */
		/*******************************************************/

		jQuery(this.send_btn).unbind("click");
		jQuery(this.send_btn).bind("click",function(){
			JSObject.disableButton(this);
			JSObject.validate();

		})
		JSObject.enableButton(this.send_btn);


		/*******************************************************/
		/*                  DISPLAY "BUTTON"                   */
		/*******************************************************/

		jQuery(this.display_btn).unbind("click");
		jQuery(this.display_btn).bind("click",function(){

			JSObject.disableButton(this);

			jQuery(JSObject.form).show();
			jQuery(JSObject.actionBox).hide();
		})
		JSObject.enableButton(this.display_btn);

		jQuery("#"+JSObject.form.id,JSObject.DOMDoc).bind("keypress", function (e) {
			if (e.keyCode == 13) return false;
		});

	}

	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                                 FUNCTION ENABLE BUTTON                                            */
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.enableButton = function(btn){
		jQuery(btn).css('cursor','pointer');
		jQuery(btn).animate({opacity:1},100);

	}


	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                                 FUNCTION DISABLE BUTTON                                           */
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.disableButton = function(btn){
		jQuery(btn).unbind("click");
		jQuery(btn).animate({opacity:0.4},100);
		jQuery(btn).css('cursor','default');
	}

	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                                 FUNCTION VALIDATE INFORMATION                                     */
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.validate = function(){

		jQuery(this.form).validate().form();

		// y coordinates of error inputs
		var arr_errorsYCoord = [];

		// find the y coordinate for the errors
		for (var name in this.validator.invalid){
			var input = jQuery(this.form[name]);
			arr_errorsYCoord.push(input.offset().top);
		}

		// if there are no errors from syntax point of view, then send data
		if (arr_errorsYCoord.length == 0){

			//send data
			JSObject.sendData();
		}
		//move container(div) scroll to the first error
		else{

			// add actions to send, cancel, ... buttons. At this moment the buttons are disabled.
			JSObject.addButtonsActions();
		}
	}


	/*****************************************************************************************************/
	/*                                                                                                   */
	/*                          FUNCTION SEND DATA   								                     */
	/*                                                                                                   */
	/*****************************************************************************************************/
	this.sendData = function(){

		WMPJSInterface.Preloader.start();

    jQuery.post(
			ajaxurl,
			{
				'action': 'wmp_join_waitlist',
				'email': jQuery("#"+JSObject.type+"_emailaddress", JSObject.container).val(),
        'joined_waitlist': JSObject.listType,
			},
			function(response){
        WMPJSInterface.Preloader.remove(100);

        var status = parseInt(response);

        if (status == 0) {
          	var message = 'There was an error. Please reload the page and try again in few seconds or contact the plugin administrator if the problem persists.'
            WMPJSInterface.Loader.display({message: message});

            // reset form
  					JSObject.form.reset();

  					//enable form elements
  					setTimeout(function(){
  						var aElems = JSObject.form.elements;
  						nElems = aElems.length;
  						for (j=0; j<nElems; j++) {
  							aElems[j].disabled = false;
  						}
  					},300);

            //enable buttons
  					JSObject.addButtonsActions();
        } else {
          // successfully joined list (response = 1) or already joined (response = 2)
          WMPJSInterface.Loader.display({message: 'Email successfully subscribed'});

          jQuery(JSObject.form).hide();
          jQuery("#"+JSObject.type + "_added", JSObject.container).show();
        }
			}
		);

		// jQuery.ajax({
		// 	url: JSObject.submitURL,
		// 	type: 'post',
		// 	data: {
		// 		'email': jQuery("#"+JSObject.type+"_emailaddress", JSObject.container).val(),
		// 	},
    //   responseType:'application/json',
		// 	success: function(responseJSON){    //
		// 		WMPJSInterface.Preloader.remove(100);
    //
		// 		var JSON = eval(responseJSON);
		// 		var response = Number(String(JSON.status));
    //
		// 		if (response == 0) {
    //
		// 			var message = 'There was an error. Please reload the page and try again in few seconds or contact the plugin administrator if the problem persists.';
		// 			WMPJSInterface.Loader.display({message: message});
    //
		// 			// reset form
		// 			JSObject.form.reset();
    //
		// 			//enable form elements
		// 			setTimeout(function(){
		// 				var aElems = JSObject.form.elements;
		// 				nElems = aElems.length;
		// 				for (j=0; j<nElems; j++) {
		// 					aElems[j].disabled = false;
		// 				}
		// 			},300);
    //
		// 			//enable buttons
		// 			JSObject.addButtonsActions();
    //
		// 		} else {
    //
		// 			// successfully joined list (response = 1) or already joined (response = 2)
		// 			WMPJSInterface.Loader.display({message: JSON.message});
    //
		// 			jQuery(JSObject.form).hide();
		// 			jQuery("#"+JSObject.type + "_added", JSObject.container).show();
    //
		// 			// make request to settings endpoint to mark the wailist as joined
		// 			if (response == 1 || response == 2) {
    //
		// 				jQuery.post(
		// 					ajaxurl,
		// 					{
		// 						'action': 'wmp_join_waitlist',
		// 						'joined_waitlist': JSObject.listType
		// 					},
		// 					function(response1){
		// 					}
		// 				);
		// 			}
    //
		// 		}
    //
		// 	},
		// 	error: function(responseJSON){
		// 	}
		// });

	}

}
