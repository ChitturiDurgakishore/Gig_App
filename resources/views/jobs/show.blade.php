@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h3>{{ $job->title }}</h3>
    <p>{{ $job->description }}</p>
    <p><strong>Price:</strong> ₹{{ $job->price }}</p>
    <p><strong>Duration:</strong> {{ $job->duration }}</p>

    @if(auth()->check() && auth()->user()->latitude && auth()->user()->longitude)
        @php $distance = $job->getDistanceTo(auth()->user()->latitude, auth()->user()->longitude); @endphp
        @if($distance)
            <p><strong>Distance:</strong> {{ round($distance, 1) }} km away</p>
        @endif
    @endif

    <p><strong>Status:</strong> {{ $job->status }}</p>

    @if($job->latitude && $job->longitude)
        <p><strong>Job Location:</strong> <a href="https://www.google.com/maps?q={{ $job->latitude }},{{ $job->longitude }}" target="_blank">View on Map</a></p>
    @endif

    @php
        $applied = \App\Models\JobApplication::where('job_id', $job->id)
            ->where('worker_id', auth()->id())
            ->exists();
    @endphp

    @if ($job->status == 'open')
        @if ($job->user_id == auth()->id())
            <span class="text-muted">This is your job.</span>
        @elseif ($applied)
            <button class="btn btn-secondary" disabled>Already Applied</button>
        @else
            <a href="{{ route('profile.show', $job->user_id) }}" class="btn btn-info">View Job Giver Profile</a>
            <form action="{{ route('apply.job', $job->id) }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn btn-success">Apply for Job</button>
            </form>
        @endif
    @elseif ($job->status == 'assigned' && ($job->user_id == auth()->id() || $job->assigned_worker_id == auth()->id()))
        <a href="{{ route('chat', $job->id) }}" class="btn btn-info">Chat</a>
        @if($job->assigned_worker_id == auth()->id())
            <h4>Contact Information</h4>
            <p><strong>Job Giver:</strong> {{ $job->user->name }}</p>
            <p><strong>Phone:</strong> {{ $job->user->phone ?? 'Not provided' }}</p>
            <p><strong>Address:</strong> {{ $job->user->address ?? 'Not provided' }}</p>
        @endif
    @elseif ($job->status == 'completed')
        <span class="text-muted">Chat Disabled</span>
    @endif
@endsection
