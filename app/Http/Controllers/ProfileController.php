<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\ProfileImageRequest;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $request->user(), // Pass the user object to Vue
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        // Validate the input fields
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:10000', // Max 5MB
        ]);

        $user = $request->user();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Handle the profile picture upload
       

        // Save the updated user data
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if (auth()->user()->profile_picture) {
                    Storage::disk('public')->delete(auth()->user()->profile_picture);
                }

                // Store the new image
                $path = $request->file('profile_picture')->store('profile-pictures', 'public');

                // Update user's profile_picture in database
                auth()->user()->update([
                    'profile_picture' => $path
                ]);

                return redirect()->back()->with('message', 'Profile picture updated successfully');
            }

            return redirect()->back()->with('error', 'No image provided');
        } catch (\Exception $e) {
            \Log::error('Profile picture upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error uploading image');
        }
    }
}
