<?php

use App\Http\Controllers\ChatController;
use App\Livewire\Audit\Master as AuditMaster;
use App\Livewire\Audit\Show as AuditShow;
use App\Livewire\Audit\Create as AuditCreate;
use App\Livewire\Chat;
use App\Livewire\ChatRoom;
use App\Livewire\Home;
use App\Livewire\LoginForm;
use App\Livewire\MasterRequest;
use App\Livewire\RoleManagement;
use App\Livewire\UserManagement;
use App\Models\AuditReports;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->middleware('auth')->name('home');

Route::get('/login', LoginForm::class)->middleware('guest')->name('login');

Route::get('/', function () {
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


    Route::prefix('/consumption')->group(function () {
        Route::get('/', \App\Livewire\Consumption\Master::class)->name('dashboard.consumption.master');
        Route::get('/show/{id}', \App\Livewire\Consumption\Show::class)->name('dashboard.consumption.show');
        Route::get('/create', \App\Livewire\Consumption\Create::class)->name('dashboard.consumption.create');
    });

    Route::prefix('/maintenance')->group(function() {
        Route::get('/create', \App\Livewire\Maintenance\Create::class)->name('dashboard.maintenance.create');
    });

    Route::prefix('/agenda')->group(function() {
        Route::get('/create', \App\Livewire\Agenda\Create::class)->name('dashboard.agenda.create');
    });

    Route::get('/maintenance')->name('dashboard.maintenance');
    Route::get('/agenda')->name('dashboard.agenda');
    Route::get('/logs')->name('dashboard.logs');
    Route::get('/settings')->name('dashboard.settings');

    Route::prefix('audit')->group(function () {
        Route::get("/", AuditMaster::class)->name('dashboard.audit.master');

        Route::get('/create', AuditCreate::class)->name('dashboard.audit.create');
        Route::get('/chat/{id}', ChatRoom::class)->name('dashboard.audit.chat');
        
        Route::get('/chat', function() {
            $firstAudit = \App\Models\AuditReports::first();
            if ($firstAudit) {
                return redirect()->route('dashboard.audit.chat', ['id' => $firstAudit->id]);
            }
            return redirect()->route('dashboard.audit.master')->with('error', 'No audit reports found');
        })->name('dashboard.chat');

        Route::get('/chat/download/{id}', [ChatController::class, 'downloadFile'])->name('dashboard.audit.chat.download');

        Route::get('/{id}', AuditShow::class)->name('dashboard.audit.show');
        
        Route::get('/files/{id}', function ($id) {
            $file = AuditReports::findOrFail($id);
            return Storage::response($file->lhp_document_path);
        })->name('audit.files');
    });

    Route::get('/master-request', MasterRequest::class)->name('dashboard.master-request');
});

