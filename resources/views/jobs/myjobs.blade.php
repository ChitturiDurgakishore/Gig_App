@extends('layouts.app')

@section('content')
    <h3>My Posted Jobs</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Price</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>₹{{ $job->price }}</td>
                    <td>{{ $job->status }}</td>
                    <td>{{ $job->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('job.applicants', $job->id) }}" class="btn btn-primary btn-sm">View Applicants</a>
                        @if ($job->status == 'assigned' && $job->user_id == auth()->id())
                            <form action="{{ route('job.complete', $job->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Mark Completed</button>
                            </form>
                        @elseif ($job->status == 'completed')
                            <span class="text-success">Completed</span>
                        @endif
                        @if ($job->status == 'assigned')
                            <a href="{{ route('chat', $job->id) }}" class="btn btn-info btn-sm">Chat</a>
                        @endif
                    </td>
                    <td>
                        @if ($job->status == 'completed' && $job->assigned_worker_id)
                            @php
                                $rating = \App\Models\Rating::where('job_id', $job->id)->where('from_user', auth()->id())->where('to_user', $job->assigned_worker_id)->first();
                            @endphp
                            @if ($rating)
                                Rated ⭐{{ str_repeat('⭐', $rating->rating) }}
                            @else
                                <a href="{{ route('rate.page', $job->id) }}" class="btn btn-warning btn-sm">Rate Worker</a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
