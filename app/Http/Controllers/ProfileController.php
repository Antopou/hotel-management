<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $previousUrl = url()->previous();

        if ($previousUrl === route('profile.edit')) {
            $previousUrl = route('dashboard');
        }

        return view('profile.edit', [
            'backUrl' => $previousUrl,
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
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

        // Use the backUrl from the form input, or fallback to dashboard
        $backUrl = $request->input('backUrl', route('profile.edit'));

        return redirect($backUrl)->with('success', 'Profile updated successfully!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'image|max:2048'
        ]);
        $user = Auth::user();
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_images', 'public');
            $user->profile_image = $path;
            $user->save();
        }
        return back()->with('success', 'Profile photo updated!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Prevent deleting main admin
        if ($user->id == 1) {
            abort(403, 'Cannot delete the main admin account.');
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function registerAdmin(Request $request)
    {
        if (Auth::id() !== 1) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'is_admin' => true,
        ]);

        // Store new admin ID in session for switch prompt
        session([
            'new_admin_id' => $user->id,
            'new_admin_email' => $user->email,
            'new_admin_name' => $user->name, // Add this line
        ]);

        return redirect()->route('profile.edit')->with('success', 'New admin registered successfully!');
    }

    public function switchUser(Request $request)
    {
        if (Auth::id() !== 1) {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($request->input('user_id'));
        Auth::logout();
        Auth::login($user);

        // Remove session prompt
        $request->session()->forget(['new_admin_id', 'new_admin_email']);

        return redirect()->route('profile.edit')->with('success', 'Switched to new admin account!');
    }
}
