<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\AdminUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');
Route::post('/memberships', [MembershipController::class, 'store'])->name('memberships.store');
Route::resource('admin/users', AdminUserController::class);
