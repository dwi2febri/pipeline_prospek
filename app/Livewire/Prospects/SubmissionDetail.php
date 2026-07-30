<?php

namespace App\Livewire\Prospects;

use App\Models\Prospect;
use App\Models\ProspectNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class SubmissionDetail extends Component
{
    public int $id;
    public Prospect $prospect;
    public string $statusUpdate = '';
    public ?string $noRekening = null;
    public ?string $estimasiNominalRealisasi = null;
    public ?string $takenByFullName = null;

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->loadAuthorizedProspect();
    }

    public function saveEstimate(): void
    {
        $this->authorizeProspect($this->prospect);

        $this->estimasiNominalRealisasi = preg_replace(
            '/[^0-9]/',
            '',
            (string) $this->estimasiNominalRealisasi
        );

        $this->validate([
            'estimasiNominalRealisasi' => ['required', 'integer', 'min:1', 'digits_between:1,18'],
        ], [
            'estimasiNominalRealisasi.required' => 'Estimasi Nominal Realisasi wajib diisi.',
            'estimasiNominalRealisasi.integer' => 'Estimasi Nominal Realisasi harus berupa angka.',
            'estimasiNominalRealisasi.min' => 'Estimasi Nominal Realisasi harus lebih dari 0.',
            'estimasiNominalRealisasi.digits_between' => 'Estimasi Nominal Realisasi maksimal 18 digit.',
        ]);

        DB::transaction(function (): void {
            $prospect = Prospect::query()->lockForUpdate()->findOrFail($this->id);
            $this->authorizeProspect($prospect);

            if (!$this->isCredit($prospect)) {
                throw ValidationException::withMessages([
                    'estimasiNominalRealisasi' => 'Estimasi hanya berlaku untuk produk Kredit.',
                ]);
            }

            if (!$this->isOpenOrFollowUp($prospect)) {
                throw ValidationException::withMessages([
                    'estimasiNominalRealisasi' => 'Status prospek sudah selesai.',
                ]);
            }

            if (filled($prospect->estimasi_nominal_realisasi)) {
                throw ValidationException::withMessages([
                    'estimasiNominalRealisasi' => 'Estimasi sudah pernah disimpan.',
                ]);
            }

            $prospect->estimasi_nominal_realisasi = (int) $this->estimasiNominalRealisasi;
            $prospect->save();
        });

        $this->loadAuthorizedProspect();
        session()->flash('success', 'Estimasi realisasi berhasil disimpan. Silakan pilih status akhir.');
    }

    public function updateStatus(): void
    {
        $this->authorizeProspect($this->prospect);

        $rules = [
            'statusUpdate' => ['required', 'in:CLOSING,REJECTED'],
        ];

        if ($this->statusUpdate === 'CLOSING') {
            $rules['noRekening'] = ['required', 'regex:/^[0-9]+$/', 'max:50'];
        }

        $this->validate($rules, [
            'statusUpdate.required' => 'Pilih status tindak lanjut.',
            'statusUpdate.in' => 'Status yang dipilih tidak valid.',
            'noRekening.required' => 'Nomor rekening wajib diisi untuk status Closing.',
            'noRekening.regex' => 'Nomor rekening hanya boleh berisi angka.',
        ]);

        DB::transaction(function (): void {
            $prospect = Prospect::query()->lockForUpdate()->findOrFail($this->id);
            $this->authorizeProspect($prospect);
            if (!$this->isOpenOrFollowUp($prospect)) {
                throw ValidationException::withMessages([
                    'statusUpdate' => 'Status akhir prospek sudah ditetapkan.',
                ]);
            }

            if ($this->isCredit($prospect) && !filled($prospect->estimasi_nominal_realisasi)) {
                throw ValidationException::withMessages([
                    'statusUpdate' => 'Simpan estimasi realisasi terlebih dahulu.',
                ]);
            }

            $oldStatus = strtoupper(trim((string) $prospect->status));
            $newStatus = $this->statusUpdate;

            $prospect->status = $newStatus;
            $prospect->is_diambil = 1;
            $prospect->diambil_oleh = (string) auth()->user()->name;

            if ($newStatus === 'CLOSING' && Schema::hasColumn('prospects', 'no_rekening')) {
                $prospect->no_rekening = $this->noRekening;
            }

            $prospect->save();

            if ($oldStatus !== $newStatus) {
                $this->notifySubmitter($prospect, $newStatus);
            }
        });

        $this->loadAuthorizedProspect();
        session()->flash('success', 'Status prospek berhasil diperbarui.');
    }

    protected function loadAuthorizedProspect(): void
    {
        $prospect = Prospect::with(['cabang', 'creator', 'creator.cabang', 'documents'])
            ->findOrFail($this->id);

        $this->authorizeProspect($prospect);

        $this->prospect = $prospect;
        $this->statusUpdate = in_array((string) $prospect->status, ['CLOSING', 'REJECTED'], true)
            ? (string) $prospect->status
            : '';
        $this->noRekening = Schema::hasColumn('prospects', 'no_rekening')
            ? ($prospect->no_rekening ?? null)
            : null;
        $this->estimasiNominalRealisasi = filled($prospect->estimasi_nominal_realisasi)
            ? number_format((int) $prospect->estimasi_nominal_realisasi, 0, ',', '.')
            : null;
        $this->takenByFullName = User::query()
            ->where('name', $prospect->diambil_oleh)
            ->value('nama_lengkap') ?: $prospect->diambil_oleh;
    }

    protected function authorizeProspect(Prospect $prospect): void
    {
        $user = auth()->user();
        $role = strtoupper(trim((string) ($user->role ?? '')));

        abort_unless($role === 'AO', 403);
        abort_unless((string) $prospect->diambil_oleh === (string) $user->name, 403);

        $allowedProducts = $this->allowedProductsFor($user);
        abort_if(
            $allowedProducts !== [] && !in_array((string) $prospect->jenis_produk, $allowedProducts, true),
            403
        );
    }

    protected function allowedProductsFor($user): array
    {
        $position = strtoupper(trim((string) ($user->job_position ?? $user->job_posisition ?? '')));

        return match ($position) {
            'AO DANA' => ['TABUNGAN', 'DEPOSITO'],
            'AO KREDIT' => ['KREDIT'],
            'AO REMIDIAL', 'AO REMEDIAL' => ['ASET'],
            default => [],
        };
    }

    protected function isCredit(Prospect $prospect): bool
    {
        return strtoupper(trim((string) $prospect->jenis_produk)) === 'KREDIT';
    }

    protected function isOpenOrFollowUp(Prospect $prospect): bool
    {
        return in_array(
            strtoupper(trim((string) $prospect->status)),
            ['OPEN', 'FOLLOW UP'],
            true
        );
    }

    protected function notifySubmitter(Prospect $prospect, string $status): void
    {
        if (empty($prospect->input_by)) {
            return;
        }

        $label = match ($status) {
            'CLOSING' => 'Closing',
            'FOLLOW UP' => 'Follow Up',
            default => 'Rejected',
        };

        ProspectNotification::query()
            ->where('user_id', (int) $prospect->input_by)
            ->where('prospect_id', $prospect->id)
            ->where('status', $status)
            ->whereNull('read_at')
            ->delete();

        ProspectNotification::create([
            'user_id' => (int) $prospect->input_by,
            'prospect_id' => $prospect->id,
            'title' => 'Status prospek diperbarui',
            'message' => 'Prospek "' . ($prospect->nama ?: '-') . '" diubah menjadi ' . $label . '.',
            'status' => $status,
            'read_at' => null,
        ]);
    }

    public function render()
    {
        return view('livewire.prospects.submission-detail')
            ->layout('layouts.bootstrap');
    }
}
