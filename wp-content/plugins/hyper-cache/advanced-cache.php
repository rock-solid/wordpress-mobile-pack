<?php

global $cache_stop;
$cache_stop = false;

// Globally used. Here because of the function "theme change"
$hyper_cache_is_mobile = false;

if (defined('IS_PHONE')) {
    $hyper_cache_is_mobile = IS_PHONE;
} else {
    $hyper_cache_is_mobile = preg_match('#(HC_MOBILE_AGENTS)#i', strtolower($_SERVER['HTTP_USER_AGENT']));
}

if (HC_MOBILE === 2 && $hyper_cache_is_mobile) {
    hyper_cache_header('stop - mobile');
    $cache_stop = true;
    return false;
}

// Use this only if you can't or don't want to modify the .htaccess
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cache_stop = true;
    return false;
}

if ($_SERVER['QUERY_STRING'] != '') {
    hyper_cache_header('stop - query string');
    $cache_stop = true;
    return false;
}

if (defined('SID') && SID != '') {
    $cache_stop = true;
    return false;
}

if (isset($_COOKIE['cache_disable'])) {
    $cache_stop = true;
    return false;
}

if (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache') {
    hyper_cache_header('stop - no cache header');
    $cache_stop = true;
    return false;
}

if (isset($_SERVER['HTTP_PRAGMA']) && $_SERVER['HTTP_PRAGMA'] == 'no-cache') {
    hyper_cache_header('stop - no cache header');
    $cache_stop = true;
    return false;
}

// Used globally
$hyper_cache_is_ssl = false;

if (isset($_SERVER['HTTPS'])) {
    if ('on' == strtolower($_SERVER['HTTPS']) || '1' == $_SERVER['HTTPS']) {
        $hyper_cache_is_ssl = true;
    } else if (isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] )) {
        $hyper_cache_is_ssl = true;
    }
}

if (HC_HTTPS === 0 && $hyper_cache_is_ssl) {
    hyper_cache_header('stop - https');
    $cache_stop = true;
    return false;
}

if (HC_REJECT_AGENTS_ENABLED && isset($_SERVER['HTTP_USER_AGENT'])) {
    if (preg_match('#(HC_REJECT_AGENTS)#i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        hyper_cache_header('stop - user agent');
        $cache_stop = true;
        return false;
    }
}

if (!empty($_COOKIE)) {
    foreach ($_COOKIE as $n => $v) {
        if (substr($n, 0, 20) == 'wordpress_logged_in_') {
            hyper_cache_header('stop - logged in cookie');
            $cache_stop = true;
            return false;
        }

        if (substr($n, 0, 12) == 'wp-postpass_') {
            hyper_cache_header('stop - password cookie');
            $cache_stop = true;
            return false;
        }

        if (HC_REJECT_COMMENT_AUTHORS && substr($n, 0, 14) == 'comment_author') {
            hyper_cache_header('stop - comment author cookie');
            $cache_stop = true;
            return false;
        }
        if (HC_REJECT_COOKIES_ENABLED) {
            if (preg_match('#(HC_REJECT_COOKIES)#i', strtolower($n))) {
                hyper_cache_header('stop - bypass cookie');
                $cache_stop = true;
                return false;
            }
        }
    }
}

// Globally used
$hyper_cache_group = '';
    
if (HC_HTTPS === 1 && $hyper_cache_is_ssl) {
    $hyper_cache_group .= '-https';
}

if (HC_MOBILE === 1 && $hyper_cache_is_mobile) {
    $hyper_cache_group .= '-mobile';
}

//$hc_file = ABSPATH . 'wp-content/cache/lite-cache' . $_SERVER['REQUEST_URI'] . '/index' . $hc_group . '.html';
$hc_uri = hyper_cache_sanitize_uri($_SERVER['REQUEST_URI']);

$hc_file = 'HC_FOLDER/' . strtolower($_SERVER['HTTP_HOST']) . $hc_uri . '/index' . $hyper_cache_group . '.html';
if (HC_GZIP == 1) {
    $hc_gzip = isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;
} else {
    $hc_gzip = false;
}

if ($hc_gzip) {
    $hc_file .= '.gz';
}

if (!is_file($hc_file)) {
    hyper_cache_header('continue - no file');
    return false;
}

$hc_file_time = filemtime($hc_file);

if (HC_MAX_AGE > 0 && $hc_file_time < time() - (HC_MAX_AGE * 3600)) {
    hyper_cache_header('continue - old file');
    return false;
}

if (array_key_exists("HTTP_IF_MODIFIED_SINCE", $_SERVER)) {
    $hc_if_modified_since = strtotime(preg_replace('/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"]));
    if ($hc_if_modified_since >= $hc_file_time) {
        header("HTTP/1.0 304 Not Modified");
        flush();
        die();
    }
}

header('Content-Type: text/html;charset=UTF-8');
header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $hc_file_time) . ' GMT');

if (HC_MOBILE === 0) {
    header('Vary: Accept-Encoding');
} else {
    header('Vary: Accept-Encoding,User-Agent');
}

if (HC_BROWSER_CACHE) {
    if (HC_BROWSER_CACHE_HOURS != 0) {
        $hc_cache_max_age = HC_BROWSER_CACHE_HOURS * 3600;
    } else {
        // If there is not a default expire time, use 24 hours.
        if (HC_MAX_AGE > 0) {
            $hc_cache_max_age = time() + (HC_MAX_AGE * 3600) - $hc_file_time;
        } else {
            $hc_cache_max_age = time() + (24 * 3600) - $hc_file_time;
        }
    }
    header('Cache-Control: max-age=' . $hc_cache_max_age);
    header('Expires: ' . gmdate("D, d M Y H:i:s", time() + $hc_cache_max_age) . " GMT");
} else {
    header('Cache-Control: must-revalidate');
    header('Pragma: no-cache');
}

if ($hc_gzip) {
    hyper_cache_header('hit - gzip' . $hyper_cache_group);
    header('Content-Encoding: gzip');
    header('Content-Length: ' . filesize($hc_file));
    echo file_get_contents($hc_file);
} else {
    hyper_cache_header('hit - plain' . $hyper_cache_group);
    header('Content-Length: ' . filesize($hc_file));
    echo file_get_contents($hc_file);
}
flush();
die();

function hyper_cache_sanitize_uri($uri) {
    $uri = preg_replace('/[^a-zA-Z0-9\.\/\-_]+/', '_', $uri);
    $uri = preg_replace('/\/+/', '/', $uri);
    $uri = rtrim($uri, '.-_/');
    if (empty($uri) || $uri[0] != '/') {
        $uri = '/' . $uri;
    }
    return rtrim($uri, '/');
}

function hyper_cache_header($value) {
    header('X-Hyper-Cache: ' . $value);
}
