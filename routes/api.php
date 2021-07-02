<?php

use Illuminate\Support\Facades\Route;
use Thiktak\LaravelBootstrapComponentSelect2\Http\Controllers\Api\ApiSelect2Controller;

Route::group(['middleware' => 'api', 'prefix' => 'api', 'as' => 'api.'], function () {
    Route::get('select2/search', [ApiSelect2Controller::class, 'search'])->name('select2.search');
});
