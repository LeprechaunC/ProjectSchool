<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
 

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Get authenticated user
        $teams = $user->teams; // Get all teams the user belongs to
        return response()->json($teams);
    }
}
