<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show($user_id)
    {
        $user = \App\Models\User::with(['jobs', 'applications.job'])->findOrFail($user_id);

        $averageRating = $user->ratingsReceived()->avg('rating') ?? 0;
        $totalReviews = $user->ratingsReceived()->count();

        $jobsPosted = $user->jobs;
        $jobsCompleted = $user->applications()->where('status', 'accepted')->with('job')->get()->pluck('job');

        // Check if current user has accepted application for this user's job
        $canSeeDetails = auth()->check() && (
            auth()->user()->applications()->where('status', 'accepted')->whereHas('job', function($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })->exists() ||
            // Or if the profile user has applied to current user's job
            auth()->user()->jobs()->whereHas('applications', function($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })->exists()
        );

        $showContact = $user_id == auth()->id() || (auth()->check() && auth()->user()->jobs()->whereHas('applications', function($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->exists());

        return view('profile.show', compact('user', 'averageRating', 'totalReviews', 'jobsPosted', 'jobsCompleted', 'canSeeDetails', 'showContact'));
    }

    public function myProfile()
    {
        return $this->show(auth()->id());
    }

    public function editProfile()
    {
        return view('profile.edit-profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        auth()->user()->update($request->only(['name', 'phone', 'address', 'profile_description', 'latitude', 'longitude']));

        return redirect()->route('profile.edit-profile')->with('success', 'Profile updated successfully');
    }
}
