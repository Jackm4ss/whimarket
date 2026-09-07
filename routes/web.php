<?php

use Illuminate\Support\Facades\Route;

use App\Support\MarketData;

Route::get('/', function () {
    return view('landing', [
        'categories' => MarketData::categories(),
        'products' => MarketData::landingProducts(),
        'sellers' => MarketData::sellers(),
        'steps' => MarketData::steps(),
    ]);
});
Route::get('/belanja', function () {
    return view('shop', [
        'products' => MarketData::shopProducts(),
        'categories' => MarketData::categories(),
    ]);
});
Route::get('/seller/rachel-vennya', function () {
    return view('seller-profile');
});
