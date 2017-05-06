function WMP_SWITCH_THEME() {

  var JSObject = this;

  this.type = 'wmp_themes';

  this.DOMDoc;

  this.selectedTheme;
  this.savingTheme = false;

  /**
   * Init method, called from WMPJSInterface
   */
  this.init = function () {

    // save a reference to WMPJSInterface Object
    WMPJSInterface = window.parent.WMPJSInterface;

    // add actions for selecting a theme
    this.initSelectButtons();
  };

  /**
   * Select themes
   */
  this.initSelectButtons = function () {

    jQuery('.' + JSObject.type + '_select').click(

      function () {

        var newTheme = Number(jQuery(this).attr('data-theme'));

        if (newTheme != JSObject.selectedTheme) {

          if (JSObject.savingTheme == true)
            return;

          var isConfirmed = confirm('This setting will change the appearance of your app and reset your color schemes and fonts. Are you sure you want to continue?');

          if (isConfirmed) {
            JSObject.switchTheme(newTheme);
          }
        }
      }
    );
  };

  /**
   * Make Ajax call to change the theme
   */
  this.switchTheme = function (newTheme) {

    WMPJSInterface.Preloader.start({ message: 'Switching theme ...' });
    JSObject.savingTheme = true;

    jQuery.get(
      ajaxurl,
      {
        'action': 'wmp_theme_switch',
        'theme': newTheme
      },
      function (response) {

        // remove preloader
        WMPJSInterface.Preloader.remove(100);
        JSObject.savingTheme = false;

        response = Boolean(Number(String(response)));

        if (response) {

          var selectThemeButton = jQuery('.' + JSObject.type + '_select[data-theme="' + String(newTheme) + '"]');

          // activate the green corner for the new selected theme
          var themeBox = selectThemeButton.closest('.theme');
          jQuery('.corner', themeBox).addClass('active');

          // hide the actions panel for the selected theme
          selectThemeButton.hide();
          jQuery('.text-select', themeBox).text('Enabled');

          // show the select button for the deactivated theme
          var previousThemeButton = jQuery('.' + JSObject.type + '_select[data-theme="' + String(JSObject.selectedTheme) + '"]');
          previousThemeButton.closest('.actions').show();

          // remove the green corner for the previous selected theme box
          var previousThemeBox = previousThemeButton.closest('.theme');
          jQuery('.corner', previousThemeBox).removeClass('active');

          // show the actions panel for the selected theme
          previousThemeButton.show();
          jQuery('.text-select', previousThemeBox).text('Activate');

          // memorize new selected theme
          JSObject.selectedTheme = newTheme;

          var message = 'Your mobile app theme has been successfully changed.';
          WMPJSInterface.Loader.display({ message: message });

        } else {

          var message = 'There was an error. Please reload the page and try again.';
          WMPJSInterface.Loader.display({ message: message });
        }
      }
    );
  };
}
