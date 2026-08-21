<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Account Pending Approval | Transport System</title>

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

        .status-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 16px;
            border: 2px solid var(--primary-accent);
            box-shadow: 0 10px 30px rgba(118, 159, 205, 0.15);
        }

        /* Pulse Animation for Waiting State */
        .pulse-icon {
            animation: pulse-animation 2s infinite;
            background-color: var(--neutral-tint);
            color: var(--main-dark);
            width: 80px;
            height: 80px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        @keyframes pulse-animation {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(118, 159, 205, 0.4);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 15px rgba(118, 159, 205, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(118, 159, 205, 0);
            }
        }

        .btn-main {
            background-color: var(--main-dark);
            color: white;
            border: none;
            transition: all 0.2s;
        }

        .btn-main:hover,
        .btn-main:active {
            background-color: #638ab5;
            color: white;
        }

        .border-custom {
            border-color: var(--primary-accent) !important;
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
        <span
            class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 rounded-pill mb-3 fw-bold">
            <i class="bi bi-shield-fill-exclamation me-1"></i> Unverified Account
        </span>

        <p class="text-muted small px-2 mb-4">
            Thank you for registering with the <strong>Naga-Uling Tracking System</strong>. Your driver application has
            been received and is currently awaiting manual review by the terminal administrator.
        </p>

        <div class="bg-light p-3 rounded-3 border border-custom text-start mb-4">
            <h6 class="fw-bold mb-1 small text-uppercase text-muted"><i
                    class="bi bi-info-circle-fill text-info me-1"></i> Next Steps:</h6>
            <ul class="small text-dark mb-0 ps-3">
                <li>Head to the dispatcher office to verify your vehicle details.</li>
                <li>Ensure your plate number is correctly filed in the system.</li>
                <li>Once approved, refreshing this page will load your live dashboard.</li>
            </ul>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('profile.index') }}"
                class="btn btn-main py-3 fw-bold rounded-3 shadow-sm d-flex justify-content-center align-items-center">
                <i class="bi bi-arrow-clockwise me-2 fs-5"></i> Check Approval Status
            </a>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none text-muted small w-100 mt-2 py-1">
                    <i class="bi bi-box-arrow-left me-1"></i> Sign Out / Switch Account
                </button>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
