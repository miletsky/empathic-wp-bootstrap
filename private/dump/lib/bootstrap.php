<?php

if(php_sapi_name() !== 'cli') {
    die;
}
if(!file_exists(realpath(__DIR__ . '/../../../public/wp-config.php'))) {
    echo "File public/wp-config.php doesn't exist, please create it first.";
}

require_once realpath(__DIR__ . '/../../../public/wp-config.php');
global $wpdb;
require_once 'utils.php';
require_once 'export_functions.php';
require_once 'import_functions.php';
require_once 'match.php';
