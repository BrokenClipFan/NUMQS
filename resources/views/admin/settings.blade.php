<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings | NUMQS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #171B21;
            --stone: #F3F4F0;
            --card: #FFFFFF;
            --line: #E2E4DC;
            --amber: #F2A63C;
            --amber-hover: #D9902A;
            --amber-ink: #4A2E05;
            --primary: transparent;
            --font-display: 'Space Grotesk', 'Segoe UI', sans-serif;
            --font-body: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background-color: var(--stone);
            color: var(--ink);
            font-family: var(--font-body);
        }

        /* Navbar Fix */
        .nav-sticky-top {
            background-color: var(--ink);
            border-bottom: 3px solid var(--amber);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .admin-card {
            background-color: var(--card);
            border-radius: 16px;
            border: 1px solid var(--line);
        }

        .form-control {
            border-color: var(--line);
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 0.25rem rgba(242, 166, 60, 0.25);
        }

        .btn-custom-primary {
            background-color: var(--amber);
            color: var(--amber-ink);
            border-radius: 8px;
            border: none;
            font-weight: 600;
        }

        .btn-custom-primary:hover {
            background-color: var(--amber-hover);
            color: var(--amber-ink);
        }
    </style>
</head>

<body>
    @include('partials.admin-nav')

    <div class="container mt-5 pb-5 px-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <h2 class="fw-bold m-0 fs-3"><i class="bi bi-gear-fill text-warning me-2"></i>System Settings</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card admin-card shadow-sm">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                        <h5 class="fw-bold m-0"><i class="bi bi-sliders text-muted me-2"></i>Global Configuration</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="queue_timer_minutes" class="form-label fw-semibold text-muted small">Queue Departure Timer (Minutes)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-stopwatch text-muted"></i></span>
                                    <input type="number" class="form-control border-start-0 ps-0" id="queue_timer_minutes" name="queue_timer_minutes" 
                                           value="{{ $queueTimerMinutes }}" min="1" max="1440" required>
                                </div>
                                <div class="form-text mt-2" style="font-size: 0.85rem;">
                                    This timer determines exactly how many minutes a driver has to fill up their jeepney before their status turns <span class="text-danger fw-bold">OVERDUE</span>.
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-custom-primary px-4 py-2">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if(isset($terminals) && $terminals->count() > 0)
                <div class="card admin-card shadow-sm mt-4">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                        <h5 class="fw-bold m-0"><i class="bi bi-router text-muted me-2"></i>Terminal Wi-Fi Configuration</h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach($terminals as $terminal)
                        <form action="{{ route('admin.terminals.update', $terminal->id) }}" method="POST" class="mb-4 pb-4 border-bottom">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name_{{ $terminal->id }}" class="form-label fw-semibold text-muted small">Terminal Name</label>
                                <input type="text" class="form-control" id="name_{{ $terminal->id }}" name="name" 
                                       value="{{ $terminal->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="bssid_{{ $terminal->id }}" class="form-label fw-semibold text-muted small">Router Physical Address (BSSID)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 font-monospace"><i class="bi bi-broadcast"></i></span>
                                    <input type="text" class="form-control border-start-0 font-monospace" id="bssid_{{ $terminal->id }}" name="bssid" 
                                           value="{{ $terminal->bssid }}" required>
                                </div>
                                <div class="form-text mt-1" style="font-size: 0.8rem;">
                                    Format: XX:XX:XX:XX:XX:XX (Required for automatic GPS snapping)
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-outline-secondary btn-sm px-3 py-1">Update Terminal</button>
                            </div>
                        </form>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>