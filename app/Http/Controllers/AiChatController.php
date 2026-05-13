<?php

namespace App\Http\Controllers;

use App\Ai\Agents\EProspekAssistantAgent;
use App\Models\Cabang;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    public function index()
    {
        return view('ai.chat');
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

        $appContext = $this->buildAppContext($message, $user);

        $finalPrompt = <<<TXT
PERTANYAAN USER:
{$message}

KONTEKS DATA APLIKASI E-PROSPEK:
{$appContext}

INSTRUKSI TAMBAHAN:
- Jika pertanyaan berkaitan dengan aplikasi, prioritaskan konteks data aplikasi di atas.
- Jika pertanyaan umum di luar aplikasi, jawab secara umum dengan jelas.
- Jika user meminta data real-time internet, jelaskan bahwa mode gratis lokal ini tidak menggunakan pencarian web real-time.
TXT;

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

        return response()->json([
            'ok' => true,
            'conversation_id' => $response->conversationId ?? null,
            'answer' => (string) $response,
        ]);
    }

    protected function buildAppContext(string $question, $user): string
    {
        $role = strtoupper(trim((string) ($user->role ?? '')));
        $baseQuery = $this->visibleProspectsQuery($user);

        $total = (clone $baseQuery)->count();
        $open = (clone $baseQuery)->where('prospects.status', 'OPEN')->count();
        $follow = (clone $baseQuery)->where('prospects.status', 'FOLLOW UP')->count();
        $closing = (clone $baseQuery)->where('prospects.status', 'CLOSING')->count();
        $rejected = (clone $baseQuery)->where('prospects.status', 'REJECTED')->count();

        $recentProspects = (clone $baseQuery)
            ->orderByDesc('prospects.tanggal_prospek')
            ->orderByDesc('prospects.id')
            ->limit(20)
            ->get([
                'prospects.id',
                'prospects.tanggal_prospek',
                'prospects.nama',
                'prospects.no_hp',
                'prospects.jenis_produk',
                'prospects.jenis_usaha',
                'prospects.status',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
            ]);

        $perCabang = (clone $baseQuery)
            ->groupBy('cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->orderBy('cabangs.kode_cabang')
            ->get([
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
                \DB::raw('COUNT(prospects.id) as total'),
                \DB::raw("SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as open_total"),
                \DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as follow_total"),
                \DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as closing_total"),
                \DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as rejected_total"),
            ]);

        $keywordApp = Str::lower($question);
        $isAskingCabang = Str::contains($keywordApp, ['cabang', 'kc', 'kanwil']);
        $isAskingStatus = Str::contains($keywordApp, ['status', 'open', 'follow up', 'closing', 'rejected', 'prospek']);
        $isAskingNasabah = Str::contains($keywordApp, ['nasabah', 'prospek', 'nama', 'hp', 'telepon']);

        $text = [];
        $text[] = "User login: {$user->name} | Role: {$role}";
        $text[] = "Ringkasan data terlihat user:";
        $text[] = "- Total Pengajuan: {$total}";
        $text[] = "- Open: {$open}";
        $text[] = "- Follow Up: {$follow}";
        $text[] = "- Closing: {$closing}";
        $text[] = "- Rejected: {$rejected}";

        if ($isAskingCabang || $role === 'ADMIN' || $role === 'MANAJEMEN' || $role === 'MANAJEMEN KANWIL' || $role === 'SUPERVISOR') {
            $text[] = "";
            $text[] = "Ringkasan per cabang:";
            foreach ($perCabang as $row) {
                $text[] = "- {$row->kode_cabang} - {$row->nama_cabang}: total {$row->total}, open {$row->open_total}, follow up {$row->follow_total}, closing {$row->closing_total}, rejected {$row->rejected_total}";
            }
        }

        if ($isAskingStatus || $isAskingNasabah || $total <= 50) {
            $text[] = "";
            $text[] = "Data prospek terbaru yang terlihat user:";
            foreach ($recentProspects as $row) {
                $tgl = $row->tanggal_prospek ? date('d/m/Y', strtotime($row->tanggal_prospek)) : '-';
                $text[] = "- {$tgl} | {$row->nama} | {$row->jenis_produk} | {$row->status} | {$row->kode_cabang} - {$row->nama_cabang} | HP: " . ($row->no_hp ?: '-');
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
            $query->where('prospects.input_by', $user->id);
            return $query;
        }

        if ($role === 'SUPERVISOR') {
            $query->where('prospects.cabang_id', $user->cabang_id);
            return $query;
        }

        if ($role === 'MANAJEMEN KANWIL') {
            $kodeCabang = optional(Cabang::find($user->cabang_id))->kode_cabang;

            if ($kodeCabang === '100') {
                $query->whereBetween('prospects.cabang_id', $this->idsByKodeRange(1, 7));
            } elseif ($kodeCabang === '200') {
                $query->whereBetween('prospects.cabang_id', $this->idsByKodeRange(8, 14));
            } elseif ($kodeCabang === '300') {
                $query->whereBetween('prospects.cabang_id', $this->idsByKodeRange(15, 21));
            } elseif ($kodeCabang === '400') {
                $query->whereBetween('prospects.cabang_id', $this->idsByKodeRange(22, 28));
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
            ->all();
    }
}
