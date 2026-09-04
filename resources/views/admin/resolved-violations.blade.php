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

    @php
        $initialsOf = function (string $text): string {
            $parts = array_filter(preg_split('/\s+/', trim($text)));
            $letters = array_map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)), array_slice($parts, 0, 2));
            return implode('', $letters) ?: '?';
        };
    @endphp

    @include('partials.admin-nav')
    @include('partials.notifications');

    <div class="container-fluid px-3 px-md-4 py-4">

        <!-- SECTION 3: CHEATING WARNINGS -->
        <div class="fleet-card">
            <div class="fleet-card-header violations bg-success bg-opacity-10 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-success"><i class="bi bi-check-circle-fill me-2"></i>Resolved Cheating Warnings</span>
                    <span class="section-tag bg-success text-white">Resolved Logs</span>
                </div>
                <a href="{{ route('fleet.management') }}" class="btn btn-sm btn-light border-secondary rounded-3 px-3 fw-bold">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            @if(count($violations) > 0)
            <div class="fleet-toolbar">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <label for="violationSearch" class="visually-hidden">Search cheating warnings</label>
                    <input type="search" id="violationSearch" placeholder="Search by driver name or warning type…" autocomplete="off">
                </div>
                <span class="results-count" id="violationResultsCount"></span>
            </div>
            @endif

            <div class="table-responsive m-0">
                <table class="table table-hover align-middle m-0" id="violationTable" data-page-size="8" style="background: var(--card);">
                    <thead class="table-dark font-monospace" style="font-size: 0.75rem; background-color: var(--ink);">
                        <tr>
                            <th class="ps-3 border-0">Driver</th>
                            <th class="border-0">Warning Type</th>
                            <th class="border-0">Location</th>
                            <th class="border-0 text-center">Severity</th>
                            <th class="border-0 text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="violationList">
                        @forelse($violations as $violation)
                            @php
                                $driverName = trim(($violation->profile->first_name ?? 'Unknown') . ' ' . ($violation->profile->last_name ?? 'Driver'));
                                $severityClass = strtolower($violation->severity ?? 'low');
                            @endphp
                            <!-- Primary Row Contextual Data -->
                            <tr class="account-item" data-search="{{ strtolower($driverName . ' ' . $violation->type . ' ' . $violation->name . ' ' . $violation->severity) }}">
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="font-monospace small fw-bold text-secondary">#{{ $violation->driver_profile_id }}</div>
                                        <div>
                                            <h6 class="account-name mb-0">{{ $driverName }}</h6>
                                            <span class="text-muted small font-monospace" style="font-size: 0.68rem;">Log Ref: #{{ $violation->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size: 0.88rem;">{{ $violation->name }}</div>
                                    <span class="badge bg-secondary-subtle text-secondary font-monospace" style="font-size: 0.65rem;">{{ $violation->type }}</span>
                                </td>
                                <td>
                                    <span class="small text-muted"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $violation->location ?? 'N/A' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="severity-pill {{ $severityClass }}">{{ $violation->severity }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-inline-flex gap-1">
                                        @if(!empty($violation->properties))
                                        <button type="button" 
                                                class="btn btn-outline-secondary btn-sm px-2 py-2 rounded-3 d-inline-flex align-items-center justify-content-center gap-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#violationDetailModal"
                                                data-id="{{ $violation->id }}"
                                                data-driver="{{ $driverName }}"
                                                data-profile-id="{{ $violation->driver_profile_id }}"
                                                data-name="{{ $violation->name }}"
                                                data-type="{{ $violation->type }}"
                                                data-location="{{ $violation->location ?? 'N/A' }}"
                                                data-severity="{{ strtoupper($violation->severity) }}"
                                                data-severity-class="{{ $severityClass }}"
                                                data-date="{{ $violation->created_at ? $violation->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s') }}"
                                                data-properties="{{ json_encode($violation->properties) }}"
                                                title="View Warning Details">
                                            <i class="bi bi-file-text"></i> View
                                        </button>
                                    @endif

                                        @if($violation->profile && $violation->profile->user_id)
                                            <a href="{{ route('view.driver', $violation->profile->user_id) }}" class="btn btn-outline-success btn-sm px-2 py-2 rounded-3 d-inline-flex align-items-center justify-content-center gap-1" title="View Profile">
                                                <i class="bi bi-eye"></i> Profile
                                            </a>
                                        @endif

                                        <span class="badge bg-success-subtle text-success px-2 py-2 rounded-3 d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-check-all"></i> Resolved
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="js-empty-tr">
                                <td colspan="5" class="border-0">
                                    <div class="empty-state">
                                        <i class="bi bi-shield-check-fill text-success fs-3"></i>
                                        Ecosystem clear. No profile structural violations logged.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(count($violations) > 0)
            <div class="pager hidden" id="violationPager">
                <button type="button" class="pager-btn pager-prev"><i class="bi bi-chevron-left"></i> Prev</button>
                <span class="pager-label pager-page-label">Page 1 of 1</span>
                <button type="button" class="pager-btn pager-next">Next <i class="bi bi-chevron-right"></i></button>
            </div>
            @endif
        </div>

    </div>

    <!-- GLOBAL CRITICAL REMOVAL MODAL (DRIVERS) -->
    <div class="modal fade" id="deleteConfirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom shadow-lg">
                <div class="modal-header modal-header-custom p-3">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle-fill"></i> Critical Security Warning
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="fs-6 mb-2">Are you completely sure you want to permanently delete the driver account for <strong id="deleteTargetName" class="text-dark"></strong>?</p>
                    <p class="text-muted small mb-0 font-monospace bg-light p-2 rounded border">
                        <i class="bi bi-info-circle me-1 text-danger"></i> This action removes all historical log coordinates, plate links, and queue metrics from the live ecosystem database.
                    </p>
                </div>
                <div class="modal-footer p-3 bg-light border-top">
                    <button type="button" class="btn btn-sm btn-secondary px-3 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <form id="globalDeleteForm" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3 py-2 rounded-3 fw-bold shadow-sm">
                            <i class="bi bi-trash3 me-1"></i> Permanently Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- VIOLATION REMOVAL MODAL -->
    <div class="modal fade" id="violationDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom shadow-lg">
                <div class="modal-header modal-header-custom p-3 bg-opacity-10">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="violationModalLabel">
                        <i class="bi bi-check-circle-fill text-success"></i> Resolve Warning
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="fs-6 mb-2">You are resolving the cheating warning for: <strong id="violationName" class="text-danger"></strong></p>
                    <p class="text-muted small">Associated Driver: <span id="violationDriverName" class="fw-bold text-dark"></span></p>
                    <p class="text-muted small mb-0 font-monospace bg-light p-2 rounded border">
                        <i class="bi bi-info-circle me-1 text-warning"></i> Resolving this warning clears the driver and marks the issue as settled.
                    </p>
                </div>
                <div class="modal-footer p-3 bg-light border-top">
                    <button type="button" class="btn btn-sm btn-secondary px-3 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <form id="violationDeleteForm" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-success px-3 py-2 rounded-3 fw-bold shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Resolve Warning
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Violation Detail & Telemetry Modal -->
    <div class="modal fade" id="violationDetailModal" tabindex="-1" aria-labelledby="violationDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <!-- Modal Header (Hidden during print) -->
                <div class="modal-header bg-dark text-white border-0 d-print-none">
                    <h5 class="modal-title font-monospace small" id="violationDetailModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>CHEATING WARNING RECORD
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Printable Body Container -->
                <div class="modal-body p-4 p-md-5" id="printableViolationArea">
                    <!-- Print Header (Only visible when printing) -->
                    <div class="d-none d-print-block text-center mb-4 border-bottom pb-3">
                        <h3 class="fw-bold text-uppercase tracking-wider mb-1">Driver Cheating Warning</h3>
                        <p class="text-muted small font-monospace mb-0">Official Warning Record</p>
                    </div>

                    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
                        <div>
                            <span class="text-muted font-monospace small d-block">RECORD NUMBER</span>
                            <h4 class="fw-bold text-dark font-monospace mb-0">#<span id="modal-log-id"></span></h4>
                        </div>
                        <div class="text-end">
                            <span class="text-muted font-monospace small d-block">TIMESTAMP</span>
                            <span class="fw-semibold font-monospace" id="modal-timestamp"></span>
                        </div>
                    </div>

                    <!-- Structured Meta Information -->
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted font-monospace d-block text-uppercase correlation-label mb-1" style="font-size: 0.7rem;">Driver Name</span>
                                <h6 class="fw-bold text-dark mb-1" id="modal-driver-name"></h6>
                                <span class="text-secondary small font-monospace d-block">Profile Ref: #<span id="modal-profile-id"></span></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted font-monospace d-block text-uppercase correlation-label mb-1" style="font-size: 0.7rem;">Reason for Warning</span>
                                <h6 class="fw-bold text-dark mb-1" id="modal-violation-name"></h6>
                                <span class="badge font-monospace bg-secondary-subtle text-secondary" id="modal-violation-type" style="font-size: 0.68rem;"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted font-monospace d-block text-uppercase correlation-label mb-1" style="font-size: 0.7rem;">Location</span>
                                <span class="text-dark fw-semibold small"><i class="bi bi-geo-alt-fill text-danger me-1"></i><span id="modal-location"></span></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted font-monospace d-block text-uppercase correlation-label mb-1" style="font-size: 0.7rem;">Severity</span>
                                <span class="badge font-monospace fw-bold" id="modal-severity-badge"></span>
                            </div>
                        </div>
                    </div>

                    <!-- JSON Telemetry & Parameters Section -->
                    <div class="mt-2">
                        <div class="font-monospace text-dark small mb-2 fw-bold d-flex align-items-center">
                            <i class="bi bi-info-circle me-2 text-secondary"></i>ADDITIONAL DETAILS
                        </div>
                        <div class="bg-light p-3 rounded-3 border small mb-0 shadow-sm" id="modal-properties-raw"></div>
                    </div>

                    <!-- Verification Stamp Footer (Only visible when printing) -->
                    <div class="d-none d-print-block mt-5 pt-4 border-top">
                        <div class="row align-items-end text-center">
                            <div class="col-4">
                                <div class="border-top mx-auto pt-2 small font-monospace text-muted" style="width: 80%;">System Operator</div>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-shield-check text-success fs-1 d-block lh-1 mb-1"></i>
                                <span class="font-monospace text-success small fw-bold">SECURED RECORD</span>
                            </div>
                            <div class="col-4">
                                <div class="border-top mx-auto pt-2 small font-monospace text-muted" style="width: 80%;">Authorized Signature</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer Controls (Hidden during print) -->
                <div class="modal-footer bg-light border-0 d-print-none justify-content-between">
                    <button type="button" class="btn btn-secondary px-3 py-2 rounded-3 small font-monospace" data-bs-dismiss="modal">Close Window</button>
                    <button type="button" class="btn btn-dark px-4 py-2 rounded-3 font-monospace fw-bold" onclick="window.print();">
                        <i class="bi bi-printer me-2"></i>Print Warning Record
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const violationModal = document.getElementById('violationDetailModal');
        if (violationModal) {
            violationModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                const button = event.relatedTarget;
                
                // Extract info from data-* attributes
                const id = button.getAttribute('data-id');
                const driver = button.getAttribute('data-driver');
                const profileId = button.getAttribute('data-profile-id');
                const name = button.getAttribute('data-name');
                const type = button.getAttribute('data-type');
                const location = button.getAttribute('data-location');
                const severity = button.getAttribute('data-severity');
                const severityClass = button.getAttribute('data-severity-class');
                const date = button.getAttribute('data-date');
                
                // Format and parse the JSON string beautifully
                let rawProps = button.getAttribute('data-properties');
                let propertiesHtml = '';
                try {
                    const parsed = JSON.parse(rawProps);
                    for (const [key, value] of Object.entries(parsed)) {
                        propertiesHtml += `<div class="mb-1"><strong class="text-dark">${key}:</strong> <span class="text-secondary">${value}</span></div>`;
                    }
                } catch (e) {
                    // Fallback if parsing fails
                    propertiesHtml = `<div class="text-secondary">${rawProps}</div>`;
                }

                // Hydrate the fields inside the modal DOM elements
                document.getElementById('modal-log-id').textContent = id;
                document.getElementById('modal-timestamp').textContent = date;
                document.getElementById('modal-driver-name').textContent = driver;
                document.getElementById('modal-profile-id').textContent = profileId;
                document.getElementById('modal-violation-name').textContent = name;
                document.getElementById('modal-violation-type').textContent = type;
                document.getElementById('modal-location').textContent = location;
                document.getElementById('modal-properties-raw').innerHTML = propertiesHtml;

                // Handle Severity Badge styling accurately
                const severityBadge = document.getElementById('modal-severity-badge');
                severityBadge.textContent = severity;
                
                // Reset colors and apply matched severity contexts
                severityBadge.className = 'badge font-monospace fw-bold';
                if (severityClass === 'high' || severityClass === 'critical') {
                    severityBadge.classList.add('bg-danger-subtle', 'text-danger');
                } else if (severityClass === 'medium' || severityClass === 'warning') {
                    severityBadge.classList.add('bg-warning-subtle', 'text-warning');
                } else {
                    severityBadge.classList.add('bg-success-subtle', 'text-success');
                }
            });
        }
    });
        // Global Modal trigger function for Account deletion
        function modalOpen(deleteUrl, driverName) {
            document.getElementById('globalDeleteForm').setAttribute('action', deleteUrl);
            document.getElementById('deleteTargetName').textContent = driverName;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        }

        // Dedicated trigger function for Violation log erasure
        function violationModalOpen(deleteUrl, violationName, driverName) {
            document.getElementById('violationDeleteForm').setAttribute('action', deleteUrl);
            document.getElementById('violationName').textContent = violationName;
            document.getElementById('violationDriverName').textContent = driverName;
            const vModal = new bootstrap.Modal(document.getElementById('violationDeleteModal'));
            vModal.show();
        }

        (function () {
            'use strict';

            function debounce(fn, wait) {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn(...args), wait);
                };
            }

            /**
             * Progressive-enhancement search + pagination framework.
             */
            function setupSection({ listId, searchId, pagerId, countId, emptyText, itemSelector = '.account-item' }) {
                const list = document.getElementById(listId);
                if (!list) return;

                // Only target the main active data rows
                const allItems = Array.from(list.querySelectorAll(itemSelector + '[data-search]'));
                const isTable = list.tagName.toLowerCase() === 'tbody';
                
                if (allItems.length === 0) return;

                const pageSize = parseInt(list.dataset.pageSize || '6', 10);
                const searchInput = document.getElementById(searchId);
                const pager = document.getElementById(pagerId);
                const countEl = document.getElementById(countId);
                const prevBtn = pager ? pager.querySelector('.pager-prev') : null;
                const nextBtn = pager ? pager.querySelector('.pager-next') : null;
                const pageLabel = pager ? pager.querySelector('.pager-page-label') : null;

                let currentPage = 1;

                function render() {
                    const query = (searchInput?.value || '').trim().toLowerCase();
                    const filtered = query
                        ? allItems.filter(el => el.dataset.search.includes(query))
                        : allItems;

                    const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
                    currentPage = Math.min(currentPage, totalPages);

                    // First pass: Hide all main elements and close/hide their parameters
                    allItems.forEach(el => {
                        el.classList.add('hidden');
                        if (isTable) {
                            const targetId = el.dataset.hasProperties;
                            if (targetId) {
                                const subRow = document.getElementById(targetId);
                                if (subRow) {
                                    subRow.classList.add('hidden');
                                    subRow.classList.remove('show'); // Force collapse close on page turns
                                }
                            }
                        }
                    });

                    const start = (currentPage - 1) * pageSize;
                    const pageSlice = filtered.slice(start, start + pageSize);
                    
                    // Second pass: Reveal rows active on the current view slice
                    pageSlice.forEach(el => {
                        el.classList.remove('hidden');
                        if (isTable) {
                            const targetId = el.dataset.hasProperties;
                            if (targetId) {
                                const subRow = document.getElementById(targetId);
                                if (subRow) {
                                    subRow.classList.remove('hidden'); // Available to open via BS interactions
                                }
                            }
                        }
                    });

                    let noResultsEl = list.querySelector('.js-no-results, .js-empty-tr');
                    if (filtered.length === 0) {
                        if (!noResultsEl) {
                            if (isTable) {
                                noResultsEl = document.createElement('tr');
                                noResultsEl.className = 'js-empty-tr';
                                noResultsEl.innerHTML = `<td colspan="5"><div class="empty-state"><i class="bi bi-search"></i>${emptyText}</div></td>`;
                            } else {
                                noResultsEl = document.createElement('div');
                                noResultsEl.className = 'empty-state js-no-results';
                                noResultsEl.innerHTML = `<i class="bi bi-search"></i>${emptyText}`;
                            }
                            list.appendChild(noResultsEl);
                        }
                    } else if (noResultsEl && (noResultsEl.classList.contains('js-no-results') || allItems.length > filtered.length)) {
                        noResultsEl.remove();
                    }

                    if (countEl) {
                        countEl.textContent = query
                            ? `${filtered.length} of ${allItems.length} shown`
                            : `${allItems.length} total`;
                    }

                    if (pager) {
                        pager.classList.toggle('hidden', totalPages <= 1);
                        if (pageLabel) pageLabel.textContent = `Page ${currentPage} of ${totalPages}`;
                        if (prevBtn) prevBtn.disabled = currentPage <= 1;
                        if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
                    }
                }

                if (searchInput) {
                    searchInput.addEventListener('input', debounce(() => {
                        currentPage = 1;
                        render();
                    }, 150));
                }
                if (prevBtn) prevBtn.addEventListener('click', () => { currentPage--; render(); });
                if (nextBtn) nextBtn.addEventListener('click', () => { currentPage++; render(); });

                render();
            }verifiedSearch

            setupSection({
                listId: 'pendingList',
                searchId: 'pendingSearch',
                pagerId: 'pendingPager',
                countId: 'pendingResultsCount',
                emptyText: 'No applications match your search.',
            });

            setupSection({
                listId: 'verifiedList',
                searchId: 'verifiedSearch',
                pagerId: 'verifiedPager',
                countId: 'verifiedResultsCount',
                emptyText: 'No drivers match your search.',
            });

            setupSection({
                listId: 'violationList',
                searchId: 'violationSearch',
                pagerId: 'violationPager',
                countId: 'violationResultsCount',
                emptyText: 'No incident logs match criteria.',
                itemSelector: 'tr'
            });
        })();
    </script>
</body>
</html>

