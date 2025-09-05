<?php

use App\Livewire\Audit\Master as AuditMaster;
use App\Livewire\Audit\Show as AuditShow;
use App\Livewire\Audit\Create as AuditCreate;
use App\Livewire\Chat;
use App\Livewire\Home;
use App\Livewire\LoginForm;
use App\Livewire\RoleManagement;
use App\Livewire\UserManagement;
use App\Models\AuditReports;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->middleware('auth')->name('home');

Route::get('/login', LoginForm::class)->middleware('guest')->name('login');

Route::get('/dashboard', function () {
    return redirect()->intended('dashboard');
})->middleware('auth')->name('dashboard.index');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('login');
})->name('logout');

Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/', Home::class)->name('dashboard');
    Route::get('/user-management', UserManagement::class)->name('dashboard.user-management');
    Route::get('/role-management', RoleManagement::class)->name('dashboard.role-management');


    Route::get('/consumption')->name('dashboard.consumption');
    Route::get('/maintenance')->name('dashboard.maintenance');
    Route::get('/agenda')->name('dashboard.agenda');
    Route::get('/chat', Chat::class)->name('dashboard.chat');
    Route::get('/logs')->name('dashboard.logs');
    Route::get('/settings')->name('dashboard.settings');

    Route::prefix('audit')->group(function () {
        Route::get("/", AuditMaster::class)->name('dashboard.audit.master');

        Route::get('/create', AuditCreate::class)->name('dashboard.audit.create');

        Route::get('/{id}', AuditShow::class)->name('dashboard.audit.show');
        
        Route::get('/files/{id}', function ($id) {
            $file = AuditReports::findOrFail($id);
            return Storage::response($file->lhp_document_path);
        })->name('audit.files');
    });
});

