<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ProspectReferralUserIdService
{
    /**
     * Replace legacy user identifiers in every prospect ownership column.
     *
     * @param  array<int, string|null>  $oldIdentifiers
     */
    public function replace(array $oldIdentifiers, string $newEmployeeId): int
    {
        $newEmployeeId = trim($newEmployeeId);
        if ($newEmployeeId === '') {
            return 0;
        }

        $identifiers = collect($oldIdentifiers)
            ->map(fn ($identifier) => trim((string) $identifier))
            ->filter(fn (string $identifier) => $identifier !== '' && $identifier !== $newEmployeeId)
            ->unique()
            ->values()
            ->all();

        if ($identifiers === []) {
            return 0;
        }

        $referralsUpdated = DB::table('prospects')
            ->whereIn('referral_user_id', $identifiers)
            ->update(['referral_user_id' => $newEmployeeId]);

        $ownersUpdated = DB::table('prospects')
            ->whereIn('diambil_oleh', $identifiers)
            ->update(['diambil_oleh' => $newEmployeeId]);

        return $referralsUpdated + $ownersUpdated;
    }
}
