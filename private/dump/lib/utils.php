<?php

function walk_recursive($obj, $closure)
{
    if ( is_object($obj) ) {
        $newObj = new stdClass();
        foreach ($obj as $property => $value) {
            $newProperty = $closure($property);
            $newValue = walk_recursive($value, $closure);
            $newObj->$newProperty = $newValue;
        }
        return $newObj;
    } else if ( is_array($obj) ) {
        $newArray = array();
        foreach ($obj as $key => $value) {
            $key = $closure($key);
            $newArray[$key] = walk_recursive($value, $closure);
        }
        return $newArray;
    } else {
        return $closure($obj);
    }
}


function str_replace_recursive($val, $search, $replace)
{
    return walk_recursive($val, function(&$i) use($search, $replace) { return str_replace($search, $replace, $i); } );
}


function str_replace_form($val, $search, $replace)
{
    foreach($val as &$field) {
        if($u = unserialize($field)) {
            $field = base64_encode(serialize(str_replace_recursive($u, $search, $replace)));
        }
    }
    return $val;
}


function str_replace_form_import($val, $search, $replace)
{
    foreach($val as &$field) {
        $field = base64_decode($field);
        if($u = unserialize($field)) {
            $field = serialize(str_replace_recursive($u, $search, $replace));
        }
    }
    return $val;
}
