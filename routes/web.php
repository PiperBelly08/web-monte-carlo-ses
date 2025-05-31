<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SahamController;
use App\Http\Controllers\MonteController;
use App\Models\Saham;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        $saham = Saham::all();

        $total = Saham::count();
        $total_saham = Saham::pluck('nama_saham')->unique()->count();
        $total_close = Saham::sum('close');

        return view('dashboard', compact('saham', 'total', 'total_saham', 'total_close'));
    })->name('dashboard');

    Route::resource('saham', SahamController::class);
    Route::get('/saham-export-pdf', [SahamController::class, 'exportPdf'])->name('saham.export.pdf');
    Route::post('/saham-import', [SahamController::class, 'importExcel'])->name('saham.import');
    Route::post('/saham-clear', [SahamController::class, 'clear'])->name('saham.clear');

    // Route::resource('monte', MonteController::class);
    Route::group(['prefix' => 'monte'], function () {
        Route::get('/monte', [MonteController::class, 'index'])->name('monte.index');
        Route::get('/monte-show-data/{id}', [MonteController::class, 'showData'])->name('monte.show.data');
        Route::post('/monte-show-data/{id}', [MonteController::class, 'showData'])->name('monte.show.data.generate');
        Route::post('/monte-export-pdf', [MonteController::class, 'exportPdf'])->name('monte.export.pdf');
    });

    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
});
