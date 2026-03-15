<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Job;
use App\Models\User;

class RatingController extends Controller
{
    public function showRatePage($job_id)
    {
        $job = Job::findOrFail($job_id);

        if ($job->status != 'completed') {
            return back()->with('error', 'Job must be completed to rate');
        }

        if ($job->user_id != auth()->id() && $job->assigned_worker_id != auth()->id()) {
            abort(403);
        }

        $to_user = ($job->user_id == auth()->id()) ? $job->assigned_worker_id : $job->user_id;

        return view('rating.rate', compact('job', 'to_user'));
    }

    public function rate(Request $request, $job_id)
    {
        $job = Job::findOrFail($job_id);

        // Only allow if job is completed
        if ($job->status != 'completed') {
            return back()->with('error', 'Job must be completed to rate');
        }

        // Check if user is job owner or assigned worker
        if ($job->user_id != auth()->id() && $job->assigned_worker_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'to_user' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string'
        ]);

        // Prevent rating yourself
        if ($request->to_user == auth()->id()) {
            return back()->with('error', 'You cannot rate yourself');
        }

        // Check if already rated
        $exists = Rating::where('job_id', $job_id)
            ->where('from_user', auth()->id())
            ->where('to_user', $request->to_user)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Already rated this user for this job');
        }

        Rating::create([
            'job_id' => $job_id,
            'from_user' => auth()->id(),
            'to_user' => $request->to_user,
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        // Update user's average rating
        $user = User::find($request->to_user);
        $avgRating = Rating::where('to_user', $request->to_user)->avg('rating');
        $user->rating = $avgRating;
        $user->save();

        return back()->with('success', 'Rating submitted');
    }
}
