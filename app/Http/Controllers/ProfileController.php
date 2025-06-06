<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        $previousUrl = url()->previous();

        if ($previousUrl === route('profile.edit')) {
            $previousUrl = route('dashboard');
        }

        // API: Return user data as JSON
        if ($request->wantsJson()) {
            return response()->json([
                'user' => $user,
                'backUrl' => $previousUrl,
            ]);
        }

        // Web: Return Blade view
        return view('profile.edit', [
            'backUrl' => $previousUrl
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->save();

        $backUrl = $request->input('backUrl', route('profile.edit'));

        // API: Return updated user as JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Profile updated successfully!',
                'user' => $user,
            ]);
        }

        // Web: Redirect as before
        return redirect($backUrl)->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // API: Return JSON confirmation
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Account deleted and logged out successfully.'
            ]);
        }

        // Web: Redirect to home
        return Redirect::to('/');
    }
}
