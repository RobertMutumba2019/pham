<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Centenary Stores Requisitioning')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        .sidebar {
            background: #ffffff;
            border-right: 1px solid #e0e0e0;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 1rem;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease-in-out;
        }
        .sidebar .logo {
            max-width: 150px;
            margin: 1rem auto;
            display: block;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            margin: 0.25rem 1rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .sidebar .nav-link:hover {
            background-color: #f0f4ff;
            color: #1a73e8;
        }
        .sidebar .nav-link.active {
            background-color: #1a73e8;
            color: #fff;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            transition: margin-left 0.3s ease-in-out;
        }
        .navbar-toggler {
            border: none;
            font-size: 1.5rem;
            color: #1a73e8;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .navbar-toggler {
                display: block;
            }
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        h1, h2, h3, h4, h5, h6 {
            color: #1a73e8;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #1a73e8;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1557b0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar" id="sidebar" aria-label="Main navigation">
                <img src="/images/centenary.png" alt="Centenary Stores Logo" class="logo">
                <!-- Sample sidebar content (replace with @include('layouts.sidebar')) -->
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link active" aria-current="page">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-box me-2"></i> Requisitions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <i class="fas fa-users me-2"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        @if(Auth::check())
        <li class="nav-item mb-2"><a href="{{ route('users.edit', Auth::id()) }}" class="nav-link">Account Settings</a></li>
        <li class="nav-item mb-2">
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-link nav-link" style="padding:0;">Logout</button>
            </form>
        </li>
    @endif
                       
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 main-content">
                <!-- Mobile Navbar Toggler -->
                <nav class="navbar navbar-light d-md-none p-2">
                    <button class="navbar-toggler" type="button" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>
                </nav>
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        const toggler = document.querySelector('.navbar-toggler');
        const sidebar = document.getElementById('sidebar');
        toggler.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 767.98 && !sidebar.contains(e.target) && !toggler.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>