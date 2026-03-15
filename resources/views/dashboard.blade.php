@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("Welcome to Gig Marketplace!") }}
            </div>
        </div>
    </div>
</div>

<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex space-x-4">
            <a href="{{ route('create.job') }}" class="btn btn-primary">Post Job</a>
            <a href="{{ route('jobs') }}" class="btn btn-success">Find Work</a>
            <a href="{{ route('my.jobs') }}" class="btn btn-warning">My Jobs</a>
            <a href="{{ route('my.applications') }}" class="btn btn-info">My Applications</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
</div>
@endsection
