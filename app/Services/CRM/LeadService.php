<?php

namespace App\Services\CRM;

use App\Models\Lead;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Activity;
use App\Models\FollowUp;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($data) {
            $data['lead_code'] = $this->generateLeadCode();
            $data['created_by'] = Auth::id();
            
            // If assigned_to is empty, maybe assign to self, but specification says nullable.
            if (empty($data['assigned_to'])) {
                $data['assigned_to'] = null;
            }

            return Lead::create($data);
        });
    }

    /**
     * Update an existing lead.
     */
    public function updateLead(Lead $lead, array $data): Lead
    {
        return DB::transaction(function () use ($lead, $data) {
            if (empty($data['assigned_to'])) {
                $data['assigned_to'] = null;
            }

            $lead->update($data);
            return $lead;
        });
    }

    /**
     * Delete a lead.
     */
    public function deleteLead(Lead $lead): bool
    {
        return DB::transaction(function () use ($lead) {
            return $lead->delete();
        });
    }

    /**
     * Convert a Lead to a Client and Contact.
     */
    public function convertLead(Lead $lead): Client
    {
        return DB::transaction(function () use ($lead) {
            // 1. Create Client
            $client = app(\App\Services\CRM\ClientService::class)->createClient([
                'company_name' => $lead->company ?? $lead->name . ' Company',
                'email' => $lead->email,
                'phone' => $lead->phone,
                'status' => 'active',
            ]);

            // 2. Create Contact
            Contact::create([
                'client_id' => $client->id,
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'is_primary' => true,
                'created_by' => Auth::id(),
            ]);

            // 3. Copy Activities
            $activities = $lead->activities;
            foreach ($activities as $activity) {
                Activity::create([
                    'client_id' => $client->id,
                    'type' => $activity->type,
                    'title' => $activity->title,
                    'description' => $activity->description,
                    'activity_date' => $activity->activity_date,
                    'created_by' => $activity->created_by,
                ]);
            }

            // 4. Copy Follow Ups
            $followUps = $lead->followUps;
            foreach ($followUps as $followUp) {
                FollowUp::create([
                    'client_id' => $client->id,
                    'type' => $followUp->type,
                    'scheduled_at' => $followUp->scheduled_at,
                    'notes' => $followUp->notes,
                    'status' => $followUp->status,
                    'created_by' => $followUp->created_by,
                ]);
            }

            // 5. Mark Lead Converted (using existing 'qualified' status)
            $lead->update(['status' => 'qualified']);
            
            // Generate Activity Log
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Lead Converted',
                'description' => "Lead {$lead->name} was converted to Client {$client->company_name}.",
            ]);

            return $client;
        });
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
