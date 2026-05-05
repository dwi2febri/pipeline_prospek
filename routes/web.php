<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

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
use App\Http\Controllers\AuditLogExportController;
use App\Livewire\Reports\ProspectRecap as ProspectRecapReport;
use App\Livewire\Contents\Manager as ContentManager;
use App\Livewire\Contents\Show as ContentShow;
use App\Livewire\SimulasiKredit\Index as SimulasiKreditIndex;

// Homepage
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $role = strtoupper(trim((string) Auth::user()->role));

    if (in_array($role, ['ADMIN', 'MANAJEMEN', 'MANAJEMEN KANWIL', 'SUPERVISOR'], true)) {
        return redirect('/dashboard');
    }

    if (in_array($role, ['AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'], true)) {
        return redirect('/prospects-diajukan');
    }

    if ($role === 'PEGAWAI') {
        return redirect('/prospects');
    }

    return redirect('/dashboard');
});

// Untuk template auth yang pakai route('home')
Route::get('/home', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $role = strtoupper(trim((string) Auth::user()->role));

    if (in_array($role, ['ADMIN', 'MANAJEMEN', 'MANAJEMEN KANWIL', 'SUPERVISOR'], true)) {
        return redirect('/dashboard');
    }

    if (in_array($role, ['AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'], true)) {
        return redirect('/prospects-diajukan');
    }

    if ($role === 'PEGAWAI') {
        return redirect('/prospects');
    }

    return redirect('/dashboard');
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

    // ===== SAVE FCM TOKEN DARI WEBVIEW ANDROID =====
    Route::post('/mobile/save-fcm-token', function () {
        request()->validate([
            'token' => ['required', 'string'],
        ]);

        $user = Auth::guard('web')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        \App\Models\User::where('id', $user->id)->update([
            'fcm_token' => request()->input('token'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token berhasil disimpan.',
            'user_id' => $user->id,
            'username' => $user->name,
        ]);
    })->name('mobile.save-fcm-token');

    // ===== DASHBOARD =====
    Route::get('/dashboard', DashboardIndex::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR')
        ->name('dashboard');

    // ===== PROFILE =====
    Route::get('/profile', [ProfileController::class, 'index'])
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('profile.index');

    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('profile.password.update');

    // ===== PIPELINE PROSPEK =====
    Route::get('/prospects', ProspectsIndex::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('prospects.index');

    Route::get('/prospects/create', ProspectsForm::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('prospects.create');

    Route::get('/prospects/{id}/edit', ProspectsForm::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('prospects.edit');

    // ===== PROSPEK DIAJUKAN =====
    Route::get('/prospects-diajukan', ProspectsSubmissions::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL')
        ->name('prospects.submissions');

    // ===== SIMULASI KREDIT =====
    Route::get('/simulasi-kredit', SimulasiKreditIndex::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('simulasi-kredit.index');

    // ===== RECYCLE BIN =====
    Route::get('/recycle-bin/prospects', ProspectsRecycle::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('prospects.recycle');

    // ===== AUDIT LOG =====
    Route::get('/audit-logs', AuditIndex::class)
        ->middleware('role:ADMIN')
        ->name('audit.index');

    Route::get('/audit-logs/export', [AuditLogExportController::class, 'export'])
        ->middleware('role:ADMIN')
        ->name('audit.export');

    // ===== MANAJEMEN USER =====
    Route::get('/users', UsersIndex::class)
        ->middleware('role:ADMIN')
        ->name('users.index');

    Route::get('/users/create', UsersForm::class)
        ->middleware('role:ADMIN')
        ->name('users.create');

    Route::get('/users/{id}/edit', UsersForm::class)
        ->middleware('role:ADMIN')
        ->name('users.edit');

    // ===== TEMPLATE CSV USER =====
    Route::get('/users/template', function () {
        $filename = 'template_users.csv';

        $header = "kode;employee_id;full_name;branch_name;unit_kerja;job_position;level;group_jabatan\n";

        $example =
            "000;130-024;HASTONI SAPTO RENGGO, SE;Kantor Wilayah Banyumas;Area Kantor Wilayah;Residen Manajemen Risiko;Kepala Sub Bidang;PS\n" .
            "000;128-063;KARTIKA PANDU FILANDU, S.Pd;Kantor Wilayah Banyumas;Area Kantor Wilayah;Residen Analis Kredit;Kepala Sub Bidang;PS\n" .
            "000;127-019;NOVI TRI UTAMI, S.Pd;Kantor Wilayah Banyumas;Area Kantor Wilayah;Staf Administrasi Kantor Wilayah;Staf;Staf\n" .
            "000;137-042;ARIF SUPRAYOGO, S.Kom;Kantor Wilayah Pekalongan;Area Kantor Wilayah;Residen Analis Kredit;Kepala Sub Bidang;PS\n" .
            "000;102-089;IFAL ALEXIS HIDAYATULLAH, S.E;Kantor Wilayah Pekalongan;Area Kantor Wilayah;Residen Manajemen Risiko;Kepala Sub Bidang;PS\n";

        return response()->streamDownload(function () use ($header, $example) {
            echo $header;
            echo $example;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    })->middleware('role:ADMIN')->name('users.template');

    // ===== MASTER CABANG =====
    Route::get('/cabangs', CabangsIndex::class)
        ->middleware('role:ADMIN')
        ->name('cabangs.index');

    Route::get('/cabangs/create', CabangsForm::class)
        ->middleware('role:ADMIN')
        ->name('cabangs.create');

    Route::get('/cabangs/{id}/edit', CabangsForm::class)
        ->middleware('role:ADMIN')
        ->name('cabangs.edit');

    // ===== REKAP PROSPEK =====
    Route::get('/rekap-prospek', ProspectRecapReport::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR')
        ->name('reports.prospect-recap');

    // ===== KONTEN APP =====
    Route::get('/konten-app', ContentManager::class)
        ->middleware('role:ADMIN')
        ->name('contents.manager');

    Route::get('/contents', ContentManager::class)
        ->middleware('role:ADMIN')
        ->name('contents.index');

    Route::get('/contents/{jenis}/{slug}', ContentShow::class)
        ->name('contents.show');
});
