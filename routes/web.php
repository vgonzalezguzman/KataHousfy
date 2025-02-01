<?php

use App\Http\Controllers\AppLoader;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [AppLoader::class, 'index']);