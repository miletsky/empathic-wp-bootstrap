<?php

// EXPORT

function export_serialized_data($search, $replace)
{
    return array(
        'options' => export_serialized_options($search, $replace),
        'rg_forms' => export_serialized_forms($search, $replace)
    );
}


function export_serialized_options($search, $replace)
{
    $result = array();
    foreach(wp_load_alloptions() as $k => $v) {
        if (strstr($v, site_url()) && $u = unserialize($v)) {
            $u = str_replace_recursive($u, $search, $replace);
            $result[$k] = base64_encode(serialize($u));
        }
    }
    return $result;
}


function export_serialized_forms($search, $replace)
{
    global $wpdb;
    $result = array();
    if($wpdb->get_var("SHOW TABLES LIKE 'wp_rg_form_meta';") === 'wp_rg_form_meta') {
        $rg_forms = $wpdb->get_results("SELECT * FROM wp_rg_form_meta;", ARRAY_A);
        foreach($rg_forms as $f) {
            foreach($f as $field) {
                if(strstr($field, site_url()) && unserialize($field)) {
                    $result[$f['form_id']] = str_replace_form($f, $search, $replace);
                    break;
                }
            }
        }
    }
    return $result;
}
