<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Prospect;
use App\Models\ProspectDocument;
use App\Support\Role;

class ProspectDocumentController extends Controller
{
    private function allowedProspect(Request $r, $id): Prospect
    {
        $u = $r->user();
        $q = Prospect::query();

        if (Role::isCabang($u)) {
            $q->where('cabang_id', (int)$u->cabang_id);
        } elseif (Role::isPegawaiOrAO($u)) {
            $q->where('input_by', (int)$u->id);
        }

        return $q->where('id', (int)$id)->firstOrFail();
    }

    public function index(Request $r, $id)
    {
        $p = $this->allowedProspect($r, $id);

        $items = ProspectDocument::where('prospect_id', (int)$p->id)
            ->latest('id')
            ->get()
            ->map(function($d){
                return [
                    'id' => $d->id,
                    'prospect_id' => $d->prospect_id,
                    'file_path' => $d->file_path,
                    'file_type' => $d->file_type,
                    'url' => $d->file_path ? asset('storage/'.$d->file_path) : null,
                    'uploaded_by' => $d->uploaded_by,
                    'created_at' => $d->created_at,
                ];
            });

        return response()->json(['ok'=>true,'items'=>$items]);
    }

    public function store(Request $r, $id)
    {
        $p = $this->allowedProspect($r, $id);

        $r->validate([
            'files' => ['required'],
            'files.*' => ['file','mimes:jpg,jpeg,png,webp','max:5120'], // 5MB
            'file_type' => ['nullable','string','max:30'],
        ]);

        $type = $r->input('file_type', 'foto');
        $saved = [];

        $files = $r->file('files');
        if (!is_array($files)) $files = [$files];

        foreach ($files as $f) {
            $ext = strtolower($f->getClientOriginalExtension() ?: 'jpg');
            $name = 'p'.$p->id.'_'.date('Ymd_His').'_'.substr(md5(uniqid('', true)),0,8).'.'.$ext;
            $path = $f->storeAs('prospects/'.$p->id, $name, 'public'); // storage/app/public/...

            $doc = new ProspectDocument();
            $doc->prospect_id = (int)$p->id;
            $doc->file_path = $path;
            $doc->file_type = $type;
            $doc->uploaded_by = (int)$r->user()->id;
            $doc->save();

            $saved[] = [
                'id' => $doc->id,
                'file_path' => $doc->file_path,
                'file_type' => $doc->file_type,
                'url' => asset('storage/'.$doc->file_path),
            ];
        }

        return response()->json(['ok'=>true,'items'=>$saved], 201);
    }

    public function destroy(Request $r, $docId)
    {
        $doc = ProspectDocument::findOrFail((int)$docId);

        // cek akses via prospect
        $this->allowedProspect($r, $doc->prospect_id);

        if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }
        $doc->delete();

        return response()->json(['ok'=>true]);
    }
}
