var $wpsmart = jQuery.noConflict();

$wpsmart(document).ready(function() {

	$wpsmart('#view_full_site').on('click',function(event) {
		event.preventDefault();
		
		document.cookie = 'wpsmart_view_full_site=1';
		window.location.reload();
		
		return false;
	});

	$wpsmart('#view-menu').on('click', function(event) {
		event.preventDefault();
		
		$wpsmart('.menu-bar').toggleClass('shown');
		
		return false;
	});
	
	$wpsmart('#view-search').on('click',function(event) {
		event.preventDefault();
		
		$wpsmart('.search-bar').toggleClass('shown');
		
		return false;
	});
	
	$wpsmart('.input-wrap input').keyup(function(event) {
    	if($wpsmart(this).val() != '') {
    		$wpsmart(this).closest('.input-wrap').find('label').hide();
    	} else {
	    	$wpsmart(this).closest('.input-wrap').find('label').show();
    	}
    });
    
    $wpsmart('.input-wrap input').keyup(function(event) {
		event.stopImmediatePropagation();
		
		if($wpsmart(this).val() == '') {
			$wpsmart(this).closest('.input-wrap').find('label').show();
		}		 
		
		return false;
    });
    
    $wpsmart('#main').on('click', '#load-more a', function(event) {
    	event.preventDefault();
    	     	
    	var object = $wpsmart(this);
    	object.text('Loading...');
    	
    	var data = {action: 'wpsmart_load_more'};
    	    	
    	$wpsmart.get(object.parent().data('url'), function(response) {
    		setTimeout(function() { $wpsmart('#load-more').replaceWith(response);replace_preview_links(); }, 1000);
            ga('send','pageview', object.parent().data('url'));
    	});
    	
    	return false;
    });
    
    $wpsmart(function() {
        replace_preview_links();
    });
    
    $wpsmart("#page").fitVids();
});

function replace_preview_links()
{
    if(window.location.search.indexOf(('wps_preview=1')) != -1) {
        $wpsmart("a").attr('href', function(i, h) {
            if(h != '#' && h.indexOf('wps_preview=1') == -1) {
                return h + (h.indexOf('?') != -1 ? "&wps_preview=1" : "?wps_preview=1");
            }
        });
    }
}


  
