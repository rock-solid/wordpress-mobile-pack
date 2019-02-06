($ => { 

	$('.disable-pt-pwa').on('click', function(e) {

		let $this = $(this);
		let confirmation = confirm("Are you sure you want to disable PWA? This can be re-activated by re-saving the settings.");

		if (confirmation) {

			$this.text('Disabling PWA...')
				.next('img').addClass('visible');
			
			e.preventDefault();

		    var data = {
		        action             : 'wp_ajax_pt_pwa_disable_pwa',
		        security           : WpData.security
		    };

		    $.post( WpData.ajaxurl, data, function(response)  { // Pass response status through

		        }).done(function(response) {

		        	setTimeout( () => {
		        		location.reload();
		        	}, 1000);

		        }).fail(function(){

		            // Something has gone wrong

		        }
		    );

		}
		
	});

})(jQuery); 