<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Map & Queue Board</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/js/app.js', 'resources/sass/app.scss'])

    <style>
        :root {
            /* -- Dispatch-board palette -------------------------------- */
            --ink: #171B21;           /* steel/charcoal chrome */
            --ink-soft: #262C36;      /* secondary dark surface */
            --stone: #E7E9E3;         /* terminal-floor paper background */
            --card: #FDFDFB;          /* ticket/card surface */
            --line: #D8DBD2;          /* hairline / divider on stone */
            --amber: #F2A63C;         /* signal amber - the one loud accent */
            --amber-ink: #4A2E05;     /* readable text on amber */
            --route-naga: #3E7CA6;    /* Naga direction */
            --route-uling: #2F8F6B;   /* Uling direction */
            --alert: #D1495B;         /* overdue / stop */
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
            margin: 0;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        ::selection { background: var(--amber); color: var(--amber-ink); }

        /* Visible focus ring everywhere, in amber, for keyboard/a11y */
        a:focus-visible, button:focus-visible, .nav-link:focus-visible {
            outline: 2px solid var(--amber);
            outline-offset: 2px;
            border-radius: 4px;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
        }

        /* ---------------------------------------------------------------
           Header / dispatch marquee
        ----------------------------------------------------------------*/
        .nav-sticky-top {
            background-color: var(--ink);
            border-bottom: 3px solid var(--amber);
            flex-shrink: 0;
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

        .brand-mark .bi {
            color: var(--amber);
            font-size: 1.15rem;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--amber);
            display: inline-block;
            animation: pulse-dot 1.8s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.45; transform: scale(0.8); }
        }

        .position-readout {
            font-family: var(--font-mono);
            font-weight: 600;
            font-size: 0.78rem;
            background: var(--ink-soft);
            color: var(--amber);
            border: 1px solid rgba(242,166,60,0.35);
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            letter-spacing: 0.03em;
        }

        .btn-dashboard {
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 0.8rem;
            background: transparent;
            color: #D8DBD2;
            border: 1px solid rgba(255,255,255,0.16);
        }
        .btn-dashboard:hover { border-color: var(--amber); color: var(--amber); }

        /* ---------------------------------------------------------------
           Layout
        ----------------------------------------------------------------*/
        .main-wrapper {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            overflow: hidden;
        }

        #map-container {
            flex: 0 0 45dvh;
            z-index: 1;
            position: relative;
            background: var(--ink);
        }

        #map { height: 100%; width: 100%; filter: saturate(0.92); }

        /* Viewfinder-style corner brackets framing the live map */
        #map-container::before,
        #map-container::after,
        .map-frame-tl, .map-frame-br {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            border: 2px solid var(--amber);
            z-index: 450;
            pointer-events: none;
            opacity: 0.85;
        }
        #map-container::before { top: 10px; left: 10px; border-right: none; border-bottom: none; }
        #map-container::after { bottom: 10px; right: 10px; border-left: none; border-top: none; }
        .map-frame-tl { top: 10px; right: 10px; border-left: none; border-bottom: none; }
        .map-frame-br { bottom: 10px; left: 10px; border-right: none; border-top: none; }

        .map-live-badge {
            position: absolute;
            top: 14px;
            left: 42px;
            z-index: 460;
            background: rgba(23,27,33,0.88);
            color: #F4F5F1;
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            padding: 0.3rem 0.55rem;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            border: 1px solid rgba(242,166,60,0.3);
        }

        #geoWarning {
            position: absolute;
            bottom: 14px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 500;
            font-size: 0.72rem;
            font-family: var(--font-body);
            font-weight: 600;
            background: var(--amber);
            color: var(--amber-ink);
            border: none;
            border-radius: 7px;
            padding: 0.45rem 0.75rem;
            box-shadow: 0 4px 14px rgba(0,0,0,0.25);
            max-width: 88%;
        }

        .scrollable-panel {
            flex-grow: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            background-color: var(--stone);
        }

        @media (min-width: 768px) {
            .main-wrapper { flex-direction: row; }
            #map-container { flex: 0 0 60%; }
        }

        /* ---------------------------------------------------------------
           Driver control deck
        ----------------------------------------------------------------*/
        .control-deck {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 1rem;
            margin: 1rem;
            margin-bottom: 0.85rem;
            box-shadow: 0 1px 2px rgba(23,27,33,0.04);
        }

        .control-deck h6 {
            font-family: var(--font-mono);
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            margin-bottom: 0.75rem;
        }

        .btn-deck {
            border-radius: 10px;
            padding: 0.85rem 0.5rem;
            font-family: var(--font-body);
            font-weight: 700;
            font-size: 0.85rem;
            border: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            transition: transform 0.12s ease, box-shadow 0.12s ease, opacity 0.12s ease;
        }
        .btn-deck:active:not(:disabled) { transform: scale(0.97); }

        .btn-deck-start {
            background: var(--amber);
            color: var(--amber-ink);
            box-shadow: 0 3px 0 #c78423;
        }
        .btn-deck-start:hover:not(:disabled) { filter: brightness(1.04); }
        .btn-deck-start:disabled { background: #EDE0C6; color: #A98F5E; box-shadow: none; opacity: 0.8; }

        .btn-deck-end {
            background: var(--card);
            color: var(--text-primary);
            border: 1.5px solid var(--line);
        }
        .btn-deck-end:hover:not(:disabled) { border-color: var(--alert); color: var(--alert); }
        .btn-deck-end:disabled { color: #B7BCC4; box-shadow: none; }

        .btn-deck.is-submitting { opacity: 0.6; pointer-events: none; }

        .status-readout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            margin-top: 0.75rem;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #B7BCC4;
        }
        .status-dot.online { background: var(--route-uling); animation: pulse-dot 1.8s ease-in-out infinite; }

        /* ---------------------------------------------------------------
           Route tabs
        ----------------------------------------------------------------*/
        #queueTabs {
            margin: 0 1rem 0.85rem;
            background: transparent;
            gap: 0.5rem;
            border: none;
            padding: 0;
        }

        .route-chip {
            font-family: var(--font-body);
            font-weight: 700;
            font-size: 0.82rem;
            color: var(--text-primary);
            background: var(--card);
            border: 1.5px solid var(--line);
            border-radius: 10px !important;
            padding: 0.6rem 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
        }

        .route-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .route-dot.naga { background: var(--route-naga); }
        .route-dot.uling { background: var(--route-uling); }

        .route-chip.active {
            background: var(--ink) !important;
            color: #F4F5F1 !important;
            border-color: var(--ink);
        }

        .tab-content { padding: 0 1rem 1.25rem; }

        .queue-section-label {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .queue-count-badge {
            font-family: var(--font-mono);
            font-weight: 600;
            font-size: 0.7rem;
            background: var(--ink) !important;
            padding: 0.35rem 0.6rem;
            border-radius: 20px;
        }

        .strict-window-note {
            font-size: 0.75rem;
            background: #FBEAEC;
            border: 1px solid #F2C6CC;
            color: #8A2E3B;
            border-radius: 10px;
        }

        /* ---------------------------------------------------------------
           Queue cards — styled as dispatch/ticket stubs
        ----------------------------------------------------------------*/
        .queue-card {
            position: relative;
            border: 1px solid var(--line);
            background-color: var(--card);
            border-radius: 12px;
            padding: 0.85rem 0.95rem 0.85rem 1.15rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            overflow: hidden;
        }

        .queue-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(23,27,33,0.08);
        }

        /* die-cut ticket notch on the left edge */
        .queue-card::before,
        .queue-card::after {
            content: '';
            position: absolute;
            left: -7px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--stone);
            border: 1px solid var(--line);
        }
        .queue-card::before { top: -7px; }
        .queue-card::after { bottom: -7px; }

        .queue-card.active-driver {
            border-color: var(--amber);
            background: linear-gradient(180deg, rgba(242,166,60,0.07), rgba(242,166,60,0.02));
            box-shadow: 0 0 0 1px rgba(242,166,60,0.25);
        }

        .queue-position {
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text-muted);
            min-width: 1.6rem;
            text-align: center;
        }
        .queue-card.active-driver .queue-position { color: var(--amber-ink); }

        .queue-name {
            font-family: var(--font-body);
            font-weight: 700;
            font-size: 0.92rem;
            margin: 0;
            color: var(--text-primary);
        }

        .queue-plate {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--text-muted);
            letter-spacing: 0.03em;
        }

        .you-tag {
            font-family: var(--font-mono);
            font-size: 0.6rem;
            font-weight: 700;
            background: var(--amber);
            color: var(--amber-ink);
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
            margin-left: 0.35rem;
            vertical-align: middle;
            letter-spacing: 0.04em;
        }

        .status-pill {
            font-family: var(--font-mono);
            font-size: 0.66rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            padding: 0.32rem 0.55rem;
            border-radius: 20px;
            white-space: nowrap;
        }
        .status-pill.queued { background: #EEF0EA; color: var(--text-muted); border: 1px solid var(--line); }
        .status-pill.filling { background: rgba(47,143,107,0.12); color: var(--route-uling); border: 1px solid rgba(47,143,107,0.3); }

        /* Split-flap departure-board countdown timer */
        .flap-display {
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .flap-tile {
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 0.78rem;
            background: var(--ink);
            color: var(--amber);
            border-radius: 3px;
            padding: 0.22rem 0.32rem;
            line-height: 1;
            position: relative;
            min-width: 0.85rem;
            text-align: center;
        }
        .flap-tile::after {
            content: '';
            position: absolute;
            left: 0; right: 0; top: 50%;
            height: 1px;
            background: rgba(0,0,0,0.35);
        }
        .flap-colon { color: var(--text-muted); font-family: var(--font-mono); font-weight: 700; font-size: 0.78rem; }
        .flap-display.overdue .flap-tile { background: var(--alert); color: #FFF3F3; }
        .flap-display .flap-icon { color: var(--text-muted); font-size: 0.75rem; margin-right: 0.1rem; }
        .flap-display.overdue .flap-icon { color: var(--alert); }

        .empty-state {
            font-family: var(--font-body);
            font-size: 0.85rem;
            text-align: center;
            padding: 2rem 0.5rem;
            color: var(--text-muted);
        }
        .empty-state .bi { font-size: 1.4rem; display: block; margin-bottom: 0.4rem; opacity: 0.5; }

        /* ---------------------------------------------------------------
           Leaflet overrides
        ----------------------------------------------------------------*/
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 4px;
            border: 2px solid var(--ink);
            font-family: var(--font-body);
        }
        .leaflet-popup-tip { background: var(--ink); }

        .driver-popup-img {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--amber);
        }

        .leaflet-control-zoom a {
            font-family: var(--font-body) !important;
        }

        @media (max-width: 400px) {
            .hide-on-mobile-xs { display: none !important; }
        }

        .jeepney-marker-container { background: transparent !important; border: none !important; }
        .jeepney-sprite {
            display: block;
            transform-origin: center center;
            transition: transform 0.25s ease-out;
            filter: drop-shadow(0 2px 3px rgba(0,0,0,0.35));
        }
    </style>
</head>
<body data-driver-id="{{ $driver->id }}" data-is-online="{{ $driver->is_online ? '1' : '0' }}">
    <nav class="navbar navbar-expand-lg nav-sticky-top px-2 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            <a class="navbar-brand brand-mark m-0" href="#">
                <i class="bi bi-bus-front-fill"></i>
                <span class="fs-6 fs-md-5">ParaTrack</span>
                <span class="live-dot ms-1" title="Live"></span>
            </a>

            <div class="d-flex align-items-center gap-2">
                <span class="position-readout">
                    <i class="bi bi-signpost-split-fill"></i>
                    <span class="hide-on-mobile-xs">POS</span>
                    <span id="navPositionBadge">—</span>
                </span>

                <a href="{{ route('profile') }}" class="btn btn-sm btn-dashboard d-flex align-items-center gap-1 px-2 rounded-2" title="Dashboard">
                    <i class="bi bi-speedometer2"></i> <span class="d-none d-md-inline">Dashboard</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="main-wrapper">
        <div id="map-container">
            <div id="map"></div>
            <div class="map-frame-tl"></div>
            <div class="map-frame-br"></div>
            <div class="map-live-badge"><span class="live-dot"></span> LIVE TRACKING</div>
            <div id="geoWarning" class="hidden">
                <i class="bi bi-exclamation-triangle-fill"></i> Location sharing is off — turn it on to update the map.
            </div>
        </div>

        <div class="scrollable-panel">

            <div class="control-deck">
                <h6><i class="bi bi-sliders me-1"></i>DRIVER CONTROLS</h6>
                <div class="row g-2">
                    <form class="col-6 drive-form" action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_online" value="1">
                        <button type="submit" id="btnStartDrive" class="btn btn-deck btn-deck-start w-100" @if($driver->is_online) disabled @endif>
                            <i class="bi bi-play-circle-fill fs-4"></i>
                            <span>Start Drive</span>
                        </button>
                    </form>
                    <form class="col-6 drive-form" action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_online" value="0">
                        <button type="submit" id="btnEndDrive" class="btn btn-deck btn-deck-end w-100" @unless($driver->is_online) disabled @endunless>
                            <i class="bi bi-stop-circle-fill fs-4"></i>
                            <span>End Drive</span>
                        </button>
                    </form>
                </div>
                <div id="driveStatusAlert" class="status-readout">
                    <span class="status-dot {{ $driver->is_online ? 'online' : '' }}"></span>
                    Status: {{ $driver->state }}
                </div>
            </div>

            <ul class="nav nav-fill" id="queueTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link route-chip active" id="naga-uling-tab" data-bs-toggle="tab" data-bs-target="#naga-uling" type="button" role="tab">
                        <span class="route-dot naga"></span> Naga → Uling
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link route-chip" id="uling-naga-tab" data-bs-toggle="tab" data-bs-target="#uling-naga" type="button" role="tab">
                        <span class="route-dot uling"></span> Uling → Naga
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="queueTabsContent">
                <div class="tab-pane fade show active" id="naga-uling" role="tabpanel" aria-labelledby="naga-uling-tab">
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="queue-section-label">Queue Lineup · FIFO</span>
                        <span class="badge queue-count-badge" id="nagaToUlingQueueCount">0 Active</span>
                    </div>

                    <div class="d-flex flex-column gap-2" id="nagaToUlingQueue">
                        <div class="empty-state">Loading queue…</div>
                    </div>
                </div>

                <div class="tab-pane fade" id="uling-naga" role="tabpanel" aria-labelledby="uling-naga-tab">
                    <div class="alert strict-window-note py-2 px-2 rounded-3 mb-2 d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                        <div><strong>Strict Window:</strong> Max 10 mins to clear dispatch.</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="queue-section-label">Dispatch Order</span>
                        <span class="badge queue-count-badge" id="ulingToNagaQueueCount">0 Active</span>
                    </div>

                    <div class="d-flex flex-column gap-2" id="ulingToNagaQueue">
                        <div class="empty-state">Loading queue…</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function () {
            'use strict';

            // ---------------------------------------------------------------
            // Setup / constants
            // ---------------------------------------------------------------
            const CURRENT_DRIVER_ID = "{{ $driver->id }}";
            const POLL_INTERVAL_MS = 2000;
            const FILL_WINDOW_MS = 10 * 60 * 1000; // 10 minute strict window

            const nagaToUlingQueueEl = document.getElementById('nagaToUlingQueue');
            const ulingToNagaQueueEl = document.getElementById('ulingToNagaQueue');
            const nagaToUlingCountEl = document.getElementById('nagaToUlingQueueCount');
            const ulingToNagaCountEl = document.getElementById('ulingToNagaQueueCount');
            const navPositionBadge = document.getElementById('navPositionBadge');
            const geoWarningEl = document.getElementById('geoWarning');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            let targetLat = null;
            let targetLng = null;
            let jeepneyMarkers = {};
            let pollTimer = null;

            // ---------------------------------------------------------------
            // Small utility: escape any driver-supplied text before it goes
            // into innerHTML, to avoid XSS via name/plate/status fields.
            // ---------------------------------------------------------------
            function escapeHtml(value) {
                const div = document.createElement('div');
                div.textContent = value ?? '';
                return div.innerHTML;
            }

            function fullNameOf(profile) {
                return [profile.first_name, profile.middle_name, profile.last_name]
                    .filter(Boolean)
                    .join(' ');
            }

            function formatCountdownParts(msRemaining) {
                const clamped = Math.max(0, msRemaining);
                const totalSeconds = Math.floor(clamped / 1000);
                const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
                const seconds = String(totalSeconds % 60).padStart(2, '0');
                return { minutes, seconds };
            }

            // ---------------------------------------------------------------
            // Map setup
            // ---------------------------------------------------------------
            const map = L.map('map', { zoomControl: false }).setView([10.2350, 123.7350], 13);

            L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {
                attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            const driversPinLayer = L.layerGroup().addTo(map);
            L.control.zoom({ position: 'bottomleft' }).addTo(map);

            setTimeout(() => map.invalidateSize(), 300);
            window.addEventListener('resize', () => map.invalidateSize());

            // ---------------------------------------------------------------
            // Driver pins on the map
            // ---------------------------------------------------------------
            function addPinsToAllDrivers(drivers) {
                drivers.forEach((driver) => {
                    const driverLat = driver.status.latitude;
                    const driverLng = driver.status.longitude;

                    if (jeepneyMarkers[driver.id]) {
                        smoothMoveWithRotation(jeepneyMarkers[driver.id], driverLat, driverLng, POLL_INTERVAL_MS);
                        return;
                    }
                    
                    if(driver.id == 1) {
                        console.log(driver);
                    }

                    const fullName = fullNameOf(driver.profile);
                    const plate = driver.profile.plate_number;
                    const status = driver.status.state;
                    const avatar = driver.avatar;
                    const iconFilePath = '/storage/' + driver.profile.jeep_icon;

                    const jeepIcon = L.divIcon({
                        className: 'jeepney-marker-container',
                        html: `<img src="${escapeHtml(iconFilePath)}" class="jeepney-sprite" style="width:50px; height:50px;" alt="jeepney">`,
                        iconSize: [50, 50],
                        iconAnchor: [25, 25],
                        popupAnchor: [0, -25],
                    });

                    const marker = L.marker([driverLat, driverLng], { icon: jeepIcon }).addTo(driversPinLayer);

                    const popupContent = `
                        <div class="p-1" style="min-width: 180px;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="${escapeHtml(avatar)}" class="driver-popup-img" alt="${escapeHtml(fullName)}">
                                <div>
                                    <h6 class="m-0 fw-bold" style="color: var(--ink); font-size:0.9rem;">${escapeHtml(fullName)}</h6>
                                    <span class="badge bg-light text-dark font-monospace border" style="font-size:0.7rem;">${escapeHtml(plate)}</span>
                                </div>
                            </div>
                            <hr class="my-1 opacity-25">
                            <div class="d-flex align-items-center gap-1 text-muted" style="font-size:0.75rem;">
                                <i class="bi bi-info-circle-fill" style="color: var(--amber);"></i>
                                <span>Status: <strong>${escapeHtml(status)}</strong></span>
                            </div>
                        </div>
                    `;
                    marker.bindPopup(popupContent);

                    jeepneyMarkers[driver.id] = marker;
                });
            }

            function smoothMoveWithRotation(marker, targetLat, targetLng, duration) {
                if (marker.animationFrameId) {
                    cancelAnimationFrame(marker.animationFrameId);
                }

                const hasNewDestination = marker.lastTargetLat !== targetLat || marker.lastTargetLng !== targetLng;

                if (hasNewDestination && marker.lastTargetLat !== undefined) {
                    const lat1 = marker.lastTargetLat, lon1 = marker.lastTargetLng;
                    const lat2 = targetLat, lon2 = targetLng;

                    const dLon = (lon2 - lon1) * Math.PI / 180;
                    const y = Math.sin(dLon) * Math.cos(lat2 * Math.PI / 180);
                    const x = Math.cos(lat1 * Math.PI / 180) * Math.sin(lat2 * Math.PI / 180) -
                              Math.sin(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.cos(dLon);

                    let angle = (Math.atan2(y, x) * 180 / Math.PI + 360) % 360;
                    angle = (angle - 90 + 360) % 360;

                    marker.lastValidAngle = angle;
                }

                marker.lastTargetLat = targetLat;
                marker.lastTargetLng = targetLng;

                const finalAngle = marker.lastValidAngle !== undefined ? marker.lastValidAngle : 0;

                const container = marker.getElement();
                if (container) {
                    const sprite = container.querySelector('.jeepney-sprite');
                    if (sprite) {
                        sprite.style.transform = `rotate(${finalAngle}deg)`;
                    }
                }

                const startPos = marker.getLatLng();
                const startTime = performance.now();

                function animate(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    const lat = startPos.lat + (targetLat - startPos.lat) * progress;
                    const lng = startPos.lng + (targetLng - startPos.lng) * progress;

                    marker.setLatLng([lat, lng]);

                    if (progress < 1) {
                        marker.animationFrameId = requestAnimationFrame(animate);
                    } else {
                        marker.animationFrameId = null;
                    }
                }
                marker.animationFrameId = requestAnimationFrame(animate);
            }

            // ---------------------------------------------------------------
            // Queue card builders
            // ---------------------------------------------------------------
            function statusPill(isFilling) {
                const badge = document.createElement('span');
                if (isFilling) {
                    badge.className = 'status-pill filling';
                    badge.textContent = 'FILLING UP';
                } else {
                    badge.className = 'status-pill queued';
                    badge.textContent = 'IN QUEUE';
                }
                return badge;
            }

            function makeFlapTile(char) {
                const tile = document.createElement('span');
                tile.className = 'flap-tile';
                tile.textContent = char;
                return tile;
            }

            function flapTimer(fillingAt) {
                if (!fillingAt) {
                    const badge = document.createElement('span');
                    badge.className = 'status-pill queued';
                    badge.textContent = 'IN QUEUE';
                    return badge;
                }

                const deadline = new Date(fillingAt).getTime() + FILL_WINDOW_MS;
                const remaining = deadline - Date.now();
                const overdue = remaining <= 0;
                const { minutes, seconds } = formatCountdownParts(remaining);

                const wrapper = document.createElement('span');
                wrapper.className = `flap-display${overdue ? ' overdue' : ''}`;

                const icon = document.createElement('i');
                icon.className = `bi ${overdue ? 'bi-exclamation-octagon-fill' : 'bi-hourglass-split'} flap-icon`;
                wrapper.appendChild(icon);

                if (overdue) {
                    wrapper.appendChild(document.createTextNode('OVERDUE'));
                    return wrapper;
                }

                wrapper.appendChild(makeFlapTile(minutes[0]));
                wrapper.appendChild(makeFlapTile(minutes[1]));

                const colon = document.createElement('span');
                colon.className = 'flap-colon';
                colon.textContent = ':';
                wrapper.appendChild(colon);

                wrapper.appendChild(makeFlapTile(seconds[0]));
                wrapper.appendChild(makeFlapTile(seconds[1]));

                return wrapper;
            }

            function buildQueueCard({ position, name, plate, isCurrentUser, badgeEl }) {
                const card = document.createElement('div');
                card.className = `card queue-card${isCurrentUser ? ' active-driver' : ''}`;

                const row = document.createElement('div');
                row.className = 'd-flex align-items-center justify-content-between';
                
                const left = document.createElement('div');
                left.className = 'd-flex align-items-center gap-2';

                const pos = document.createElement('div');
                pos.className = 'queue-position';
                pos.textContent = position;

                const info = document.createElement('div');

                const nameEl = document.createElement('h6');
                nameEl.className = 'queue-name';
                nameEl.textContent = name;
                if (isCurrentUser) {
                    const youBadge = document.createElement('span');
                    youBadge.className = 'you-tag';
                    youBadge.textContent = 'YOU';
                    nameEl.appendChild(youBadge);
                }

                const plateEl = document.createElement('span');
                plateEl.className = 'queue-plate';
                plateEl.textContent = `PLATE ${plate}`;

                info.appendChild(nameEl);
                info.appendChild(plateEl);

                left.appendChild(pos);
                left.appendChild(info);

                row.appendChild(left);
                row.appendChild(badgeEl);
                card.appendChild(row);

                return card;
            }

            function renderEmptyState(container, message) {
                const el = document.createElement('div');
                el.className = 'empty-state';
                el.innerHTML = `<i class="bi bi-inbox"></i>${escapeHtml(message)}`;
                container.appendChild(el);
            }

            // ---------------------------------------------------------------
            // Queue rendering
            // ---------------------------------------------------------------
            function updateQueue(drivers) {
                const nagaQueue = drivers.filter(d => d.status.queued_in === 'Naga');
                const ulingQueue = drivers.filter(d => d.status.queued_in === 'Uling');

                nagaToUlingCountEl.textContent = `${nagaQueue.length} Active`;
                ulingToNagaCountEl.textContent = `${ulingQueue.length} Active`;

                nagaToUlingQueueEl.replaceChildren();
                ulingToNagaQueueEl.replaceChildren();

                let myPosition = null;
                let myQueueLabel = null;

                if (nagaQueue.length === 0) {
                    renderEmptyState(nagaToUlingQueueEl, 'No drivers currently queued.');
                } else {
                    nagaQueue.forEach((driver, index) => {
                        const isFilling = driver.filling_at != null;
                        const isCurrentUser = String(driver.id) === CURRENT_DRIVER_ID;
                        if (isCurrentUser) {
                            myPosition = index + 1;
                            myQueueLabel = 'N';
                        }

                        const card = buildQueueCard({
                            position: index + 1,
                            name: fullNameOf(driver.profile),
                            plate: driver.profile.plate_number,
                            isCurrentUser,
                            badgeEl: statusPill(isFilling),
                        });
                        nagaToUlingQueueEl.appendChild(card);
                    });
                }

                if (ulingQueue.length === 0) {
                    renderEmptyState(ulingToNagaQueueEl, 'No drivers currently queued.');
                } else {
                    ulingQueue.forEach((driver, index) => {
                        const isCurrentUser = String(driver.id) === CURRENT_DRIVER_ID;
                        if (isCurrentUser) {
                            myPosition = index + 1;
                            myQueueLabel = 'U';
                        }

                        const card = buildQueueCard({
                            position: index + 1,
                            name: fullNameOf(driver.profile),
                            plate: driver.profile.plate_number,
                            isCurrentUser,
                            badgeEl: flapTimer(driver.filling_at),
                        });
                        ulingToNagaQueueEl.appendChild(card);
                    });
                }

                navPositionBadge.textContent = myPosition ? `${myQueueLabel}-${String(myPosition).padStart(2, '0')}` : '—';
            }

            // ---------------------------------------------------------------
            // Network polling
            // ---------------------------------------------------------------
            function getAllQueues() {
                return fetch('/queue')
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to load queue data');
                        return response.json();
                    })
                    .then(updateQueue)
                    .catch(err => console.error('getAllQueues:', err));
            }

            function getDriversCoord() {
                return fetch('/drivers')
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to load driver locations');
                        return response.json();
                    })
                    .then(addPinsToAllDrivers)
                    .catch(err => console.error('getDriversCoord:', err));
            }

            function saveLocationToDatabase(lat, lng) {
                return fetch('/driver/location/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng }),
                }).catch(err => console.error('saveLocationToDatabase:', err));
            }

            function pollOnce() {
                if (targetLat !== null && targetLng !== null) {
                    saveLocationToDatabase(targetLat, targetLng);
                }
                getDriversCoord();
                getAllQueues();
            }

            function startPolling() {
                if (pollTimer) return;
                pollOnce();
                pollTimer = setInterval(pollOnce, POLL_INTERVAL_MS);
            }

            function stopPolling() {
                if (pollTimer) {
                    clearInterval(pollTimer);
                    pollTimer = null;
                }
            }

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    stopPolling();
                } else {
                    startPolling();
                }
            });

            // ---------------------------------------------------------------
            // Geolocation — only track while the driver is marked online
            // ---------------------------------------------------------------
            const isOnline = document.body.dataset.isOnline === '1';

            function startLocationTracking() {
                if (!navigator.geolocation) {
                    geoWarningEl.classList.remove('hidden');
                    geoWarningEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> This browser doesn\'t support location sharing.';
                    return;
                }

                navigator.geolocation.watchPosition(
                    (pos) => {
                        targetLat = pos.coords.latitude;
                        targetLng = pos.coords.longitude;
                        geoWarningEl.classList.add('hidden');
                    },
                    (err) => {
                        console.error('Geolocation error:', err);
                        geoWarningEl.classList.remove('hidden');
                        geoWarningEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Location permission needed to update the map.';
                    },
                    { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 }
                );
            }

            if (isOnline) {
                startLocationTracking();
            }

            // ---------------------------------------------------------------
            // Drive control forms — prevent double submits
            // ---------------------------------------------------------------
            document.querySelectorAll('.drive-form').forEach((form) => {
                form.addEventListener('submit', () => {
                    const btn = form.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.classList.add('is-submitting');
                        btn.disabled = true;
                    }
                });
            });

            // ---------------------------------------------------------------
            // Boot
            // ---------------------------------------------------------------
            startPolling();
        })();
    </script>
    @include('partials.notifications')
</body>
</html>