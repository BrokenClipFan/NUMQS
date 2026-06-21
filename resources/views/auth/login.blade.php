<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Login | Transport System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-light: rgb(247, 251, 252);
            --neutral-tint: rgb(214, 230, 242);
            --primary-accent: rgb(185, 215, 234);
            --main-dark: rgb(118, 159, 205);
            --text-dark: #2c3e50;
            --fb-blue: #1877F2;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            border: 2px solid var(--primary-accent);
            box-shadow: 0 10px 30px rgba(118, 159, 205, 0.15);
        }

        /* Custom Form Inputs */
        .form-control {
            background-color: var(--bg-light);
            border: 1px solid var(--primary-accent);
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: var(--main-dark);
            box-shadow: 0 0 0 0.25rem rgba(118, 159, 205, 0.25);
            background-color: #ffffff;
        }

        /* Custom Buttons & Accents */
        .btn-main {
            background-color: var(--main-dark);
            color: white;
            border: none;
            transition: all 0.2s;
        }

        .btn-main:hover, .btn-main:active {
            background-color: #638ab5;
            color: white;
            transform: translateY(-1px);
        }

        .btn-facebook {
            background-color: var(--fb-blue);
            color: white;
            border: none;
            transition: all 0.2s;
        }

        .btn-facebook:hover, .btn-facebook:active {
            background-color: #166fe5;
            color: white;
            transform: translateY(-1px);
        }

        .text-main-dark { color: var(--main-dark); }
        
        .form-check-input:checked {
            background-color: var(--main-dark);
            border-color: var(--main-dark);
        }

        /* Session Status Alert */
        .alert-status {
            background-color: var(--neutral-tint);
            color: var(--text-dark);
            border-color: var(--primary-accent);
        }
    </style>
</head>
<body>

    <div class="auth-card p-4 p-md-5">
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-custom-tint rounded-circle mb-3" style="width: 70px; height: 70px; background-color: var(--neutral-tint);">
                <i class="bi bi-truck-front-fill fs-1 text-main-dark"></i>
            </div>
            <h4 class="fw-bold mb-1">Naga-Uling Dispatch</h4>
            <p class="text-muted small">Enter your credentials to access the queue</p>
        </div>

        @if (session('status'))
            <div class="alert alert-status text-center small rounded-3 mb-4">
                {{ session('status') }}
            </div>
        @endif

        <a href="{{ route('facebook.redirect') }}" class="btn btn-facebook w-100 py-3 fw-bold rounded-3 shadow-sm d-flex justify-content-center align-items-center mb-3">
            <i class="bi bi-facebook fs-5 me-2"></i> Continue with Facebook
        </a>

        <div class="d-flex align-items-center my-4">
            <hr class="flex-grow-1 text-muted opacity-25">
            <span class="mx-3 text-muted small text-uppercase fw-bold">Or log in with email</span>
            <hr class="flex-grow-1 text-muted opacity-25">
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-bold small text-uppercase text-muted">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--primary-accent);">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
                @error('email')
                    <div class="text-danger small mt-1 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label fw-bold small text-uppercase text-muted mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none text-main-dark fw-bold">Forgot?</a>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--primary-accent);">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                </div>
                @error('password')
                    <div class="text-danger small mt-1 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-check">