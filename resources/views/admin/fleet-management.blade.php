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
           Account rows
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
    </style>
</head>
<body>

    @php
        // Small local helper — turns "Juan Dela Cruz" into "JD" for the
        // fallback avatar circle when no photo is on file.
        $initialsOf = function (string $text): string {
            $parts = array_filter(preg_split('/\s+/', trim($text)));
            $letters = array_map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)), array_slice($parts, 0, 2));
            return implode('', $letters) ?: '?';
        };
        $totalRegistered = ($unverifiedCount ?? count($unVerifiedUsers)) + ($verifiedCount ?? count($verifiedUsers));
    @endphp

    <!-- Admin Navigation Header -->
    <nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0" style="max-width: 960px; margin: 0 auto;">
            <a class="navbar-brand brand-mark m-0" href="#">
                <i class="bi bi-shield-lock-fill"></i>
                <span class="fs-6 fs-md-5">Admin Fleet Directory</span>
            </a>
            <span class="admin-readout">
                <span class="live-dot"></span>
                <span class="hide-on-mobile-xs">System Active</span>
            </span>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 960px;">

        <!-- Quick Fleet Overview Section -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="metric-card p-3 d-flex align-items-center gap-3">
                    <div class="metric-icon amber">
                        <i class="bi bi-person-fill-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="metric-value">{{ $unverifiedCount ?? count($unVerifiedUsers) }}</h4>
                        <span class="metric-label">Pending Approval</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="metric-card p-3 d-flex align-items-center gap-3">
                    <div class="metric-icon green">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div>
                        <h4 class="metric-value">{{ $verifiedCount ?? count($verifiedUsers) }}</h4>
                        <span class="metric-label">Verified Fleet</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="metric-card p-3 d-flex align-items-center gap-3">
                    <div class="metric-icon blue">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <h4 class="metric-value">{{ $totalRegistered }}</h4>
                        <span class="metric-label">Total Registered</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 1: PENDING UNVERIFIED ACCOUNTS -->
        <div class="fleet-card mb-4">
            <div class="fleet-card-header pending">
                <span><i class="bi bi-clock-history me-2"></i>Pending Applications</span>
                <span class="section-tag pending">Needs Verification</span>
            </div>

            @if(count($unVerifiedUsers) > 0)
            <div class="fleet-toolbar">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <label for="pendingSearch" class="visually-hidden">Search pending applications</label>
                    <input type="search" id="pendingSearch" placeholder="Search by name or email…" autocomplete="off">
                </div>
                <span class="results-count" id="pendingResultsCount"></span>
            </div>
            @endif

            <div class="list-group list-group-flush m-0" id="pendingList" data-page-size="6">
                @forelse($unVerifiedUsers as $user)
                <div class="p-3 account-item d-flex align-items-center justify-content-between flex-wrap gap-2"
                     data-search="{{ strtolower(($user->name ?? '') . ' ' . ($user->email ?? '')) }}">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle pending">
                            @if(!empty($user->avatar))
                                <img src="{{ $user->avatar }}" alt="">
                            @else
                                {{ $initialsOf($user->name ?? '?') }}
                            @endif
                        </div>
                        <div>
                            <h6 class="account-name">{{ $user->name }}</h6>
                            <span class="account-meta">
                                @if($user->email)
                                    <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                                @else
                                    <i class="bi bi-facebook me-1"></i>
                                    <a href="https://www.facebook.com/profile.php?id={{ $user->facebook_id }}" class="text-decoration-none">
                                        Facebook Profile
                                    </a>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="account-item-actions">
                        <a href="{{ route('verify.driver', $user->id) }}" class="btn btn-verify px-3 py-2 rounded-3 btn-sm shadow-sm d-inline-flex align-items-center justify-content-center gap-1">
                            <i class="bi bi-pencil-square"></i> Verify &amp; Complete Profile
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    No pending applications right now.
                </div>
                @endforelse
            </div>

            @if(count($unVerifiedUsers) > 0)
            <div class="pager hidden" id="pendingPager">
                <button type="button" class="pager-btn pager-prev"><i class="bi bi-chevron-left"></i> Prev</button>
                <span class="pager-label pager-page-label">Page 1 of 1</span>
                <button type="button" class="pager-btn pager-next">Next <i class="bi bi-chevron-right"></i></button>
            </div>
            @endif
        </div>

        <!-- SECTION 2: VERIFIED ACCOUNTS -->
        <div class="fleet-card">
            <div class="fleet-card-header verified">
                <span><i class="bi bi-shield-check me-2"></i>Verified Fleet Database</span>
                <span class="section-tag verified">Active Trackers</span>
            </div>

            @if(count($verifiedUsers) > 0)
            <div class="fleet-toolbar">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <label for="verifiedSearch" class="visually-hidden">Search verified drivers</label>
                    <input type="search" id="verifiedSearch" placeholder="Search by name or plate number…" autocomplete="off">
                </div>
                <span class="results-count" id="verifiedResultsCount"></span>
            </div>
            @endif

            <div class="list-group list-group-flush m-0" id="verifiedList" data-page-size="8">
                @forelse($verifiedUsers as $user)
                    @php
                        $fullName = trim(($user->profile->first_name ?? '') . ' ' . ($user->profile->last_name ?? ''));
                    @endphp
                    <div class="p-3 account-item d-flex align-items-center justify-content-between flex-wrap gap-3"
                         data-search="{{ strtolower($fullName . ' ' . ($user->profile->license_number ?? '')) }}">
                        <div class="d-flex align-items-center flex-grow-1 gap-3">
                            <div class="avatar-circle verified">
                                @if(!empty($user->avatar))
                                    <img src="{{ $user->avatar }}" alt="">
                                @else
                                    {{ $initialsOf($fullName ?: '?') }}
                                @endif
                            </div>
                            <div class="row w-100 g-1 align-items-center">
                                <div class="col-12 col-md-5">
                                    <h6 class="account-name">{{ $fullName }}</h6>
                                    <span class="account-meta">ID: {{ $user->profile->id }}</span>
                                </div>
                                <div class="col-7 col-md-4">
                                    <span class="plate-pill">
                                        <i class="bi bi-truck-front-fill"></i>{{ $user->profile->license_number }}
                                    </span>
                                </div>
                                <div class="col-5 col-md-3 text-md-end">
                                    <span class="status-pill verified">
                                        <i class="bi bi-check-circle-fill"></i> Verified
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="account-item-actions">
                            {{-- <form action="{{ route('admin.drivers.destroy', $user->id) }}" method="POST" class="m-0" --}}
                            <form method="POST" class="m-0"
                                  onsubmit="return confirm('CRITICAL WARNING: Are you completely sure you want to permanently delete this driver account? This action removes all historical log coordinates, plate links, and queue metrics.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete-account px-3 py-2 rounded-3 btn-sm shadow-sm d-inline-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-trash3"></i> Delete Account
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        No verified drivers yet.
                    </div>
                @endforelse
            </div>

            @if(count($verifiedUsers) > 0)
            <div class="pager hidden" id="verifiedPager">
                <button type="button" class="pager-btn pager-prev"><i class="bi bi-chevron-left"></i> Prev</button>
                <span class="pager-label pager-page-label">Page 1 of 1</span>
                <button type="button" class="pager-btn pager-next">Next <i class="bi bi-chevron-right"></i></button>
            </div>
            @endif
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
             * Progressive-enhancement search + pagination.
             * Reads the rows Blade already rendered — never rebuilds the DOM,
             * so the page still works (just unpaginated) if this script fails.
             */
            function setupSection({ listId, searchId, pagerId, countId, emptyText }) {
                const list = document.getElementById(listId);
                if (!list) return;

                const allItems = Array.from(list.querySelectorAll('.account-item[data-search]'));
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

                    allItems.forEach(el => el.classList.add('hidden'));
                    const start = (currentPage - 1) * pageSize;
                    filtered.slice(start, start + pageSize).forEach(el => el.classList.remove('hidden'));

                    let noResultsEl = list.querySelector('.js-no-results');
                    if (filtered.length === 0) {
                        if (!noResultsEl) {
                            noResultsEl = document.createElement('div');
                            noResultsEl.className = 'empty-state js-no-results';
                            noResultsEl.innerHTML = `<i class="bi bi-search"></i>${emptyText}`;
                            list.appendChild(noResultsEl);
                        }
                    } else if (noResultsEl) {
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
            }

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
        })();
    </script>
</body>
</html>