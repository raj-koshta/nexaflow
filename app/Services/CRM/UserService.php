<?php

namespace App\Services\CRM;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Create a new user.
     */
    public function createUser(array $data): User
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if (isset($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create user: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing user.
     */
    public function updateUser(User $user, array $data): User
    {
        DB::beginTransaction();
        try {
            $user->name = $data['name'];
            $user->email = $data['email'];
            
            if (isset($data['roles'])) {
                $user->syncRoles($data['roles']);
            } else {
                $user->syncRoles([]);
            }

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update user: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Soft delete a user.
     */
    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }
}
