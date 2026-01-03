<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #171a21;
            color: #ffffff;
        }

        .admin-header {
            background-color: #1b2838;
            padding: 20px 0;
            border-bottom: 1px solid #2a475e;
        }

        .brand-link {
            color: #66c0f4;
            font-size: 1.5rem;
            font-weight: bold;
            transition: color 0.3s ease;
            cursor: default;
            text-decoration: none;
        }

        .brand-link:hover {
            color: #ffffff;
            text-shadow: 0 0 8px rgba(102, 192, 244, 0.4);
        }

        .btn-steam-green {
            background-color: #4c6b22;
            color: white;
            border: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-steam-green:hover {
            background-color: #67922d;
            color: white;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 8px;
            color: #000000;
            padding: 30px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div>
            <a class="navbar-brand" href="#">Gym System</a>
            <div>
                <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
                <a class="nav-link" href="#">Memberships</a>
            </div>
        </div>
    </nav>


    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>