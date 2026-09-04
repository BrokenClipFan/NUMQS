<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Fleet Management | Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            /* Same dispatch-board token system as the driver map page */
            --ink: #171B21;
            --ink-soft: #262C36;
            --stone: #E7E9E3;
            --card: #FDFDFB;
            --line: #D8DBD2;
            --amber: #F2A63C;
            --amber-ink: #4A2E05;
            --route-naga: #3E7CA6;
            --route-uling: #2F8F6B;
            --alert: #D1495B;
            --text-primary: #1B1F26;
            --text-muted: #6B7280;

            --font-display: 'Space Grotesk', 'Segoe UI', sans-serif;
            --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
            --font-body: 'Inter', 'Segoe UI', sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--stone);
            color: var(--text-primary);
            font-family: var(--font-body);
            min-height: 100dvh;
        }

        ::selection { background: var(--amber); color: var(--amber-ink); }

        a:focus-visible, button:focus-visible, input:focus-visible {
            outline: 2px solid var(--amber);
            outline-offset: 2px;
            border-radius: 4px;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
        }

        .hidden { display: none !important; }

        /* ---------------------------------------------------------------
           Header
        ----------------------------------------------------------------*/
        .nav-sticky-top {
            background-color: var(--ink);
            border-bottom: 3px solid var(--amber);
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 14px rgba(0,0,0,0.25);
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #F4F5F1;
            font-family: var(--font-display);
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        .brand-mark .bi { color: var(--amber); font-size: 1.15rem; }

        .admin-readout {
            font-family: var(--font-mono);
            font-weight: 600;
            font-size: 0.75rem;
            background: var(--ink-soft);
            color: var(--amber);
            border: 1px solid rgba(242,166,60,0.35);
            padding: 0.4rem 0.7rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            letter-spacing: 0.03em;
        }

        .live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--amber);
            display: inline-block;
            animation: pulse-dot 1.8s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.45; transform: scale(0.8); }
        }

        @media (max-width: 400px) {
            .hide-on-mobile-xs { display: none !important; }
        }

        /* ---------------------------------------------------------------
           Metric cards
        ----------------------------------------------------------------*/
        .metric-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(23,27,33,0.04);
        }

        .metric-icon {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .metric-icon.amber { background: rgba(242,166,60,0.15); color: #A5691B; }
        .metric-icon.green { background: rgba(47,143,107,0.14); color: var(--route-uling); }
        .metric-icon.blue  { background: rgba(62,124,166,0.14); color: var(--route-naga); }
        .metric-icon.red   { background: rgba(209,73,91,0.14); color: var(--alert); }

        .metric-value {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0;
            line-height: 1.1;
        }
        .metric-label {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* ---------------------------------------------------------------
           Fleet card sections
        ----------------------------------------------------------------*/
        .fleet-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(23,27,33,0.06);
        }

        .fleet-card-header {
            padding: 1rem 1.1rem;
            font-family: var(--font-body);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .fleet-card-header.pending {
            background: rgba(242,166,60,0.12);
            border-bottom: 1px solid rgba(242,166,60,0.3);
            color: var(--text-primary);
        }
        .fleet-card-header.verified {
            background: var(--ink);
            color: #F4F5F1;
        }
        .fleet-card-header.violations {
            background: rgba(209, 73, 91, 0.1);
            border-bottom: 2px solid var(--alert);
            color: var(--ink);
        }

        .section-tag {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
        }
        .section-tag.pending { background: var(--amber); color: var(--amber-ink); }
        .section-tag.verified { background: rgba(255,255,255,0.14); color: #F4F5F1; border: 1px solid rgba(255,255,255,0.2); }
        .section-tag.violations { background: var(--alert); color: white; }

        /* ---------------------------------------------------------------
           Toolbar: search + results count
        ----------------------------------------------------------------*/
        .fleet-toolbar {
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            background: var(--card);
        }

        .search-bar {
            position: relative;
            flex: 1 1 220px;
            min-width: 0;
        }
        .search-bar .bi-search {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.85rem;
            pointer-events: none;
        }
        .search-bar input {
            width: 100%;
            padding: 0.55rem 0.75rem 0.55rem 2.1rem;
            border-radius: 20px;
            border: 1.5px solid var(--line);
            background: var(--stone);
            font-family: var(--font-body);
            font-size: 0.85rem;
            color: var(--text-primary);
        }
        .search-bar input:focus {
            outline: none;
            border-color: var(--amber);
            background: var(--card);
            box-shadow: 0 0 0 3px rgba(242,166,60,0.18);
        }

        .results-count {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            color: var(--text-muted);
            white-space: nowrap;
        }
        

        /* ---------------------------------------------------------------
           Account & Table rows
        ----------------------------------------------------------------*/
        .account-item {
            border-bottom: 1px solid var(--line);
            transition: background-color 0.15s ease;
        }
        .account-item:last-child { border-bottom: none; }
        .account-item:hover { background-color: rgba(214, 230, 242, 0.15); }

        .avatar-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.85rem;
            overflow: hidden;
        }
        .avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .avatar-circle.pending { background: rgba(242,166,60,0.15); color: #A5691B; border: 1px solid rgba(242,166,60,0.35); }
        .avatar-circle.verified { background: rgba(47,143,107,0.14); color: var(--route-uling); border: 1px solid rgba(47,143,107,0.3); }

        .account-name {
            font-family: var(--font-body);
            font-weight: 700;
            font-size: 0.92rem;
            margin: 0;
            color: var(--text-primary);
        }

        .account-meta {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--text-muted);
        }

        .plate-pill {
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 0.75rem;
            background: var(--stone);
            border: 1px solid var(--line);
            color: var(--text-primary);
            padding: 0.3rem 0.55rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .status-pill {
            font-family: var(--font-mono);
            font-size: 0.66rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            padding: 0.3rem 0.55rem;
            border-radius: 20px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .status-pill.verified { background: rgba(47,143,107,0.12); color: var(--route-uling); border: 1px solid rgba(47,143,107,0.3); }

        /* Severity Pill Configuration */
        .severity-pill {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            text-transform: uppercase;
            display: inline-block;
        }
        .severity-pill.low { background: rgba(47,143,107,0.12); color: var(--route-uling); border: 1px solid rgba(47,143,107,0.3); }
        .severity-pill.medium { background: rgba(242,166,60,0.12); color: #A5691B; border: 1px solid rgba(242,166,60,0.3); }
        .severity-pill.high { background: rgba(209,73,91,0.12); color: var(--alert); border: 1px solid rgba(209,73,91,0.3); }
        .severity-pill.critical { background: var(--alert); color: white; border: 1px solid var(--alert); }

        /* ---------------------------------------------------------------
           Buttons
        ----------------------------------------------------------------*/
        .btn-verify {
            background-color: var(--amber);
            color: var(--amber-ink);
            font-weight: 700;
            font-size: 0.82rem;
            border: none;
            box-shadow: 0 2px 0 #c78423;
            transition: filter 0.12s ease, transform 0.12s ease;
        }
        .btn-verify:hover { filter: brightness(1.04); color: var(--amber-ink); }
        .btn-verify:active { transform: scale(0.97); }

        .btn-delete-account {
            background-color: transparent;
            color: var(--alert);
            border: 1.5px solid var(--alert);
            font-weight: 700;
            font-size: 0.82rem;
        }
        .btn-delete-account:hover { background-color: var(--alert); color: white; }

        /* Custom design tweaks for our themed delete modal */
        .modal-content-custom {
            background: var(--card);
            border: 2px solid var(--line);
            border-radius: 14px;
        }
        .modal-header-custom {
            border-bottom: 1px solid var(--line);
            background: rgba(209, 73, 91, 0.08);
            color: var(--alert);
        }

        /* ---------------------------------------------------------------
           Pagination
        ----------------------------------------------------------------*/
        .pager {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.9rem 1.1rem;
            border-top: 1px solid var(--line);
            background: var(--card);
        }
        .pager-btn {
            font-family: var(--font-mono);
            font-weight: 600;
            font-size: 0.75rem;
            background: var(--ink);
            color: #F4F5F1;
            border: none;
            border-radius: 20px;
            padding: 0.4rem 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .pager-btn:disabled { opacity: 0.35; }
        .pager-label {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--text-muted);
            letter-spacing: 0.03em;
        }

        /* ---------------------------------------------------------------
           Empty / no-results state
        ----------------------------------------------------------------*/
        .empty-state {
            font-family: var(--font-body);
            font-size: 0.85rem;
            text-align: center;
            padding: 2.25rem 1rem;
            color: var(--text-muted);
        }
        .empty-state .bi { font-size: 1.4rem; display: block; margin-bottom: 0.4rem; opacity: 0.5; }

        /* ---------------------------------------------------------------
           Mobile tweaks
        ----------------------------------------------------------------*/
        @media (max-width: 576px) {
            .account-item .row > div { margin-bottom: 0.15rem; }
            .account-item-actions { width: 100%; }
            .account-item-actions form,
            .account-item-actions a { width: 100%; }
            .account-item-actions .btn { width: 100%; justify-content: center; }
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            color: #F4F5F1;
            font-family: var(--font-display);
            font-weight: 700;
            text-decoration: none;
        }
        .back-link .bi-arrow-left-short { font-size: 1.5rem; color: var(--amber); }
        .back-link:hover { color: var(--amber); }

        @media print {
            body * {
                visibility: hidden;
            }
            #violationDetailModal, #violationDetailModal * {
                visibility: visible;
            }
            #violationDetailModal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                border: none;
            }
            .modal-dialog {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .modal-content {
                box-shadow: none !important;
                border: none !important;
            }
            pre {
                background-color: #f8f9fa !important;
                color: #212529 !important;
                border: 1px solid #dee2e6 !important;
            }
        }

        @media (max-width: 768px) {
            /* Make search toolbars full width on tablets and phones */
            .fleet-toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
            .search-bar { flex: 1 1 100%; max-width: 100%; }
            .results-count { text-align: left; }
            
            /* Metric cards spacing */
            .metric-card { padding: 1rem !important; gap: 0.75rem !important; }
        }

        @media (max-width: 576px) {
            /* Stack Metric Cards neatly */
            .metric-card {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            /* Stack Account List Items vertically */
            .account-item {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 1rem !important;
            }
            
            /* Ensure the inner content takes full width */
            .account-item > div:first-child {
                width: 100%;
            }

            /* Reset Bootstrap row margins inside flex containers to prevent horizontal scroll */
            .account-item .row { 
                margin: 0; 
                width: 100%; 
            }
            
            /* Stack the inner columns (Name, Plate, Action Buttons) */
            .account-item .row > div { 
                padding-left: 0; 
                padding-right: 0; 
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 0.75rem;
            }
            .account-item .row > div:last-child {
                margin-bottom: 0;
                gap: 0.5rem;
            }

            /* Make all action buttons full-width for easier thumb-tapping */
            .account-item-actions { 
                width: 100%; 
                display: flex; 
                flex-direction: column; 
                gap: 0.5rem; 
                margin-top: 0.5rem;
            }
            .account-item-actions form,
            .account-item-actions a,
            .account-item-actions button,
            .account-item .row > div a { 
                width: 100%; 
                justify-content: center; 
            }
            
            /* Table formatting: Prevent data from wrapping weirdly, force horizontal scroll */
            #violationTable td, #violationTable th {
                white-space: nowrap;
            }
            
            /* Force table action buttons to not wrap awkwardly */
            #violationTable .d-inline-flex {
                flex-wrap: nowrap;
            }
        }

    </style>
</head>
<body>
    @include('partials.admin-nav')
    @include('partials.notifications')

    <div class="container-fluid px-3 px-md-4 py-4">

        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="mb-0 fs-3 fw-bold" style="color: var(--ink); font-family: var(--font-display);">
                    <i class="bi bi-person-badge-fill" style="color: var(--amber);"></i> Dispatcher Accounts
                </h1>
                <p class="mb-0 text-muted">Manage terminal dispatchers and revoke access if necessary.</p>
            </div>
        </div>

        <!-- Dispatchers List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background-color: var(--card);">
                    <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center" style="background-color: var(--ink); border-color: var(--line);">
                        <h6 class="mb-0 fw-bold d-flex align-items-center gap-2" style="color: var(--stone); font-family: var(--font-display);">
                            <i class="bi bi-shield-lock-fill" style="color: var(--amber);"></i> Active Dispatchers
                        </h6>
                        <span class="badge bg-custom-tint text-dark px-3 py-2 rounded-pill shadow-sm" style="font-family: var(--font-mono); background-color: var(--stone); color: var(--ink);">
                            {{ count($dispatchers) }} total
                        </span>
                    </div>

                    <div class="p-0">
                        @if (count($dispatchers) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle custom-table">
                                    <thead style="background-color: var(--stone); border-bottom: 2px solid var(--line);">
                                        <tr>
                                            <th class="border-0 text-secondary fw-semibold py-3 ps-4" style="font-size: 0.85rem; border-top-left-radius: 6px;">DISPATCHER</th>
                                            <th class="border-0 text-secondary fw-semibold py-3" style="font-size: 0.85rem;">EMAIL</th>
                                            <th class="border-0 text-secondary fw-semibold py-3" style="font-size: 0.85rem;">DATE GRANTED</th>
                                            <th class="border-0 text-secondary fw-semibold py-3 text-end pe-4" style="font-size: 0.85rem; border-top-right-radius: 6px;">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dispatchers-body">
                                        @foreach ($dispatchers as $dispatcher)
                                            <tr class="queue-row border-bottom" style="border-color: var(--line) !important; transition: background-color 0.2s ease;">
                                                <td class="py-3 ps-4">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="avatar-circle verified" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: var(--stone); color: var(--ink); font-weight: 700; border: 2px solid var(--amber);">
                                                            {{ strtoupper(substr($dispatcher->name, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold" style="color: var(--ink);">{{ $dispatcher->name }}</div>
                                                            <div class="text-muted small" style="font-family: var(--font-mono);">ID: {{ $dispatcher->id }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <span class="badge" style="background-color: var(--stone); color: var(--ink); border: 1px solid var(--line);">
                                                        <i class="bi bi-envelope-fill me-1"></i>{{ $dispatcher->email }}
                                                    </span>
                                                </td>
                                                <td class="py-3">
                                                    <div class="text-muted small">
                                                        <i class="bi bi-calendar-check me-1"></i> {{ $dispatcher->updated_at->format('M d, Y h:i A') }}
                                                    </div>
                                                </td>
                                                <td class="py-3 text-end pe-4">
                                                    <form method="POST" action="{{ route('admin.dispatchers.revoke', $dispatcher->id) }}" class="d-inline-block m-0" onsubmit="return confirm('Are you sure you want to revoke this dispatcher account? They will lose all access immediately.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-2 rounded-3 d-inline-flex align-items-center justify-content-center gap-1 shadow-sm" title="Revoke Account" style="border-width: 2px;">
                                                            <i class="bi bi-trash3-fill"></i> <span class="d-none d-xl-inline">Revoke Account</span>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 empty-state">
                                <i class="bi bi-person-x-fill text-muted mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                                <h5 class="fw-bold text-dark mb-1">No Dispatchers Found</h5>
                                <p class="text-muted mb-0">You have not assigned any users to the dispatcher role yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
