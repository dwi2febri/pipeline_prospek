<?php

use App\Livewire\NominatifKredit\Index;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses(Tests\TestCase::class);

function nominatifReportComponentForTest(): Index
{
    return new class extends Index
    {
        public function scopedQueryForTest(bool $applyCabangFilter = true): Builder
        {
            return $this->closingProspectsQuery($applyCabangFilter);
        }
    };
}

afterEach(function () {
    Auth::forgetUser();
});

test('supervisor branch filter is locked to the authenticated branch', function () {
    Auth::setUser(new User([
        'role' => 'SUPERVISOR',
        'cabang_id' => 987654,
        'aktif' => 1,
    ]));

    $component = nominatifReportComponentForTest();
    $component->mount();

    expect($component->lockCabangFilter)->toBeTrue()
        ->and($component->filterCabang)->toBe('987654');

    $component->filterCabang = '123456';
    $component->updatedFilterCabang();

    expect($component->filterCabang)->toBe('987654');
});

test('supervisor query cannot bypass its branch through ranking mode', function () {
    Auth::setUser(new User([
        'role' => 'SUPERVISOR',
        'cabang_id' => 987654,
        'aktif' => 1,
    ]));

    $query = nominatifReportComponentForTest()->scopedQueryForTest(false);

    expect($query->toSql())->toMatch('/["`]prospects["`]\.["`]cabang_id["`] = \?/')
        ->and($query->getBindings())->toContain(987654);
});

test('supervisor without a branch fails closed', function () {
    Auth::setUser(new User([
        'role' => 'SUPERVISOR',
        'cabang_id' => null,
        'aktif' => 1,
    ]));

    $query = nominatifReportComponentForTest()->scopedQueryForTest(false);

    expect($query->toSql())->toContain('1 = 0');
});

test('unauthorized roles cannot mount the realisasi report', function (string $role) {
    Auth::setUser(new User([
        'role' => $role,
        'aktif' => 1,
    ]));

    try {
        nominatifReportComponentForTest()->mount();
        $this->fail("Role {$role} seharusnya tidak dapat membuka laporan Realisasi.");
    } catch (HttpException $exception) {
        expect($exception->getStatusCode())->toBe(403);
    }
})->with([
    'pegawai' => 'PEGAWAI',
    'ao' => 'AO',
    'ao kredit' => 'AO_KREDIT',
    'ao dana' => 'AO_DANA',
    'ao remedial' => 'AO_REMEDIAL',
]);

test('management roles can mount the realisasi report', function (string $role) {
    Auth::setUser(new User([
        'role' => $role,
        'cabang_id' => $role === 'SUPERVISOR' ? 987654 : null,
        'aktif' => 1,
    ]));

    $component = nominatifReportComponentForTest();
    $component->mount();

    expect(true)->toBeTrue();
})->with([
    'admin' => 'ADMIN',
    'manajemen' => 'MANAJEMEN',
    'manajemen kanwil' => 'MANAJEMEN KANWIL',
    'supervisor' => 'SUPERVISOR',
]);

test('realisasi route only declares the management role middleware', function () {
    $route = app('router')->getRoutes()->getByName('nominatif-kredit.index');

    expect($route)->not->toBeNull()
        ->and($route->gatherMiddleware())->toContain(
            'role:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR'
        );
});
