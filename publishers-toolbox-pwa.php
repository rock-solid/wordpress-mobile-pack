<?php
    /**
     * Plugin Name: Publishers Toolbox PWA
     * Plugin URI:  https://www.publisherstoolbox.com/websuite/
     * Description: Publisher's Toolbox PWA is a mobile plugin that helps you transform your WordPress site into a progressive web application. It comes with a choice of two mobile app themes.
     * Author: Publisher's Toolbox
     * Author URI: https://publisherstoolbox.com/
     * Version: 1.8.0
     * License: Publishers Toolbox PWA is Licensed under the Apache License, Version 2.0
     * Text Domain: publishers-toolbox-pwa
     */

    include('core/class-config.php');

    $Pt_Pwa_Config = new Pt_Pwa_Config();

    include('core/class-pwa.php');

    /**
     * Used to load the required files on the plugins_loaded hook, instead of immediately.
     */
    function pt_pwa_frontend_init() {
        $Pt_Pwa_Config = new Pt_Pwa_Config();
        if ($Pt_Pwa_Config->PWA_ENABLED) {
            require_once('frontend/class-application.php');
            new PtPwa_Application(plugin_dir_url(__FILE__));
        }
    }

    /**
     * init admin section
     */
    function pt_pwa_admin_init() {
        require_once('admin/class-admin-init.php');
        new Pt_Pwa_Admin_Init();
    }

    /**
     * Fallback if anything fails
     */
    function pt_pwa_insert_fallback_script() {

        $themeManager = new PtPwaThemeManager(new PtPwaTheme());
        $theme = $themeManager->getTheme();

        ?>
        <script>
            "use strict";
            window.appEndpoint = '<?php echo $theme->getAppEndpoint() ?>';
            window.appColour = '<?php echo $theme->getThemeColour() ?>';

            function detect() {
                var e = getNodeVersion();
                return e || ("undefined" != typeof navigator ? parseUserAgent(navigator.userAgent) : null)
            }

            function detectOS(n) {
                var e = getOperatingSystemRules().filter(function (e) {
                    return e.rule && e.rule.test(n)
                })[0];
                return e ? e.name : null
            }

            function getNodeVersion() {
                return "undefined" == typeof navigator && "undefined" != typeof process ? {
                    name: "node",
                    version: process.version.slice(1),
                    os: require("os").type().toLowerCase()
                } : null
            }

            function parseUserAgent(t) {
                var e = getBrowserRules();
                if (!t) return null;
                var n = e.map(function (e) {
                    var n = e.rule.exec(t),
                        o = n && n[1].split(/[._]/).slice(0, 3);
                    return o && o.length < 3 && (o = o.concat(1 === o.length ? [0, 0] : [0])), n && {
                        name: e.name,
                        version: o.join(".")
                    }
                }).filter(Boolean)[0] || null;
                return n && (n.os = detectOS(t)), n
            }

            function getBrowserRules() {
                return buildRules([
                    ["aol", /AOLShield\/([0-9\._]+)/],
                    ["edge", /Edge\/([0-9\._]+)/],
                    ["yandexbrowser", /YaBrowser\/([0-9\._]+)/],
                    ["vivaldi", /Vivaldi\/([0-9\.]+)/],
                    ["kakaotalk", /KAKAOTALK\s([0-9\.]+)/],
                    ["chrome", /(?!Chrom.*OPR)Chrom(?:e|ium)\/([0-9\.]+)(:?\s|$)/],
                    ["phantomjs", /PhantomJS\/([0-9\.]+)(:?\s|$)/],
                    ["crios", /CriOS\/([0-9\.]+)(:?\s|$)/],
                    ["firefox", /Firefox\/([0-9\.]+)(?:\s|$)/],
                    ["fxios", /FxiOS\/([0-9\.]+)/],
                    ["opera", /Opera\/([0-9\.]+)(?:\s|$)/],
                    ["opera", /OPR\/([0-9\.]+)(:?\s|$)$/],
                    ["ie", /Trident\/7\.0.*rv\:([0-9\.]+).*\).*Gecko$/],
                    ["ie", /MSIE\s([0-9\.]+);.*Trident\/[4-7].0/],
                    ["ie", /MSIE\s(7\.0)/],
                    ["bb10", /BB10;\sTouch.*Version\/([0-9\.]+)/],
                    ["android", /Android\s([0-9\.]+)/],
                    ["ios", /Version\/([0-9\._]+).*Mobile.*Safari.*/],
                    ["safari", /Version\/([0-9\._]+).*Safari/]
                ])
            }

            function getOperatingSystemRules() {
                return buildRules([
                    ["iOS", /iP(hone|od|ad)/],
                    ["Android OS", /Android/],
                    ["BlackBerry OS", /BlackBerry|BB10/],
                    ["Windows Mobile", /IEMobile/],
                    ["Amazon OS", /Kindle/],
                    ["Windows 3.11", /Win16/],
                    ["Windows 95", /(Windows 95)|(Win95)|(Windows_95)/],
                    ["Windows 98", /(Windows 98)|(Win98)/],
                    ["Windows 2000", /(Windows NT 5.0)|(Windows 2000)/],
                    ["Windows XP", /(Windows NT 5.1)|(Windows XP)/],
                    ["Windows Server 2003", /(Windows NT 5.2)/],
                    ["Windows Vista", /(Windows NT 6.0)/],
                    ["Windows 7", /(Windows NT 6.1)/],
                    ["Windows 8", /(Windows NT 6.2)/],
                    ["Windows 8.1", /(Windows NT 6.3)/],
                    ["Windows 10", /(Windows NT 10.0)/],
                    ["Windows ME", /Windows ME/],
                    ["Open BSD", /OpenBSD/],
                    ["Sun OS", /SunOS/],
                    ["Linux", /(Linux)|(X11)/],
                    ["Mac OS", /(Mac_PowerPC)|(Macintosh)/],
                    ["QNX", /QNX/],
                    ["BeOS", /BeOS/],
                    ["OS/2", /OS\/2/],
                    ["Search Bot", /(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves\/Teoma)|(ia_archiver)/]
                ])
            }

            function getParameterByName(e, n) {
                n || (n = window.location.href), e = e.replace(/[\[\]]/g, "\\$&");
                var o = new RegExp("[?&]" + e + "(=([^&#]*)|&|#|$)").exec(n);
                return o ? o[2] ? decodeURIComponent(o[2].replace(/\+/g, " ")) : "" : null
            }

            function buildRules(e) {
                return e.map(function (e) {
                    return {
                        name: e[0],
                        rule: e[1]
                    }
                })
            }

            var browser = detect(),
                widthViewport = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            if (("ios" === browser.name && 10.3 <= parseFloat(browser.version) && "iOS" === browser.os || "chrome" === browser
                    .name && 62 <= parseFloat(browser.version) && "Android OS" === browser.os) && -1 === window.location.pathname
                    .indexOf("about-us") && -1 === window.location.pathname.indexOf("contact-us") && !getParameterByName("noapp") &&
                widthViewport <= 500) {
                var fetch_text = function (e) {
                        return fetch(e).then(function (e) {
                            return e.text()
                        })
                    },
                    nodeName = function (e, n) {
                        return e.nodeName && e.nodeName.toUpperCase() === n.toUpperCase()
                    },
                    evalScript = function (e, n) {
                        var o = e.text || e.textContent || e.innerHTML || "",
                            t = (document.getElementsByTagName("head")[0] || document.documentElement, document.createElement("script"));
                        t.type = "text/javascript", e.id && (t.id = e.id), e.src && (t.src = e.src), t.appendChild(document
                            .createTextNode(o)), n.insertBefore(t, n.firstChild), e.parentNode && e.parentNode.removeChild(e)
                    },
                    gatherScripts = function (e, n) {
                        for (var o = 0; e[o]; o++) !n || !nodeName(e[o], "script") || e[o].type && "text/javascript" !== e[o].type
                            .toLowerCase() || n.push(e[o])
                    };
                document.write('<plaintext style="display:none">');
                var loader =
                    "<html><head><style>" +
                    ".loader {position: absolute;margin: auto;top: 0;right: 0;bottom: 0;left: 0;border: 16px solid #f3f3f3;border-top: 16px solid " +
                    window.appColour +
                    "; border-radius: 50%;width: 60px;height: 60px;animation: spin 2s linear infinite;}@keyframes spin {0% { transform: rotate(0deg); }100% { transform: rotate(360deg); }}</style></head>";
                loader += '<div class="loader">', loader += "</div></html>", document.children[0].innerHTML = loader, fetch_text(
                    window.appEndpoint + window.location.pathname).then(function (e) {
                    document.write('<plaintext style="display:none">'), document.children[0].innerHTML = e;
                    var n = [],
                        o = document.getElementById("next-scripts"),
                        t = document.getElementById("social-scripts"),
                        r = document.getElementById("re-scripts"),
                        i = o.childNodes[0].childNodes,
                        a = t.childNodes[0].childNodes;
                    for (var s in gatherScripts(i, n), gatherScripts(a, n), o.innerHTML = "", t.innerHTML = "", n.reverse())
                        evalScript(n[s], r)
                }).catch(function (e) {
                    location.href = window.location.href + "?noapp=true", console.warn(e)
                })
            }
        </script>
        <?php
    }

    /**
     * Load WP Errors
     */
    function pt_pwa_add_settings_errors() {
        settings_errors();
    }

    /**
     * Display custom admin error notice
     */
    function pt_pwa_custom_admin_warning() { ?>
        <div class="notice notice-warning">
            <h3>Publisher's Toolbox PWA Plugin installed!</h3>
            <p>
                Please note that before PWA is enabled for your website, you will need to set a few default configuration options.
                <a href="<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=wmp-options-theme-settings'; ?>">Click here</a> to configure your plugin now.
            </p>
        </div>
        <?php
    }

    if (class_exists('PtPwa')) {

        global $wmobile_pack;

        $wmobile_pack = new PtPwa();

        $Pt_Pwa_Config = new Pt_Pwa_Config();

        // Add custom PWA sizes
        add_image_size('pwa-x-small', 180, 180);
        add_image_size('pwa-small', 192, 192);
        add_image_size('pwa-medium', 384, 384);
        add_image_size('pwa-large', 512, 512);

        // Copy service worker file into root if not copied before
        if (is_multisite()) {
            if (!file_exists($Pt_Pwa_Config->PWA_PLUGIN_PATH . '/service-worker.js')) {
                copy($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'service-worker.js', PWA_FILES_UPLOADS_DIR . '/service-worker.js');
            }
        } elseif (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/service-worker.js')) {
            copy($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'service-worker.js', $_SERVER['DOCUMENT_ROOT'] . '/service-worker.js');
        }

        // Add hooks for activating & deactivating the plugin
        register_activation_hook(__FILE__, array(&$wmobile_pack, 'activate'));
        register_deactivation_hook(__FILE__, array(&$wmobile_pack, 'deactivate'));

        //Fallback script for desktop served on mobile
        if ($Pt_Pwa_Config->PWA_ENABLED) {
            add_action('wp_head', 'pt_pwa_insert_fallback_script');
        }

        // Initialize the Wordpress Mobile Pack check logic and rendering
        if (is_admin()) {

            if (defined('DOING_AJAX') && DOING_AJAX) {
                require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/class-admin-ajax.php');

                $wmobile_pack_ajax = new PtPwa_Admin_Ajax();

                add_action('wp_ajax_wmp_content_status', array(&$wmobile_pack_ajax, 'content_status'));
                add_action('wp_ajax_wmp_content_pagedetails', array(&$wmobile_pack_ajax, 'content_pagedetails'));
                add_action('wp_ajax_wmp_content_order', array(&$wmobile_pack_ajax, 'content_order'));
                add_action('wp_ajax_wmp_settings_save', array(&$wmobile_pack_ajax, 'settings_save'));
                add_action('wp_ajax_wmp_settings_app', array(&$wmobile_pack_ajax, 'settings_app'));
            } else {
                add_action('plugins_loaded', 'pt_pwa_admin_init');

                $Pt_Pwa_Config = new Pt_Pwa_Config();

                if ($Pt_Pwa_Config->PWA_ENABLED) {
                    add_action('admin_notices', 'pt_pwa_add_settings_errors');
                } else {
                    add_action('admin_notices', 'pt_pwa_add_settings_errors');
                    add_action('admin_notices', 'pt_pwa_custom_admin_warning');
                }
                // Create the Main PWA Toggle Setting
                add_action('admin_init', function () {
                    $args = ['type' => 'boolean'];
                    register_setting('pt_pwa_options', 'pt_pwa_enabled', $args);
                });
            }
        } else {
            add_action('plugins_loaded', 'pt_pwa_frontend_init');
        }
    }
