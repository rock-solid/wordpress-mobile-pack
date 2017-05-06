<?php
if (class_exists('WMobilePack')):

    if ( ! class_exists( 'WMobilePack_Export_Settings' ) ) {
        require_once(WMP_PLUGIN_PATH.'export/class-export-settings.php');
    }

    $wmp_export = new WMobilePack_Export_Settings();
    $wmp_texts_json = $wmp_export->load_language(get_locale(), 'list');

    $wmp_footer_text = 'Switch to mobile version';
    if ($wmp_texts_json !== false && isset($wmp_texts_json['APP_TEXTS']['LINKS']) && isset($wmp_texts_json['APP_TEXTS']['LINKS']['VISIT_APP'])){
        $wmp_footer_text = $wmp_texts_json['APP_TEXTS']['LINKS']['VISIT_APP'];
}
    ?>
    <div id="show-mobile" style="width:100%; text-align: center;">
        <a href="<?php echo home_url(); echo parse_url(home_url(), PHP_URL_QUERY) ? '&' : '?'; echo WMobilePack_Cookie::$prefix; ?>theme_mode=mobile" title="<?php echo $wmp_texts_json;?>"><?php echo $wmp_footer_text;?></a>
    </div>
<?php endif;?>