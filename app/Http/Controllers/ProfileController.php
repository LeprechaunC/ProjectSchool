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
    public function uploadImage(ProfileImageRequest $request){
        $user = Auth::user(); // Get the authenticated user

        if ($request->hasFile('profile_image')) {

            $file = $request->file('profile_image');
            $filename = "XaYPfty10". $user->id . '.' . $file->getClientOriginalExtension(); // Create a unique filename

            $path = $file->storeAs('public/profile_images', $filename);

            $user->profile_image = $filename;
            $user->save();

            return Inertia::render('Profile/Edit', [
                'message' => 'Profile image updated successfully.',
                'user' => $user // Optionally pass the updated user data back
            ]);
        }

        // Handle the case where no file was uploaded
        return redirect()->back()->withErrors(['profile_image' => 'Please upload an image.']);
}

}
