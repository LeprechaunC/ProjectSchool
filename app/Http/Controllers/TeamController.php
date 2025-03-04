<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Get authenticated user
        // Get all teams the user belongs to, and eager load the 'users' relationship
        $teams = $user->teams()->with('users')->get(); 

        return response()->json($teams);
    }
}
