<?php

namespace App\Http\Controllers;

use App\Ai\Agents\EProspekAssistantAgent;
use App\Models\Cabang;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    public function index()
    {
        return response()->view('layouts.bootstrap', [
            'slot' => new HtmlString(view('ai.chat')->render()),
        ]);
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'conversation_id' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $message = trim((string) $request->message);
        $conversationId = trim((string) $request->conversation_id);

        try {
            $isAppQuestion = $this->isAppRelatedQuestion($message);

            if ($isAppQuestion) {
                $appContext = $this->buildAppContext($message, $user);

                $finalPrompt = <<<TXT
Anda adalah AI Assistant untuk aplikasi E-Prospek.

Jawab dengan bahasa Indonesia yang jelas, rapi, dan TANPA format markdown.
Jangan gunakan tanda seperti ##, **, *, -, atau nomor markdown.
Gunakan kalimat biasa atau poin sederhana tanpa simbol markdown.

PERTANYAAN USER:
{$message}

KONTEKS DATA APLIKASI E-PROSPEK:
{$appContext}

ATURAN:
- Prioritaskan konteks data aplikasi di atas.
- Jika data aplikasi tidak cukup, katakan dengan jujur bahwa data aplikasi yang tersedia belum cukup.
- Jangan mengarang angka, nama, status, atau cabang yang tidak ada pada konteks.
- Jika diminta ringkasan atau analisa, jawab singkat, jelas, dan langsung ke inti.
TXT;
            } else {
                $finalPrompt = <<<TXT
Anda adalah asisten AI umum.

Jawab pertanyaan user secara umum dengan bahasa Indonesia yang jelas, natural, dan mudah dipahami.
JANGAN kaitkan jawaban dengan aplikasi E-Prospek.
JANGAN gunakan format markdown seperti ##, **, *, atau bullet markdown.
Berikan jawaban polos/plain text yang rapi.

PERTANYAAN USER:
{$message}
TXT;
            }

            $agent = new EProspekAssistantAgent();

            if ($conversationId !== '') {
                $response = $agent
                    ->continue($conversationId, as: $user)
                    ->prompt($finalPrompt);
            } else {
                $response = $agent
                    ->forUser($user)
                    ->prompt($finalPrompt);
            }

            $answer = $this->sanitizeAnswer((string) $response);

            return response()->json([
                'ok' => true,
                'conversation_id' => $response->conversationId ?? null,
                'answer' => $answer,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Maaf, proses AI gagal. ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function isAppRelatedQuestion(string $message): bool
    {
        $text = Str::lower(trim($message));

        $keywords = [
            'e-prospek',
            'prospek',
            'pengajuan',
            'follow up',
            'closing',
            'rejected',
            'open',
            'cabang',
            'kc',
            'kanwil',
            'dashboard',
            'nasabah',
            'ao',
            'pegawai',
            'rekap',
            'simulasi kredit',
            'status prospek',
            'jumlah prospek',
            'jumlah pengajuan',
            'data prospek',
            'aplikasi ini',
            'di aplikasi',
            'di sistem',
        ];

        foreach ($keywords as $keyword) {
            if (Str::contains($text, $keyword)) {
                return true;
            }
        }

        return false;
    }

    protected function sanitizeAnswer(string $answer): string
    {
        $answer = str_replace(["\r\n", "\r"], "\n", $answer);

        // hapus heading markdown
        $answer = preg_replace('/^\s{0,3}#{1,6}\s*/m', '', $answer);

        // hapus bullet markdown di awal baris
        $answer = preg_replace('/^\s*[-*+]\s+/m', '', $answer);

        // hapus numbering markdown sederhana
        $answer = preg_replace('/^\s*\d+\.\s+/m', '', $answer);

        // hapus bold / italic markdown
        $answer = str_replace(['**', '__', '*', '`'], '', $answer);

        // rapikan baris kosong berlebih
        $answer = preg_replace("/\n{3,}/", "\n\n", $answer);

        return trim($answer);
    }

    protected function buildAppContext(string $question, $user): string
    {
        $role = strtoupper(trim((string) ($user->role ?? '')));
        $baseQuery = $this->visibleProspectsQuery($user);

        $summary = (clone $baseQuery)
            ->selectRaw("
                COUNT(prospects.id) as total,
                SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as total_open,
                SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as total_follow,
                SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as total_closing,
                SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as total_rejected
            ")
            ->first();

        $recentProspects = (clone $baseQuery)
            ->orderByDesc('prospects.tanggal_prospek')
            ->orderByDesc('prospects.id')
            ->limit(10)
            ->get([
                'prospects.tanggal_prospek',
                'prospects.nama',
                'prospects.no_hp',
                'prospects.jenis_produk',
                'prospects.jenis_usaha',
                'prospects.status',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
            ]);

        $keyword = Str::lower($question);
        $needCabang = Str::contains($keyword, ['cabang', 'kc', 'kanwil', 'rekap']);
        $perCabang = collect();

        if ($needCabang || in_array($role, ['ADMIN', 'MANAJEMEN', 'MANAJEMEN KANWIL', 'SUPERVISOR'], true)) {
            $perCabang = (clone $baseQuery)
                ->groupBy('cabangs.kode_cabang', 'cabangs.nama_cabang')
                ->orderBy('cabangs.kode_cabang')
                ->limit(15)
                ->get([
                    'cabangs.kode_cabang',
                    'cabangs.nama_cabang',
                    DB::raw('COUNT(prospects.id) as total'),
                    DB::raw("SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as open_total"),
                    DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as follow_total"),
                    DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as closing_total"),
                    DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as rejected_total"),
                ]);
        }

        $text = [];
        $text[] = "User login: {$user->name}";
        $text[] = "Role: {$role}";
        $text[] = "Ringkasan data:";
        $text[] = "Total Pengajuan: " . (int) ($summary->total ?? 0);
        $text[] = "Open: " . (int) ($summary->total_open ?? 0);
        $text[] = "Follow Up: " . (int) ($summary->total_follow ?? 0);
        $text[] = "Closing: " . (int) ($summary->total_closing ?? 0);
        $text[] = "Rejected: " . (int) ($summary->total_rejected ?? 0);

        if ($perCabang->isNotEmpty()) {
            $text[] = "";
            $text[] = "Ringkasan per cabang:";
            foreach ($perCabang as $row) {
                $text[] = "{$row->kode_cabang} - {$row->nama_cabang}: total {$row->total}, open {$row->open_total}, follow up {$row->follow_total}, closing {$row->closing_total}, rejected {$row->rejected_total}";
            }
        }

        if ($recentProspects->isNotEmpty()) {
            $text[] = "";
            $text[] = "Prospek terbaru:";
            foreach ($recentProspects as $row) {
                $tgl = $row->tanggal_prospek ? date('d/m/Y', strtotime($row->tanggal_prospek)) : '-';
                $text[] = "{$tgl} | {$row->nama} | {$row->jenis_produk} | {$row->status} | {$row->kode_cabang} - {$row->nama_cabang} | HP: " . ($row->no_hp ?: '-');
            }
        }

        return implode("\n", $text);
    }

    protected function visibleProspectsQuery($user)
    {
        $role = strtoupper(trim((string) ($user->role ?? '')));

        $query = Prospect::query()
            ->leftJoin('cabangs', 'cabangs.id', '=', 'prospects.cabang_id')
            ->whereNull('prospects.deleted_at');

        if (in_array($role, ['PEGAWAI', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'], true)) {
            return $query->where('prospects.input_by', $user->id);
        }

        if ($role === 'SUPERVISOR') {
            return $query->where('prospects.cabang_id', $user->cabang_id);
        }

        if ($role === 'MANAJEMEN KANWIL') {
            $kodeCabang = optional(Cabang::find($user->cabang_id))->kode_cabang;

            if ($kodeCabang === '100') {
                $ids = $this->idsByKodeRange(1, 7);
                if (!empty($ids)) $query->whereIn('prospects.cabang_id', $ids);
            } elseif ($kodeCabang === '200') {
                $ids = $this->idsByKodeRange(8, 14);
                if (!empty($ids)) $query->whereIn('prospects.cabang_id', $ids);
            } elseif ($kodeCabang === '300') {
                $ids = $this->idsByKodeRange(15, 21);
                if (!empty($ids)) $query->whereIn('prospects.cabang_id', $ids);
            } elseif ($kodeCabang === '400') {
                $ids = $this->idsByKodeRange(22, 28);
                if (!empty($ids)) $query->whereIn('prospects.cabang_id', $ids);
            }

            return $query;
        }

        return $query;
    }

    protected function idsByKodeRange(int $start, int $end): array
    {
        return Cabang::query()
            ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN {$start} AND {$end}")
            ->pluck('id')
            ->toArray();
    }
}
