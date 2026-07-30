<?php

use App\Services\ProspectReferralUserIdService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class);

beforeEach(function () {
    config()->set('database.default', 'sqlite');
    config()->set('database.connections.sqlite.database', ':memory:');

    DB::purge('sqlite');
    DB::reconnect('sqlite');

    Schema::create('prospects', function (Blueprint $table) {
        $table->id();
        $table->string('referral_user_id', 150)->nullable();
        $table->string('diambil_oleh', 150)->nullable();
    });
});

test('it updates prospect referral and owner IDs from every legacy user identifier', function () {
    DB::table('prospects')->insert([
        ['referral_user_id' => 'K-123-456', 'diambil_oleh' => '999-999'],
        ['referral_user_id' => 'K-123-456-OLD', 'diambil_oleh' => 'K-123-456'],
        ['referral_user_id' => '999-999', 'diambil_oleh' => 'K-123-456-OLD'],
    ]);

    $updated = app(ProspectReferralUserIdService::class)->replace(
        ['K-123-456', 'K-123-456-OLD'],
        '123-456'
    );

    expect($updated)->toBe(4);
    expect(DB::table('prospects')->where('referral_user_id', '123-456')->count())->toBe(2);
    expect(DB::table('prospects')->where('diambil_oleh', '123-456')->count())->toBe(2);
    expect(DB::table('prospects')->where('referral_user_id', '999-999')->count())->toBe(1);
    expect(DB::table('prospects')->where('diambil_oleh', '999-999')->count())->toBe(1);
});

test('it skips an update when the employee ID did not change', function () {
    DB::table('prospects')->insert([
        'referral_user_id' => '123-456',
        'diambil_oleh' => '123-456',
    ]);

    $updated = app(ProspectReferralUserIdService::class)->replace(
        ['123-456'],
        '123-456'
    );

    expect($updated)->toBe(0);
    expect(DB::table('prospects')->value('referral_user_id'))->toBe('123-456');
    expect(DB::table('prospects')->value('diambil_oleh'))->toBe('123-456');
});
