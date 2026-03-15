<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{

    public function apply($job_id)
    {
        $job = Job::findOrFail($job_id);

        // Prevent job owner applying
        if ($job->user_id == auth()->id()) {
            return back()->with('error', 'You cannot apply to your own job');
        }

        $exists = JobApplication::where('job_id', $job_id)
            ->where('worker_id', auth()->id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Already applied for this job');
        }

        JobApplication::create([
            'job_id' => $job_id,
            'worker_id' => auth()->id()
        ]);

        return back()->with('success', 'Application sent successfully');
    }

    public function applicants($job_id)
    {

        $job = Job::findOrFail($job_id);

        // security check
        if ($job->user_id != auth()->id()) {
            abort(403);
        }

        $applications = JobApplication::where('job_id', $job_id)
            ->with('worker')
            ->get();

        return view('applications.index', compact('applications', 'job'));
    }

    public function accept($id)
    {

        $application = JobApplication::findOrFail($id);

        $job = Job::findOrFail($application->job_id);

        // security check
        if ($job->user_id != auth()->id()) {
            abort(403);
        }

        // update application
        $application->status = 'accepted';
        $application->save();

        // reject others
        JobApplication::where('job_id', $job->id)
            ->where('id', '!=', $id)
            ->update(['status' => 'rejected']);

        // update job
        $job->status = 'assigned';
        $job->assigned_worker_id = $application->worker_id;
        $job->save();

        return redirect('/my-jobs')->with('success', 'Worker Assigned');
    }

    public function myApplications()
    {

        $applications = JobApplication::where('worker_id', auth()->id())
            ->with('job')
            ->latest()
            ->get();

        return view('applications.myapplications', compact('applications'));
    }
}
