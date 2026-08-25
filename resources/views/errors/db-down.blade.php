<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Server Cannot Be Reached</title>

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
        }

        ::selection {
            background: var(--amber);
            color: var(--amber-ink);
        }

        .error-card {
            background: var(--card);
            border-radius: 14px;
            border: 1px solid var(--line);
            box-shadow: 0 4px 16px rgba(23, 27, 33, 0.06);
            padding: 3rem 2rem;
            max-width: 460px;
            width: 100%;
            text-align: center;
        }

        .error-icon {
            font-size: 4rem;
            color: var(--alert);
            margin-bottom: 1rem;
        }

        .error-title {
            font-family: var(--font-display);
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .error-code {
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--alert);
            background: rgba(209, 73, 91, 0.1);
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .error-message {
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-main {
            background-color: var(--amber);
            color: var(--amber-ink);
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: 0.85rem 1.5rem;
            box-shadow: 0 3px 0 #c78423;
            transition: filter 0.12s ease, transform 0.12s ease;
            width: 100%;
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

        button:focus-visible {
            outline: 2px solid var(--amber);
            outline-offset: 2px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container px-3">
        <div class="error-card mx-auto">
            <div class="mb-4">
                <img src="{{ asset('Logo.png') }}" alt="Logo"
                    style="max-width: 260px; height: auto; object-fit: contain;">
                <div class="mb-2 text-center">
                    <img src="{{ asset('jeepney_maintenance.png') }}" alt="Jeepney under maintenance"
                        style="max-width: 240px; height: auto; border-radius: 12px; mix-blend-mode: multiply;">
                </div>
                <h1 class="error-title">Connection Lost</h1>
                <div class="error-code">Error 503 : Database Offline</div>
                <p class="error-message">We are currently unable to reach the database server. Our team has been
                    notified. Please try again in a few moments.</p>
                <button onclick="location.reload()"
                    class="btn btn-main d-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-clockwise me-2 fs-5"></i> Retry Connection
                </button>
            </div>
        </div>
</body>

</html>
