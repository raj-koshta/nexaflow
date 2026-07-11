<?php

namespace App\Services\CRM;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CompanyService
{
    public function createCompany(array $data): Company
    {
        DB::beginTransaction();
        try {
            $logoPath = null;
            if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
                $logoPath = $data['logo']->store('company_logos', 'public');
            }

            $company = Company::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'website' => $data['website'] ?? null,
                'address' => $data['address'] ?? null,
                'logo_path' => $logoPath,
            ]);

            DB::commit();
            return $company;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create company: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateCompany(Company $company, array $data): Company
    {
        DB::beginTransaction();
        try {
            $logoPath = $company->logo_path;
            
            if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
                if ($logoPath) {
                    Storage::disk('public')->delete($logoPath);
                }
                $logoPath = $data['logo']->store('company_logos', 'public');
            } elseif (isset($data['remove_logo']) && $data['remove_logo']) {
                if ($logoPath) {
                    Storage::disk('public')->delete($logoPath);
                }
                $logoPath = null;
            }

            $company->update([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'website' => $data['website'] ?? null,
                'address' => $data['address'] ?? null,
                'logo_path' => $logoPath,
            ]);

            DB::commit();
            return $company;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update company: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteCompany(Company $company): bool
    {
        return $company->delete();
    }

    public function addMember(Company $company, int $userId, bool $isPrimary = false): void
    {
        if (!$company->users()->where('user_id', $userId)->exists()) {
            $company->users()->attach($userId, ['is_primary' => $isPrimary]);
        } else if ($isPrimary) {
            $company->users()->updateExistingPivot($userId, ['is_primary' => true]);
        }
    }

    public function removeMember(Company $company, int $userId): void
    {
        $company->users()->detach($userId);
    }
}
