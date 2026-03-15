<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Job;

class PaymentController extends Controller
{
    public function payForJob(Request $request, $job_id)
    {
        $job = Job::findOrFail($job_id);

        if ($job->user_id != auth()->id()) {
            abort(403);
        }

        // Simulate payment
        Payment::create([
            'user_id' => auth()->id(),
            'job_id' => $job_id,
            'amount' => 1.00, // ₹1
            'status' => 'completed',
            'payment_method' => 'simulated'
        ]);

        return back()->with('success', 'Payment successful');
    }
}
