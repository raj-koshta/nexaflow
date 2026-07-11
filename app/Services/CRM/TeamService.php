<?php

namespace App\Services\CRM;

use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeamService
{
    public function createTeam(array $data): Team
    {
        DB::beginTransaction();
        try {
            $team = Team::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            DB::commit();
            return $team;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create team: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateTeam(Team $team, array $data): Team
    {
        DB::beginTransaction();
        try {
            $team->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            DB::commit();
            return $team;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update team: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteTeam(Team $team): bool
    {
        return $team->delete();
    }

    public function addMember(Team $team, int $userId, bool $isLeader = false): void
    {
        if (!$team->users()->where('user_id', $userId)->exists()) {
            $team->users()->attach($userId, ['is_leader' => $isLeader]);
        }
    }

    public function removeMember(Team $team, int $userId): void
    {
        $team->users()->detach($userId);
    }
}
