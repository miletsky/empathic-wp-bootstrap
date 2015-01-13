<?php

require_once __DIR__ . '/lib/bootstrap.php';

$dump_json = export_serialized_data($url_values, $url_tokens);
file_put_contents(__DIR__ . '/data/dump.json', json_encode($dump_json));
echo "Serialized data prepared.\n";

echo "Launching mysqldump...\n";

$args = array(
    'cd ' . __DIR__,
    '&&',
    'mysqldump',
    '--user="' . DB_USER . '"',
    '--password="' . DB_PASSWORD . '"',
    DB_NAME
);

$dump = shell_exec(join(' ', $args));

echo "Replacing stuff...\n";

$dump = str_replace($url_values, $url_tokens, $dump);
file_put_contents(__DIR__ . '/data/dump.sql', $dump);

if(in_array('-u', $_SERVER['argv'])) {
    echo "Zipping uploads...\n";
    $content_dir = realpath(__DIR__ . '/../../public/wp-content');
    $target_dir = __DIR__ . '/data/uploads.zip';
    shell_exec("cd $content_dir && zip -9rq $target_dir uploads");
}

echo "All done!\n";
