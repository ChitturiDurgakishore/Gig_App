@extends('layouts.app')

@section('content')
    <h3>Chat - {{ $job->title }}</h3>

    <div class="card">
        <div class="card-body" style="height:300px;overflow-y:scroll" id="messages">
            @foreach ($messages as $msg)
                <div class="mb-2">
                    <strong>{{ $msg->sender?->name ?? 'Unknown' }}:</strong> {{ $msg->message }}
                    <small class="text-muted">{{ $msg->created_at->format('H:i') }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <br>

    <form method="POST" action="{{ route('send.message', $job->id) }}">
        @csrf
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type message" required>
            <button class="btn btn-primary">Send</button>
        </div>
    </form>
@endsection

<script>
    setInterval(function() {
        location.reload();
    }, 10000);
</script>
