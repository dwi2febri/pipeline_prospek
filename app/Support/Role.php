<?php

namespace App\Support;

class Role
{
    public static function isAdmin($u): bool
    {
        return $u && strtoupper(trim((string)($u->role ?? ''))) === 'ADMIN';
    }

    public static function isCabang($u): bool
    {
        return $u && strtoupper(trim((string)($u->role ?? ''))) === 'CABANG';
    }

    public static function isPegawaiOrAO($u): bool
    {
        if (!$u) return false;
        $r = strtoupper(trim((string)($u->role ?? '')));
        return $r === 'PEGAWAI' || strpos($r, 'AO_') === 0; // AO_KREDIT/AO_DANA/AO_REMEDIAL
    }
}
