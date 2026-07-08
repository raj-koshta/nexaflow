<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use App\Services\CRM\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display a listing of the clients.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Client::class);

        $filters = $request->only(['search', 'status']);
        $clients = $this->clientService->getClients($filters);

        if ($request->ajax()) {
            return view('clients.partials.table', compact('clients'))->render();
        }

        return view('clients.index', compact('clients'));
    }

    /**
     * Store a newly created client.
     */
    public function store(StoreClientRequest $request)
    {
        Gate::authorize('create', Client::class);

        try {
            DB::beginTransaction();
            $client = $this->clientService->createClient($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully.',
                'client' => $client
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating client: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified client.
     */
    public function show(Client $client)
    {
        Gate::authorize('view', $client);
        return response()->json($client);
    }

    /**
     * Update the specified client.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        Gate::authorize('update', $client);

        try {
            DB::beginTransaction();
            $client = $this->clientService->updateClient($client, $request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully.',
                'client' => $client
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating client: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified client.
     */
    public function destroy(Client $client)
    {
        Gate::authorize('delete', $client);

        try {
            $this->clientService->deleteClient($client);
            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting client: ' . $e->getMessage()
            ], 500);
        }
    }
}
