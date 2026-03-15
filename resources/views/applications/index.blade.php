@extends('layouts.app')

@section('content')
    <h3>Applicants for: {{ $job->title }}</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Rating</th>
                <th>Profile</th>
                <th>Applied At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($applications as $app)
                <tr>
                    <td>{{ $app->worker->name }}</td>
                    <td>⭐{{ number_format($app->worker->rating, 1) }}</td>
                    <td><a href="{{ route('profile.show', $app->worker->id) }}" class="btn btn-info btn-sm">View Profile</a></td>
                    <td>{{ $app->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $app->status }}</td>
                    <td>
                        @if ($app->status == 'pending')
                            <form action="{{ route('accept.worker', $app->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-success">Accept Worker</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
