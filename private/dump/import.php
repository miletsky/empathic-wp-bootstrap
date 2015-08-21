<?php

require_once __DIR__ . '/lib/bootstrap.php';

log("Starting import...");

$tmp_dir = rtrim(sys_get_temp_dir(), '/') . '/wp-backup/' . DB_NAME;
if(!is_dir($tmp_dir)) {
    mkdir($tmp_dir, 0777, true);
}
$tmp_file = $tmp_dir . '/' . uniqid('backup-') . '.sql';
$backup_file = __DIR__ .  . '/backup/' . date('%Y-%m-%d-%H-%i-%s') . '.sql';

$args = array(
    'cd ' . __DIR__,
    '&&',
    'mysqldump',
    '--user="' . DB_USER . '"',
    '--password="' . DB_PASSWORD . '"',
    DB_NAME,
    '> ' . $backup_file;
);
shell_exec(join(' ', $args));

log("Backed up to $backup_file before import", 'b');

log("Replacing stuff...");

$dump = file_get_contents(__DIR__ . '/data/dump.sql');
$dump = str_replace($url_tokens, $url_values, $dump);
$tmp_file =  $tmp_dir . '/wp-backup.' . DB_NAME . '.import.tmp.sql';
file_put_contents($tmp_file, $dump);

log("Importing dump...");

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
    log("Updating serialized values...");
    $dump_json = json_decode(file_get_contents(__DIR__ . '/data/dump.json'), true);
    import_serialized_data($dump_json, $url_tokens, $url_values);
}

if(in_array('-u', $_SERVER['argv']) && file_exists(__DIR__ . '/data/uploads.zip')) {
    log("Unzipping uploads...", 'y');
    $content_dir = realpath(__DIR__ . '/../../public/wp-content');
    $target_dir = __DIR__ . '/data/uploads.zip';
    // exec("rm -rf $content_dir/uploads");
    // unzip -o option instead of complete rm
    exec("unzip -o $target_dir -d $content_dir");
    exec("rm -rf $content_dir/uploads/wp-less $content_dir/uploads/gravity_forms $content_dir/uploads/et_temp");
}

log("All done!", 'g');
