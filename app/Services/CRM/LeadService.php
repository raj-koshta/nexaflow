<?php

namespace App\Services\CRM;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class LeadService
{
    /**
     * Get a paginated list of leads with optional filters.
     */
    public function getLeads(array $filters = [], $perPage = 10)
    {
        $query = Lead::query()->latest();

        if (isset($filters['trashed']) && $filters['trashed'] == '1') {
            $query->onlyTrashed();
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('lead_code', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new lead.
     */
    public function createLead(array $data): Lead
    {
        $data['lead_code'] = $this->generateLeadCode();
        $data['created_by'] = Auth::id();
        
        // If assigned_to is empty, maybe assign to self, but specification says nullable.
        if (empty($data['assigned_to'])) {
            $data['assigned_to'] = null;
        }

        return Lead::create($data);
    }

    /**
     * Update an existing lead.
     */
    public function updateLead(Lead $lead, array $data): Lead
    {
        if (empty($data['assigned_to'])) {
            $data['assigned_to'] = null;
        }

        $lead->update($data);
        return $lead;
    }

    /**
     * Delete a lead.
     */
    public function deleteLead(Lead $lead): bool
    {
        return $lead->delete();
    }

    /**
     * Bulk delete leads.
     */
    public function bulkDelete(array $ids)
    {
        return Lead::whereIn('id', $ids)->delete();
    }

    /**
     * Bulk update leads status.
     */
    public function bulkUpdate(array $ids, array $data)
    {
        return Lead::whereIn('id', $ids)->update($data);
    }

    /**
     * Generate a unique sequential lead code (e.g. LED-001)
     */
    public function generateLeadCode(): string
    {
        $lastLead = Lead::withTrashed()->orderBy('id', 'desc')->first();
        
        if (!$lastLead) {
            return 'LED-001';
        }

        $lastCode = $lastLead->lead_code; // LED-XXX
        $parts = explode('-', $lastCode);
        
        if (count($parts) == 2) {
            $number = intval($parts[1]) + 1;
            return 'LED-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        return 'LED-' . str_pad($lastLead->id + 1, 3, '0', STR_PAD_LEFT);
    }
}
