<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
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
    
}