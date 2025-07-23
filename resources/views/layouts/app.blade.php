<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Centenary Stores Requisitioning')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                @include('layouts.sidebar')
            </nav>
            <main class="col-md-10 ms-sm-auto px-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html> 