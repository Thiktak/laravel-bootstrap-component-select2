<?php

use Illuminate\Support\Facades\Route;
use Thiktak\LaravelBootstrapComponentSelect2\Http\Controllers\Api\ApiSelect2Controller;

Route::middleware('api')->group(function () {
    Route::get('select2/search', [ApiSelect2Controller::class, 'search']);
});
