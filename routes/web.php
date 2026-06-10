<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'SIAKAD Suzuran Backend API is running.',
        'status' => 'OK'
    ]);
});
