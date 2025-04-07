<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Middleware\AdminMiddleware;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', AdminMiddleware::class]);
    }

    public function getUsers()
    {
        $users = User::select('id', 'name', 'email', 'role', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

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

    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    public function getTeams()
    {
        $teams = Team::withCount('users')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($teams);
    }

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

    public function deleteTeam(Team $team)
    {
        $team->delete();
        return response()->json(null, 204);
    }

    public function getSettings()
    {
        $settings = Setting::first();
        return response()->json($settings);
    }

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