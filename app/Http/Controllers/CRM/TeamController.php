<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\StoreTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Models\Team;
use App\Models\User;
use App\Services\CRM\TeamService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $teams = Team::withCount('users')->latest()->get();
            return view('teams.table', compact('teams'))->render();
        }

        return view('teams.index');
    }

    public function store(StoreTeamRequest $request)
    {
        $team = $this->teamService->createTeam($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Team created successfully.',
                'data' => $team
            ]);
        }

        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    public function show(Team $team)
    {
        $team->load('users');
        $availableUsers = User::whereDoesntHave('teams', function($query) use ($team) {
            $query->where('teams.id', $team->id);
        })->get();

        return view('teams.show', compact('team', 'availableUsers'));
    }

    public function edit(Team $team)
    {
        return response()->json($team);
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        $team = $this->teamService->updateTeam($team, $request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Team updated successfully.',
                'data' => $team
            ]);
        }

        return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        $this->teamService->deleteTeam($team);

        return response()->json([
            'success' => true,
            'message' => 'Team deleted successfully.'
        ]);
    }

    public function addMember(Request $request, Team $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'is_leader' => 'nullable|boolean'
        ]);

        $this->teamService->addMember($team, $request->user_id, $request->is_leader ?? false);

        return back()->with('success', 'Member added to team.');
    }

    public function removeMember(Team $team, User $user)
    {
        $this->teamService->removeMember($team, $user->id);

        return back()->with('success', 'Member removed from team.');
    }
}
