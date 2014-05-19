// Nanoslider (https://github.com/jamesflorentino/nanoScrollerJS)
(function(e,t,n){"use strict";var r,i,s,o,u,a,f,l,c,h,p,d,v,m,g,y,b,w,E,S,x;S={paneClass:"pane",sliderClass:"slider",contentClass:"content",iOSNativeScrolling:!1,preventPageScrolling:!1,disableResize:!1,alwaysVisible:!1,flashDelay:1500,sliderMinHeight:20,sliderMaxHeight:null},y="scrollbar",g="scroll",l="mousedown",c="mousemove",p="mousewheel",h="mouseup",m="resize",u="drag",w="up",v="panedown",s="DOMMouseScroll",o="down",E="wheel",a="keydown",f="keyup",b="touchmove",r=t.navigator.appName==="Microsoft Internet Explorer"&&/msie 7./i.test(t.navigator.appVersion)&&t.ActiveXObject,i=null,x=function(){var e,t,r;return e=n.createElement("div"),t=e.style,t.position="absolute",t.width="100px",t.height="100px",t.overflow=g,t.top="-9999px",n.body.appendChild(e),r=e.offsetWidth-e.clientWidth,n.body.removeChild(e),r},d=function(){function a(r,s){this.el=r,this.options=s,i||(i=x()),this.$el=e(this.el),this.doc=e(n),this.win=e(t),this.generate(),this.createEvents(),this.addEvents(),this.reset()}return a.prototype.preventScrolling=function(e,t){if(!this.isActive)return;if(e.type===s)(t===o&&e.originalEvent.detail>0||t===w&&e.originalEvent.detail<0)&&e.preventDefault();else if(e.type===p){if(!e.originalEvent||!e.originalEvent.wheelDelta)return;(t===o&&e.originalEvent.wheelDelta<0||t===w&&e.originalEvent.wheelDelta>0)&&e.preventDefault()}},a.prototype.updateScrollValues=function(){var e;e=this.content,this.maxScrollTop=e.scrollHeight-e.clientHeight,this.contentScrollTop=e.scrollTop,this.maxSliderTop=this.paneHeight-this.sliderHeight,this.sliderTop=this.contentScrollTop*this.maxSliderTop/this.maxScrollTop},a.prototype.createEvents=function(){var e=this;this.events={down:function(t){return e.isBeingDragged=!0,e.offsetY=t.pageY-e.slider.offset().top,e.pane.addClass("active"),e.doc.bind(c,e.events[u]).bind(h,e.events[w]),!1},drag:function(t){return e.sliderY=t.pageY-e.$el.offset().top-e.offsetY,e.scroll(),e.updateScrollValues(),e.contentScrollTop>=e.maxScrollTop?e.$el.trigger("scrollend"):e.contentScrollTop===0&&e.$el.trigger("scrolltop"),!1},up:function(t){return e.isBeingDragged=!1,e.pane.removeClass("active"),e.doc.unbind(c,e.events[u]).unbind(h,e.events[w]),!1},resize:function(t){e.reset()},panedown:function(t){return e.sliderY=(t.offsetY||t.originalEvent.layerY)-e.sliderHeight*.5,e.scroll(),e.events.down(t),!1},scroll:function(t){if(e.isBeingDragged)return;e.updateScrollValues(),e.sliderY=e.sliderTop,e.slider.css({top:e.sliderTop});if(t==null)return;e.contentScrollTop>=e.maxScrollTop?(e.options.preventPageScrolling&&e.preventScrolling(t,o),e.$el.trigger("scrollend")):e.contentScrollTop===0&&(e.options.preventPageScrolling&&e.preventScrolling(t,w),e.$el.trigger("scrolltop"))},wheel:function(t){if(t==null)return;return e.sliderY+=-t.wheelDeltaY||-t.delta,e.scroll(),!1}}},a.prototype.addEvents=function(){var e;this.removeEvents(),e=this.events,this.options.disableResize||this.win.bind(m,e[m]),this.slider.bind(l,e[o]),this.pane.bind(l,e[v]).bind(""+p+" "+s,e[E]),this.$content.bind(""+g+" "+p+" "+s+" "+b,e[g])},a.prototype.removeEvents=function(){var e;e=this.events,this.win.unbind(m,e[m]),this.slider.unbind(),this.pane.unbind(),this.$content.unbind(""+g+" "+p+" "+s+" "+b,e[g])},a.prototype.generate=function(){var e,t,n,r,s;return n=this.options,r=n.paneClass,s=n.sliderClass,e=n.contentClass,!this.$el.find(""+r).length&&!this.$el.find(""+s).length&&this.$el.append('<div class="'+r+'"><div class="'+s+'" /></div>'),this.$content=this.$el.children("."+e),this.$content.attr("tabindex",0),this.content=this.$content[0],this.slider=this.$el.find("."+s),this.pane=this.$el.find("."+r),i&&(t=this.$el.css("direction")==="rtl"?{left:-i}:{right:-i},this.$el.addClass("has-scrollbar")),n.iOSNativeScrolling&&(t||(t={}),t.WebkitOverflowScrolling="touch"),t!=null&&this.$content.css(t),this},a.prototype.restore=function(){this.stopped=!1,this.pane.show(),this.addEvents()},a.prototype.reset=function(){var e,t,n,s,o,u,a,f,l;return this.$el.find("."+this.options.paneClass).length||this.generate().stop(),this.stopped&&this.restore(),e=this.content,n=e.style,s=n.overflowY,r&&this.$content.css({height:this.$content.height()}),t=e.scrollHeight+i,u=this.pane.outerHeight(),f=parseInt(this.pane.css("top"),10),o=parseInt(this.pane.css("bottom"),10),a=u+f+o,l=Math.round(a/t*a),l<this.options.sliderMinHeight?l=this.options.sliderMinHeight:this.options.sliderMaxHeight!=null&&l>this.options.sliderMaxHeight&&(l=this.options.sliderMaxHeight),s===g&&n.overflowX!==g&&(l+=i),this.maxSliderTop=a-l,this.contentHeight=t,this.paneHeight=u,this.paneOuterHeight=a,this.sliderHeight=l,this.slider.height(l),this.events.scroll(),this.pane.show(),this.isActive=!0,e.scrollHeight===e.clientHeight||this.pane.outerHeight(!0)>=e.scrollHeight&&s!==g?(this.pane.hide(),this.isActive=!1):this.el.clientHeight===e.scrollHeight&&s===g?this.slider.hide():this.slider.show(),this.pane.css({opacity:this.options.alwaysVisible?1:"",visibility:this.options.alwaysVisible?"visible":""}),this},a.prototype.scroll=function(){if(!this.isActive)return;return this.sliderY=Math.max(0,this.sliderY),this.sliderY=Math.min(this.maxSliderTop,this.sliderY),this.$content.scrollTop((this.paneHeight-this.contentHeight+i)*this.sliderY/this.maxSliderTop*-1),this.slider.css({top:this.sliderY}),this},a.prototype.scrollBottom=function(e){if(!this.isActive)return;return this.reset(),this.$content.scrollTop(this.contentHeight-this.$content.height()-e).trigger(p),this},a.prototype.scrollTop=function(e){if(!this.isActive)return;return this.reset(),this.$content.scrollTop(+e).trigger(p),this},a.prototype.scrollTo=function(t){if(!this.isActive)return;return this.reset(),this.scrollTop(e(t).get(0).offsetTop),this},a.prototype.stop=function(){return this.stopped=!0,this.removeEvents(),this.pane.hide(),this},a.prototype.flash=function(){var e=this;if(!this.isActive)return;return this.reset(),this.pane.addClass("flashed"),setTimeout(function(){e.pane.removeClass("flashed")},this.options.flashDelay),this},a}(),e.fn.nanoScroller=function(t){return this.each(function(){var n,r;(r=this.nanoscroller)||(n=e.extend({},S,t),this.nanoscroller=r=new d(this,n));if(t&&typeof t=="object"){e.extend(r.options,t);if(t.scrollBottom)return r.scrollBottom(t.scrollBottom);if(t.scrollTop)return r.scrollTop(t.scrollTop);if(t.scrollTo)return r.scrollTo(t.scrollTo);if(t.scroll==="bottom")return r.scrollBottom(0);if(t.scroll==="top")return r.scrollTop(0);if(t.scroll&&t.scroll instanceof e)return r.scrollTo(t.scroll);if(t.stop)return r.stop();if(t.flash)return r.flash()}return r.reset()})}})(jQuery,window,document);
// AjaxUpload
(function(){var h=document,l=window;function b(d){if(typeof d=="string"){d=h.getElementById(d)}return d}function e(q,p,d){if(l.addEventListener){q.addEventListener(p,d,false)}else{if(l.attachEvent){var r=function(){d.call(q,l.event)};q.attachEvent("on"+p,r)}}}var c=function(){var d=h.createElement("div");return function(p){d.innerHTML=p;var q=d.childNodes[0];d.removeChild(q);return q}}();function f(p,d){return p.className.match(new RegExp("(\\s|^)"+d+"(\\s|$)"))}function g(p,d){if(!f(p,d)){p.className+=" "+d}}function m(q,d){var p=new RegExp("(\\s|^)"+d+"(\\s|$)");q.className=q.className.replace(p," ")}if(document.documentElement.getBoundingClientRect){var n=function(d){var t=d.getBoundingClientRect(),x=d.ownerDocument,u=x.body,p=x.documentElement,s=p.clientTop||u.clientTop||0,v=p.clientLeft||u.clientLeft||0,y=1;if(u.getBoundingClientRect){var r=u.getBoundingClientRect();y=(r.right-r.left)/u.clientWidth}if(y>1){s=0;v=0}var w=t.top/y+(window.pageYOffset||p&&p.scrollTop/y||u.scrollTop/y)-s,q=t.left/y+(window.pageXOffset||p&&p.scrollLeft/y||u.scrollLeft/y)-v;return{top:w,left:q}}}else{var n=function(d){if(l.jQuery){return jQuery(d).offset()}var q=0,p=0;do{q+=d.offsetTop||0;p+=d.offsetLeft||0}while(d=d.offsetParent);return{left:p,top:q}}}function a(q){var s,p,r,d;var t=n(q);s=t.left;r=t.top;p=s+q.offsetWidth;d=r+q.offsetHeight;return{left:s,right:p,top:r,bottom:d}}function j(r){if(!r.pageX&&r.clientX){var q=1;var d=document.body;if(d.getBoundingClientRect){var p=d.getBoundingClientRect();q=(p.right-p.left)/d.clientWidth}return{x:r.clientX/q+h.body.scrollLeft+h.documentElement.scrollLeft,y:r.clientY/q+h.body.scrollTop+h.documentElement.scrollTop}}return{x:r.pageX,y:r.pageY}}var i=function(){var d=0;return function(){return"ValumsAjaxUpload"+d++}}();function o(d){return d.replace(/.*(\/|\\)/,"")}function k(d){return(/[.]/.exec(d))?/[^.]+$/.exec(d.toLowerCase()):""}Ajax_upload=AjaxUpload=function(q,d){if(q.jquery){q=q[0]}else{if(typeof q=="string"&&/^#.*/.test(q)){q=q.slice(1)}}q=b(q);this._input=null;this._button=q;this._disabled=false;this._submitting=false;this._justClicked=false;this._parentDialog=h.body;if(window.jQuery&&jQuery.ui&&jQuery.ui.dialog){var r=jQuery(this._button).parents(".ui-dialog");if(r.length){this._parentDialog=r[0]}}this._settings={action:"upload.php",name:"userfile",data:{},autoSubmit:true,responseType:false,onChange:function(s,t){},onSubmit:function(s,t){},onComplete:function(t,s){}};for(var p in d){this._settings[p]=d[p]}this._createInput();this._rerouteClicks()};AjaxUpload.prototype={setData:function(d){this._settings.data=d},disable:function(){this._disabled=true},enable:function(){this._disabled=false},destroy:function(){if(this._input){if(this._input.parentNode){this._input.parentNode.removeChild(this._input)}this._input=null}},_createInput:function(){var p=this;var d=h.createElement("input");d.setAttribute("type","file");d.setAttribute("name",this._settings.name);var r={position:"absolute",margin:"-5px 0 0 -175px",padding:0,width:"220px",height:"30px",fontSize:"14px",opacity:0,cursor:"hand",display:"none",zIndex:2147483583};for(var q in r){d.style[q]=r[q]}if(!(d.style.opacity==="0")){d.style.filter="alpha(opacity=0)"}this._parentDialog.appendChild(d);e(d,"change",function(){var s=o(this.value);if(p._settings.onChange.call(p,s,k(s))==false){return}if(p._settings.autoSubmit){p.submit()}});e(d,"click",function(){p.justClicked=true;setTimeout(function(){p.justClicked=false},2500)});this._input=d},_rerouteClicks:function(){var p=this;var q,d={top:0,left:0},r=false;e(p._button,"mouseover",function(s){if(!p._input||r){return}r=true;q=a(p._button);if(p._parentDialog!=h.body){d=n(p._parentDialog)}});e(document,"mousemove",function(u){var t=p._input;if(!t||!r){return}if(p._disabled){m(p._button,"hover");t.style.display="none";return}var v=j(u);if((v.x>=q.left)&&(v.x<=q.right)&&(v.y>=q.top)&&(v.y<=q.bottom)){t.style.top=v.y-d.top+"px";t.style.left=v.x-d.left+"px";t.style.display="block";g(p._button,"hover")}else{r=false;var s=setInterval(function(){if(p.justClicked){return}if(!r){t.style.display="none"}clearInterval(s)},25);m(p._button,"hover")}})},_createIframe:function(){var p=i();var d=c('<iframe src="javascript:false;" name="'+p+'" />');d.id=p;d.style.display="none";h.body.appendChild(d);return d},submit:function(){var d=this,r=this._settings;if(this._input.value===""){return}var p=o(this._input.value);if(!(r.onSubmit.call(this,p,k(p))==false)){var q=this._createIframe();var t=this._createForm(q);t.appendChild(this._input);t.submit();h.body.removeChild(t);t=null;this._input=null;this._createInput();var s=false;e(q,"load",function(w){if(q.src=="javascript:'%3Chtml%3E%3C/html%3E';"||q.src=="javascript:'<html></html>';"){if(s){setTimeout(function(){h.body.removeChild(q)},0)}return}var v=q.contentDocument?q.contentDocument:frames[q.id].document;if(v.readyState&&v.readyState!="complete"){return}if(v.body&&v.body.innerHTML=="false"){return}var u;if(v.XMLDocument){u=v.XMLDocument}else{if(v.body){u=v.body.innerHTML;if(r.responseType&&r.responseType.toLowerCase()=="json"){if(v.body.firstChild&&v.body.firstChild.nodeName.toUpperCase()=="PRE"){u=v.body.firstChild.firstChild.nodeValue}if(u){u=window["eval"]("("+u+")")}else{u={}}}}else{var u=v}}r.onComplete.call(d,p,u);s=true;q.src="javascript:'<html></html>';"})}else{h.body.removeChild(this._input);this._input=null;this._createInput()}},_createForm:function(q){var p=this._settings;var r=c('<form method="post" enctype="multipart/form-data"></form>');r.style.display="none";r.action=p.action;r.target=q.name;h.body.appendChild(r);for(var s in p.data){var d=h.createElement("input");d.type="hidden";d.name=s;d.value=p.data[s];r.appendChild(d)}return r}}})();
// Cookie
jQuery.cookie=function(b,j,m){if(typeof j!="undefined"){m=m||{};if(j===null){j="";m.expires=-1}var e="";if(m.expires&&(typeof m.expires=="number"||m.expires.toUTCString)){var f;if(typeof m.expires=="number"){f=new Date();f.setTime(f.getTime()+(m.expires*24*60*60*1000))}else{f=m.expires}e="; expires="+f.toUTCString()}var l=m.path?"; path="+(m.path):"";var g=m.domain?"; domain="+(m.domain):"";var a=m.secure?"; secure":"";document.cookie=[b,"=",encodeURIComponent(j),e,l,g,a].join("")}else{var d=null;if(document.cookie&&document.cookie!=""){var k=document.cookie.split(";");for(var h=0;h<k.length;h++){var c=jQuery.trim(k[h]);if(c.substring(0,b.length+1)==(b+"=")){d=decodeURIComponent(c.substring(b.length+1));break}}}return d}};

// Farbtastic
/**
 * Farbtastic Color Picker 1.2
 * © 2008 Steven Wittens
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

jQuery.fn.farbtastic = function (callback) {
  jQuery.farbtastic(this, callback);
  return this;
};

jQuery.farbtastic = function (container, callback) {
  var container = jQuery(container).get(0);
  return container.farbtastic || (container.farbtastic = new jQuery._farbtastic(container, callback));
}

jQuery._farbtastic = function (container, callback) {
  // Store farbtastic object
  var fb = this;

  // Insert markup
  jQuery(container).html('<div class="farbtastic"><div class="color"></div><div class="wheel"></div><div class="overlay"></div><div class="h-marker marker"></div><div class="sl-marker marker"></div></div>');
  var e = jQuery('.farbtastic', container);
  fb.wheel = jQuery('.wheel', container).get(0);
  // Dimensions
  fb.radius = 84;
  fb.square = 100;
  fb.width = 194;

  // Fix background PNGs in IE6
  if (navigator.appVersion.match(/MSIE [0-6]\./)) {
    jQuery('*', e).each(function () {
      if (this.currentStyle.backgroundImage != 'none') {
        var image = this.currentStyle.backgroundImage;
        image = this.currentStyle.backgroundImage.substring(5, image.length - 2);
        jQuery(this).css({
          'backgroundImage': 'none',
          'filter': "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=crop, src='" + image + "')"
        });
      }
    });
  }

  /**
   * Link to the given element(s) or callback.
   */
  fb.linkTo = function (callback) {
    // Unbind previous nodes
    if (typeof fb.callback == 'object') {
      jQuery(fb.callback).unbind('keyup', fb.updateValue);
    }

    // Reset color
    fb.color = null;

    // Bind callback or elements
    if (typeof callback == 'function') {
      fb.callback = callback;
    }
    else if (typeof callback == 'object' || typeof callback == 'string') {
      fb.callback = jQuery(callback);
      fb.callback.bind('keyup', fb.updateValue);
      if (fb.callback.get(0).value) {
        fb.setColor(fb.callback.get(0).value);
      }
    }
    return this;
  }
  fb.updateValue = function (event) {
    if (this.value && this.value != fb.color) {
      fb.setColor(this.value);
    }
  }

  /**
   * Change color with HTML syntax #123456
   */
  fb.setColor = function (color) {
    var unpack = fb.unpack(color);
    if (fb.color != color && unpack) {
      fb.color = color;
      fb.rgb = unpack;
      fb.hsl = fb.RGBToHSL(fb.rgb);
      fb.updateDisplay();
    }
    return this;
  }

  /**
   * Change color with HSL triplet [0..1, 0..1, 0..1]
   */
  fb.setHSL = function (hsl) {
    fb.hsl = hsl;
    fb.rgb = fb.HSLToRGB(hsl);
    fb.color = fb.pack(fb.rgb);
    fb.updateDisplay();
    return this;
  }

  /////////////////////////////////////////////////////

  /**
   * Retrieve the coordinates of the given event relative to the center
   * of the widget.
   */
  fb.widgetCoords = function (event) {
    var x, y;
    var el = event.target || event.srcElement;
    var reference = fb.wheel;

    if (typeof event.offsetX != 'undefined') {
      // Use offset coordinates and find common offsetParent
      var pos = { x: event.offsetX, y: event.offsetY };

      // Send the coordinates upwards through the offsetParent chain.
      var e = el;
      while (e) {
        e.mouseX = pos.x;
        e.mouseY = pos.y;
        pos.x += e.offsetLeft;
        pos.y += e.offsetTop;
        e = e.offsetParent;
      }

      // Look for the coordinates starting from the wheel widget.
      var e = reference;
      var offset = { x: 0, y: 0 }
      while (e) {
        if (typeof e.mouseX != 'undefined') {
          x = e.mouseX - offset.x;
          y = e.mouseY - offset.y;
          break;
        }
        offset.x += e.offsetLeft;
        offset.y += e.offsetTop;
        e = e.offsetParent;
      }

      // Reset stored coordinates
      e = el;
      while (e) {
        e.mouseX = undefined;
        e.mouseY = undefined;
        e = e.offsetParent;
      }
    }
    else {
      // Use absolute coordinates
      var pos = fb.absolutePosition(reference);
      x = (event.pageX || 0*(event.clientX + jQuery('html').get(0).scrollLeft)) - pos.x;
      y = (event.pageY || 0*(event.clientY + jQuery('html').get(0).scrollTop)) - pos.y;
    }
    // Subtract distance to middle
    return { x: x - fb.width / 2, y: y - fb.width / 2 };
  }

  /**
   * Mousedown handler
   */
  fb.mousedown = function (event) {
    // Capture mouse
    if (!document.dragging) {
      jQuery(document).bind('mousemove', fb.mousemove).bind('mouseup', fb.mouseup);
      document.dragging = true;
    }

    // Check which area is being dragged
    var pos = fb.widgetCoords(event);
    fb.circleDrag = Math.max(Math.abs(pos.x), Math.abs(pos.y)) * 2 > fb.square;

    // Process
    fb.mousemove(event);
    return false;
  }

  /**
   * Mousemove handler
   */
  fb.mousemove = function (event) {
    // Get coordinates relative to color picker center
    var pos = fb.widgetCoords(event);

    // Set new HSL parameters
    if (fb.circleDrag) {
      var hue = Math.atan2(pos.x, -pos.y) / 6.28;
      if (hue < 0) hue += 1;
      fb.setHSL([hue, fb.hsl[1], fb.hsl[2]]);
    }
    else {
      var sat = Math.max(0, Math.min(1, -(pos.x / fb.square) + .5));
      var lum = Math.max(0, Math.min(1, -(pos.y / fb.square) + .5));
      fb.setHSL([fb.hsl[0], sat, lum]);
    }
    return false;
  }

  /**
   * Mouseup handler
   */
  fb.mouseup = function () {
    // Uncapture mouse
    jQuery(document).unbind('mousemove', fb.mousemove);
    jQuery(document).unbind('mouseup', fb.mouseup);
    document.dragging = false;
  }

  /**
   * Update the markers and styles
   */
  fb.updateDisplay = function () {
    // Markers
    var angle = fb.hsl[0] * 6.28;
    jQuery('.h-marker', e).css({
      left: Math.round(Math.sin(angle) * fb.radius + fb.width / 2) + 'px',
      top: Math.round(-Math.cos(angle) * fb.radius + fb.width / 2) + 'px'
    });

    jQuery('.sl-marker', e).css({
      left: Math.round(fb.square * (.5 - fb.hsl[1]) + fb.width / 2) + 'px',
      top: Math.round(fb.square * (.5 - fb.hsl[2]) + fb.width / 2) + 'px'
    });

    // Saturation/Luminance gradient
    jQuery('.color', e).css('backgroundColor', fb.pack(fb.HSLToRGB([fb.hsl[0], 1, 0.5])));

    // Linked elements or callback
    if (typeof fb.callback == 'object') {
      // Set background/foreground color
      jQuery(fb.callback).css({
        backgroundColor: fb.color,
        color: fb.hsl[2] > 0.5 ? '#000' : '#fff'
      });

      // Change linked value
      jQuery(fb.callback).each(function() {
        if (this.value && this.value != fb.color) {
          this.value = fb.color;
        }
      });
    }
    else if (typeof fb.callback == 'function') {
      fb.callback.call(fb, fb.color);
    }
  }

  /**
   * Get absolute position of element
   */
  fb.absolutePosition = function (el) {
    var r = { x: el.offsetLeft, y: el.offsetTop };
    // Resolve relative to offsetParent
    if (el.offsetParent) {
      var tmp = fb.absolutePosition(el.offsetParent);
      r.x += tmp.x;
      r.y += tmp.y;
    }
    return r;
  };

  /* Various color utility functions */
  fb.pack = function (rgb) {
    var r = Math.round(rgb[0] * 255);
    var g = Math.round(rgb[1] * 255);
    var b = Math.round(rgb[2] * 255);
    return '#' + (r < 16 ? '0' : '') + r.toString(16) +
           (g < 16 ? '0' : '') + g.toString(16) +
           (b < 16 ? '0' : '') + b.toString(16);
  }

  fb.unpack = function (color) {
    if (color.length == 7) {
      return [parseInt('0x' + color.substring(1, 3)) / 255,
        parseInt('0x' + color.substring(3, 5)) / 255,
        parseInt('0x' + color.substring(5, 7)) / 255];
    }
    else if (color.length == 4) {
      return [parseInt('0x' + color.substring(1, 2)) / 15,
        parseInt('0x' + color.substring(2, 3)) / 15,
        parseInt('0x' + color.substring(3, 4)) / 15];
    }
  }

  fb.HSLToRGB = function (hsl) {
    var m1, m2, r, g, b;
    var h = hsl[0], s = hsl[1], l = hsl[2];
    m2 = (l <= 0.5) ? l * (s + 1) : l + s - l*s;
    m1 = l * 2 - m2;
    return [this.hueToRGB(m1, m2, h+0.33333),
        this.hueToRGB(m1, m2, h),
        this.hueToRGB(m1, m2, h-0.33333)];
  }

  fb.hueToRGB = function (m1, m2, h) {
    h = (h < 0) ? h + 1 : ((h > 1) ? h - 1 : h);
    if (h * 6 < 1) return m1 + (m2 - m1) * h * 6;
    if (h * 2 < 1) return m2;
    if (h * 3 < 2) return m1 + (m2 - m1) * (0.66666 - h) * 6;
    return m1;
  }

  fb.RGBToHSL = function (rgb) {
    var min, max, delta, h, s, l;
    var r = rgb[0], g = rgb[1], b = rgb[2];
    min = Math.min(r, Math.min(g, b));
    max = Math.max(r, Math.max(g, b));
    delta = max - min;
    l = (min + max) / 2;
    s = 0;
    if (l > 0 && l < 1) {
      s = delta / (l < 0.5 ? (2 * l) : (2 - 2 * l));
    }
    h = 0;
    if (delta > 0) {
      if (max == r && max != g) h += (g - b) / delta;
      if (max == g && max != b) h += (2 + (b - r) / delta);
      if (max == b && max != r) h += (4 + (r - g) / delta);
      h /= 6;
    }
    return [h, s, l];
  }

  // Install mousedown handler (the others are set on the document on-demand)
  jQuery('*', e).mousedown(fb.mousedown);

    // Init color
  fb.setColor('#000000');

  // Set linked elements/callback
  if (callback) {
    fb.linkTo(callback);
  }
}