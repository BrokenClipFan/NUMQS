<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Registration | Transport System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-light: rgb(247, 251, 252);
            --neutral-tint: rgb(214, 230, 242);
            --primary-accent: rgb(185, 215, 234);
            --main-dark: rgb(118, 159, 205);
            --text-dark: #2c3e50;
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
            max-width: 450px;
            background: #ffffff;
            border-radius: 16px;
            border: 2px solid var(--primary-accent);
            box-shadow: 0 10px 30px rgba(118, 159, 205, 0.15);
        }

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

        .text-main-dark { color: var(--main-dark); }
    </style>
</head>
<body>

    <div class="auth-card p-4 p-md-5 my-3">
        
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">Driver Registration</h4>
            <p class="text-muted small">Create your profile to join the terminal queue</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-bold small text-uppercase text-muted">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--primary-accent);">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input id="name" type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                </div>
                @error('name')
                    <div class="text-danger small mt-1 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold small text-uppercase text-muted">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--primary-accent);">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="username">
                </div>
                @error('email')
                    <div class="text-danger small mt-1 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold small text-uppercase text-muted">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--primary-accent);">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                </div>
                @error('password')
                    <div class="text-danger small mt-1 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-bold small text-uppercase text-muted">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--primary-accent);">
                        <i class="bi bi-check-circle text-muted"></i>
                    </span>
                    <input id="password_confirmation" type="password" class="form-control border-start-0 ps-0 @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                </div>
                @error('password_confirmation')
                    <div class="text-danger small mt-1 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-main w-100 py-3 fw-bold rounded-3 shadow-sm d-flex justify-content-center align-items-center">
                Create Account <i class="bi bi-person-plus-fill ms-2"></i>
            </button>
        </form>

        <div class="mt-4 text-center">
            <span class="text-muted small">Already registered?</span>
            <a href="{{ route('login') }}" class="text-decoration-none text-main-dark fw-bold small ms-1">Sign in here</a>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>