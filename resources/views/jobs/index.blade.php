@extends('layouts.app')

@section('content')
    <h3>Available Jobs</h3>

    <div class="row">

        @foreach ($jobs as $job)
            <div class="col-md-4">

                <div class="card mb-3">

                    <div class="card-body">

                        <h5>{{ $job->title }}</h5>

                        <p>{{ $job->description }}</p>

                        <p><strong>Price:</strong> ₹{{ $job->price }}</p>

                        <p><strong>Duration:</strong> {{ $job->duration }}</p>

                        @if(auth()->check() && auth()->user()->latitude && auth()->user()->longitude)
                            @php $distance = $job->getDistanceTo(auth()->user()->latitude, auth()->user()->longitude); @endphp
                            @if($distance)
                                <p><strong>Distance:</strong> {{ round($distance, 1) }} km</p>
                            @endif
                        @endif

                        <a href="/job/{{ $job->id }}" class="btn btn-primary">View</a>

                    </div>

                </div>

            </div>
        @endforeach

    </div>
@endsection
