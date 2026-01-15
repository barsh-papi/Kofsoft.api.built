<?php
function copyAll($src, $dst) {
    $dir = opendir($src);
    if (!is_dir($dst)) mkdir($dst, 0755, true);
    while(false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyAll($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// SOURCE → storage/app/public
$source = __DIR__ . '/../storage/app/public';

// DESTINATION → public/storage
$destination = __DIR__ . '/storage';

copyAll($source, $destination);

echo "Synced!";