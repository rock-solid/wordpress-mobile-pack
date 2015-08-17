<?php
if (class_exists('WMobilePack')):

    $wmp_texts_json = WMobilePack::wmp_load_language(get_locale());

    $wmp_footer_text = 'Switch to mobile version';
    if ($wmp_texts_json !== false && isset($wmp_texts_json['appTexts']['links']) && isset($wmp_texts_json['appTexts']['links']['visit_app']))
        $wmp_footer_text = $wmp_texts_json['appTexts']['links']['visit_app'];
    ?>
    <div id="show-mobile" style="width:100%; text-align: center;">
        <a href="<?php echo home_url(); echo parse_url(home_url(), PHP_URL_QUERY) ? '&' : '?'; ?>wmp_theme_mode=mobile" title="<?php echo $wmp_footer_text;?>"><?php echo $wmp_footer_text;?></a>
    </div>
<?php endif;?>