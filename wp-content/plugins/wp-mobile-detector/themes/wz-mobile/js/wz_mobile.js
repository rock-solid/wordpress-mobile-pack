var wz_mobile = {
	gnStartX: 0,
	gnStartY: 0,
	gnEndX: 0,
	gnEndY: 0,
	gnEndOffsetY: 0,
	validMove: false,
	currentWidth: 0,
	startX: null,
	startY: null,
	offsetY: 0,
	dx: null,
	direction: null,
	el: null,
	listener: null,
	tStart: function(event){
		wz_mobile.gnStartX = event.touches[0].pageX;
  	wz_mobile.gnStartY = event.touches[0].pageY;
	},
	tMove: function(event){
		var bottom = document.body.scrollHeight - window.innerHeight;
		if(window.pageYOffset < wz_mobile.gnEndOffsetY && bottom != window.pageYOffset){
  		if(!jMobile.select('.ghost_bar').is(':visible')){
  			jMobile.select('.ghost_bar').fadeIn('fast');
				jMobile.select('#content').removeClass('full').addClass('part');
  		}
  	}else if(window.pageYOffset > wz_mobile.gnEndOffsetY){
  		if(jMobile.select('.ghost_bar').is(':visible')){
  			jMobile.select('.ghost_bar').fadeOut('fast');
				jMobile.select('#content').removeClass('part').addClass('full');
  		}
  	}
		
		wz_mobile.gnEndOffsetY = window.pageYOffset;
		wz_mobile.gnEndX = event.touches[0].pageX;
  	wz_mobile.gnEndY = event.touches[0].pageY;
	},
	tEnd: function(event){

	},
	tScroll: function(){
		if(window.pageYOffset == 0){
			if(!jMobile.select('.ghost_bar').is(':visible')){
  			jMobile.select('.ghost_bar').fadeIn('fast');
				jMobile.select('#content').removeClass('full').addClass('part');
  		}
		}
	},
	open_left_menu: function(){
		if(jMobile.select('#header').hasClass('left_menu_open')){
			jMobile.select('#header').removeClass('left_menu_open').removeClass('shadow');
			jMobile.select('#content').removeClass('left_menu_open').removeClass('shadow');
			jMobile.select('#lbMenu').hide();
		}else{
			jMobile.select('#lbMenu').show();
			jMobile.select('#header').addClass('left_menu_open').addClass('shadow');
			jMobile.select('#content').addClass('left_menu_open').addClass('shadow');
		}
	},
	open_right_menu: function(){
		if(jMobile.select('#header').hasClass('right_menu_open')){
			jMobile.select('#header').removeClass('right_menu_open').removeClass('shadow');
			jMobile.select('#content').removeClass('right_menu_open').removeClass('shadow');
			jMobile.select('#rbMenu').hide();
		}else{
			jMobile.select('#rbMenu').show();
			jMobile.select('#header').addClass('right_menu_open').addClass('shadow');
			jMobile.select('#content').addClass('right_menu_open').addClass('shadow');
		}
	}
}

window.addEventListener('touchstart',function(event) {
  wz_mobile.tStart(event);
},false);

window.addEventListener('touchmove',function(event) {
  wz_mobile.tMove(event);
},false);

window.addEventListener('touchend',function(event) {
	wz_mobile.tEnd(event);
},false);

window.addEventListener('scroll', function(){
	wz_mobile.tScroll();
},false);

/* Fixes for iOS from jQuery Mobile */

window.scrollTo( 0, 1 );

var body = jMobile.select('body').html();
var html = "<div id='wrapper' data-role='page' style='display: none;'>"+body+"</div>";
jMobile.select('body').html(html);

window.addEventListener("load",function() {
	setTimeout( function() {
		window.scrollTo( 0, 1 );
		jMobile.select('#wrapper').show();
	}, 20 );
});