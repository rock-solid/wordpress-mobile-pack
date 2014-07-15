// TinyMCE Advanced jQuery sortables

(function($) {
	tadvSortable = {

		init : function() {
			$("#toolbar_1").sortable({
				connectWith: ["#toolbar_2, #toolbar_3, #toolbar_4, #tadvpalette"],
				items : 'li',
				stop : tadvSortable.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			$("#toolbar_2").sortable({
				connectWith: ["#toolbar_1, #toolbar_3, #toolbar_4, #tadvpalette"],
				items : 'li',
				stop : tadvSortable.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			$("#toolbar_3").sortable({
				connectWith: ["#toolbar_2, #toolbar_1, #toolbar_4, #tadvpalette"],
				items : 'li',
				stop : tadvSortable.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			$("#toolbar_4").sortable({
				connectWith: ["#toolbar_2, #toolbar_3, #toolbar_1, #tadvpalette"],
				items : 'li',
				stop : tadvSortable.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			$("#tadvpalette").sortable({
				connectWith: ["#toolbar_1, #toolbar_2, #toolbar_3, #toolbar_4"],
				items : 'li',
				stop : tadvSortable.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			this.update();
			$(window).resize(function(){
  				tadvSortable.update();
			});
		},

		I : function(a) {
			return document.getElementById(a);
		},

		serialize : function() {
			var tb1, tb2, tb3, tb4;

			tb1 = $('#toolbar_1').sortable('serialize',{expression : '([^_]+)_(.+)'});
			tb2 = $('#toolbar_2').sortable('serialize',{expression : '([^_]+)_(.+)'});
			tb3 = $('#toolbar_3').sortable('serialize',{expression : '([^_]+)_(.+)'})
			tb4 = $('#toolbar_4').sortable('serialize',{expression : '([^_]+)_(.+)'})

			$('#toolbar_1order').val(tb1);
			$('#toolbar_2order').val(tb2);
			$('#toolbar_3order').val(tb3);
			$('#toolbar_4order').val(tb4);
			
			if ( (tb1.indexOf('wp_adv') != -1 && ! tb2) ||
				 (tb2.indexOf('wp_adv') != -1 && ! tb3) ||
				 (tb3.indexOf('wp_adv') != -1 && ! tb4) ||
				 tb4.indexOf('wp_adv') != -1 ) {
					$('#sink_err').css('display', 'inline');
					return false;
				 }
			$('#tadvadmin').submit();
		},

		reset : function() {
			var pd = this.I('tadvpalette');
			if ( ! pd ) return;
			if( pd.childNodes.length > 6 ) {
				var last = pd.lastChild.previousSibling;
			    pd.style.height = last.offsetTop + last.offsetHeight + 30 + "px";
			} else pd.style.height = "60px";
		},

		update : function() {
			var t = tadvSortable, w;

			t.reset();
			$('#too_long').css('display', 'none');
			$('#sink_err').css('display', 'none');

			$('.container').each(function(no,o){
			    var kids = o.childNodes, tbwidth = o.clientWidth, W = 0;

			    for( i = 0; i < kids.length; i++ ) {
					if ( w = kids[i].offsetWidth )
						W += w;
				}

			    if( (W+8) > tbwidth )
					$('#too_long').css('display', 'inline');
			});
		}
	}
}(jQuery));

jQuery(document).ready(function(){ tadvSortable.init(); });
