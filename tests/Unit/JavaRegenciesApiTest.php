<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses(Tests\TestCase::class);

beforeEach(function () {
    config()->set('cache.default', 'array');
    config()->set('session.driver', 'array');
    Cache::forget('wilayah:regencies:java');
});

test('java regencies come from all six province APIs and subsequent requests use the cache', function () {
    Http::fake(function ($request) {
        $provinceId = basename($request->url());

        return Http::response(['data' => [
            ['code' => $provinceId.'01', 'name' => 'Kabupaten '.$provinceId],
        ]]);
    });

    $response = $this->getJson('/api-wilayah/regencies-java');
    $response->assertOk()->assertJsonCount(6, 'data');
    expect(array_column($response->json('data'), 'code'))
        ->toBe(['3101', '3201', '3301', '3401', '3501', '3601']);
    Http::assertSentCount(6);

    $this->getJson('/api-wilayah/regencies-java')
        ->assertOk()->assertExactJson($response->json());
    Http::assertSentCount(6);
});

test('an upstream failure does not cache an incomplete list of java regencies', function () {
    Http::fake(function ($request) {
        $provinceId = basename($request->url());

        return $provinceId === '35'
            ? Http::response([], 503)
            : Http::response(['data' => [
                ['code' => $provinceId.'01', 'name' => 'Kabupaten '.$provinceId],
            ]]);
    });

    $this->getJson('/api-wilayah/regencies-java')
        ->assertStatus(503)->assertJsonPath('data', []);
    expect(Cache::has('wilayah:regencies:java'))->toBeFalse();
});

test('invalid province data is rejected without caching it', function () {
    Http::fake([
        '*' => Http::response(['data' => [['code' => '1101', 'name' => 'Outside Java']]]),
    ]);

    $this->getJson('/api-wilayah/regencies-java')->assertStatus(503);
    expect(Cache::has('wilayah:regencies:java'))->toBeFalse();
});
