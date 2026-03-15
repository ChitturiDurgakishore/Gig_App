@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $user->name }}'s Profile</h2>

    @if(auth()->check() && auth()->id() == $user->id)
        <a href="{{ route('profile.edit-profile') }}" class="btn btn-primary">Edit Profile</a>
    @endif

    <div class="row">
        <div class="col-md-6">
            <h4>Basic Information</h4>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            @if($showContact ?? false)
                <p><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                <p><strong>Address:</strong> {{ $user->address ?? 'Not provided' }}</p>
                <p><strong>Description:</strong> {{ $user->profile_description ?? 'No description' }}</p>
                @if($user->latitude && $user->longitude)
                    <p><strong>Location:</strong> <a href="https://www.google.com/maps?q={{ $user->latitude }},{{ $user->longitude }}" target="_blank">View on Map</a></p>
                @endif
            @else
                <p><em>Contact details available after job assignment</em></p>
            @endif
            <p><strong>Average Rating:</strong> ⭐{{ number_format($averageRating, 1) }} ({{ $totalReviews }} reviews)</p>
        </div>
        <div class="col-md-6">
            <h4>Reviews</h4>
            @foreach ($user->ratingsReceived as $rating)
                <div class="border p-2 mb-2">
                    <strong>⭐{{ $rating->rating }}</strong> - {{ $rating->review }}
                    <small class="text-muted">by {{ $rating->fromUser->name }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <h4>Jobs Posted</h4>
    <ul>
        @foreach ($jobsPosted as $job)
            <li>{{ $job->title }} - ₹{{ $job->price }} - {{ $job->status }}</li>
        @endforeach
    </ul>

    <h4>Jobs Completed</h4>
    <ul>
        @foreach ($jobsCompleted as $job)
            <li>{{ $job->title }} - ₹{{ $job->price }}</li>
        @endforeach
    </ul>
</div>
@endsection
