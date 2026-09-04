<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pending Approval | NUMQS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --ink: #171B21;
            --ink-soft: #262C36;
            --stone: #E7E9E3;
            --card: #FDFDFB;
            --line: #D8DBD2;
            --amber: #F2A63C;
            --amber-ink: #4A2E05;
            --text-primary: #1B1F26;
            --text-muted: #6B7280;

            --font-display: 'Space Grotesk', 'Segoe UI', sans-serif;
            --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
            --font-body: 'Inter', 'Segoe UI', sans-serif;
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

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
        }

        .status-card {
            width: 100%;
            max-width: 440px;
            background: var(--card);
            border-radius: 16px;
            border: 1px solid var(--line);
            box-shadow: 0 10px 30px rgba(0,0,0, 0.05);
        }

        /* Pulse Animation for Waiting State */
        .pulse-icon {
            animation: pulse-animation 2s infinite;
            background-color: var(--stone);
            color: var(--ink);
            width: 80px;
            height: 80px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid var(--line);
        }

        @keyframes pulse-animation {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(23, 27, 33, 0.1);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 15px rgba(23, 27, 33, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(23, 27, 33, 0);
            }
        }

        .btn-main {
            background-color: var(--ink);
            color: #F4F5F1;
            border: none;
            transition: all 0.2s;
            font-family: var(--font-display);
        }

        .btn-main:hover,
        .btn-main:active {
            background-color: var(--ink-soft);
            color: #F4F5F1;
        }
        
        .badge-warning-custom {
            background: var(--amber) !important;
            color: var(--amber-ink) !important;
            font-family: var(--font-mono);
            letter-spacing: -0.01em;
            border: none !important;
        }
    </style>
</head>
<body>

    <div class="status-card p-4 p-md-5 text-center">

        <div class="mb-4">
            <div class="pulse-icon">
                <i class="bi bi-person-clock fs-1"></i>
            </div>
        </div>

        <h4 class="fw-bold mb-2">Verification Pending</h4>
        <span class="badge badge-warning-custom px-3 py-2 rounded-pill mb-3 fw-bold">
            <i class="bi bi-shield-fill-exclamation me-1"></i> UNVERIFIED ACCOUNT
        </span>

        <p class="text-muted small px-2 mb-4" style="line-height: 1.6;">
            Thank you for registering with the <strong>NUMQS Tracking System</strong>. Your driver application has been received and is currently awaiting manual review by the terminal administrator.
        </p>

        <div class="p-3 rounded-3 text-start mb-4" style="background: var(--stone); border: 1px solid var(--line);">
            <h6 class="fw-bold mb-2 small text-uppercase" style="color: var(--ink);"><i class="bi bi-info-circle-fill me-1"></i> Next Steps:</h6>
            <ul class="small text-muted mb-0 ps-3" style="line-height: 1.6;">
                <li class="mb-1">Head to the dispatcher office to verify your vehicle details.</li>
                <li class="mb-1">Ensure your plate number is correctly filed in the system.</li>
                <li>Once approved, click below to load your dashboard.</li>
            </ul>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('check.status') }}" class="btn btn-main py-3 fw-bold rounded-3 shadow-sm d-flex justify-content-center align-items-center">
                <i class="bi bi-arrow-clockwise me-2 fs-5"></i> Check Approval Status
            </a>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none text-muted small w-100 mt-2 py-1" style="font-family: var(--font-body);">
                    <i class="bi bi-box-arrow-left me-1"></i> Sign Out / Switch Account
                </button>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
