<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Livewire\Dashboard\Index as DashboardIndex;

use App\Livewire\Prospects\Index as ProspectsIndex;
use App\Livewire\Prospects\Form as ProspectsForm;
use App\Livewire\Prospects\RecycleBin as ProspectsRecycle;
use App\Livewire\Prospects\Submissions as ProspectsSubmissions;
use App\Livewire\Prospects\SubmissionDetail as ProspectSubmissionDetail;

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
use App\Livewire\NominatifKredit\Index as NominatifKreditIndex;
use App\Http\Controllers\AiChatController;

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
$fetchWilayah = static function (string $endpoint, string $cacheKey) {
    try {
        $payload = Cache::remember($cacheKey, now()->addDays(7), function () use ($endpoint) {
            return Http::acceptJson()
                ->connectTimeout(3)
                ->timeout(6)
                ->retry(2, 250, throw: false)
                ->get("https://wilayah.web.id/api/{$endpoint}")
                ->throw()
                ->json();
        });

        return response()->json(is_array($payload) ? $payload : ['data' => []]);
    } catch (\Throwable $e) {
        Log::warning('API wilayah gagal dimuat.', [
            'endpoint' => $endpoint,
            'message' => $e->getMessage(),
        ]);

        return response()->json([
            'data' => [],
            'message' => 'Data wilayah sedang tidak dapat dimuat. Silakan coba lagi.',
        ], 503);
    }
};

Route::get('/api-wilayah/regencies/{provinceId}', function ($provinceId) use ($fetchWilayah) {
    abort_unless(preg_match('/^\d+$/', (string) $provinceId), 422);

    return $fetchWilayah(
        "regencies/{$provinceId}",
        "wilayah:regencies:{$provinceId}"
    );
})->name('api.wilayah.regencies');

Route::get('/api-wilayah/districts/{regencyId}', function ($regencyId) use ($fetchWilayah) {
    abort_unless(preg_match('/^\d+$/', (string) $regencyId), 422);

    return $fetchWilayah(
        "districts/{$regencyId}",
        "wilayah:districts:{$regencyId}"
    );
})->name('api.wilayah.districts');

Route::get('/api-wilayah/villages/{districtId}', function ($districtId) use ($fetchWilayah) {
    abort_unless(preg_match('/^\d+$/', (string) $districtId), 422);

    return $fetchWilayah(
        "villages/{$districtId}",
        "wilayah:villages:{$districtId}"
    );
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

    Route::get('/prospects-diajukan/{id}', ProspectSubmissionDetail::class)
        ->whereNumber('id')
        ->middleware('role:AO')
        ->name('prospects.submissions.show');

    // ===== SIMULASI KREDIT =====
    Route::get('/simulasi-kredit', SimulasiKreditIndex::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('simulasi-kredit.index');

    // ===== NOMINATIF KREDIT =====
    Route::get('/nominatif-kredit', NominatifKreditIndex::class)
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR')
        ->name('nominatif-kredit.index');

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

    // ===== AI CHAT =====
    Route::get('/ai-chat', [AiChatController::class, 'index'])
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('ai.chat.index');

    Route::post('/ai-chat/ask', [AiChatController::class, 'ask'])
        ->middleware('role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,AO_KREDIT,AO_DANA,AO_REMEDIAL,PEGAWAI')
        ->name('ai.chat.ask');
});
