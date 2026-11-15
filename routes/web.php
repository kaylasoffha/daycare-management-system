<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

// Route::get('/', function () {
//     return view('admin');
// });

Route::get('/', function () {
    return Redirect::route('filament.admin.auth.login');
    
    // Atau jika ingin langsung ke URL
    // return Redirect::to('/admin/login');
});
