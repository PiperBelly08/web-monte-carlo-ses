<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SahamController;
use App\Http\Controllers\MonteController;
 
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
        return view('dashboard');
    })->name('dashboard');
 
    // Route::controller(ProductController::class)->prefix('products')->group(function () {
    //     Route::get('', 'index')->name('products');
    //     Route::get('create', 'create')->name('products.create');
    //     Route::post('store', 'store')->name('products.store');
    //     Route::get('show/{id}', 'show')->name('products.show');
    //     Route::get('edit/{id}', 'edit')->name('products.edit');
    //     Route::put('edit/{id}', 'update')->name('products.update');
    //     Route::delete('destroy/{id}', 'destroy')->name('products.destroy');
    // });
    Route::resource('saham', SahamController::class);
    Route::get('/saham-export-pdf', [SahamController::class, 'exportPdf'])->name('saham.export.pdf');
    Route::post('/saham-import', [SahamController::class, 'importExcel'])->name('saham.import');
    Route::post('/saham-clear', [SahamController::class, 'clear'])->name('saham.clear');

    Route::resource('monte', MonteController::class);
    Route::post('/monte-export-pdf', [MonteController::class, 'exportPdf'])->name('monte.export.pdf');

    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
});