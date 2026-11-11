<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class ContentImage
{
    public static function url(?string $path): ?string
    {
        if (empty($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
