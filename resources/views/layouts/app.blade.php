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
            background: linear-gradient(135deg, #eef2f3, #8e9eab);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        .sidebar {
            background: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            padding: 1rem;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .sidebar .logo {
            width: 80%;
            margin: 0 auto 2rem;
            display: block;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1.2rem;
            border-radius: 12px;
            margin: 0.3rem 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }
        .main-content {
            margin-left: 260px;
            padding: 2rem;
        }
        .navbar-toggler {
            border: none;
            font-size: 1.5rem;
            color: #007bff;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1050;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: none;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #007bff;
            font-weight: 700;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.4rem;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar" id="sidebar">
                <img src="/images/centenary.png" alt="Centenary Stores Logo" class="logo">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-home me-2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('requisitions.index') }}" class="nav-link {{ request()->routeIs('requisitions.*') ? 'active' : '' }}"><i class="fas fa-box me-2"></i> Requisitions</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('approvals.pending') }}" class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}"><i class="fas fa-clock me-2"></i> Pending Approvals</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="fas fa-users me-2"></i> Users</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('attachments.index') }}" class="nav-link {{ request()->routeIs('attachments.*') ? 'active' : '' }}"><i class="fas fa-file me-2"></i> File Management</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('workflows.index') }}" class="nav-link {{ request()->routeIs('workflows.*') || request()->routeIs('approval-workflows.*') ? 'active' : '' }}"><i class="fas fa-sitemap me-2"></i> Workflows</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.dashboard') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="fas fa-chart-bar me-2"></i> Reports</a>
                    </li>
                    @if(Auth::check())
                    <li class="nav-item">
                        <a href="{{ route('users.edit', Auth::id()) }}" class="nav-link"><i class="fas fa-user-cog me-2"></i> Account Settings</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.change-password.form') }}" class="nav-link"><i class="fas fa-key me-2"></i> Update Credentials</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                        </form>
                    </li>
                    @endif
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 main-content">
                <!-- Mobile Navbar Toggler -->
                <nav class="navbar navbar-light d-md-none">
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
        toggler?.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 767.98 && !sidebar.contains(e.target) && !toggler.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
