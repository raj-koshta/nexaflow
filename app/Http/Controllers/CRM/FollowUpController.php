<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowUp\StoreFollowUpRequest;
use App\Http\Requests\FollowUp\UpdateFollowUpRequest;
use App\Models\FollowUp;
use App\Models\Client;
use App\Models\Lead;
use App\Models\User;
use App\Services\CRM\FollowUpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FollowUpController extends Controller
{
    protected $followUpService;

    public function __construct(FollowUpService $followUpService)
    {
        $this->followUpService = $followUpService;
    }

    /**
     * Display a listing of the follow-ups.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', FollowUp::class);

        $category = $request->get('category', 'today');
        $filters = $request->only(['search']);
        
        $followUps = $this->followUpService->getCategorizedFollowUps($category, $filters);
        
        // Fetch clients, leads, and users for the form
        $clients = Client::select('id', 'company_name')->orderBy('company_name')->get();
        $leads = Lead::select('id', 'name', 'company')->orderBy('name')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        if ($request->ajax()) {
            return view('follow-ups.partials.list', compact('followUps', 'category'))->render();
        }

        return view('follow-ups.index', compact('followUps', 'clients', 'leads', 'users', 'category'));
    }

    /**
     * Store a newly created follow up.
     */
    public function store(StoreFollowUpRequest $request)
    {
        Gate::authorize('create', FollowUp::class);

        try {
            DB::beginTransaction();
            $followUp = $this->followUpService->createFollowUp($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Follow up scheduled successfully.',
                'followUp' => $followUp
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error scheduling follow up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified follow up.
     */
    public function show(FollowUp $followUp)
    {
        Gate::authorize('view', $followUp);
        $followUp->load(['client', 'lead', 'assignee']);
        return response()->json($followUp);
    }

    /**
     * Update the specified follow up.
     */
    public function update(UpdateFollowUpRequest $request, FollowUp $followUp)
    {
        Gate::authorize('update', $followUp);

        try {
            DB::beginTransaction();
            $followUp = $this->followUpService->updateFollowUp($followUp, $request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Follow up updated successfully.',
                'followUp' => $followUp
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating follow up: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mark follow up as completed
     */
    public function markCompleted(FollowUp $followUp)
    {
        Gate::authorize('update', $followUp);
        
        try {
            DB::beginTransaction();
            $this->followUpService->completeFollowUp($followUp);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Follow up marked as completed!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error completing follow up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified follow up.
     */
    public function destroy(FollowUp $followUp)
    {
        Gate::authorize('delete', $followUp);

        try {
            $this->followUpService->deleteFollowUp($followUp);
            return response()->json([
                'success' => true,
                'message' => 'Follow up deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting follow up: ' . $e->getMessage()
            ], 500);
        }
    }
}
