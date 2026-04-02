<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Student Portal Routes
Route::get('/', [StudentController::class, 'index'])->name('student.search')->middleware(\App\Http\Middleware\PreventBackHistory::class);
Route::post('/search', [StudentController::class, 'searchResult'])->name('student.search.post');
Route::get('/result/clear', [StudentController::class, 'clearSession'])->name('student.clear');
Route::post('/result/record-print/{roll_number}', [StudentController::class, 'recordPrint'])->name('student.record_print');
Route::get('/result/{roll_number}', [StudentController::class, 'showResult'])
    ->name('student.result')
    ->middleware(\App\Http\Middleware\PreventBackHistory::class);

// Admin Auth Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login')->middleware(\App\Http\Middleware\PreventBackHistory::class);
Route::post('/admin/login', [AuthController::class, 'login']);
Route::match(['get', 'post'], '/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Panel Routes (Protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/update-ajax', [AdminController::class, 'updateSettingAjax'])->name('settings.update-ajax');
    
    // Result Template Designer
    Route::get('/template', [AdminController::class, 'editTemplate'])->name('template.index');
    Route::post('/template/update', [AdminController::class, 'updateTemplate'])->name('template.update');
    Route::post('/template/reset', [AdminController::class, 'resetTemplate'])->name('template.reset');

    // Results Management
    Route::get('/results', [AdminController::class, 'manageResults'])->name('results.index');
    Route::get('/results/create', [AdminController::class, 'create'])->name('results.create');
    Route::post('/results', [AdminController::class, 'store'])->name('results.store');
    Route::get('/results/{result}/edit', [AdminController::class, 'edit'])->name('results.edit');
    Route::put('/results/{result}', [AdminController::class, 'update'])->name('results.update');
    Route::post('/results/import', [AdminController::class, 'importResults'])->name('results.import');
    Route::delete('/results/{result}', [AdminController::class, 'destroy'])->name('results.destroy');
});
