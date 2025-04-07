<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $teams = $user->teams()->with('users')->get(); 

        return response()->json($teams);
    }

    public function removeMember($teamId, $userId)
    {
        $team = Team::with('users')->findOrFail($teamId);
        $authUser = auth()->user();
    
        // Check if the authenticated user is an admin
        $isAdmin = $team->users->where('id', $authUser->id)->where('role', 'admin')->count() > 0;
    
        if (!$isAdmin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // Remove user from team
        DB::table('team_user')->where('team_id', $teamId)->where('user_id', $userId)->delete();
    
        return response()->json(['message' => 'User removed successfully']);
    }
    public function createTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user(); // Get authenticated user

        $team = Team::create([
            'name' => $request->name,
            'owner' => $user->id,
            'invite_code' => Str::random(8), // Generates an 8-letter random invite code
        ]);

        // Attach the creator as an admin
        $team->users()->attach($user->id, ['role' => 'admin']);

        return response()->json([
            'message' => 'Team created successfully',
            'team' => $team,
        ]);
    }
    public function joinTeam(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|exists:teams,invite_code',
        ]);

        $user = $request->user();
        $team = Team::where('invite_code', $request->invite_code)->firstOrFail();

        // Check if the user is already a member
        if ($team->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'You are already a member of this team.'], 400);
        }

        // Add user to the team
        $team->users()->attach($user->id, ['role' => 'member']);

        return response()->json([
            'message' => 'Successfully joined the team!',
            'team' => $team->load('users'), // Load team with members
        ]);
    }
    public function votekick(Request $request, $teamId)
    {
        $user = auth()->user();  // The currently authenticated user
        $team = Team::findOrFail($teamId);  // Find the team by ID
        
        if (!$team->users->contains($user)) {
            return response()->json(['error' => 'User is not part of this team.'], 403);
        }

     

        $userId = $request->input('userId');
        $userToKick = User::findOrFail($userId);  

   
        if (!$team->users->contains($userToKick)) {
            return response()->json(['error' => 'User is not part of the team.'], 404);
        }

        // Remove the user from the team
        $team->users()->detach($userToKick->id);

        return response()->json(['message' => 'User has been successfully votekicked.']);
    }
    public function generateInviteCode($teamId)
    {
        $team = Team::findOrFail($teamId);
        $authUser = auth()->user();
    
        // Check if the authenticated user is an admin
        $isAdmin = $team->users()
            ->wherePivot('user_id', $authUser->id)
            ->wherePivot('role', 'admin')
            ->exists();
    
        if (!$isAdmin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // Generate and update the invite code
        $newInviteCode = Str::random(8);
        $team->invite_code = $newInviteCode;
        $team->save();
    
        return response()->json(['invite_code' => $newInviteCode]);
    }
    
    public function exitTeam($teamId)
    {
        $team = Team::with('users')->findOrFail($teamId);
        $user = auth()->user();

        // Check if user is the last admin
        $adminCount = $team->users()->wherePivot('role', 'admin')->count();
        $isAdmin = $team->users()->wherePivot('user_id', $user->id)->wherePivot('role', 'admin')->exists();

        if ($isAdmin && $adminCount <= 1) {
            // If this is the last admin, find the next oldest member to make admin
            $nextAdmin = $team->users()
                ->wherePivot('user_id', '!=', $user->id)
                ->orderBy('created_at', 'asc')
                ->first();

            if ($nextAdmin) {
                $team->users()->updateExistingPivot($nextAdmin->id, ['role' => 'admin']);
            }
        }

        // Remove user from team
        $team->users()->detach($user->id);

        return response()->json(['message' => 'Successfully left the team']);
    }

    public function updateTeamName(Request $request, $teamId)
    {
        $team = Team::findOrFail($teamId);
        $user = auth()->user();

        // Check if user is admin
        if (!$team->users()->wherePivot('user_id', $user->id)->wherePivot('role', 'admin')->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $team->update(['name' => $request->name]);

        return response()->json($team);
    }

    public function makeAdmin($teamId, $userId)
    {
        $team = Team::findOrFail($teamId);
        $user = auth()->user();

        // Check if current user is admin
        if (!$team->users()->wherePivot('user_id', $user->id)->wherePivot('role', 'admin')->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update user role to admin
        $team->users()->updateExistingPivot($userId, ['role' => 'admin']);

        return response()->json(['message' => 'User is now an admin']);
    }

    public function removeAdmin($teamId, $userId)
    {
        $team = Team::findOrFail($teamId);
        $user = auth()->user();

        // Check if current user is admin
        if (!$team->users()->wherePivot('user_id', $user->id)->wherePivot('role', 'admin')->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if this is the last admin
        $adminCount = $team->users()->wherePivot('role', 'admin')->count();
        if ($adminCount <= 1) {
            return response()->json(['error' => 'Cannot remove the last admin'], 400);
        }

        // Update user role to member
        $team->users()->updateExistingPivot($userId, ['role' => 'member']);

        return response()->json(['message' => 'User is no longer an admin']);
    }

    public function deleteTeam($teamId)
    {
        $team = Team::findOrFail($teamId);
        $user = auth()->user();

        // Check if user is admin
        if (!$team->users()->wherePivot('user_id', $user->id)->wherePivot('role', 'admin')->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $team->delete();

        return response()->json(['message' => 'Team deleted successfully']);
    }
}