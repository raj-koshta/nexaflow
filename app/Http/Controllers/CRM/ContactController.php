<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Models\Contact;
use App\Models\Client;
use App\Services\CRM\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the contacts.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Contact::class);

        $filters = $request->only(['search', 'client_id']);
        $contacts = $this->contactService->getContacts($filters);
        
        // Fetch clients for the dropdown filter and create form
        $clients = Client::select('id', 'company_name')->orderBy('company_name')->get();

        if ($request->ajax()) {
            return view('contacts.partials.table', compact('contacts'))->render();
        }

        return view('contacts.index', compact('contacts', 'clients'));
    }

    /**
     * Store a newly created contact.
     */
    public function store(StoreContactRequest $request)
    {
        Gate::authorize('create', Contact::class);

        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            $data['is_primary'] = $request->has('is_primary'); // Checkbox handling

            $contact = $this->contactService->createContact($data);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Contact created successfully.',
                'contact' => $contact
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating contact: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified contact.
     */
    public function show(Contact $contact)
    {
        Gate::authorize('view', $contact);
        $contact->load('client');
        return response()->json($contact);
    }

    /**
     * Update the specified contact.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        Gate::authorize('update', $contact);

        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            $data['is_primary'] = $request->has('is_primary');

            $contact = $this->contactService->updateContact($contact, $data);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Contact updated successfully.',
                'contact' => $contact
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating contact: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified contact.
     */
    public function destroy(Contact $contact)
    {
        Gate::authorize('delete', $contact);

        try {
            $this->contactService->deleteContact($contact);
            return response()->json([
                'success' => true,
                'message' => 'Contact deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting contact: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set the contact as primary.
     */
    public function setPrimary(Contact $contact)
    {
        Gate::authorize('update', $contact);

        try {
            DB::beginTransaction();
            $this->contactService->setPrimaryContact($contact);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Contact set as primary successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating primary status: ' . $e->getMessage()
            ], 500);
        }
    }
}
