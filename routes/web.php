<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PaymentController;

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [JobController::class, 'dashboard'])->name('dashboard');

    Route::get('/create-job', [JobController::class, 'create'])->name('create.job');

    Route::post('/store-job', [JobController::class, 'store'])->name('store.job');
    Route::get('/my-jobs', [JobController::class, 'myJobs'])->name('my.jobs');

    Route::get('/jobs', [JobController::class, 'jobs'])->name('jobs');

    Route::get('/job/{id}', [JobController::class, 'show'])->name('job.show');

    //Job applications

    Route::post('/apply-job/{id}', [ApplicationController::class, 'apply'])->name('apply.job');

    Route::get('/job-applicants/{job_id}', [ApplicationController::class, 'applicants'])->name('job.applicants');

    Route::post('/accept-worker/{id}', [ApplicationController::class, 'accept'])->name('accept.worker');

    Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('my.applications');

    // Chat
    Route::get('/chat/{job_id}', [ChatController::class, 'index'])->name('chat');
    Route::post('/send-message/{job_id}', [ChatController::class, 'send'])->name('send.message');

    // Profile
    Route::get('/profile/{user_id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/my-profile', [ProfileController::class, 'myProfile'])->name('profile.my');
    Route::get('/edit-profile', [ProfileController::class, 'editProfile'])->name('profile.edit-profile');
    Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.update-profile');

    // Rating page
    Route::get('/rate/{job_id}', [RatingController::class, 'showRatePage'])->name('rate.page');
    Route::post('/rate/{job_id}', [RatingController::class, 'rate'])->name('rate');

    // Job completion
    Route::post('/job/{id}/complete', [JobController::class, 'complete'])->name('job.complete');

    // Payment
    Route::post('/pay-for-job/{job_id}', [PaymentController::class, 'payForJob'])->name('pay.job');

});
