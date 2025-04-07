<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfExportController extends Controller
{
    /**
     * Export user's personal goals to PDF
     */
    public function exportPersonalGoals()
    {
        $user = Auth::user();
        $goals = Goal::whereNull('team_id')
            ->where('user_id', $user->id)
            ->get(['id', 'title', 'description', 'start_time', 'end_time', 'done', 'priority']);

        $pdf = PDF::loadView('pdf.personal-goals', [
            'goals' => $goals,
            'user' => $user
        ]);
        
        return $pdf->download('personal-goals.pdf');
    }

    /**
     * Export team goals to PDF
     */
    public function exportTeamGoals($teamId)
    {
        $user = Auth::user();
        $team = Team::findOrFail($teamId);
        
        // Check if user belongs to the team
        if (!$user->teams->contains($teamId)) {
            abort(403, 'You do not have access to this team');
        }
        
        $goals = Goal::where('team_id', $teamId)
            ->get(['id', 'title', 'description', 'start_time', 'end_time', 'user_id', 'done', 'priority']);

        $pdf = PDF::loadView('pdf.team-goals', [
            'goals' => $goals,
            'team' => $team,
            'user' => $user
        ]);
        
        return $pdf->download($team->name . '-goals.pdf');
    }

    /**
     * Export user's teams to PDF
     */
    public function exportUserTeams()
    {
        $user = Auth::user();
        $teams = $user->teams;
        
        $pdf = PDF::loadView('pdf.user-teams', [
            'teams' => $teams,
            'user' => $user
        ]);
        
        return $pdf->download('my-teams.pdf');
    }
} 