<?php

use App\Livewire\Reports\ProspectRecap;
use App\Models\Prospect;
use App\Models\User;
use App\Services\ProspectValidationReport;
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

        public function branchItems(): Collection
        {
            return $this->getDetailKcQuery()->get();
        }

        public function letterData(string $scope): array
        {
            return $this->validationData($scope);
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
        $table->integer('cabang_id')->default(1);
        $table->string('no_hp')->nullable();
        $table->string('alamat')->nullable();
        $table->date('tanggal_prospek');
        $table->string('nama')->default('Nasabah');
        $table->string('jenis_produk');
        $table->string('jenis_usaha')->default('LAINNYA');
        $table->string('status')->default('CLOSING');
        $table->string('no_rekening')->nullable();
        $table->softDeletes();
    });
    Schema::create('cabangs', function (Blueprint $table) {
        $table->id();
        $table->string('kode_cabang');
        $table->string('nama_cabang');
    });
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('nama_lengkap')->nullable();
        $table->string('job_position')->nullable();
        $table->string('role')->default('PEGAWAI');
        $table->integer('cabang_id')->default(1);
        $table->integer('aktif')->default(1);
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
        'detailSummary' => app(ProspectValidationReport::class)->summarize(collect(), []),
        'errors' => new \Illuminate\Support\ViewErrorBag,
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

    $data['detailPegawai'] = null;
    $data['detailKcCabang'] = (object) ['kode_cabang' => '001', 'nama_cabang' => 'KC Utama'];
    $data['detailKcItems'] = $data['detailItems'];
    $data['detailKcDisplay'] = ['nominatif' => $data['detailNominatif'], 'summary' => $data['detailSummary'], 'unavailable' => false, 'unmatchedCount' => 1];
    $html = view('livewire.reports.prospect-recap', $data)->render();
    expect($html)->toContain('Detail Pengajuan Per KC', 'detailKcStatus', 'NOA', 'Cetak PDF', 'Rekening e-Prospek tidak ditemukan di nominatif kredit.');
});

test('status and product filters apply equally to employee and branch details', function (string $status) {
    foreach (['OPEN', 'FOLLOW UP', 'CLOSING', 'REJECTED'] as $index => $value) {
        DB::table('prospects')->insert(['id' => $index + 1, 'input_by' => 10, 'cabang_id' => 1, 'tanggal_prospek' => '2026-10-13', 'jenis_produk' => 'TABUNGAN', 'status' => $value]);
    }
    DB::table('prospects')->insert(['id' => 5, 'input_by' => 11, 'cabang_id' => 2, 'tanggal_prospek' => '2026-10-13', 'jenis_produk' => 'TABUNGAN', 'status' => $status]);
    DB::table('prospects')->insert(['id' => 6, 'input_by' => 10, 'cabang_id' => 1, 'tanggal_prospek' => '2026-10-13', 'jenis_produk' => 'KREDIT', 'status' => $status]);
    $component = recapDetailComponent();
    $component->detailPegawaiId = 10;
    $component->detailKcCabangId = 1;
    $component->detailProductTab = $component->detailKcProductTab = 'dana';
    $component->detailStatus = $component->detailKcStatus = $status;

    expect($component->detailItems()->pluck('status')->all())->toBe([$status])
        ->and($component->branchItems()->pluck('status')->all())->toBe([$status]);
})->with(['OPEN', 'FOLLOW UP', 'CLOSING', 'REJECTED']);

test('summary deduplicates accounts per product and counts matched zero balances as NOA', function () {
    $items = collect([
        [1, 'KREDIT', 'CLOSING', '001'], [2, 'KREDIT', 'CLOSING', '001'],
        [3, 'TABUNGAN', 'CLOSING', '001'], [4, 'DEPOSITO', 'CLOSING', '002'],
        [5, 'KREDIT', 'OPEN', '003'], [6, 'KREDIT', 'CLOSING', '004'],
    ])->map(fn ($row) => (new Prospect)->forceFill(array_combine(['id', 'jenis_produk', 'status', 'no_rekening'], $row)));
    $nominatif = [
        1 => ['nominal' => 100.0, 'status' => 'matched'], 2 => ['nominal' => 100.0, 'status' => 'matched'],
        3 => ['nominal' => 0.0, 'status' => 'matched'], 4 => ['nominal' => 200.0, 'status' => 'matched'],
        5 => ['nominal' => 999.0, 'status' => 'matched'], 6 => ['nominal' => null, 'status' => 'unmatched'],
    ];
    expect(app(ProspectValidationReport::class)->summarize($items, $nominatif))->toBe([
        'all' => ['nominal' => 300.0, 'noa' => 3], 'kredit' => ['nominal' => 100.0, 'noa' => 1], 'dana' => ['nominal' => 200.0, 'noa' => 2],
    ]);
});

function seedValidationLetter(): void
{
    DB::table('cabangs')->insert([
        ['id' => 1, 'kode_cabang' => '001', 'nama_cabang' => 'KC Utama'],
        ['id' => 2, 'kode_cabang' => '002', 'nama_cabang' => 'KC Lain'],
    ]);
    DB::table('users')->insert([
        ['id' => 10, 'name' => '102-013', 'nama_lengkap' => 'Pegawai Pengaju', 'job_position' => 'Staf', 'cabang_id' => 1, 'aktif' => 1],
        ['id' => 11, 'name' => '137-056', 'nama_lengkap' => 'KEPALA KC UTAMA', 'job_position' => 'Kepala Cabang', 'cabang_id' => 1, 'aktif' => 1],
        ['id' => 12, 'name' => '137-057', 'nama_lengkap' => 'KEPALA KC LAIN', 'job_position' => 'Kepala Cabang', 'cabang_id' => 2, 'aktif' => 1],
    ]);
    DB::table('prospects')->insert([
        ['id' => 1, 'input_by' => 10, 'cabang_id' => 1, 'tanggal_prospek' => '2026-10-13', 'jenis_produk' => 'KREDIT', 'status' => 'CLOSING'],
        ['id' => 2, 'input_by' => 10, 'cabang_id' => 1, 'tanggal_prospek' => '2026-10-13', 'jenis_produk' => 'TABUNGAN', 'status' => 'CLOSING'],
        ['id' => 3, 'input_by' => 10, 'cabang_id' => 1, 'tanggal_prospek' => '2026-10-13', 'jenis_produk' => 'KREDIT', 'status' => 'OPEN'],
        ['id' => 4, 'input_by' => 10, 'cabang_id' => 1, 'tanggal_prospek' => '2026-09-13', 'jenis_produk' => 'KREDIT', 'status' => 'CLOSING'],
        ['id' => 5, 'input_by' => 12, 'cabang_id' => 2, 'tanggal_prospek' => '2026-10-13', 'jenis_produk' => 'KREDIT', 'status' => 'CLOSING'],
    ]);
    \Illuminate\Support\Facades\Auth::setUser(new User(['role' => 'ADMIN']));
}

test('PDF data always selects Closing within product date and subject scope with the correct signer', function (string $scope, int $signerId) {
    seedValidationLetter();
    $component = recapDetailComponent();
    $component->detailPegawaiId = 10;
    $component->detailKcCabangId = 1;
    $component->detailProductTab = $component->detailKcProductTab = 'kredit';
    $component->detailStatus = $component->detailKcStatus = 'OPEN';
    $component->detailFilterMode = $component->detailKcFilterMode = 'monthly';
    $component->detailFilterBulan = $component->detailKcFilterBulan = '10';
    $component->detailFilterTahun = $component->detailKcFilterTahun = '2026';

    $data = $component->letterData($scope);
    expect($data['items']->pluck('id')->all())->toBe([1])
        ->and($data['signer']->id)->toBe($signerId);
    $html = view('reports.prospect-validation-pdf', $data)->render();
    expect($html)->toContain('SURAT VALIDASI PENGAJUAN CLOSING', $data['signer']->nama_lengkap);
    expect(app(ProspectValidationReport::class)->pdf($data))->toStartWith('%PDF-');
})->with([['pegawai', 10], ['kc', 11]]);

test('PDF rejects a supervisor accessing another branch', function () {
    seedValidationLetter();
    \Illuminate\Support\Facades\Auth::setUser(new User(['role' => 'SUPERVISOR', 'cabang_id' => 2]));
    $component = recapDetailComponent();
    $component->detailKcCabangId = 1;
    $component->letterData('kc');
})->throws(\Symfony\Component\HttpKernel\Exception\HttpException::class);

test('ambiguous or inactive branch heads are not selected arbitrarily', function () {
    seedValidationLetter();
    DB::table('users')->where('id', 11)->update(['aktif' => 0]);
    expect(app(ProspectValidationReport::class)->branchHead(1))->toBeNull();
    DB::table('users')->where('id', 11)->update(['aktif' => 1]);
    DB::table('users')->insert(['id' => 13, 'name' => 'other', 'cabang_id' => 1, 'job_position' => 'Kepala Cabang', 'aktif' => 1]);
    expect(app(ProspectValidationReport::class)->branchHead(1))->toBeNull();
});

test('PDF explains an empty Closing selection without downloading a blank letter', function () {
    seedValidationLetter();
    $component = recapDetailComponent();
    $component->detailPegawaiId = 10;
    $component->detailFilterMode = 'monthly';
    $component->detailFilterBulan = '1';
    $component->detailFilterTahun = '2020';
    expect($component->exportValidationPdf('pegawai'))->toBeNull()
        ->and($component->getErrorBag()->first('validationPdf'))->toContain('Tidak ada pengajuan Closing');
});

test('PDF does not issue a letter with an unavailable nominatif source', function () {
    seedValidationLetter();
    DB::table('prospects')->where('id', 1)->update(['no_rekening' => '001']);
    $component = recapDetailComponent();
    $component->detailPegawaiId = 10;
    expect($component->exportValidationPdf('pegawai'))->toBeNull()
        ->and($component->getErrorBag()->first('validationPdf'))->toContain('Data nominatif belum dapat dimuat');
});

test('missing Dompdf opens a printable letter for either modal instead of throwing a server error', function (string $scope, string $signer) {
    seedValidationLetter();
    $this->partialMock(ProspectValidationReport::class, function ($mock) {
        $mock->shouldReceive('supportsPdf')->once()->andReturn(false);
        $mock->shouldNotReceive('pdf');
    });
    $component = recapDetailComponent();
    $component->detailPegawaiId = 10;
    $component->detailKcCabangId = 1;

    expect($component->exportValidationPdf($scope))->toBeNull();
    $event = \Livewire\store($component)->get('dispatched')[0]->serialize();
    expect($event['name'])->toBe('print-prospect-validation')
        ->and($event['params']['scope'])->toBe($scope)
        ->and($event['params']['filename'])->toEndWith('.pdf')
        ->and($event['params']['html'])->toContain('SURAT VALIDASI PENGAJUAN CLOSING', $signer, 'size: A4 landscape')
        ->and($component->getErrorBag()->isEmpty())->toBeTrue();
})->with([['pegawai', 'Pegawai Pengaju'], ['kc', 'KEPALA KC UTAMA']]);

test('installed Dompdf still downloads the PDF directly', function () {
    seedValidationLetter();
    $component = recapDetailComponent();
    $component->detailPegawaiId = 10;
    $response = $component->exportValidationPdf('pegawai');

    expect($response)->toBeInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class)
        ->and($response->headers->get('Content-Type'))->toBe('application/pdf')
        ->and(\Livewire\store($component)->get('dispatched', []))->toBe([]);
});
