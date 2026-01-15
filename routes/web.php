<?php

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/s3-test', function () {
    Storage::disk('s3')->put('test.txt','Hello s3');
    return 'Uploaded';
});

Route::get('/s3-debug', function () {
 $url = Storage::disk('s3')->url('images/example.jpg');

return [
    'default_disk' => config('filesystems.default'),
    's3_config' => config('filesystems.disks.s3'),
    'test_url' => $url
];

});



