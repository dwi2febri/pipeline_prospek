<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Livewire\Dashboard\Index as DashboardIndex;

use App\Livewire\Prospects\Index as ProspectsIndex;
use App\Livewire\Prospects\Form as ProspectsForm;
use App\Livewire\Prospects\RecycleBin as ProspectsRecycle;
use App\Livewire\Prospects\Submissions as ProspectsSubmissions;

use App\Livewire\AuditLogs\Index as AuditIndex;

use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Users\Form as UsersForm;

use App\Livewire\Cabangs\Index as CabangsIndex;
use App\Livewire\Cabangs\Form as CabangsForm;

use App\Http\Controllers\ProfileController;

// Homepage
Route::get('/', function () {
    return Auth::check()
        ? redirect('/dashboard')
        : redirect('/login');
});

// Untuk template auth yang pakai route('home')
Route::get('/home', function () {
    return Auth::check()
        ? redirect('/dashboard')
        : redirect('/login');
})->name('home');

// =====================
// PROXY API WILAYAH
// =====================
Route::get('/api-wilayah/regencies/{provinceId}', function ($provinceId) {
    $res = Http::timeout(20)
        ->acceptJson()
        ->get("https://wilayah.web.id/api/regencies/{$provinceId}");

    return response()->json($res->json(), $res->status());
})->name('api.wilayah.regencies');

Route::get('/api-wilayah/districts/{regencyId}', function ($regencyId) {
    $res = Http::timeout(20)
        ->acceptJson()
        ->get("https://wilayah.web.id/api/districts/{$regencyId}");

    return response()->json($res->json(), $res->status());
})->name('api.wilayah.districts');

Route::get('/api-wilayah/villages/{districtId}', function ($districtId) {
    $res = Http::timeout(20)
        ->acceptJson()
        ->get("https://wilayah.web.id/api/villages/{$districtId}");

    return response()->json($res->json(), $res->status());
})->name('api.wilayah.villages');

// Semua halaman aplikasi harus login
Route::middleware(['auth'])->group(function () {

    // ===== DASHBOARD =====
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

    // ===== PROFILE =====
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ===== PIPELINE PROSPEK =====
    Route::get('/prospects', ProspectsIndex::class)->name('prospects.index');
    Route::get('/prospects/create', ProspectsForm::class)->name('prospects.create');
    Route::get('/prospects/{id}/edit', ProspectsForm::class)->name('prospects.edit');

    // ===== PROSPEK DIAJUKAN (ADMIN) =====
    Route::get('/prospects-diajukan', ProspectsSubmissions::class)
        ->middleware('role:ADMIN')
        ->name('prospects.submissions');

    // ===== RECYCLE BIN (ADMIN) =====
    Route::get('/recycle-bin/prospects', ProspectsRecycle::class)
        ->middleware('role:ADMIN')
        ->name('prospects.recycle');

    // ===== AUDIT LOG (ADMIN) =====
    Route::get('/audit-logs', AuditIndex::class)
        ->middleware('role:ADMIN')
        ->name('audit.index');

    // ===== MANAJEMEN USER (ADMIN) =====
    Route::get('/users', UsersIndex::class)
        ->middleware('role:ADMIN')
        ->name('users.index');

    Route::get('/users/create', UsersForm::class)
        ->middleware('role:ADMIN')
        ->name('users.create');

    Route::get('/users/{id}/edit', UsersForm::class)
        ->middleware('role:ADMIN')
        ->name('users.edit');

    // ===== TEMPLATE CSV USER (ADMIN) =====
    Route::get('/users/template', function () {
        $filename = 'template_users.csv';

        $header = "username;password;nama_lengkap;role;id_cabang;job_posisi\n";
        $example =
            "admin2;password;Administrator;ADMIN;29;ADMIN\n" .
            "102-004;password;SAIFUL AZIS NASUTION, SH;PEGAWAI;31;Kepala Kantor Wilayah\n" .
            "102-029;password;YUSUF MAHENDRA, S.Ak., M.M.;PEGAWAI;31;Residen Kepatuhan\n" .
            "103-017;password;DIAH NUR HAYATI, SE;PEGAWAI;32;Kepala Kantor Wilayah\n";

        return response()->streamDownload(function () use ($header, $example) {
            echo $header;
            echo $example;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    })->middleware('role:ADMIN')->name('users.template');

    // ===== MASTER CABANG (ADMIN) =====
    Route::get('/cabangs', CabangsIndex::class)
        ->middleware('role:ADMIN')
        ->name('cabangs.index');

    Route::get('/cabangs/create', CabangsForm::class)
        ->middleware('role:ADMIN')
        ->name('cabangs.create');

    Route::get('/cabangs/{id}/edit', CabangsForm::class)
        ->middleware('role:ADMIN')
        ->name('cabangs.edit');
});
