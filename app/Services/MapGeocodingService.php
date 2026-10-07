<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MapGeocodingService
{
    public function search(string $keyword): array
    {
        return $this->request('search', [
            'q' => trim($keyword), 'countrycodes' => 'id', 'limit' => 5,
        ]);
    }

    public function reverse(float $lat, float $lng): array
    {
        return $this->request('reverse', [
            'lat' => round($lat, 6), 'lon' => round($lng, 6),
        ]);
    }

    private function request(string $endpoint, array $parameters): array
    {
        $baseUrl = rtrim(config('services.map_geocoding.url'), '/');
        $key = 'map:geocoding:'.sha1($baseUrl.$endpoint.json_encode($parameters));

        return Cache::remember($key, now()->addDays(7), function () use ($baseUrl, $endpoint, $parameters) {
            // Serialize lookups across users, allowing at most one request per second.
            return Cache::lock('map:geocoding:lock', 15)->block(3, function () use ($baseUrl, $endpoint, $parameters) {
                $remaining = 1 - (microtime(true) - (float) Cache::get('map:geocoding:last-request', 0));
                if ($remaining > 0) {
                    usleep((int) ceil($remaining * 1_000_000));
                }
                Cache::put('map:geocoding:last-request', microtime(true), 60);
                $payload = Http::acceptJson()
                    ->withUserAgent('PipelineProspek/1.0 ('.config('app.url').')')
                    ->connectTimeout(3)->timeout(8)
                    ->get($baseUrl.'/'.$endpoint, array_merge($parameters, [
                        'format' => 'jsonv2', 'accept-language' => 'id',
                    ]))->throw()->json();
                if (!is_array($payload) || isset($payload['error'])) {
                    throw new \RuntimeException('Data alamat tidak tersedia.');
                }

                return $payload;
            });
        });
    }
}
