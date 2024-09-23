<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'title' => 'Home',
    ]);
});

Route::get('/kegiatan', function () {
    return view('posts', [
        'title' => 'Kegiatan'
    ]);
});

Route::get('/tentang', function () {
    return view('about', [
        'title' => 'Tentang'
    ]);
});

Route::get('/kontak', function () {
    return view('contact', [
        'title' => 'Kontak'
    ]);
});