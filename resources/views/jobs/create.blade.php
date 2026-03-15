@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Job</h2>

        <form method="POST" action="{{ route('store.job') }}">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" name="category" class="form-control">
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price (₹)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="duration" class="form-label">Duration</label>
                <input type="text" name="duration" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <button type="button" id="getLocation" class="btn btn-secondary">Get Current Location</button>
            </div>

            <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Create Job</button>
        </form>

        <script>
            document.getElementById('getLocation').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                    }, function(error) {
                        alert('Error getting location: ' + error.message);
                    });
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });
        </script>
    </div>
@endsection
