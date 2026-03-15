@extends('layouts.app')

@section('content')
    <h3>My Applications</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Job</th>
                <th>Price</th>
                <th>Status</th>
                <th>Applied At</th>
                <th>Details</th>
                <th>Chat</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($applications as $app)
                <tr>
                    <td>{{ $app->job->title }}</td>
                    <td>₹{{ $app->job->price }}</td>
                    <td>{{ $app->status }}</td>
                    <td>{{ $app->created_at->format('d M Y H:i') }}</td>
                    <td><a href="{{ route('job.show', $app->job->id) }}" class="btn btn-primary btn-sm">View Details</a></td>
                    <td>
                        @if ($app->status == 'accepted')
                            <a href="{{ route('chat', $app->job->id) }}" class="btn btn-success btn-sm">Chat</a>
                        @else
                            <span class="text-muted">Not Available</span>
                        @endif
                    </td>
                    <td>
                        @if ($app->job->status == 'completed')
                            @php
                                $rating = \App\Models\Rating::where('job_id', $app->job->id)->where('from_user', auth()->id())->where('to_user', $app->job->user_id)->first();
                            @endphp
                            @if ($rating)
                                Rated ⭐{{ str_repeat('⭐', $rating->rating) }}
                            @else
                                <a href="{{ route('rate.page', $app->job->id) }}" class="btn btn-warning btn-sm">Rate Job Giver</a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
