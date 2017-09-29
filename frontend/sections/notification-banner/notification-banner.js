var WMPAppBanner = WMPAppBanner || {};
WMPAppBanner.message = WMPAppBanner.message || '';
WMPAppBanner.cookiePrefix = WMPAppBanner.cookiePrefix || 'wmp_';
WMPAppBanner.isSecure = WMPAppBanner.isSecure || false;

(function() {
  /**
   * Create timer that will check if the document is ready
   * @type {number}
   */
  var DOMLoadTimer = setInterval(function() {
    if (/loading|loaded|complete/i.test(document.readyState)) {
      clearInterval(DOMLoadTimer);
      documentLoaded();
    }
  }, 10);

  /**
   * Init method, called when the document is ready
   *
   * The 'redirect' GET param is used for hosted apps (on Appticles).
   * Setting redirect=false will deactivate the app banner.
   *
   */
  function documentLoaded() {
    if (WMPAppBanner.message !== '' && WMPAppBanner.cookiePrefix !== '') {
      var redirect = getCookie(WMPAppBanner.cookiePrefix + 'redirect'),
        closed = getCookie(WMPAppBanner.cookiePrefix + 'closed');

      // if there is a cookie already set, then convert it to a boolean value
      // redirect param is used for hosted apps (on Appticles)
      redirect = redirect !== null ? Boolean(Number(String(redirect))) : true;

      var urlParams = window.location.href.split('?');

      // if the URL contains a redirect param, then set up a cookie with this value
      if (urlParams.length > 1) {
        if (urlParams[urlParams.length - 1].indexOf('redirect=false') != -1) {
          setCookie(WMPAppBanner.cookiePrefix + 'redirect', 0, 7);
          redirect = false;
        } else if (
          urlParams[urlParams.length - 1].indexOf('redirect=true') != -1
        ) {
          setCookie(WMPAppBanner.cookiePrefix + 'redirect', 1, 7);
          redirect = true;
        }
      }

      // create the wrapper bar
      if (redirect && !closed) {
        createBar();
      }
    }
  }

  /**
   * Display the notification / app banner.
   */
  function createBar() {
    new Noty({
      text: WMPAppBanner.message,
      layout: 'topCenter',
      theme: 'relax',
      closeWith: ['button'],
      callbacks: {
        onClose: function() {
          setCookie(WMPAppBanner.cookiePrefix + 'closed', 1, 15);
        }
      }
    }).show();
  }

  /**
   * Search a cookie by name in document cookies.
   * @param {String} c_name
   */
  function getCookie(c_name) {
    var i,
      x,
      y,
      ARRcookies = document.cookie.split(';');
    for (i = 0; i < ARRcookies.length; i++) {
      x = ARRcookies[i].substr(0, ARRcookies[i].indexOf('='));
      y = ARRcookies[i].substr(ARRcookies[i].indexOf('=') + 1);
      x = x.replace(/^\s+|\s+$/g, '');
      if (x == c_name) {
        return decodeURIComponent(y);
      }
    }

    return null;
  }

  /**
   * Set a cookie value
   * @param {String} c_name
   * @param {String, Number of Boolean} value
   * @param {Number} expireDays
   */
  function setCookie(c_name, value, expireDays) {
    var expireDate = new Date();
    expireDate.setDate(expireDate.getDate() + expireDays);

    var c_value =
      encodeURIComponent(value) +
      (expireDays == null ? '' : '; expires=' + expireDate.toUTCString()) +
      '; path=/; ' +
      (WMPAppBanner.isSecure === true ? 'secure' : '');

    document.cookie = c_name + '=' + c_value;
  }
})();
