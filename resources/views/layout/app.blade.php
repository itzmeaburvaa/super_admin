<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 220px;
            background-color: #00264d;
            color: white;
        }
        .sidebar a {
            color: white;
            padding: 10px;
            display: block;
            text-decoration: none;
        }
        
        .dropdown-menu a {
    color: rgb(7, 41, 74) !important;
    background-color: white !important;
}

.dropdown-menu a:hover,
.dropdown-menu a:focus,
.dropdown-menu a:active {
    color: white !important;
    background-color: #1a5597 !important; /* nice blue hover bg */
}

        .content {
            flex: 1;
            padding: 20px;
            background-color: #f4f6f8;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4 class="text-center">Super Admin Panel</h4>
        <hr>
        <div class="mb-3">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    Manage Club
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('clubs.index') }}">View Clubs</a></li>
                    <li><a class="dropdown-item" href="{{ route('clubs.create') }}">Add Club</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
