<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMessage;
use Illuminate\Http\Request;

class TeamMessageController extends Controller
{
    public function index(Team $team)
    {
        // Check if user is a member of the team
        if (!$team->users->contains(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = TeamMessage::with('user')
            ->where('team_id', $team->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request, Team $team)
    {
        // Check if user is a member of the team
        if (!$team->users->contains(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = TeamMessage::create([
            'team_id' => $team->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        $message->load('user');

        return response()->json($message, 201);
    }
} 