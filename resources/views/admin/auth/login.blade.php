<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Secure Login | Dispatch System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-light: rgb(247, 251, 252);
            --neutral-tint: rgb(214, 230, 242);
            --primary-accent: rgb(185, 215, 234);
            --main-dark: rgb(118, 159, 205);
            --text-dark: #2c3e50;
            --admin-warning: #fd7e14;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Login Container constraints */
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 1rem;
        }

        /* Brand Logo/Icon Area */
        .brand-icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: #ffffff;
            border: 2px solid var(--admin-warning);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto -40px auto;
            position: relative;
            z-index: 10;
            box-shadow: 0 4px 15px rgba(253, 126, 20, 0.15);
        }

        /* Main Login Card */
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--primary-accent);
            border-top: 4px solid var(--admin-warning);
            box-shadow: 0 8px 25px rgba(118, 159, 205, 0.1);
            padding: 3rem 1.5rem 1.5rem 1.5rem; /* Extra top padding to clear the overlapping icon */
        }

        /* Input Overrides */
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
        .input-group-text {
            background-color: #ffffff;
            border: 1px solid var(--primary-accent);
            color: var(--main-dark);
        }

        /* Action Button */
        .btn-main {
            background-color: var(--main-dark);
            color: white;
            transition: all 0.2s;
            font-weight: 600;
            padding: 0.75rem;
        }
        .btn-main:hover {
            background-color: #638ab5;
            color: white;
            transform: translateY(-1px);
        }

        /* Custom Checkbox */
        .form-check-input:checked {
            background-color: var(--main-dark);
            border-color: var(--main-dark);
        }
        
        .link-custom {
            color: var(--main-dark);
            text-decoration: none;
            font-weight: 600;
        }
        .link-custom:hover {
            color: var(--admin-warning);
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        
        <!-- Overlapping System Icon -->
        <div class="brand-icon-wrapper text-warning">
            <i class="bi bi-shield-lock-fill fs-1" style="color: var(--admin-warning);"></i>
        </div>

        <div class="login-card">
            
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1">Admin Portal</h4>
                <p class="text-muted small">Terminal Fleet Management System</p>
            </div>

            <!-- Laravel Error Handling Example -->
            <!-- 
            @if ($errors->any())
                <div class="alert alert-danger small py-2 mb-3 border-0 rounded-3" style="background-color: rgba(220,53,69,0.1); color: #dc3545;">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> Invalid credentials provided.
                </div>
            @endif 
            -->

            <form action="{{-- route('admin.login.submit') --}}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Administrator Email</label>
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text border-end-0 bg-white">
                            <i class="bi bi-envelope-fill text-muted"></i>
                        </span>
                        <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="admin@terminal.com" required autofocus value="{{-- old('email') --}}">
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-0">Password</label>
                        <!-- Optional Forgot Password Link -->
                        <a href="#" class="small link-custom" style="font-size: 0.75rem;">Forgot?</a>
                    </div>
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text border-end-0 bg-white">
                            <i class="bi bi-key-fill text-muted"></i>
                        </span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember" class="form-check-input shadow-sm" id="rememberMe">
                    <label class="form-check-label small text-muted user-select-none" for="rememberMe">
                        Keep me logged in
                    </label>
                </div>

                <button type="submit" class="btn btn-main w-100 rounded-3 shadow-sm text-uppercase d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-in-right fs-5 me-2"></i> Access Dashboard
                </button>
            </form>

        </div>
        
        <!-- Back to Main System Link -->
        <div class="text-center mt-4">
            <a href="{{-- route('home') --}}#" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Return to Driver App
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>