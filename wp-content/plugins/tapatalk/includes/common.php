<?php

function tt_json_error($code, $message = '', $data = array())
{
    $default = array(
        '1'      => 'Failed opening required file',
        '-32700' => 'Parse error',
        '-32600' => 'Invalid Request',
        '-32601' => 'Method not found',
        '-32602' => 'Invalid params',
        '-32603' => 'Internal error',
    );

    if (isset($default[$code])) $message = $default[$code];

    $error = array(
        'code'      => $code,
        'message'   => $message,
    );

    if ($data) $error['data'] = $data;

    tt_json_response($error, 0);
}

function tt_json_response($data, $type = 1)
{
    $response = array();

    if ($type === 0) {
        $response['error'] = $data;
    } else {
        $response['result'] = $data;
    }

    echo tt_json_encode($response);
    exit;
}

function tt_get_avatar_by_uid($uid)
{
    if (empty($uid)) return '';

    return preg_replace("/^.*src='([^']*?)'.*$/", '$1', get_avatar($uid));
}

function tt_process_short_content($str, $length = 200)
{
    $str = strip_tags($str);
    $str = preg_replace('/\[RSSjb .*?\]/si', '', $str);
    $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    $str = preg_replace('/[\n\r\t\s]+/', ' ', $str);
    $str = function_exists('mb_substr') ? mb_substr($str, 0, $length) : substr($str, 0, $length);
    return trim($str);
}

function tt_post_html_clean($str)
{
    $str = str_replace("&nbsp;", ' ', $str );
    $str = str_replace("\r\n", '', $str );
    $str = str_replace(array("\r", "\n"), '', $str );
    $str = str_replace("\t", '', $str );
    //$str = preg_replace('/>\s+</si', '><', $str);
    $str = preg_replace('/<p(.*?)>/si', '<br/>', $str);
    $str = str_replace('</p>', '<br/>', $str);
    $str = preg_replace('/<li>/si', '*', $str);
    $str = preg_replace('/<\/li>/si', '<br/>', $str);
    $search = array(
        "/<strong>(.*?)<\/strong>/si",
        "/<em>(.*?)<\/em>/si",
        "/<blockquote>(.*?)<\/blockquote>/si",
        "/<code>(.*?)<\/code>/si",
        "/<img .*?src=\"(.*?)\".*?\/?>/si",
        "/<a .*?href=\"(.*?)\".*?>(.*?)<\/a>/si",
        "/<script( [^>]*)?>([^<]*?)<\/script>/si",
    	"/<br(.*?)\/?>/si"
    );

    $replace = array(
        '<b>$1</b>',
        '<i>$1</i>',
        '[quote]$1[/quote]',
        '[quote]$1[/quote]',
        '[img]$1[/img]',
        '[url=$1]$2[/url]',
        '',
    	'<br>',
    );

    $str = preg_replace($search, $replace, $str);
    $str = strip_tags($str, '<br><i><b><u>');

    return $str;
}

function tt_add_timestamp_filter($where, $object = '')
{
    global $wpdb, $wp, $tt_timestamp_filter;

    if ( empty( $object ) )
        return $where;

    if ( $tt_timestamp_filter > 0 )
        $where .= " AND UNIX_TIMESTAMP($wpdb->posts.post_date_gmt) > $tt_timestamp_filter ";

    return $where;
}

function tt_get_post_count($cat = '', $timestemp = 0)
{
    global $wpdb;

    if ($cat || $timestemp)
    {
        $user = wp_get_current_user();

        if ($cat)
        {
            $term = get_term($cat, 'category');
            $query = "SELECT post_status, COUNT( * ) AS num_posts
                      FROM $wpdb->term_relationships INNER JOIN $wpdb->posts ON object_id = ID
                      WHERE term_taxonomy_id = '{$term->term_taxonomy_id}'";

            if ($timestemp)
            {
                $query .= " AND UNIX_TIMESTAMP(post_date_gmt) > $timestemp";
            }
        }
        else
        {
            $query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE UNIX_TIMESTAMP(post_date_gmt) > $timestemp";
        }

        if ( is_user_logged_in() ) {
            $post_type_object = get_post_type_object('post');
            if ( !current_user_can( $post_type_object->cap->read_private_posts ) ) {
                $query .= " AND (post_status != 'private' OR ( post_author = '$user->ID' AND post_status = 'private' ))";
            }
        }

        $query .= ' GROUP BY post_status';
        $count = $wpdb->get_results( $wpdb->prepare( $query, 'post' ), ARRAY_A );
        $stats = array();
        foreach ( get_post_stati() as $state )
            $stats[$state] = 0;

        foreach ( (array) $count as $row )
            $stats[$row['post_status']] = $row['num_posts'];

        $numposts = (object) $stats;
    }
    else
    {
        $numposts = wp_count_posts('post', 'readable');
    }

    $total = $numposts->publish;
    if (is_user_logged_in())
        $total += $numposts->private;

    return $total;
}

function tt_json_encode($data)
{
    if (!function_exists('json_encode') || version_compare(PHP_VERSION, '5.4.0', '<'))
    {
        return tt_my_json_encode($data);
    }
    else
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

function tt_my_json_encode($a=false)
{
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';

    if (is_scalar($a))
    {
        if (is_float($a))
        {
            // Always use "." for floats.
            return floatval(str_replace(",", ".", strval($a)));
        }

        if (is_string($a))
        {
            static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
            return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
        }
        else
        return $a;
    }

    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
        if (key($a) !== $i)
        {
            $isList = false;
            break;
        }
    }

    $result = array();
    if ($isList)
    {
        foreach ($a as $v) $result[] = tt_my_json_encode($v);
            return '[' . join(',', $result) . ']';
    }
    else
    {
        foreach ($a as $k => $v) $result[] = tt_my_json_encode($k).':'.tt_my_json_encode($v);
            return '{' . join(',', $result) . '}';
    }
}