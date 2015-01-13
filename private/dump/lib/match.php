<?php

$url_tokens = array();
$url_values = array();

if(defined('MULTISITE') && MULTISITE) {
    global $wpdb;
    $wpdb->query("select blog_id, domain from wp_blogs order by blog_id desc;", ARRAY_A);
    foreach ($wpdb->last_result as $blog) {
        $url_tokens[] = '{{{blog_url_' . $blog->blog_id . '}}}';
        $url_values[] = $blog->domain;
    }
} else {
    $url_tokens[] = '{{{site_url}}}';
    $url_values[] = site_url();
}
