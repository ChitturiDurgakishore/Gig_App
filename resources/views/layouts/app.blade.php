<!DOCTYPE html>
<html>

<head>
    <title>Gig App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : url('/') }}">GigApp</a>

            @auth
                <div>
                    <a href="{{ route('create.job') }}" class="btn btn-success">Post Job</a>
                    <a href="{{ route('jobs') }}" class="btn btn-primary">Find Work</a>
                    <a href="{{ route('my.jobs') }}" class="btn btn-warning">My Jobs</a>
                    <a href="{{ route('my.applications') }}" class="btn btn-info me-2">My Applications</a>
                    <a href="{{ route('profile.my') }}" class="btn btn-secondary me-2">Profile</a>

                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-danger">Logout</button>
                    </form>
                </div>
            @else
                <div>
                    <a href="{{ route('login') }}" class="btn btn-outline-light">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-light">Register</a>
                    @endif
                </div>
            @endauth

        </div>
    </nav>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
