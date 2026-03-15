@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Rate User for Job: {{ $job->title }}</h2>

    <form method="POST" action="{{ route('rate', $job->id) }}">
        @csrf
        <input type="hidden" name="to_user" value="{{ $to_user }}">

        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="review" class="form-label">Review</label>
            <textarea name="review" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-warning">Submit Rating</button>
    </form>
</div>
@endsection
