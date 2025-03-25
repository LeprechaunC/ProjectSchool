<?php


namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
 
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class GoalController extends Controller
{
    public function getGoalsByTeam($teamId)
    {
        $goals = Goal::where('team_id', $teamId)
            ->get(['id', 'title', 'description', 'start_time', 'end_time', 'user_id', 'done']);

        return response()->json($goals);
    }
    public function getAllUserGoals(Request $request)
    {
        $user = auth()->user();  
        $goals = Goal::whereNull('team_id')
            ->where('user_id', $user->id)  
            ->get(['id', 'title', 'description', 'start_time', 'end_time', 'user_id', 'done']);
    
        return response()->json($goals);
    }
    
    /**
     * Store a new goal.
     */
   public function store(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // Validate the incoming request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'nullable|in:high,medium,low',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after_or_equal:start_time',
        'team_id' => 'nullable|exists:teams,id',
    ]);

    // Use the null-coalescing operator (??) to set a default value safely
    $priority = $validated['priority'] ?? 'medium';

    // Create a new goal
    $goal = Goal::create([
        'title'       => $validated['title'],
        'description' => $validated['description'] ?? null,
        'priority'    => $priority,  // Use the safe variable instead of accessing $validated directly
        'start_time'  => $validated['start_time'],
        'end_time'    => $validated['end_time'],
        'team_id'     => $validated['team_id'] ?? null,
        'user_id'     => Auth::id(),
    ]);

    return response()->json($goal, 201);
}
    
public function update(Request $request, $id)
    {
        // Find the goal by ID
        $goal = Goal::findOrFail($id);

        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        // Update the goal with new data
        $goal->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json($goal);
    }
    public function delete($id)
    {
        // Find the goal by ID
        $goal = Goal::find($id);
    
        // Check if the goal exists
        if (!$goal) {
            return response()->json([
                'message' => 'Goal not found.',
            ], 404); // Return 404 if not found
        }
    
        // Delete the goal
        $goal->delete();
    
        // Return a success response
        return response()->json([
            'message' => 'Goal successfully deleted.',
            'id' => $id,
        ]);
    }
    public function getAllGoals(Request $request)
{
    // Fetch all goals for all teams
    $goals = Goal::all(['id', 'title', 'description', 'start_time', 'end_time', 'done']);
    return response()->json($goals);
}

public function markAsDone(Request $request, $id)
{
    $goal = Goal::findOrFail($id);

    $goal->update([
        'done' => $request->input('done', false), // Toggle done status
    ]);

    return response()->json([
        'message' => 'Goal status updated successfully.',
        'goal' => $goal
    ]);
}


 

}

