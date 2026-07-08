<?php

namespace App\Services\CRM;

use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactService
{
    /**
     * Get a paginated list of contacts with optional filters.
     */
    public function getContacts(array $filters = [], $perPage = 10)
    {
        $query = Contact::query()->with('client')->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new contact.
     */
    public function createContact(array $data): Contact
    {
        $data['created_by'] = Auth::id();
        $contact = Contact::create($data);

        // Handle Primary Contact logic
        if (!empty($data['is_primary'])) {
            $this->setPrimaryContact($contact);
        }

        return $contact;
    }

    /**
     * Update an existing contact.
     */
    public function updateContact(Contact $contact, array $data): Contact
    {
        $contact->update($data);

        // Handle Primary Contact logic
        if (!empty($data['is_primary'])) {
            $this->setPrimaryContact($contact);
        }

        return $contact;
    }

    /**
     * Delete a contact.
     */
    public function deleteContact(Contact $contact): bool
    {
        return $contact->delete();
    }

    /**
     * Set a contact as the primary contact for their client.
     */
    public function setPrimaryContact(Contact $contact)
    {
        // Set all other contacts for this client to non-primary
        Contact::where('client_id', $contact->client_id)
            ->where('id', '!=', $contact->id)
            ->update(['is_primary' => false]);

        $contact->update(['is_primary' => true]);
    }
}
