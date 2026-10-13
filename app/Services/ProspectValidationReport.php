<?php

namespace App\Services;

use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Collection;

class ProspectValidationReport
{
    public function summarize(Collection $items, array $nominatif): array
    {
        $summary = array_fill_keys(['all', 'kredit', 'dana'], ['nominal' => 0.0, 'noa' => 0]);
        $seen = [];
        foreach ($items as $item) {
            $record = $nominatif[$item->id] ?? [];
            $product = strtoupper(trim((string) $item->jenis_produk));
            if ($item->status !== 'CLOSING' || ($record['status'] ?? '') !== 'matched' || !isset($record['nominal'])) {
                continue;
            }
            $key = $product.'|'.trim((string) $item->no_rekening);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $group = $product === 'KREDIT' ? 'kredit' : 'dana';
            foreach (['all', $group] as $category) {
                $summary[$category]['nominal'] += $record['nominal'];
                $summary[$category]['noa']++;
            }
        }

        return $summary;
    }

    public function branchHead(int $branchId): ?User
    {
        $heads = User::query()->where('cabang_id', $branchId)->where('aktif', 1)
            ->whereIn(\Illuminate\Support\Facades\DB::raw('LOWER(TRIM(job_position))'), [
                'kepala cabang', 'plt. kepala cabang', 'plh. kepala cabang',
            ])->get();

        // Do not put an arbitrary person's name on a validation letter.
        return $heads->count() === 1 ? $heads->first() : null;
    }

    public function pdf(array $data): string
    {
        $options = new Options;
        $options->set('isRemoteEnabled', false);
        $options->set('isPhpEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('tempDir', storage_path('framework/cache'));
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml(view('reports.prospect-validation-pdf', $data)->render(), 'UTF-8');
        $dompdf->render();
        $dompdf->getCanvas()->page_text(38, 566, 'E-Prospek | Surat Validasi Pengajuan Closing', null, 8, [0.4, 0.45, 0.5]);
        $dompdf->getCanvas()->page_text(708, 566, 'Halaman {PAGE_NUM} / {PAGE_COUNT}', null, 8, [0.4, 0.45, 0.5]);

        return $dompdf->output();
    }
}
