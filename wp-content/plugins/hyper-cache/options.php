<?php
$controls = new HyperCacheControls();
$plugin = HyperCache::$instance;

if (!isset($plugin->options['translation_disabled'])) {
    if (function_exists('load_plugin_textdomain')) {
        load_plugin_textdomain('hyper-cache', false, 'hyper-cache/languages');
    }
}

if ($controls->is_action('save')) {
    $controls->options = stripslashes_deep($_POST['options']);

    $controls->options['folder'] = trim($controls->options['folder']);
    if (!empty($controls->options['folder']))
        $controls->options['folder'] = untrailingslashit($controls->options['folder']);

    if (!is_numeric($controls->options['max_age'])) {
        $controls->options['max_age'] = 24;
    }

    if (!is_numeric($controls->options['browser_cache_hours'])) {
        $controls->options['browser_cache_hours'] = 0;
    }

    // Mobile Agents
    $controls->options['mobile_agents'] = strtolower(trim($controls->options['mobile_agents']));
    $controls->options['mobile_agents'] = $plugin->text_to_list($controls->options['mobile_agents']);

    // Rejected Agents
    $controls->options['reject_agents'] = strtolower(trim($controls->options['reject_agents']));
    if (empty($controls->options['reject_agents'])) {
        unset($controls->options['reject_agents_enabled']);
        $controls->options['reject_agents'] = array();
    } else {
        $controls->options['mobile_agents'] = str_replace('#', ' ', $controls->options['mobile_agents']);
        $controls->options['reject_agents'] = $plugin->text_to_list($controls->options['mobile_agents']);
    }
    // Rejected Agents

    $controls->options['reject_cookies'] = strtolower(trim($controls->options['reject_cookies']));
    if (empty($controls->options['reject_cookies'])) {
        unset($controls->options['reject_cookies_enabled']);
        $controls->options['reject_cookies'] = array();
    } else {
        $controls->options['reject_cookies'] = str_replace('#', ' ', $controls->options['reject_cookies']);
        $controls->options['reject_cookies'] = $plugin->text_to_list($controls->options['reject_cookies']);
    }
    // Rejected URIs
    $controls->options['reject_uris'] = strtolower(trim($controls->options['reject_uris']));
    if (empty($controls->options['reject_uris'])) {
        unset($controls->options['reject_uris_enabled']);
        $controls->options['reject_uris'] = array();
    } else {
        $controls->options['reject_uris'] = str_replace('#', ' ', $controls->options['reject_uris']);
        $controls->options['reject_uris'] = $plugin->text_to_list($controls->options['reject_uris']);
    }

    $controls->options['reject_uris_exact'] = strtolower(trim($controls->options['reject_uris_exact']));
    if (empty($controls->options['reject_uris_exact'])) {
        unset($controls->options['reject_uris_exact_enabled']);
        $controls->options['reject_uris_exact'] = array();
    } else {
        $controls->options['reject_uris_exact'] = str_replace('#', ' ', $controls->options['reject_uris_exact']);
        $controls->options['reject_uris_exact'] = $plugin->text_to_list($controls->options['reject_uris_exact']);
    }

    update_option('hyper-cache', $controls->options);

    $controls->messages = __('Options saved. If you changes any of the bypasses empty the cache.', 'hyper-cache');

    $plugin->options = $controls->options;
    $r = $plugin->build_advanced_cache();

    if ($r == false) {
        $controls->errors = 'Unable to write the <code>wp-content/advanced-cache.php</code> file. Check the file or folder permissions.';
    }
}

if ($controls->is_action('clean')) {
    $folder = $plugin->get_folder();
    $plugin->remove_dir($folder . '');
     $controls->messages = __('The cache folder has been cleaned.', 'hyper-cache');
}

if ($controls->is_action('autoclean')) {
    $plugin->hook_hyper_cache_clean();
    $controls->messages = 'Done!';
}

if ($controls->is_action('clean-home')) {
    $home = get_option('home');
    $home = substr($home, strpos($home, '://') + 1);
    $folder = $plugin->get_folder() . '/' . $home;
    @unlink($folder . '/index.html');
    @unlink($folder . '/index.html.gz');
    @unlink($folder . '/index-https.html');
    @unlink($folder . '/index-https.html.gz');
    @unlink($folder . '/index-mobile.html');
    @unlink($folder . '/index-mobile.html.gz');
    @unlink($folder . '/index-https-mobile.html');
    @unlink($folder . '/index-https-mobile.html.gz');
    @unlink($folder . '/robots.txt');
    $plugin->remove_dir($folder . '/feed/');
    $plugin->remove_dir($folder . '/page/');
    $base = get_option('category_base');
    if (empty($base)) {
        $base = 'category';
    }
    $plugin->remove_dir($folder . '/' . $base . '/');

    $base = get_option('tag_base');
    if (empty($base)) {
        $base = 'tag';
    }
    $plugin->remove_dir($folder . '/' . $base . '/');

    $plugin->remove_dir($folder . '/type/');

    $plugin->remove_dir($folder . '/' . date('Y') . '/');
}

if ($controls->is_action('delete')) {
    delete_option('hyper-cahe');
    $controls->messages = 'Options deleted';
}

if ($controls->is_action('size')) {
    $folder = $plugin->get_folder();
    $controls->messages = __('Cache size', 'hyper-cache') . ': ' . size_format((hc_size($folder . '/')));
}

if ($controls->is_action('reset_mobile_agents')) {
    $controls->options['mobile_agents'] = explode('|', HyperCache::MOBILE_AGENTS);
}
if ($controls->is_action('import')) {

    $old_options = get_option('hyper');

    if (!is_array($old_options)) {
        $controls->errors = 'Old Hyper Cache options are missing or not readable';
    } else {

        $uris = $plugin->text_to_list($old_options['reject']);
        $controls->options['reject_uris'] = array();
        $controls->options['reject_uris_exact'] = array();

        foreach ($uris as $uri) {
            if (substr($uri, 0, 1) == '"') {
                $controls->options['reject_uris_exact'] = str_replace('"', '', $uri);
            } else {
                $controls->options['reject_uris'];
            }
        }

        $controls->options['mobile'] = isset($old_options['timeout']) ? 1 : 0;
        $controls->options['max_age'] = (int) $old_options['timeout'] / 60;
        $controls->options['reject_agents'] = $plugin->text_to_list($old_options['reject_agents']);
        $controls->options['reject_cookies'] = $plugin->text_to_list($old_options['reject_cookies']);
        $controls->options['mobile_agents'] = $plugin->text_to_list($old_options['mobile_agents']);

        update_option('hyper-cache', $controls->options);

        $controls->messages = 'Old options imported, now review them and save.';
    }
}

function hc_size($dir) {
    $files = glob($dir . '*', GLOB_MARK);
    $size = 0;
    if (!empty($files)) {
        foreach ($files as &$file) {
            if (substr($file, -1) == '/')
                $size += hc_size($file);
            else
                $size += @filesize($file);
        }
    }
    return $size;
}

if ($controls->options == null) {
    $controls->options = get_option('hyper-cache');
}

// For installation that does not create the directory on activation
wp_mkdir_p($plugin->get_folder());

// Sometime it happens that a scheduled job is lost...
if (!wp_next_scheduled('hyper_cache_clean')) {
    wp_schedule_event(time()+300, 'hourly', 'hyper_cache_clean');
}

?>
<script>
    jQuery(document).ready(function() {
        jQuery(function() {
            tabs = jQuery("#tabs").tabs({
                cookie: {
                    expires: 30
                }
            });
        });
    });
</script>
<div class="wrap">

    <h2>Hyper Cache</h2>

    <?php if (!defined('WP_CACHE') || !WP_CACHE) { ?>
        <div class="error">
            <p>
                You must add to the file wp-config.php (after the <code>define('WPLANG', '');</code>) the line of code:
                <code>define('WP_CACHE', true);</code>.
            </p>
        </div>
    <?php } ?>

    <?php if (@filemtime(WP_CONTENT_DIR . '/advanced-cache.php') < @filemtime(dirname(__FILE__) . '/advanced-cache.php')) { ?>
        <div class="error">
            <p>
                <?php _e('You must save the options since some files must be updated.', 'hyper-cache'); ?>
            </p>
        </div>
    <?php } ?>

    <?php if (!is_dir($plugin->get_folder())) { ?>
        <div class="error">
            <p>
                <?php
                printf(__('Hyper Cache was not able to create or find the %s folder.', 'hyper-cache'),
                    '<code>' . $plugin->get_folder() . '</code>');
                _e('Please create it manually with list, write and read permissions (usually 777).', 'hyper-cache');
                ?>

            </p>
        </div>
    <?php } ?>

    <?php if (get_option('permalink_structure') == '') { ?>
        <div class="error">
            <p>
                 <?php
                _e('You should choose a different permalink structure.', 'hyper-cache');
                _e('Change it on the <a href="options-permalink.php" target="_blank">permalink panel</a> otherwise Hyper Cache cannot work properly.', 'hyper-cache');
                ?>
            </p>
        </div>
    <?php } ?>


    <?php $controls->show(); ?>


    <form method="post" action="">
        <?php $controls->init(); ?>


        <p>
            Please, refer to the <a href="http://www.satollo.net/plugins/hyper-cache" target="_blank">official page</a>
            and the <a href="http://www.satollo.net/forums/forum/hyper-cache" target="_blank">official forum</a> for support.

            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5PHGDGNHAYLJ8" target="_blank"><img style="vertical-align: bottom" src="http://www.satollo.net/images/donate.png"></a>
            Even <b>2$</b> helps! (<a href="http://www.satollo.net/donations" target="_blank">read more</a>)
        </p>
        <p>
            Want a full mail marketing system in your blog? Try my free <a href="http://www.satollo.net/plugins/newsletter" target="_blank">Newsletter</a> plugin.
        </p>
        <p>
            <?php $controls->button('clean', __('Clean the whole cache', 'hyper-cache')); ?>
            <?php $controls->button('clean-home', __('Clean home and archives', 'hyper-cache')); ?>
            <?php $controls->button('size', __('Compute the cache size', 'hyper-cache')); ?>
            <?php $controls->button('import', __('Import old options', 'hyper-cache'), 'Sure? Your setting will be overwritten.'); ?>
        </p>

        <div id="tabs">
            <ul>
                <li><a href="#tabs-general"><?php _e('General', 'hyper-cache'); ?></a></li>
                <li><a href="#tabs-rejects"><?php _e('Bypasses', 'hyper-cache'); ?></a></li>
                <li><a href="#tabs-mobile"><?php _e('Mobile', 'hyper-cache'); ?></a></li>
            </ul>

            <div id="tabs-general">

                <table class="form-table">
                    <tr>
                        <th>Disable translations</th>
                        <td>
                            <?php $controls->checkbox('translation_disabled', 'Disable'); ?>
                            <p class="description">
                                <!-- Do not translate that -->
                                If you want to see this panel with the original labels, you can disable the
                                tranlsation.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Cached pages will be valid for', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->text('max_age'); ?> hours
                            <p class="description"><?php _e('0 means forever.', 'hyper-cache'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Enable compression', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('gzip'); ?>

                            <p class="description">
                                <?php _e('If you note odd characters when enabled, disable it since your server is already
                                compressing the pages.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>When the home is refreshed, refresh even the</th>
                        <td>
                            <?php $controls->text('clean_last_posts', 5); ?> latest post
                            <p class="description">
                                <?php _e('The number of latest posts to invalidate when the home is invalidated.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th><?php _e('Cache folder', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->text('folder', 70); ?> path on disk
                            <p class="description">
                                Leave blank for default value. You can even evaluate to leave
                                this blank and create a symbolic link <code>wp-content/cache/hyper-cache -&gt; [your folder]</code>. Your blog is located on
                                <code><?php echo ABSPATH; ?></code>. A wrong configuration can destroy your blog.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Next autoclean will run in', 'hyper-cache'); ?></th>
                        <td>
                            <?php echo (int)((wp_next_scheduled('hyper_cache_clean')-time())/60) ?> minutes
                            <p class="description">
                                <?php _e('The autoclean process removes old files to save disk space.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th><?php _e('Allow browser caching', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('browser_cache', 'enable it'); ?>

                            with an expire time of <?php $controls->text('browser_cache_hours', 5); ?> hours
                            <p class="description">
                                When enabled Hyper Cache sends a signal to the browser allowing it to NOT request a page more than once and
                                to use the copy it has in the local cache. If you set an expire time greater than zero, the browser should keep the copy
                                for that amount of hours, otherwise Hyper Cache will tell the browser to keep the copy until the page expire in the blog
                                cache.<br>
                                Usually I keep this feature disabled.
                            </p>
                        </td>
                    </tr>
                    <!--
                    <tr>
                        <th><?php _e('Remove HTTP/HTTPS', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('remove_protocol', 'Enable'); ?>
                            <p class="description">
                               If your site uses both HTTP and HTTPS it's better to have page links as
                               <code>://www.domain.com/...</code> without the protocol.
                            </p>
                        </td>
                    </tr>
                    -->

                    <tr valign="top">
                        <th><?php _e('HTTPS', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->select('https', array(0 => __('Bypass the cache', 'hyper-cache'),
                                1 => __('Cache separately', 'hyper-cache'),
                                2 => __('Use the standard cache (I have HTTP/HTTPS aware pages)', 'hyper-cache'))); ?>
                            <p class="description">
                            </p>
                        </td>
                    </tr>

                </table>

            </div>

            <div id="tabs-rejects">
                <table class="form-table">
                    <tr>
                        <th><?php _e('Do not cache the home page', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_home'); ?>
                            <p class="description">
                                <?php _e('When active, the home page and its subpages are not cached.', 'hyper-cache'); ?>
                                <?php _e('Works even with a static home page.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Do not cache the "404 - Not found" page', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_404'); ?>
                            <p class="description">
                                <?php _e('When active, Hyper Cache does not serve a cached "404 not found" page.', 'hyper-cache'); ?>
                                <?php _e('Requests which lead to a 404 not found page overload you blog since WordPress must generate a full page', 'hyper-cache'); ?>
                                <?php _e('Caching it help in reduce that overload.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Do not cache the blog main feeds', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_feeds'); ?>
                            <p class="description">
                                <?php printf(__('When active, the main blog feed %s is not cached.', 'hyper-cache'),
                                        '(<code>' . get_option('home') . '/feed</code>)'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Do not cache single post comment feed', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_comment_feeds'); ?>
                            <p class="description">
                                <?php _e('When active, the single post comment feeds are not cached.', 'hyper-cache'); ?>
                                <?php _e('Usually I enable this bypass since it saves disk space and comment feed on single posts are not usually used.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Exact URIs to bypass', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_uris_exact_enabled', __('Enable', 'hyper-cache')); ?><br>
                            <?php $controls->textarea('reject_uris_exact'); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('Those URIs are exactly matched.', 'hyper-cache'); ?>
                                <?php _e('For example if you add the <code>/my-single-post</code> URI and a request is received for <code>http://youblog.com<strong>/my-single-post</strong></code> that page IS NOT cached.', 'hyper-cache'); ?>
                                <?php _e('A request for <code>http://youblog.com<strong>/my-single-post-something</strong></code> IS cached.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('(Starting with) URIs to bypass', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_uris_enabled', __('Enable', 'hyper-cache')); ?><br>
                            <?php $controls->textarea('reject_uris'); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('Those URIs match if a requested URI starts with one of them.', 'hyper-cache'); ?>
                                <?php _e('For example if you add the <code>/my-single-post</code> URI and a request is received for <code>http://youblog.com<strong>/my-single-post</strong></code> that page IS NOT cached.', 'hyper-cache'); ?>

                                <?php _e('A request for <code>http://youblog.com<strong>/my-single-post-something</strong></code> IS NOT cached as well.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Cookies to bypass', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_cookies_enabled', __('Enable', 'hyper-cache')); ?><br>
                            <?php $controls->textarea('reject_cookies'); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('If the visitor has a cookie named as one of the listed values, the cache is bypassed.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Devices (user agents) to bypass', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_agents_enabled', __('Enable', 'hyper-cache')); ?><br>
                            <?php $controls->textarea('reject_agents'); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('If the visitor has a device with a user agent named as one of the listed values, the cache is bypassed.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th><?php _e('Don\'t serve cached pages to comment authors', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_comment_authors', __('Enable', 'hyper-cache')); ?>

                            <p class="description">
                                <?php _e('Hyper Cache is able to work with users who left a comment and completes the comment form with
                                user data even on cached page', 'hyper-cache'); ?>
                                <?php _e('(with a small JavaScript added at the end of the pages).', 'hyper-cache'); ?>
                                <?php _e('But the "awaiting moderation" message cannot be shown.', 'hyper-cache'); ?>
                                <?php _e('If you have few readers who comment you can disable this feature to get back the classical WordPress comment flow.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Reject posts older than', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->text('reject_old_posts', 5); ?> days
                            <p class="description">
                                <?php _e('Older posts won\'t be cached and stored resulting in a lower disk space usage.', 'hyper-cache'); ?>
                                <?php _e('Useful when older posts have low traffic.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="tabs-mobile">
                <table class="form-table">
                    <tr>
                        <th><?php _e('Working mode', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->select('mobile', array(0 => __('Use the standard cache', 'hyper-cache'),
                                1 => __('Cache separately', 'hyper-cache'),
                                2 => __('Bypass the cache', 'hyper-cache'))); ?>

                            <p class="description">
                                <?php _e('Choose "cache separately" if you produce different content for mobile devices', 'hyper-cache'); ?><br>
                                <?php _e('See for example my <a href="http://www.satollo.net/plugins/header-footer" target="_blank">Header and Footer</a> plugin for different desktop/mobile ads injection in posts.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Mobile theme', 'hyper-cache'); ?></th>
                        <td>
                            <?php
                            $themes = wp_get_themes();
                            //var_dump($themes);
                            $list = array('' => 'Use the active blog theme');
                            foreach ($themes as $theme)
                                $list[$theme->stylesheet] = $theme->name;
                            ?>
                            <?php $controls->select('theme', $list); ?>
                            <p class="description">
                                <?php _e('If you have plugins which produce different content for desktop and mobile devices, you should use a separate cache for mobile.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Mobile user agents', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->textarea('mobile_agents'); ?>
                            <?php $controls->button('reset_mobile_agents', 'Reset'); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('A "user agent" is a text which identify the kind of device used to surf the site.', 'hyper-cache'); ?>
                                <?php _e('For example and iPhone has <code>iphone</code> as user agent.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>


        </div>
        <p>
            <?php $controls->button('save', __('Save', 'hyper-cache')); ?>

            <?php if ($_SERVER['HTTP_HOST'] == 'www.satollo.net' || $_SERVER['HTTP_HOST'] == 'www.satollo.com') { ?>
            <?php $controls->button('delete', 'Delete options'); ?>
            <?php $controls->button('autoclean', 'Autoclean'); ?>
            <?php } ?>
        </p>

    </form>
</div>

<?php

class HyperCacheControls {

    var $options = null;
    var $errors = null;
    var $messages = null;

    function is_action($action = null) {
        if ($action == null)
            return !empty($_REQUEST['act']);
        if (empty($_REQUEST['act']))
            return false;
        if ($_REQUEST['act'] != $action)
            return false;
        if (check_admin_referer('save'))
            return true;
        die('Invalid call');
    }

    function text($name, $size = 20) {
        if (!isset($this->options[$name]))
            $this->options[$name] = '';
        $value = $this->options[$name];
        if (is_array($value))
            $value = implode(',', $value);
        echo '<input name="options[' . $name . ']" type="text" size="' . $size . '" value="';
        echo htmlspecialchars($value);
        echo '"/>';
    }

    function checkbox($name, $label = '') {
        if (!isset($this->options[$name]))
            $this->options[$name] = '';
        $value = $this->options[$name];
        echo '<label><input class="panel_checkbox" name="options[' . $name . ']" type="checkbox" value="1"';
        if (!empty($value))
            echo ' checked';
        echo '>';
        echo $label;
        echo '</label>';
    }

    function textarea($name) {
        if (!isset($this->options[$name]))
            $value = '';
        else
            $value = $this->options[$name];
        if (is_array($value))
            $value = implode("\n", $value);
        echo '<textarea name="options[' . $name . ']" style="width: 100%; heigth: 120px;">';
        echo htmlspecialchars($value);
        echo '</textarea>';
    }

    function select($name, $options) {
        if (!isset($this->options[$name]))
            $this->options[$name] = '';
        $value = $this->options[$name];

        echo '<select name="options[' . $name . ']">';
        foreach ($options as $key => $label) {
            echo '<option value="' . $key . '"';
            if ($value == $key)
                echo ' selected';
            echo '>' . htmlspecialchars($label) . '&nbsp;&nbsp;</option>';
        }
        echo '</select>';
    }

    function button($action, $label, $message = null) {
        if ($message == null) {
            echo '<input class="button-primary" type="submit" value="' . $label . '" onclick="this.form.act.value=\'' . $action . '\'"/>';
        } else {
            echo '<input class="button-primary" type="submit" value="' . $label . '" onclick="this.form.act.value=\'' . $action . '\';return confirm(\'' .
            htmlspecialchars($message) . '\')"/>';
        }
    }

    function init() {
        echo '<script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery("textarea").focus(function() {
                    jQuery(this).css("height", "400px");
                });
                jQuery("textarea").blur(function() {
                    jQuery(this).css("height", "120px");
                });
            });
            </script>
            ';
        echo '<input name="act" type="hidden" value=""/>';
        wp_nonce_field('save');
    }

    function show() {
        if (!empty($this->errors)) {
            echo '<div class="error"><p>';
            echo $this->errors;
            echo '</p></div>';
        }

        if (!empty($this->messages)) {
            echo '<div class="updated"><p>';
            echo $this->messages;
            echo '</p></div>';
        }
    }

}
?>