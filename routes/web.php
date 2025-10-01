<?php

use App\Http\Controllers\MattTestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard', [
        'user' => [
            'name'  => auth()->user()->name,
            'email' => auth()->user()->email,
        ],
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('matttest', function () {
    return Inertia::render('matttest/Matttest', [
        'users' => [
            [ // auth user
                'name'  => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            [ // matt test user
                'name'  => 'Matt User',
                'email' => 'matt@testuser.com',
            ]
        ],
        // 'user' => [
        //     'name'  => auth()->user()->name,
        //     'email' => auth()->user()->email,
        // ],
    ]);
})->middleware(['auth', 'verified'])->name('matttest');

Route::controller(MattTestController::class)
    ->prefix('matttestdata')
    ->group(function() {
        Route::get('', 'getSomeData')->name('matttest.getdata');
        Route::post('', 'sendSomeData')->name('matttest.senddata');
        Route::post('/form', 'sendFormData')->name('matttest.sendformdata');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
