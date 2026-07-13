<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadRequest;
use App\Models\Lead;
use App\Services\CRM\LeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LeadController extends Controller
{
    protected $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    /**
     * Display a listing of the leads.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Lead::class);

        $filters = $request->only(['search', 'status', 'source']);
        $leads = $this->leadService->getLeads($filters);

        if ($request->ajax()) {
            return view('leads.partials.table', compact('leads'))->render();
        }

        return view('leads.index', compact('leads'));
    }

    /**
     * Store a newly created lead.
     */
    public function store(StoreLeadRequest $request)
    {
        Gate::authorize('create', Lead::class);

        try {
            DB::beginTransaction();
            $lead = $this->leadService->createLead($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully.',
                'lead' => $lead
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified lead.
     */
    public function show(Lead $lead)
    {
        Gate::authorize('view', $lead);
        
        $lead->load([
            'notes.creator',
            'documents.creator',
            'activities' => function($q) {
                $q->orderBy('activity_date', 'desc');
            },
            'activities.creator',
            'assignee',
            'creator'
        ]);

        return view('leads.show', compact('lead'));
    }

    /**
     * Update the specified lead.
     */
    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        Gate::authorize('update', $lead);

        try {
            DB::beginTransaction();
            $lead = $this->leadService->updateLead($lead, $request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lead updated successfully.',
                'lead' => $lead
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified lead.
     */
    public function destroy(Lead $lead)
    {
        Gate::authorize('delete', $lead);

        try {
            $this->leadService->deleteLead($lead);
            return response()->json([
                'success' => true,
                'message' => 'Lead deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting lead: ' . $e->getMessage()
            ], 500);
        }
    }
}
