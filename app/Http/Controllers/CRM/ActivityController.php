<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Activity\StoreActivityRequest;
use App\Http\Requests\Activity\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\Client;
use App\Models\Lead;
use App\Services\CRM\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ActivityController extends Controller
{
    protected $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * Display a listing of the activities (Timeline View).
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Activity::class);

        $filters = $request->only(['search', 'client_id', 'lead_id', 'type']);
        $activities = $this->activityService->getTimeline($filters);
        
        // Fetch clients and leads for the dropdown filters and create form
        $clients = Client::select('id', 'company_name')->orderBy('company_name')->get();
        $leads = Lead::select('id', 'name', 'company')->orderBy('name')->get();
        
        $types = ['Phone Call', 'Meeting', 'Email', 'Demo', 'Discussion', 'Visit', 'Follow-up', 'Other'];

        if ($request->ajax()) {
            return view('activities.partials.timeline', compact('activities'))->render();
        }

        return view('activities.index', compact('activities', 'clients', 'leads', 'types'));
    }

    /**
     * Store a newly created activity.
     */
    public function store(StoreActivityRequest $request)
    {
        Gate::authorize('create', Activity::class);

        try {
            DB::beginTransaction();
            $activity = $this->activityService->createActivity($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Activity logged successfully.',
                'activity' => $activity
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating activity: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified activity.
     */
    public function show(Activity $activity)
    {
        Gate::authorize('view', $activity);
        $activity->load(['client', 'lead', 'creator']);
        return response()->json($activity);
    }

    /**
     * Update the specified activity.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        Gate::authorize('update', $activity);

        try {
            DB::beginTransaction();
            $activity = $this->activityService->updateActivity($activity, $request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Activity updated successfully.',
                'activity' => $activity
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating activity: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified activity.
     */
    public function destroy(Activity $activity)
    {
        Gate::authorize('delete', $activity);

        try {
            $this->activityService->deleteActivity($activity);
            return response()->json([
                'success' => true,
                'message' => 'Activity deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting activity: ' . $e->getMessage()
            ], 500);
        }
    }
}
