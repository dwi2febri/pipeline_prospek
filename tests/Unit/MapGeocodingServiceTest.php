<?php

use App\Services\MapGeocodingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses(Tests\TestCase::class);

beforeEach(function () {
    config()->set('cache.default', 'array');
    config()->set('services.map_geocoding.url', 'https://geocoding.example');
    Cache::flush();
});

test('location searches restrict results to Indonesia and cache repeated queries', function () {
    $results = [['lat' => '-6.6', 'lon' => '110.6', 'display_name' => 'Pantai Kartini']];
    Http::fake(['*' => Http::response($results)]);
    $service = new MapGeocodingService;

    expect($service->search(' Pantai Kartini '))->toBe($results);
    expect($service->search('Pantai Kartini'))->toBe($results);
    Http::assertSentCount(1);
    Http::assertSent(fn ($request) => $request['q'] === 'Pantai Kartini'
        && $request['countrycodes'] === 'id'
        && $request->hasHeader('User-Agent'));
});

test('reverse geocoding uses coordinates and caches the address', function () {
    $address = ['display_name' => 'Semarang'];
    Http::fake(['*' => Http::response($address)]);
    $service = new MapGeocodingService;

    expect($service->reverse(-6.9667, 110.4167))->toBe($address);
    expect($service->reverse(-6.9667, 110.4167))->toBe($address);
    Http::assertSentCount(1);
    Http::assertSent(fn ($request) => str_contains($request->url(), '/reverse')
        && (float) $request['lat'] === -6.9667 && (float) $request['lon'] === 110.4167);
});

test('provider errors are not cached as valid addresses', function () {
    Http::fake(['*' => Http::response(['error' => 'Unable to geocode'])]);

    expect(fn () => (new MapGeocodingService)->reverse(0, 0))
        ->toThrow(RuntimeException::class, 'Data alamat tidak tersedia.');
});
