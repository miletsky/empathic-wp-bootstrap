<?php

require_once __DIR__ . '/lib/bootstrap.php';

echo "Starting import...\n";

$tmp_dir = rtrim(sys_get_temp_dir(), '/') . '/wp-backup/' . DB_NAME;
if(!is_dir($tmp_dir)) {
    mkdir($tmp_dir, 0777, true);
}
$tmp_file = $tmp_dir . '/' . uniqid('backup-') . '.sql';
$args = array(
    'cd ' . __DIR__,
    '&&',
    'mysqldump',
    '--user="' . DB_USER . '"',
    '--password="' . DB_PASSWORD . '"',
    DB_NAME,
    '> ' . $tmp_file
);
shell_exec(join(' ', $args));

echo "Backed up to $tmp_file before import\n";

echo "Replacing stuff...\n";

$dump = file_get_contents(__DIR__ . '/data/dump.sql');
$dump = str_replace($url_tokens, $url_values, $dump);
$tmp_file =  $tmp_dir . '/wp-backup.' . DB_NAME . '.import.tmp.sql';
file_put_contents($tmp_file, $dump);

echo "Importing dump...\n";

$args = array(
    'cd ' . __DIR__,
    '&&',
    'mysql',
    '--user="' . DB_USER . '"',
    '--password="' . DB_PASSWORD . '"',
    DB_NAME,
    '< ' . $tmp_file
);
shell_exec(join(' ', $args));

unlink($tmp_file);

if(file_exists(__DIR__ . '/data/dump.json')) {
    echo "Updating serialized values...\n";
    $dump_json = json_decode(file_get_contents(__DIR__ . '/data/dump.json'), true);
    import_serialized_data($dump_json, $url_tokens, $url_values);
}

if(in_array('-u', $_SERVER['argv']) && file_exists(__DIR__ . '/data/uploads.zip')) {
    echo "Unzipping uploads...\n";
    $content_dir = realpath(__DIR__ . '/../../public/wp-content');
    $target_dir = __DIR__ . '/data/uploads.zip';
    // exec("rm -rf $content_dir/uploads");
    // unzip -o option instead of complete rm
    exec("unzip -o $target_dir -d $content_dir");
    exec("rm -rf $content_dir/uploads/wp-less $content_dir/uploads/gravity_forms $content_dir/uploads/et_temp");
}

echo "All done!\n";
