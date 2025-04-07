<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Goal;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Middleware\AdminMiddleware;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', AdminMiddleware::class]);
    }

    /**
     * Get statistics for the admin dashboard
     */
    public function getStatistics()
    {
        $totalUsers = User::count();
        $totalTeams = Team::count();
        $totalGoals = Goal::count();
        $completedGoals = Goal::where('done', true)->count();
        
        // Get recent activity data for the charts
        $recentActivity = [];
        
        // Get recent user registrations
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        foreach ($recentUsers as $user) {
            $recentActivity[] = [
                'type' => 'user',
                'description' => 'New user registered: ' . $user->name,
                'timestamp' => $user->created_at
            ];
        }
        
        // Get recent team creations
        $recentTeams = Team::orderBy('created_at', 'desc')->take(5)->get();
        foreach ($recentTeams as $team) {
            $recentActivity[] = [
                'type' => 'team',
                'description' => 'Team "' . $team->name . '" was created',
                'timestamp' => $team->created_at
            ];
        }
        
        // Get recent goal updates
        $recentGoals = Goal::orderBy('updated_at', 'desc')->take(5)->get();
        foreach ($recentGoals as $goal) {
            $status = $goal->done ? 'completed' : 'updated';
            $recentActivity[] = [
                'type' => 'goal',
                'description' => 'Goal "' . $goal->title . '" was ' . $status,
                'timestamp' => $goal->updated_at,
                'priority' => $goal->priority ?? 'medium'
            ];
        }
        
        // Sort activity by timestamp (newest first)
        usort($recentActivity, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });
        
        // Limit to 10 most recent activities
        $recentActivity = array_slice($recentActivity, 0, 10);
        
        return response()->json([
            'totalUsers' => $totalUsers,
            'totalTeams' => $totalTeams,
            'totalGoals' => $totalGoals,
            'completedGoals' => $completedGoals,
            'recentActivity' => $recentActivity
        ]);
    }

    /**
     * Get all users
     */
    public function getUsers()
    {
        $users = User::select('id', 'name', 'email', 'role', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

    /**
     * Create a new user
     */
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:user,admin'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'password' => ['required', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return response()->json($user, 201);
    }

    /**
     * Update a user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'in:user,admin'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'password' => ['nullable', Password::defaults()],
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return response()->json($user);
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    /**
     * Get all teams
     */
    public function getTeams()
    {
        $teams = Team::withCount('users')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($teams);
    }

    /**
     * Create a new team
     */
    public function createTeam(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'members' => ['required', 'array'],
            'members.*' => ['exists:users,id'],
        ]);

        $team = Team::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        $team->users()->sync($validated['members']);

        return response()->json($team, 201);
    }

    /**
     * Update a team
     */
    public function updateTeam(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'members' => ['required', 'array'],
            'members.*' => ['exists:users,id'],
        ]);

        $team->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        $team->users()->sync($validated['members']);

        return response()->json($team);
    }

    /**
     * Delete a team
     */
    public function deleteTeam(Team $team)
    {
        $team->delete();
        return response()->json(null, 204);
    }

    /**
     * Get settings
     */
    public function getSettings()
    {
        $settings = Setting::first();
        return response()->json($settings);
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'siteName' => ['required', 'string', 'max:255'],
            'defaultRole' => ['required', 'string', 'in:user,admin'],
            'emailNotifications' => ['required', 'boolean'],
        ]);

        $settings = Setting::first();
        if (!$settings) {
            $settings = new Setting();
        }

        $settings->fill($validated);
        $settings->save();

        return response()->json($settings);
    }
} 