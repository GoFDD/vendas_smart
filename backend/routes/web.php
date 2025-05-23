<?php

use Illuminate\Support\Facades\Route;

Route::get('api/login', function () {
    return response()->json(['message' => 'Por favor, faça login'], 401);
})->name('login');
