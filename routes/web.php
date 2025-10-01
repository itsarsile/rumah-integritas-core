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
use App\Livewire\Admin\Approvals as AdminApprovals;
use App\Livewire\Admin\RequestTracking as AdminRequestTracking;
use App\Livewire\Account\Settings as AccountSettings;
use App\Livewire\Admin\AccessControl as AdminAccessControl;
use App\Livewire\Admin\LoginSliderManagement;
use App\Models\AuditReports;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Logs as ActivityLogs;

Route::get('/', function () {
    return view('login');
})->middleware('auth')->name('home');

Route::get('/login', function() {
    return view('login');
})->middleware('guest')->name('login');

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

    Route::prefix('/maintenance')->group(function () {
        Route::get('/create', \App\Livewire\Maintenance\Create::class)->name('dashboard.maintenance.create');
        Route::get('/', \App\Livewire\Maintenance\Master::class)->name('dashboard.maintenance.master');
        Route::get('/show/{id}', \App\Livewire\Maintenance\Show::class)->name('dashboard.maintenance.show');
    });

    Route::prefix('/agenda')->group(function () {
        Route::get('/create', \App\Livewire\Agenda\Create::class)->name('dashboard.agenda.create');
        Route::get('/', \App\Livewire\Agenda\Master::class)->name('dashboard.agenda.master');
        Route::get('/show/{id}', \App\Livewire\Agenda\Show::class)->name('dashboard.agenda.show');
    });

    Route::get('/logs', ActivityLogs::class)->name('dashboard.logs');
    Route::get('/settings', AccountSettings::class)->name('dashboard.settings');
    
    // Admin-only approvals page
    Route::get('/approvals', AdminApprovals::class)
        ->name('dashboard.approvals');

    // Admin-only request tracking page
    Route::get('/tracking', AdminRequestTracking::class)
        ->name('dashboard.tracking');

    // Admin-only RBAC management per module
    Route::get('/access-control', AdminAccessControl::class)
        ->name('dashboard.access-control');

    Route::get('/appearance/login-slider', LoginSliderManagement::class)
        ->name('dashboard.login-slider');

    // Master Data
    Route::prefix('/master-data')->group(function () {
        Route::get('/regions', \App\Livewire\Admin\MasterData\Regions::class)->name('dashboard.master-data.regions');
        Route::get('/divisions', \App\Livewire\Admin\MasterData\Divisions::class)->name('dashboard.master-data.divisions');
        Route::get('/consumption-types', \App\Livewire\Admin\MasterData\ConsumptionTypes::class)->name('dashboard.master-data.consumption-types');
        Route::get('/asset-types', \App\Livewire\Admin\MasterData\AssetTypes::class)->name('dashboard.master-data.asset-types');
        Route::get('/organizations', \App\Livewire\Admin\MasterData\Organizations::class)->name('dashboard.master-data.organizations');
    });

    Route::prefix('audit')->group(function () {
        Route::get("/", AuditMaster::class)->name('dashboard.audit.master');

        Route::get('/create', AuditCreate::class)->name('dashboard.audit.create');
        Route::get('/chat/{id}', ChatRoom::class)->name('dashboard.audit.chat');

        Route::get('/chat', function () {
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

            if (empty($file->lhp_document_path) || !Storage::disk('local')->exists($file->lhp_document_path)) {
                abort(404, 'file_not_found');
            }

            return Storage::disk('local')->response($file->lhp_document_path);
        })->name('audit.files');
    });

    Route::get('/master-request', MasterRequest::class)->name('dashboard.master-request');
});
