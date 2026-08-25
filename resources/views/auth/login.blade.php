<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Login | Transport System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --ink: #171B21;
            --ink-soft: #262C36;
            --stone: #E7E9E3;
            --card: #FDFDFB;
            --line: #D8DBD2;
            --amber: #F2A63C;
            --amber-ink: #4A2E05;
            --alert: #D1495B;
            --text-primary: #1B1F26;
            --text-muted: #6B7280;
            --fb-blue: #1877F2;

            --font-display: 'Space Grotesk', 'Segoe UI', sans-serif;
            --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
            --font-body: 'Inter', 'Segoe UI', sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--stone);
            color: var(--text-primary);
            font-family: var(--font-body);
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        ::selection {
            background: var(--amber);
            color: var(--amber-ink);
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--card);
            border-radius: 14px;
            border: 1px solid var(--line);
            box-shadow: 0 4px 16px rgba(23, 27, 33, 0.06);
        }

        .auth-title {
            font-family: var(--font-display);
            font-weight: 700;
            color: var(--ink);
        }

        .field-label {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* Custom Form Inputs */
        .form-control {
            background-color: var(--stone);
            border: 1.5px solid var(--line);
            padding: 0.75rem 1rem;
            font-size: 0.88rem;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 3px rgba(242, 166, 60, 0.18);
            background-color: var(--card);
        }

        .input-group-text {
            background-color: var(--stone);
            border: 1.5px solid var(--line);
            color: var(--text-muted);
        }

        /* Custom Buttons & Accents */
        .btn-main {
            background-color: var(--amber);
            color: var(--amber-ink);
            font-weight: 700;
            border: none;
            box-shadow: 0 3px 0 #c78423;
            transition: filter 0.12s ease, transform 0.12s ease;
            font-family: var(--font-body);
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .btn-main:hover {
            filter: brightness(1.04);
            color: var(--amber-ink);
        }

        .btn-main:active {
            transform: scale(0.99);
        }

        .btn-facebook {
            background-color: var(--fb-blue);
            color: white;
            border: none;
            box-shadow: 0 3px 0 #125bb8;
            font-weight: 700;
            transition: filter 0.12s ease, transform 0.12s ease;
            font-family: var(--font-body);
        }

        .btn-facebook:hover, .btn-facebook:active {
            filter: brightness(1.08);
            color: white;
        }

        .btn-facebook:active {
            transform: scale(0.99);
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible {
            outline: 2px solid var(--amber);
            outline-offset: 2px;
            border-radius: 4px;
        }

        .text-amber { color: var(--amber-ink); }
        
        .form-check-input {
            border-color: var(--line);
            background-color: var(--stone);
        }

        .form-check-input:checked {
            background-color: var(--amber-ink);
            border-color: var(--amber-ink);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(242, 166, 60, 0.25);
            border-color: var(--amber);
        }

        /* Session Status Alert */
        .alert-status {
            background-color: rgba(242, 166, 60, 0.15);
            color: var(--amber-ink);
            border: 1px solid rgba(242, 166, 60, 0.35);
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <div class="auth-card p-4 p-md-5">
        
        <div class="text-center mb-4">
            <div class="mb-3">
                <img src="{{ asset('Logo.png') }}" alt="Logo" style="max-width: 180px; height: auto; object-fit: contain;">
            </div>
            <h4 class="auth-title mb-1">Naga-Uling Dispatch</h4>
            <p class="text-muted small" style="font-family: var(--font-mono); font-size: 0.72rem;">Enter your credentials to access the queue</p>
        </div>

        @if (session('status'))
            <div class="alert alert-status text-center rounded-3 mb-4">
                {{ session('status') }}
            </div>
        @endif

        <a href="{{ route('facebook.redirect') }}" class="btn btn-facebook w-100 py-3 rounded-3 d-flex justify-content-center align-items-center mb-3">
            <i class="bi bi-facebook fs-5 me-2"></i> Continue with Facebook
        </a>

        <div class="d-flex align-items-center my-4">
            <hr class="flex-grow-1 text-muted opacity-25">
            <span class="mx-3 text-muted" style="font-family: var(--font-mono); font-size: 0.65rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Or log in with email</span>
            <hr class="flex-grow-1 text-muted opacity-25">
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="field-label mb-2">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text border-end-0">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
                @error('email')
                    <div class="text-danger small mt-1 fw-bold" style="font-family: var(--font-mono); font-size: 0.7rem;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label for="password" class="field-label mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-amber fw-bold" style="font-family: var(--font-mono); font-size: 0.7rem;">Forgot?</a>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-text border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                </div>
                @error('password')
                    <div class="text-danger small mt-1 fw-bold" style="font-family: var(--font-mono); font-size: 0.7rem;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label text-muted fw-bold" for="remember" style="font-family: var(--font-body); font-size: 0.8rem;">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-main w-100 py-3 rounded-3 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i> Log in
            </button>

            @if (Route::has('register'))
                <div class="text-center mt-3">
                    <span class="text-muted" style="font-family: var(--font-body); font-size: 0.8rem;">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="text-decoration-none text-amber fw-bold" style="font-family: var(--font-body); font-size: 0.8rem;">Sign up</a>
                </div>
            @endif
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>