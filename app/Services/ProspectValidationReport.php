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

    public function supportsPdf(): bool
    {
        return class_exists(Dompdf::class) && class_exists(Options::class);
    }

    public function pageSettings(string $paper = 'A4', string $orientation = 'landscape'): array
    {
        $paper = in_array($paper, ['A4', 'F4'], true) ? $paper : 'A4';
        $orientation = in_array($orientation, ['portrait', 'landscape'], true) ? $orientation : 'landscape';
        $width = 210;
        $height = $paper === 'F4' ? 330 : 297;
        if ($orientation === 'landscape') {
            [$width, $height] = [$height, $width];
        }

        return ['paper' => $paper, 'orientation' => $orientation, 'widthMm' => $width, 'heightMm' => $height];
    }

    public function pdf(array $data): string
    {
        $page = $this->pageSettings($data['page']['paper'] ?? 'A4', $data['page']['orientation'] ?? 'landscape');
        $data['page'] = $page;
        $options = new Options;
        $options->set('isRemoteEnabled', false);
        $options->set('isPhpEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('defaultMediaType', 'print');
        $options->set('tempDir', storage_path('framework/cache'));
        $dompdf = new Dompdf($options);
        $dompdf->setPaper([0, 0, $page['widthMm'] * 72 / 25.4, $page['heightMm'] * 72 / 25.4]);
        $dompdf->loadHtml(view('reports.prospect-validation-pdf', $data)->render(), 'UTF-8');
        $dompdf->render();
        $canvas = $dompdf->getCanvas();
        $footerY = $canvas->get_height() - 22;
        $canvas->page_text(28.35, $footerY, 'E-Prospek | Surat Validasi Pengajuan Closing', null, 7, [0.4, 0.45, 0.5]);
        $canvas->page_text($canvas->get_width() - 125, $footerY, 'Halaman {PAGE_NUM} / {PAGE_COUNT}', null, 7, [0.4, 0.45, 0.5]);

        return $dompdf->output();
    }
}
