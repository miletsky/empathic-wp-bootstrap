<?php

// IMPORT

function import_serialized_data($data, $search, $replace)
{
    if($data) {
        if($data['options']) {
            import_serialized_options($data['options'], $search, $replace);
        }
        if($data['rg_forms']) {
            import_serialized_forms($data['rg_forms'], $search, $replace);
        }
    }
}


function import_serialized_options($data, $search, $replace)
{
    global $wpdb;
    foreach($data as $k => $v) {
        $v = base64_decode(unserialize($v));
        $v = str_replace_recursive($v, $search, $replace);
        $v = serialize($v);
        $wpdb->query("UPDATE wp_options SET option_value = '$v' WHERE option_name = '$k';");
    }
}


function import_serialized_forms($data, $search, $replace)
{
    global $wpdb;
    foreach($data as $id => $f) {
        $f = str_replace_form_import($f, $search, $replace);
        $wpdb->query("UPDATE wp_rg_form_meta
            SET display_meta = '{$f['display_meta']}',
            confirmations = '{$f['confirmations']}',
            notifications = '{$f['notifications']}'
            WHERE form_id = '{$id}';");
    }
}
