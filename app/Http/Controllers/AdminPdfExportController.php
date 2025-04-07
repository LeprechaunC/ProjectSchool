<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Middleware\AdminMiddleware;

class AdminPdfExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', AdminMiddleware::class]);
    }
    
    /**
     * Export all users to PDF
     */
    public function exportUsers()
    {
        $users = User::with('teams')->get();
        
        $pdf = PDF::loadView('pdf.admin.users', [
            'users' => $users,
            'totalUsers' => $users->count(),
            'date' => now()->format('Y-m-d H:i:s')
        ]);
        
        return $pdf->download('all-users-' . now()->format('Y-m-d') . '.pdf');
    }
    
    /**
     * Export all teams to PDF
     */
    public function exportTeams()
    {
        $teams = Team::with('users')->get();
        
        $pdf = PDF::loadView('pdf.admin.teams', [
            'teams' => $teams,
            'totalTeams' => $teams->count(),
            'date' => now()->format('Y-m-d H:i:s')
        ]);
        
        return $pdf->download('all-teams-' . now()->format('Y-m-d') . '.pdf');
    }
    
    /**
     * Export goal statistics to PDF
     */
    public function exportGoalStats()
    {
        $totalGoals = Goal::count();
        $completedGoals = Goal::where('done', true)->count();
        $pendingGoals = Goal::where('done', false)->count();
        $personalGoals = Goal::whereNull('team_id')->count();
        $teamGoals = Goal::whereNotNull('team_id')->count();
        
        // Goals by priority
        $highPriorityGoals = Goal::where('priority', 'high')->count();
        $mediumPriorityGoals = Goal::where('priority', 'medium')->count();
        $lowPriorityGoals = Goal::where('priority', 'low')->count();
        
        $pdf = PDF::loadView('pdf.admin.goal-stats', [
            'totalGoals' => $totalGoals,
            'completedGoals' => $completedGoals,
            'pendingGoals' => $pendingGoals,
            'personalGoals' => $personalGoals,
            'teamGoals' => $teamGoals,
            'highPriorityGoals' => $highPriorityGoals,
            'mediumPriorityGoals' => $mediumPriorityGoals,
            'lowPriorityGoals' => $lowPriorityGoals,
            'date' => now()->format('Y-m-d H:i:s')
        ]);
        
        return $pdf->download('goal-statistics-' . now()->format('Y-m-d') . '.pdf');
    }
    
    /**
     * Export comprehensive system report to PDF
     */
    public function exportSystemReport()
    {
        $users = User::count();
        $teams = Team::count();
        $goals = Goal::count();
        
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();
        $recentTeams = Team::orderBy('created_at', 'desc')->take(10)->get();
        $recentGoals = Goal::orderBy('created_at', 'desc')->take(10)->get();
        
        $pdf = PDF::loadView('pdf.admin.system-report', [
            'userCount' => $users,
            'teamCount' => $teams,
            'goalCount' => $goals,
            'recentUsers' => $recentUsers,
            'recentTeams' => $recentTeams,
            'recentGoals' => $recentGoals,
            'date' => now()->format('Y-m-d H:i:s')
        ]);
        
        return $pdf->download('system-report-' . now()->format('Y-m-d') . '.pdf');
    }
} 