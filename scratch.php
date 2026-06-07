<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$m = App\Models\Mahasiswa::first();
if (!$m) {
    echo 'NO_MAHASISWA';
} else {
    $id = $m->id;
    echo 'Deleting ID ' . $id . '... ';
    try {
        $res = app()->make(App\Http\Controllers\MahasiswaController::class)->destroy($id);
        echo 'SUCCESS. Response: ' . json_encode($res->getData());
    } catch (\Exception $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
}
