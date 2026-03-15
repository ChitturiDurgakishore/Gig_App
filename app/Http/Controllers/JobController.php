<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class JobController extends Controller
{

    public function dashboard()
    {
        return view('dashboard');
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'price' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        $job = Job::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'duration' => $request->duration,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Simulate job posting fee
        \App\Models\Payment::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'amount' => 1.00,
            'status' => 'completed',
            'payment_method' => 'simulated',
        ]);

        return redirect('/my-jobs')->with('success', 'Job Created Successfully');
    }

    public function myJobs()
    {

        $jobs = Job::where('user_id', auth()->id())->latest()->get();

        return view('jobs.myjobs', compact('jobs'));
    }

    public function jobs()
    {

        $jobs = Job::where('status', 'open')
            ->where('user_id', '!=', auth()->id()) // hide own jobs
            ->latest()
            ->get();

        return view('jobs.index', compact('jobs'));
    }

    public function show($id)
    {

        $job = Job::findOrFail($id);

        return view('jobs.show', compact('job'));
    }

    public function complete($id)
    {
        $job = Job::findOrFail($id);

        if (auth()->id() != $job->user_id && auth()->id() != $job->assigned_worker_id) {
            abort(403);
        }

        $job->status = 'completed';
        $job->save();

        return back()->with('success', 'Job marked as completed');
    }
}

