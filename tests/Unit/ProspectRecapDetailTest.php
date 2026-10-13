<?php

use App\Livewire\Reports\ProspectRecap;
use App\Models\Prospect;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class);

function recapDetailComponent(): ProspectRecap
{
    return new class extends ProspectRecap
    {
        public function detailItems(): Collection
        {
            return $this->getDetailPegawaiQuery()->get();
        }

        public function amounts(Collection $items): array
        {
            return $this->getDetailNominatif($items);
        }
    };
}

beforeEach(function () {
    config()->set('database.default', 'sqlite');
    config()->set('database.connections.sqlite.database', ':memory:');
    config()->set('database.connections.dpk', ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']);
    DB::purge('sqlite');
    DB::purge('dpk');

    Schema::create('prospects', function (Blueprint $table) {
        $table->id();
        $table->integer('input_by');
        $table->date('tanggal_prospek');
        $table->string('nama')->default('Nasabah');
        $table->string('jenis_produk');
        $table->string('jenis_usaha')->default('LAINNYA');
        $table->string('status')->default('CLOSING');
        $table->string('no_rekening')->nullable();
        $table->softDeletes();
    });
});

afterEach(function () {
    DB::purge('dpk');
    DB::purge('sqlite');
});

test('employee product tabs preserve the employee and date scope', function (string $tab, array $expected) {
    DB::table('prospects')->insert([
        ['id' => 1, 'input_by' => 10, 'tanggal_prospek' => '2026-10-01', 'jenis_produk' => 'KREDIT'],
        ['id' => 2, 'input_by' => 10, 'tanggal_prospek' => '2026-10-02', 'jenis_produk' => 'DEPOSITO'],
        ['id' => 3, 'input_by' => 10, 'tanggal_prospek' => '2026-10-03', 'jenis_produk' => 'TABUNGAN'],
        ['id' => 4, 'input_by' => 11, 'tanggal_prospek' => '2026-10-02', 'jenis_produk' => 'KREDIT'],
        ['id' => 5, 'input_by' => 10, 'tanggal_prospek' => '2026-09-02', 'jenis_produk' => 'TABUNGAN'],
        ['id' => 6, 'input_by' => 10, 'tanggal_prospek' => '2026-10-04', 'jenis_produk' => 'DEPOSITO'],
    ]);
    DB::table('prospects')->where('id', 6)->update(['deleted_at' => '2026-10-05 00:00:00']);

    $component = recapDetailComponent();
    $component->detailPegawaiId = 10;
    $component->detailFilterMode = 'range';
    $component->detailFilterTanggalAwal = '2026-10-01';
    $component->detailFilterTanggalAkhir = '2026-10-31';
    $component->setDetailProductTab($tab);

    expect($component->detailItems()->pluck('id')->all())->toBe($expected);
})->with([
    'all products' => ['all', [3, 2, 1]],
    'credit only' => ['kredit', [1]],
    'savings and deposits' => ['dana', [3, 2]],
]);

test('opening another employee resets the product tab and inherits the date filter', function () {
    $component = recapDetailComponent();
    $component->setDetailProductTab('dana');
    $component->setDetailProductTab('invalid');
    expect($component->detailProductTab)->toBe('dana');

    $component->filterMode = 'monthly';
    $component->filterBulan = '10';
    $component->filterTahun = '2026';
    $component->openDetailPegawai(11);

    expect($component->detailProductTab)->toBe('all')
        ->and($component->detailPegawaiId)->toBe(11)
        ->and($component->detailFilterMode)->toBe('monthly')
        ->and($component->detailFilterBulan)->toBe('10')
        ->and($component->detailFilterTahun)->toBe('2026');
});

test('names and realization come from product nominatif with explicit account matching results', function () {
    $nameColumns = ['nominatif' => 'nama_nasabah', 'nominatif_deposito' => 'nama', 'nominatif_tabungan' => 'nama_pemilik'];
    foreach (['nominatif' => 'jml_pinjaman', 'nominatif_deposito' => 'saldo_akhir', 'nominatif_tabungan' => 'saldo'] as $table => $column) {
        Schema::connection('dpk')->create($table, function (Blueprint $blueprint) use ($column, $table, $nameColumns) {
            $blueprint->string('no_rekening');
            $blueprint->decimal($column, 18, 2);
            $blueprint->string($nameColumns[$table]);
        });
    }
    DB::connection('dpk')->table('nominatif')->insert(['no_rekening' => '001', 'jml_pinjaman' => 15000000, 'nama_nasabah' => 'NAMA KREDIT']);
    DB::connection('dpk')->table('nominatif_deposito')->insert(['no_rekening' => '001', 'saldo_akhir' => 5000000, 'nama' => 'NAMA DEPOSITO']);
    DB::connection('dpk')->table('nominatif_tabungan')->insert(['no_rekening' => '001', 'saldo' => 0, 'nama_pemilik' => 'NAMA TABUNGAN']);

    $items = collect([
        [1, 'KREDIT', 'CLOSING', '001'],
        [2, 'DEPOSITO', 'CLOSING', '001'],
        [3, 'TABUNGAN', 'CLOSING', '001'],
        [4, 'KREDIT', 'CLOSING', '002'],
        [5, 'KREDIT', 'FOLLOW UP', '001'],
        [6, 'KREDIT', 'REJECTED', '001'],
        [7, 'KREDIT', 'OPEN', ''],
        [8, 'TABUNGAN', 'CLOSING', '1'],
    ])->map(function ($values) {
        return (new Prospect(['nama' => 'Nama dari eprospek']))->forceFill(array_combine(['id', 'jenis_produk', 'status', 'no_rekening'], $values));
    });

    [$amounts, $unavailable] = recapDetailComponent()->amounts($items);

    expect($unavailable)->toBeFalse()
        ->and($amounts[1])->toMatchArray(['nama' => 'NAMA KREDIT', 'nominal' => 15000000.0, 'status' => 'matched'])
        ->and($amounts[2])->toMatchArray(['nama' => 'NAMA DEPOSITO', 'nominal' => 5000000.0, 'status' => 'matched'])
        ->and($amounts[3])->toMatchArray(['nama' => 'NAMA TABUNGAN', 'nominal' => 0.0, 'status' => 'matched'])
        ->and($amounts[4])->toMatchArray(['nama' => '', 'nominal' => null, 'status' => 'unmatched'])
        ->and($amounts[4]['keterangan'])->toContain('tidak ditemukan')
        ->and($amounts[5])->toMatchArray(['nama' => 'NAMA KREDIT', 'nominal' => null, 'status' => 'matched'])
        ->and($amounts[6]['nominal'])->toBeNull()
        ->and($amounts[7])->toMatchArray(['nama' => '', 'nominal' => null, 'status' => 'missing_account'])
        ->and($amounts[8]['status'])->toBe('unmatched');
});

test('a missing nominatif source keeps the detail usable and signals unavailable amounts', function () {
    $prospect = (new Prospect)->forceFill(['id' => 1, 'jenis_produk' => 'KREDIT', 'status' => 'CLOSING', 'no_rekening' => '001']);

    [$amounts, $unavailable] = recapDetailComponent()->amounts(collect([$prospect]));

    expect($amounts[1])->toMatchArray(['nama' => '', 'nominal' => null, 'status' => 'unavailable'])
        ->and($amounts[1]['keterangan'])->toContain('belum dapat dicocokkan')
        ->and($unavailable)->toBeTrue();
});

test('nominatif with no name column never falls back to the prospect name', function () {
    Schema::connection('dpk')->create('nominatif', function (Blueprint $table) {
        $table->string('no_rekening');
        $table->decimal('jml_pinjaman', 18, 2);
    });
    DB::connection('dpk')->table('nominatif')->insert(['no_rekening' => '001', 'jml_pinjaman' => 120000]);
    $prospect = (new Prospect(['nama' => 'Nama eprospek']))->forceFill(['id' => 1, 'jenis_produk' => 'KREDIT', 'status' => 'CLOSING', 'no_rekening' => '001']);

    [$details, $unavailable] = recapDetailComponent()->amounts(collect([$prospect]));

    expect($unavailable)->toBeFalse()
        ->and($details[1])->toMatchArray(['nama' => '', 'nominal' => 120000.0, 'status' => 'matched'])
        ->and($details[1]['keterangan'])->toBe('Nama nasabah belum tersedia di nominatif.');
});

test('employee modal renders nominatif names and the unmatched account explanation', function () {
    $component = recapDetailComponent();
    $component->activeTab = 'pegawai';
    $prospect = (new Prospect(['nama' => 'NAMA LAMA EPROSPEK']))->forceFill([
        'id' => 1, 'tanggal_prospek' => '2026-10-13', 'jenis_produk' => 'KREDIT',
        'status' => 'CLOSING', 'no_rekening' => '001', 'jenis_usaha' => 'LAINNYA',
    ]);
    $data = array_merge(get_object_vars($component), [
        'items' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
        'cabangs' => collect(), 'bulanOptions' => [], 'tahunOptions' => [],
        'detailPegawai' => (object) ['name' => 'Pegawai uji'],
        'detailItems' => collect([$prospect]), 'detailKcCabang' => null,
        'detailRealisasiUnavailable' => false, 'detailUnmatchedCount' => 0,
        'detailNominatif' => [1 => ['nama' => 'NAMA NOMINATIF', 'nominal' => 15000000, 'status' => 'matched', 'keterangan' => '-']],
    ]);

    $html = view('livewire.reports.prospect-recap', $data)->render();
    expect($html)->toContain('NAMA NOMINATIF', 'Rp 15.000.000', 'No. Rekening')
        ->not->toContain('NAMA LAMA EPROSPEK');

    $data['detailUnmatchedCount'] = 1;
    $data['detailNominatif'][1] = ['nama' => '', 'nominal' => null, 'status' => 'unmatched', 'keterangan' => 'Rekening e-Prospek tidak ditemukan di nominatif kredit.'];
    $html = view('livewire.reports.prospect-recap', $data)->render();
    expect($html)->toContain('Rekening e-Prospek tidak ditemukan di nominatif kredit.', '1 pengajuan', '001')
        ->not->toContain('NAMA LAMA EPROSPEK', 'Rp 15.000.000');
});
