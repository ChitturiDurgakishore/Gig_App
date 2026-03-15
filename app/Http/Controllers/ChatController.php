<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Job;
use Auth;

class ChatController extends Controller
{

    public function index($job_id)
    {

        $job = Job::findOrFail($job_id);

        // only job owner or assigned worker allowed, and only if assigned
        if ($job->user_id != auth()->id() && $job->assigned_worker_id != auth()->id()) {
            abort(403);
        }

        if ($job->status != 'assigned') {
            abort(403, 'Chat is only available for assigned jobs.');
        }

        $messages = Message::where('job_id', $job_id)
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('chat.index',compact('messages','job'));

    }

    public function send(Request $request,$job_id)
    {

        $job = Job::findOrFail($job_id);

        // only job owner or assigned worker allowed, and only if assigned
        if ($job->user_id != auth()->id() && $job->assigned_worker_id != auth()->id()) {
            abort(403);
        }

        if ($job->status != 'assigned') {
            abort(403, 'Chat is only available for assigned jobs.');
        }

        $receiver_id = ($job->user_id == auth()->id()) ? $job->assigned_worker_id : $job->user_id;

        Message::create([

            'job_id'=>$job_id,
            'sender_id'=>auth()->id(),
            'receiver_id'=>$receiver_id,
            'message'=>$request->message

        ]);

        return back();

    }

}
