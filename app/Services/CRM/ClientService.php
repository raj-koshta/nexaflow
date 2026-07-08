<?php

namespace App\Services\CRM;

use App\Models\Client;
use Illuminate\Support\Facades\Log;

class ClientService
{
    /**
     * Get paginated and filtered clients.
     */
    public function getClients(array $filters = [], int $perPage = 10)
    {
        $query = Client::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('client_code', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new client.
     */
    public function createClient(array $data)
    {
        $data['client_code'] = $this->generateClientCode();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $client = Client::create($data);

        Log::info('New client created: ' . $client->client_code);

        return $client;
    }

    /**
     * Update an existing client.
     */
    public function updateClient(Client $client, array $data)
    {
        $data['updated_by'] = auth()->id();
        $client->update($data);

        Log::info('Client updated: ' . $client->client_code);

        return $client;
    }

    /**
     * Delete a client.
     */
    public function deleteClient(Client $client)
    {
        $client->delete();
        Log::info('Client deleted (soft): ' . $client->client_code);
        return true;
    }

    /**
     * Generate a unique client code (e.g., CLI-001)
     */
    private function generateClientCode(): string
    {
        $lastClient = Client::withTrashed()->orderBy('id', 'desc')->first();
        
        if (!$lastClient) {
            return 'CLI-001';
        }

        // Extract the numeric part and increment
        $number = (int) str_replace('CLI-', '', $lastClient->client_code);
        $nextNumber = $number + 1;

        return 'CLI-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
