<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Centenary Stores Requisitioning</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            font-family: 'Inter', sans-serif;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background-color: #1a73e8;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1557b0;
        }
        .btn-primary:disabled {
            background-color: #a0c4ff;
            cursor: not-allowed;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        }
        .alert {
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 576px) {
            .card {
                min-width: 300px;
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4">
            <!-- Logo (replace 'logo.png' with your actual logo) -->
            <img src="/images/centenary.png" alt="Centenary Stores Logo" class="logo mx-auto d-block">
            <h3 class="mb-4 text-center text-dark">Centenary Stores</h3>
<div class="container">
    <h2>Forgot Password</h2>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>
</div>
</body>
</html>