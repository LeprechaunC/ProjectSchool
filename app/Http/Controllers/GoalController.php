<?php


namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function getGoalsByTeam(Request $request, $teamId)
    {
        // Fetch the goals for the selected team
        $goals = Goal::where('team_id', $teamId)->get(['id','title', 'description', 'start_time', 'end_time']);

        return response()->json($goals);
    }
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'team_id' => 'required|exists:teams,id',
        ]);
    
        // Create a new goal
        $goal = Goal::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'team_id' => $request->team_id,
        ]);
    
        // Return the created goal, including the generated ID
        return response()->json([
            'id' => $goal->id,
            'title' => $goal->title,
            'description' => $goal->description,
            'start_time' => $goal->start_time,
            'end_time' => $goal->end_time,
            'team_id' => $goal->team_id,
          ]);
          
    }
    
public function update(Request $request, $id)
    {
        // Find the goal by ID
        $goal = Goal::findOrFail($id);

        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
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
        // You can just return a success response here without deleting anything
        return response()->json([
            'message' => 'Goal deletion requested, but no action performed.',
            'id' => $id,
        ]);
    }
    

  
   
 

}

