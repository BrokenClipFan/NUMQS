<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Map & Queue Board</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* -- Dispatch-board palette -------------------------------- */
            --ink: #171B21;
            /* steel/charcoal chrome */
            --ink-soft: #262C36;
            /* secondary dark surface */
            --stone: #E7E9E3;
            /* terminal-floor paper background */
            --card: #FDFDFB;
            /* ticket/card surface */
            --line: #D8DBD2;
            /* hairline / divider on stone */
            --amber: #F2A63C;
            /* signal amber - the one loud accent */
            --amber-ink: #4A2E05;
            /* readable text on amber */
            --route-naga: #3E7CA6;
            /* Naga direction */
            --route-uling: #2F8F6B;
            /* Uling direction */
            --alert: #D1495B;
            /* overdue / stop */
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
            margin: 0;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        ::selection {
            background: var(--amber);
            color: var(--amber-ink);
        }

        /* Visible focus ring everywhere, in amber, for keyboard/a11y */
        a:focus-visible,
        button:focus-visible,
        .nav-link:focus-visible {
            outline: 2px solid var(--amber);
            outline-offset: 2px;
            border-radius: 4px;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.001ms !important;
                transition-duration: 0.001ms !important;
            }
        }

        /* ---------------------------------------------------------------
           Header / dispatch marquee
        ----------------------------------------------------------------*/
        .nav-sticky-top {
            background-color: var(--ink);
            border-bottom: 3px solid var(--amber);
            flex-shrink: 0;
            z-index: 1030;
            box-shadow: 0 2px 14px rgba(0, 0, 0, 0.25);
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

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.45;
                transform: scale(0.8);
            }
        }

        .position-readout {
            font-family: var(--font-mono);
            font-weight: 600;
            font-size: 0.78rem;
            background: var(--ink-soft);
            color: var(--amber);
            border: 1px solid rgba(242, 166, 60, 0.35);
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
            border: 1px solid rgba(255, 255, 255, 0.16);
        }

        .btn-dashboard:hover {
            border-color: var(--amber);
            color: var(--amber);
        }

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
            flex: 1 1 auto;
            min-height: 40dvh;
            z-index: 1;
            position: relative;
            background: var(--ink);
        }

        #map {
            height: 100%;
            width: 100%;
            filter: saturate(0.92);
        }

        /* Viewfinder-style corner brackets framing the live map */
        #map-container::before,
        #map-container::after,
        .map-frame-tl,
        .map-frame-br {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            border: 2px solid var(--amber);
            z-index: 450;
            pointer-events: none;
            opacity: 0.85;
        }

        #map-container::before {
            top: 10px;
            left: 10px;
            border-right: none;
            border-bottom: none;
        }

        #map-container::after {
            bottom: 10px;
            right: 10px;
            border-left: none;
            border-top: none;
        }

        .map-frame-tl {
            top: 10px;
            right: 10px;
            border-left: none;
            border-bottom: none;
        }

        .map-frame-br {
            bottom: 10px;
            left: 10px;
            border-right: none;
            border-top: none;
        }

        .map-live-badge {
            position: absolute;
            top: 14px;
            left: 42px;
            z-index: 460;
            background: rgba(23, 27, 33, 0.88);
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
            border: 1px solid rgba(242, 166, 60, 0.3);
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
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
            max-width: 88%;
        }

        .scrollable-panel {
            flex: 0 0 auto;
            max-height: 60dvh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            background-color: var(--stone);
            z-index: 2;
            box-shadow: 0 -4px 24px rgba(23, 27, 33, 0.15);
        }

        @media (min-width: 768px) {
            .main-wrapper {
                flex-direction: row;
            }

            #map-container {
                flex: 1 1 auto;
            }

            .scrollable-panel {
                flex: 0 0 380px;
                max-width: 380px;
                max-height: none;
                border-left: 1px solid var(--line);
                box-shadow: -4px 0 24px rgba(23, 27, 33, 0.08);
            }
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
            box-shadow: 0 1px 2px rgba(23, 27, 33, 0.04);
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

        .btn-deck:active:not(:disabled) {
            transform: scale(0.97);
        }

        .btn-deck-start {
            background: var(--amber);
            color: var(--amber-ink);
            box-shadow: 0 3px 0 #c78423;
        }

        .btn-deck-start:hover:not(:disabled) {
            filter: brightness(1.04);
        }

        .btn-deck-start:disabled {
            background: #EDE0C6;
            color: #A98F5E;
            box-shadow: none;
            opacity: 0.8;
        }

        .btn-deck-end {
            background: var(--card);
            color: var(--text-primary);
            border: 1.5px solid var(--line);
        }

        .btn-deck-end:hover:not(:disabled) {
            border-color: var(--alert);
            color: var(--alert);
        }

        .btn-deck-end:disabled {
            color: #B7BCC4;
            box-shadow: none;
        }

        .btn-deck.is-submitting {
            opacity: 0.6;
            pointer-events: none;
        }

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

        .status-dot.online {
            background: var(--route-uling);
            animation: pulse-dot 1.8s ease-in-out infinite;
        }

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

        .route-dot.naga {
            background: var(--route-naga);
        }

        .route-dot.uling {
            background: var(--route-uling);
        }

        .route-chip.active {
            background: var(--ink) !important;
            color: #F4F5F1 !important;
            border-color: var(--ink);
        }

        .tab-content {
            padding: 0 1rem 1.25rem;
        }

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
            box-shadow: 0 8px 20px rgba(23, 27, 33, 0.08);
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

        .queue-card::before {
            top: -7px;
        }

        .queue-card::after {
            bottom: -7px;
        }

        .queue-card.active-driver {
            border-color: var(--amber);
            background: linear-gradient(180deg, rgba(242, 166, 60, 0.07), rgba(242, 166, 60, 0.02));
            box-shadow: 0 0 0 1px rgba(242, 166, 60, 0.25);
        }

        .queue-position {
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text-muted);
            min-width: 1.6rem;
            text-align: center;
        }

        .queue-card.active-driver .queue-position {
            color: var(--amber-ink);
        }

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

        .status-pill.queued {
            background: #EEF0EA;
            color: var(--text-muted);
            border: 1px solid var(--line);
        }

        .status-pill.filling {
            background: rgba(47, 143, 107, 0.12);
            color: var(--route-uling);
            border: 1px solid rgba(47, 143, 107, 0.3);
        }

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
            left: 0;
            right: 0;
            top: 50%;
            height: 1px;
            background: rgba(0, 0, 0, 0.35);
        }

        .flap-colon {
            color: var(--text-muted);
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 0.78rem;
        }

        .flap-display.overdue .flap-tile {
            background: var(--alert);
            color: #FFF3F3;
        }

        .flap-display .flap-icon {
            color: var(--text-muted);
            font-size: 0.75rem;
            margin-right: 0.1rem;
        }

        .flap-display.overdue .flap-icon {
            color: var(--alert);
        }

        .empty-state {
            font-family: var(--font-body);
            font-size: 0.85rem;
            text-align: center;
            padding: 2rem 0.5rem;
            color: var(--text-muted);
        }

        .empty-state .bi {
            font-size: 1.4rem;
            display: block;
            margin-bottom: 0.4rem;
            opacity: 0.5;
        }

        /* ---------------------------------------------------------------
           Leaflet overrides
        ----------------------------------------------------------------*/
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 4px;
            border: 2px solid var(--ink);
            font-family: var(--font-body);
        }

        .leaflet-popup-tip {
            background: var(--ink);
        }

        .driver-popup-img {
            width: 52px;
            height: 52px;
            min-width: 52px;
            flex-shrink: 0;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--amber);
        }

        .leaflet-control-zoom a {
            font-family: var(--font-body) !important;
        }

        @media (max-width: 400px) {
            .hide-on-mobile-xs {
                display: none !important;
            }
        }

        .jeepney-marker-container {
            background: transparent !important;
            border: none !important;
        }

        .jeepney-sprite {
            display: block;
            width: 100%;
            height: 100%;
            transform-origin: center center;
            transition: transform 0.25s ease-out;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.35));

            /* NEW: Hardware acceleration to prevent blur */
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            will-change: transform;
        }

        /* Search Result Dropdown Styles */
        .search-bar {
            position: relative;
            flex: 1 1 250px;
            max-width: 320px;
        }

        .search-bar .bi-search {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--amber);
            /* Amber accent for the icon */
            font-size: 0.9rem;
            pointer-events: none;
            z-index: 5;
            opacity: 0.8;
        }

        .search-bar input {
            width: 100%;
            padding: 0.55rem 1rem 0.55rem 2.6rem;
            border-radius: 50px;
            /* Fully rounded pill shape */
            border: 1px solid rgba(242, 166, 60, 0.3);
            /* Subtle amber border */
            background: var(--ink-soft);
            /* Dark inset background */
            color: #F4F5F1;
            font-family: var(--font-mono);
            /* Terminal-style font */
            font-size: 0.8rem;
            letter-spacing: 0.02em;
            transition: all 0.2s ease;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .search-bar input::placeholder {
            color: rgba(244, 245, 241, 0.4);
            font-family: var(--font-body);
            /* Keep placeholder readable */
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--amber);
            background: var(--ink);
            box-shadow: 0 0 0 4px rgba(242, 166, 60, 0.15), inset 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* Updated Dropdown to match the dark theme */
        #searchResults {
            background: var(--ink-soft);
            border: 1px solid rgba(242, 166, 60, 0.3) !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4) !important;
        }

        .search-result-item {
            background-color: transparent !important;
            color: #F4F5F1 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        .search-result-item:hover,
        .search-result-item:focus {
            background-color: var(--ink) !important;
            border-left: 3px solid var(--amber) !important;
            /* Amber highlight on hover */
        }

        .search-result-item {
            background-color: var(--card);
            color: var(--text-primary);
            border-bottom: 1px solid var(--line);
            font-family: var(--font-body);
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .search-result-item:hover,
        .search-result-item:focus {
            background-color: var(--stone);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .text-amber {
            color: var(--amber);
        }
        .landmark-label {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            font-weight: 700 !important;
            color: #f8fafc !important; /* light text for dark mode map */
            font-family: var(--font-body) !important;
            font-size: 11px !important;
            text-align: center;
            text-shadow: 2px 2px 0 #0f172a, -2px -2px 0 #0f172a, 2px -2px 0 #0f172a, -2px 2px 0 #0f172a, 0 2px 0 #0f172a, 2px 0 0 #0f172a, 0 -2px 0 #0f172a, -2px 0 0 #0f172a !important;
        }
    </style>
</head>

<body data-driver-id="{{ $driver->id }}" data-is-online="{{ $driver->is_online ? '1' : '0' }}">
    <!-- Full Screen Loading Overlay -->
    <div id="mapLoadingScreen" class="d-flex flex-column justify-content-center align-items-center" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: #111827; z-index: 99999; transition: opacity 0.5s ease; opacity: 1;">
        <div class="spinner-border mb-3" style="width: 3rem; height: 3rem; color: #fbbf24 !important;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h4 class="text-white fw-bold mb-1">NUMQS Tracking</h4>
        <p class="text-secondary small">Acquiring GPS Signal...</p>
    </div>
    <nav class="navbar navbar-expand-lg nav-sticky-top px-2 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            <a class="navbar-brand brand-mark m-0" href="javascript:void(0);" onclick="window.location.reload();" style="cursor: pointer;">
                <img src="{{ asset('Logo.png') }}" alt="Logo"
                    style="height: 28px; width: auto; object-fit: contain;">
                <span class="live-dot ms-1" title="Live"></span>
            </a>

                        <div class="d-flex align-items-center gap-3 pe-1">
                <!-- Search Button -->
                <button type="button" class="btn p-0 border-0 text-amber d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#searchModal" title="Search Drivers" style="background: transparent; font-size: 1.25rem;">
                    <i class="bi bi-search"></i>
                </button>

                <!-- Admin Button -->
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('fleet.management') }}"
                        class="btn p-0 border-0 text-amber d-flex align-items-center justify-content-center"
                        title="Admin Dashboard" style="background: transparent; font-size: 1.25rem;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </a>
                @endif

                
                <!-- Debug GPS Button -->
                <a href="/debug-gps"
                    class="btn p-0 border-0 text-danger d-flex align-items-center justify-content-center"
                    title="Debug GPS" style="background: transparent; font-size: 1.25rem;">
                    <i class="bi bi-bug-fill"></i>
                </a>
                
                <!-- Queue Button -->
                <a href="{{ route('driver.queue') }}"
                    class="btn p-0 border-0 text-amber d-flex align-items-center justify-content-center"
                    title="Queue Lineup" style="background: transparent; font-size: 1.25rem;">
                    <i class="bi bi-list-ol"></i>
                </a>

                <!-- Profile Button -->
                <a href="{{ route('profile.index') }}"
                    class="btn p-0 rounded-circle overflow-hidden d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 2px solid var(--amber) !important;" title="My Profile">
                    @php
                        $avatar = auth()->user()->profile && auth()->user()->profile->image_profile_path 
                            ? '/storage/' . auth()->user()->profile->image_profile_path 
                            : '/images/default-avatar.png';
                    @endphp
                    <img src="{{ $avatar }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
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
                                        <div class="col-6">
                        <button type="button" class="btn btn-deck btn-deck-start w-100" data-bs-toggle="modal" data-bs-target="#startDriveModal"
                            @if ($driver->is_online) disabled @endif>
                            <i class="bi bi-play-circle-fill fs-4"></i>
                            <span>Start Drive</span>
                        </button>
                    </div>
                    <form class="col-6 drive-form" action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_online" value="0">
                        <button type="submit" id="btnEndDrive" class="btn btn-deck btn-deck-end w-100"
                            @unless ($driver->is_online) disabled @endunless>
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

            </div>
    </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/leaflet.polylineDecorator.js"></script>

    <script type="module">
        document.addEventListener("DOMContentLoaded", () => {
            'use strict';

            // ---------------------------------------------------------------
            // Setup / constants
            // ---------------------------------------------------------------
            const CURRENT_DRIVER_ID = "{{ Auth::id() }}";
            const DRIVER_DESTINATION = "{{ $driver->going_to ?? 'none' }}";
            let rawPoints = {!! isset($routePath) ? $routePath->path : '[]' !!};
            const FULL_ROUTE_POINTS = typeof rawPoints === 'string' ? JSON.parse(rawPoints) : rawPoints;
            const routeLatLngs = FULL_ROUTE_POINTS.map(coord => [coord.lat, coord.lng]);
            
            const POLL_INTERVAL_MS = 2000;
            const FILL_WINDOW_MS = {{ \App\Models\Setting::getValue('queue_timer_minutes', 15) }} * 60 * 1000; // Configurable window

            const navPositionBadge = document.getElementById('navPositionBadge');
            const geoWarningEl = document.getElementById('geoWarning');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            let targetLat = null;
            let targetLng = null;
            let dbTargetLat = null;
            let dbTargetLng = null;
            let jeepneyMarkers = {};
            let pollTimer = null;
            let allDriversData = [];
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
                return {
                    minutes,
                    seconds
                };
            }

            // ---------------------------------------------------------------
            // Map setup
            // ---------------------------------------------------------------
            const map = L.map('map', {
                zoomControl: false
            }).setView([10.2350, 123.7350], 13);

            // Use standard, clean OSM map tiles (most reliable, no ORB issues)
            
            // Add static Terminal Markers
            if (routeLatLngs.length > 0) {
                const nagaCoords = routeLatLngs[0];
                const ulingCoords = routeLatLngs[routeLatLngs.length - 1];
                
                const terminalIconHtml = `
                    <div style="position: relative; width: 36px; height: 36px; display: flex; justify-content: center;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 36px; line-height: 1; color: var(--amber); filter: drop-shadow(0px 4px 4px rgba(0,0,0,0.5));"></i>
                        <div style="position: absolute; top: 4px; left: 50%; transform: translateX(-50%); width: 16px; height: 16px; background: transparent; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-building" style="font-size: 9px; color: var(--amber);"></i>
                        </div>
                    </div>
                `;
                
                L.marker(nagaCoords, {
                    icon: L.divIcon({
                        className: 'terminal-marker',
                        html: terminalIconHtml,
                        iconSize: [36, 36],
                        iconAnchor: [18, 36]
                    }),
                    zIndexOffset: 100
                }).addTo(map).bindPopup('<div style="font-weight: 700; color: #111827;">Naga Terminal</div>');

                L.marker(ulingCoords, {
                    icon: L.divIcon({
                        className: 'terminal-marker',
                        html: terminalIconHtml,
                        iconSize: [36, 36],
                        iconAnchor: [18, 36]
                    }),
                    zIndexOffset: 100
                }).addTo(map).bindPopup('<div style="font-weight: 700; color: #111827;">Uling Terminal</div>');
            }

            
            // Fetch and Add Dynamic Landmarks
            fetch('/landmarks')
                .then(res => res.json())
                .then(landmarks => {
                    landmarks.forEach(landmark => {
                        let color = '#f97316'; // default orange
                        let innerIcon = 'bi-geo-fill';
                        
                        if (landmark.type === 'gas_station') { color = '#f97316'; innerIcon = 'bi-fuel-pump-fill'; }
                        else if (landmark.type === 'school') { color = '#3b82f6'; innerIcon = 'bi-book-fill'; }
                        else if (landmark.type === 'market') { color = '#22c55e'; innerIcon = 'bi-shop'; }
                        else if (landmark.type === 'mini_stop') { color = '#a855f7'; innerIcon = 'bi-signpost-2-fill'; }

                        const iconHtml = `
                                                <div style="position: relative; width: 32px; height: 32px; display: flex; justify-content: center;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 32px; line-height: 1; color: ${color}; filter: drop-shadow(0px 3px 3px rgba(0,0,0,0.4)); -webkit-text-stroke: 1px #171B21;"></i>
                        <!-- Solid white circle to cover the messy inner stroke of the hole -->
                        <div style="position: absolute; top: 4.5px; left: 50%; transform: translateX(-50%); width: 13px; height: 13px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;">
                            <i class="bi ${innerIcon}" style="font-size: 9px; color: #171B21;"></i>
                        </div>
                    </div>
                        `;
                        
                        const popupHtml = `
                            <div class="lm-popup-container">
                                <div class="lm-header">
                                    <div class="lm-icon-circle" style="color: ${color}">
                                        <i class="bi ${innerIcon}" style="filter: drop-shadow(0px 2px 2px rgba(0,0,0,0.3)); -webkit-text-stroke: 0.5px #171B21;"></i>
                                    </div>

                                    <div class="lm-name" title="${escapeHtml(landmark.name)}">
                                        ${escapeHtml(landmark.name)}
                                    </div>
                                </div>
                                
                                ${landmark.image_path 
                                    ? `<img src="/storage/${landmark.image_path}" class="lm-image">`
                                    : `<div class="lm-image"><i class="bi bi-image fs-2"></i></div>`
                                }
                            </div>
                        `;

                        L.marker([landmark.latitude, landmark.longitude], {
                            icon: L.divIcon({
                                className: 'landmark-marker',
                                html: iconHtml,
                                iconSize: [32, 32],
                                iconAnchor: [16, 32]
                            }),
                            zIndexOffset: 50
                        }).addTo(map).bindPopup(popupHtml, {
                            className: 'custom-landmark-popup',
                            minWidth: 220
                        }).bindTooltip(landmark.name, {
                            permanent: true,
                            direction: 'bottom',
                            className: 'landmark-label',
                            offset: [0, 15]
                        });
                    });
                }).catch(err => console.error('Failed to load landmarks:', err));

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 20,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const driversPinLayer = L.layerGroup().addTo(map);
            L.control.zoom({
                position: 'bottomleft'
            }).addTo(map);

            setTimeout(() => map.invalidateSize(), 300);
            window.addEventListener('resize', () => map.invalidateSize());
            map.on('popupclose', () => {
                window.viewingOtherDriverId = null;
                if(typeof updateRouteLine === 'function') {
                    const fallbackLat = targetLat !== null ? targetLat : dbTargetLat;
                    const fallbackLng = targetLng !== null ? targetLng : dbTargetLng;
                    if (fallbackLat !== null && fallbackLng !== null) {
                        updateRouteLine(fallbackLat, fallbackLng, DRIVER_DESTINATION);
                    } else {
                        updateRouteLine(0, 0, 'none');
                    }
                }
            });

            window.routeCasing = L.polyline([], {color: '#172554', weight: 9, opacity: 0.9, lineCap: 'round', lineJoin: 'round', interactive: false}).addTo(map);
            window.routePolyline = L.polyline([], {color: '#3b82f6', weight: 5, opacity: 1.0, lineCap: 'round', lineJoin: 'round', interactive: false}).addTo(map);
            window.routeConnectionLine = L.polyline([], {color: '#64748b', dashArray: '5, 5', weight: 3, opacity: 0.8, interactive: false}).addTo(map);
            
            if (DRIVER_DESTINATION && DRIVER_DESTINATION !== 'none' && routeLatLngs && routeLatLngs.length > 0) {
                let initPath = [];
                if (DRIVER_DESTINATION.toLowerCase() === 'uling') {
                    initPath = routeLatLngs;
                } else if (DRIVER_DESTINATION.toLowerCase() === 'naga') {
                    initPath = [...routeLatLngs].reverse();
                }
                window.routeCasing.setLatLngs(initPath);
                window.routePolyline.setLatLngs(initPath);
            }
            
            if (typeof L.polylineDecorator === 'function') {
                window.routeArrows = L.polylineDecorator(window.routePolyline, {
                    patterns: [
                        { offset: 50, repeat: 100, symbol: L.Symbol.arrowHead({pixelSize: 12, polygon: false, pathOptions: {stroke: true, color: '#ffffff', weight: 3, opacity: 0.9, lineCap: 'round'}}) }
                    ]
                }).addTo(map);
            }

            // Force draw the permanent static highway line on initial load
            updateRouteLine(null, null, 'none');


            // ---------------------------------------------------------------
            // Dynamic Icon Scaling
            // ---------------------------------------------------------------
            
            function updateRouteLine(lat, lng, destination) {
                if (!routeLatLngs || routeLatLngs.length === 0) return;

                // If driver is offline or has no GPS, draw the ENTIRE static route line so the map isn't empty!
                if (!destination || destination === 'none' || lat === null || lng === null || typeof lat === 'undefined' || typeof lng === 'undefined') {
                    if(window.routeCasing) window.routeCasing.setLatLngs(routeLatLngs);
                    if(window.routePolyline) window.routePolyline.setLatLngs(routeLatLngs);
                    if(window.routeConnectionLine) window.routeConnectionLine.setLatLngs([]);
                    if(window.routeArrows) window.routeArrows.setPaths([]);
                    return;
                }
                
                let minDistance = Infinity;
                let closestIdx = 0;
                const currentPos = L.latLng(lat, lng);

                for (let i = 0; i < routeLatLngs.length; i++) {
                    const d = currentPos.distanceTo(L.latLng(routeLatLngs[i][0], routeLatLngs[i][1]));
                    if (d < minDistance) {
                        minDistance = d;
                        closestIdx = i;
                    }
                }

                let newPath = [];
                const dest = destination.toLowerCase().trim();
                if (dest === 'uling') {
                    newPath = routeLatLngs.slice(closestIdx);
                } else if (dest === 'naga') {
                    newPath = routeLatLngs.slice(0, closestIdx + 1).reverse();
                } else {
                    newPath = routeLatLngs;
                }

                if(window.routeCasing) window.routeCasing.setLatLngs(newPath);
                if(window.routePolyline) window.routePolyline.setLatLngs(newPath);
                if(window.routeArrows) window.routeArrows.setPaths(newPath);
            }

            function updateIconScale() {
                const zoom = map.getZoom();
                let scale = 1; // Default base scale for zoom level 13

                if (zoom >= 15) scale = 1.3; // Zoomed in very close (larger)
                else if (zoom === 14) scale = 1.1;
                else if (zoom === 13) scale = 1.0;
                else if (zoom === 12) scale = 0.75;
                else if (zoom === 11) scale = 0.55;
                else if (zoom <= 10) scale = 0.4; // Zoomed out far (smaller)

                document.documentElement.style.setProperty('--jeep-scale', scale);
            }

            // Set initial scale and listen for zoom changes
            updateIconScale();
            map.on('zoom', updateIconScale);
            // ---------------------------------------------------------------
            // Driver pins on the map
            // ---------------------------------------------------------------
            function isSnappedToTerminal(lat, lng) {
                if (!routeLatLngs || routeLatLngs.length === 0) return false;
                const nagaCoords = routeLatLngs[0];
                const ulingCoords = routeLatLngs[routeLatLngs.length - 1];
                
                const isNaga = Math.abs(lat - nagaCoords[0]) < 0.0001 && Math.abs(lng - nagaCoords[1]) < 0.0001;
                const isUling = Math.abs(lat - ulingCoords[0]) < 0.0001 && Math.abs(lng - ulingCoords[1]) < 0.0001;
                return isNaga || isUling;
            }

            function addPinsToAllDrivers(drivers) {
                drivers.forEach((driver) => {
                    const driverLat = driver.status.latitude;
                    const driverLng = driver.status.longitude;
                    
                    if (!driverLat || !driverLng) return;

                    if (jeepneyMarkers[driver.id]) {
                        const amISnapped = (String(driver.id) === String(CURRENT_DRIVER_ID)) && isSnappedToTerminal(driverLat, driverLng);
                        if (amISnapped) window.amISnapped = true;
                        else if (String(driver.id) === String(CURRENT_DRIVER_ID)) window.amISnapped = false;

                        if (String(driver.id) !== String(CURRENT_DRIVER_ID) || amISnapped) {
                            smoothMoveWithRotation(jeepneyMarkers[driver.id], driverLat, driverLng, POLL_INTERVAL_MS);
                            if (window.viewingOtherDriverId === driver.id) {
                                if(typeof updateRouteLine === 'function') updateRouteLine(driverLat, driverLng, driver.status.going_to);
                            }
                        } else {
                            if(typeof updateRouteLine === 'function' && !window.viewingOtherDriverId) updateRouteLine(driverLat, driverLng, DRIVER_DESTINATION);
                            
                            // Restore marker to live GPS immediately in case `onLocationUpdate` is idle (e.g., sitting still)
                            if (targetLat !== null && targetLng !== null) {
                                smoothMoveWithRotation(jeepneyMarkers[driver.id], targetLat, targetLng, 2000);
                            }
                        }
                        return;
                    }

                    const fullName = fullNameOf(driver.profile);
                    const plate = driver.profile.plate_number;
                    const status = driver.status.state;

                    // FIX: Construct storage URL using standard JavaScript
                    const avatarPath = driver.profile?.image_profile_path ?
                        `/storage/${driver.profile.image_profile_path}` :
                        '/images/default-avatar.png'; // Optional fallback image

                    const iconFilePath = driver.profile?.jeep_icon ?
                        `/storage/${driver.profile.jeep_icon}` :
                        '/Logo.png'; // Fallback to logo or default icon if null
                    const jeepIcon = L.divIcon({
                        className: 'jeepney-marker-container',
                        html: `<img src="${escapeHtml(iconFilePath)}" class="jeepney-sprite" alt="jeepney">`,
                        iconSize: [30, 30],
                        iconAnchor: [15, 15],
                        popupAnchor: [0, -30],
                    });

                    const marker = L.marker([driverLat, driverLng], {
                        icon: jeepIcon
                    }).addTo(driversPinLayer);

                    const popupContent = `
            <div class="p-1" style="min-width: 180px;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="${escapeHtml(avatarPath)}" class="driver-popup-img" alt="${escapeHtml(fullName)}">
                    <div>
                        <strong>${escapeHtml(fullName)}</strong><br>
                        <small class="text-muted">Plate: ${escapeHtml(plate)}</small>
                    </div>
                </div>
                <div>Status: <span class="badge bg-info">${escapeHtml(status)}</span></div>
            </div>`;

                    marker.bindPopup(popupContent);
                    
                    marker.on('popupopen', () => {
                        window.viewingOtherDriverId = driver.id;
                        if(typeof updateRouteLine === 'function') updateRouteLine(driverLat, driverLng, driver.status.going_to);
                    });

                    jeepneyMarkers[driver.id] = marker;
                    if (String(driver.id) === String(CURRENT_DRIVER_ID)) {
                        if(typeof updateRouteLine === 'function' && !window.viewingOtherDriverId) updateRouteLine(driverLat, driverLng, DRIVER_DESTINATION);
                    } else if (window.viewingOtherDriverId === driver.id) {
                        if(typeof updateRouteLine === 'function') updateRouteLine(driverLat, driverLng, driver.status.going_to);
                    }
                });
            }

            function smoothMoveWithRotation(marker, targetLat, targetLng, duration) {
                if (marker.animationFrameId) {
                    cancelAnimationFrame(marker.animationFrameId);
                }

                const hasNewDestination = marker.lastTargetLat !== targetLat || marker.lastTargetLng !== targetLng;

                if (hasNewDestination && marker.lastTargetLat !== undefined) {
                    const lat1 = marker.lastTargetLat,
                        lon1 = marker.lastTargetLng;
                    const lat2 = targetLat,
                        lon2 = targetLng;

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
                        sprite.style.transform =
                            `rotate(${finalAngle}deg) scale(var(--jeep-scale, 1)) translateZ(0)`;
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

                // ---------------------------------------------------------------
                // Driver Search & Zoom Logic
                // ---------------------------------------------------------------
                const searchInput = document.getElementById('driverSearch');
                const searchResultsEl = document.getElementById('searchResults');

                let searchDebounceTimer;

                searchInput.addEventListener('input', function() {
                    clearTimeout(searchDebounceTimer);

                    searchDebounceTimer = setTimeout(() => {
                        const query = this.value.toLowerCase().trim();
                        searchResultsEl.innerHTML = ''; // Clear previous results

                        if (query.length === 0) {
                            searchResultsEl.classList.add('d-none');
                            return;
                        }

                        // Filter drivers by name or plate number
                        const matches = allDriversData.filter(driver => {
                            const fullName = fullNameOf(driver.profile).toLowerCase();
                            const plate = (driver.profile.plate_number || '').toLowerCase();
                            return fullName.includes(query) || plate.includes(query);
                        });

                        if (matches.length === 0) {
                            searchResultsEl.innerHTML =
                                '<div class="p-3 text-muted text-center" style="background: var(--card); font-size: 0.85rem;">No drivers found</div>';
                            searchResultsEl.classList.remove('d-none');
                            return;
                        }

                        // Render matches
                        matches.forEach(driver => {
                            const item = document.createElement('button');
                            item.type = 'button';
                                                        item.className = 'list-group-item search-result-item d-flex justify-content-between align-items-center p-3 rounded-3 mb-2';
                            item.style.border = '1px solid var(--line)';

                            item.innerHTML = `
                            <div class="d-flex flex-column text-start">
                                <span class="fw-bold" style="font-size: 0.9rem;">${escapeHtml(fullNameOf(driver.profile))}</span>
                                <small class="text-muted font-monospace" style="font-size: 0.75rem;">${escapeHtml(driver.profile.plate_number)}</small>
                            </div>
                            <i class="bi bi-crosshair text-amber ms-2"></i>
                        `;

                            // Handle click event: Zoom map and open popup
                            item.addEventListener('click', () => {
                                const lat = driver.status.latitude;
                                const lng = driver.status.longitude;

                                if (lat && lng) {
                                    // Zoom in close to the driver
                                    map.flyTo([lat, lng], 17, {
                                        animate: true,
                                        duration: 1.5
                                    });

                                    // Open the marker's popup after the zoom animation completes
                                    if (jeepneyMarkers[driver.id]) {
                                        setTimeout(() => {
                                            jeepneyMarkers[driver.id]
                                                .openPopup();
                                        }, 1500);
                                    }
                                }

                                                                // Clean up UI after selection
                                searchInput.value = '';
                                searchResultsEl.classList.add('d-none');
                                searchInput.blur();
                                
                                const modalInst = bootstrap.Modal.getInstance(document.getElementById('searchModal'));
                                if(modalInst) modalInst.hide();
                            });

                            searchResultsEl.appendChild(item);
                        });

                        searchResultsEl.classList.remove('d-none');
                    }, 300); // 300ms debounce
                });

                // Hide search results if the user clicks anywhere else on the screen
                document.addEventListener('click', (e) => {
                    if (!searchInput.contains(e.target) && !searchResultsEl.contains(e.target)) {
                        searchResultsEl.classList.add('d-none');
                    }
                });
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

                // Fix Safari/Chrome UTC parsing by replacing space with T and appending Z
                const safeDateStr = fillingAt.replace(' ', 'T') + (fillingAt.endsWith('Z') ? '' : 'Z');
                const deadline = new Date(safeDateStr).getTime() + FILL_WINDOW_MS;
                const remaining = deadline - Date.now();
                const overdue = remaining <= 0;
                const {
                    minutes,
                    seconds
                } = formatCountdownParts(remaining);

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

            function buildQueueCard({
                position,
                name,
                plate,
                isCurrentUser,
                badgeEl
            }) {
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

                        let queueCard = nagaToUlingQueueEl.appendChild(card);
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

                if (navPositionBadge) {
                    navPositionBadge.innerHTML = myPosition ?
                        `${myQueueLabel}-${String(myPosition).padStart(2, '0')}` :
                        '&mdash;';
                }
            }

            // ---------------------------------------------------------------
            // Network polling
            // ---------------------------------------------------------------
            
            let hasAutoZoomed = false; // Add this near your other let declarations

            function getDriversCoord() {
                return fetch('/drivers')
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to load driver locations');
                        return response.json();
                    })
                    .then(drivers => {
                                                allDriversData = drivers;
                        
                        // Hide loading screen on first successful load
                        const loader = document.getElementById('mapLoadingScreen');
                        if (loader && loader.style.opacity !== '0') {
                            loader.style.opacity = '0';
                            setTimeout(() => loader.remove(), 500);
                        }
                        addPinsToAllDrivers(drivers);

                        // --- ADDED: URL Parameter Auto-Zoom Logic ---
                        if (!hasAutoZoomed) {
                            const urlParams = new URLSearchParams(window.location.search);
                            const targetDriverId = urlParams.get('driver_id');

                            if (targetDriverId) {
                                const targetDriver = drivers.find(d => String(d.id) === targetDriverId);

                                if (targetDriver && targetDriver.status.latitude && targetDriver.status
                                    .longitude) {
                                    // Zoom in close to the target driver
                                    map.flyTo([targetDriver.status.latitude, targetDriver.status.longitude],
                                    17, {
                                        animate: true,
                                        duration: 1.5
                                    });

                                    // Open the marker's popup after the zoom animation completes
                                    if (jeepneyMarkers[targetDriver.id]) {
                                        setTimeout(() => {
                                            jeepneyMarkers[targetDriver.id].openPopup();
                                        }, 1500);
                                    }
                                }
                            }
                            hasAutoZoomed = true; // Prevent zooming on subsequent polling intervals
                        }
                        // ---------------------------------------------
                    })
                    .catch(err => console.error('getDriversCoord:', err));
            }

            async function saveLocationToDatabase(lat, lng) {
                let wifi_bssid = null;
                let wifi_ssid = null;
                
                const fakeBssidEnabled = localStorage.getItem('fakeBssidEnabled') === 'true';
                const fakeBssidValue = localStorage.getItem('fakeBssidValue');

                if (fakeBssidEnabled && fakeBssidValue) {
                    wifi_bssid = fakeBssidValue;
                    wifi_ssid = 'Fake Terminal WiFi';
                } else if (window.CapacitorWifiNetwork) {
                    try {
                        const info = await window.CapacitorWifiNetwork.getWifiInfo();
                        if (info && info.bssid) {
                            wifi_bssid = info.bssid;
                            wifi_ssid = info.ssid;
                        }
                    } catch (e) {
                        // Wi-Fi not available or error
                    }
                }

                return fetch('/driver/location/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng,
                        wifi_bssid: wifi_bssid,
                        wifi_ssid: wifi_ssid
                    }),
                }).catch(err => console.error('saveLocationToDatabase:', err));
            }

            function pollOnce() {
                // ALWAYS send the ping to the backend, even if targetLat/targetLng are null.
                // The backend will fallback to their Terminal Wi-Fi location or last known location!
                saveLocationToDatabase(targetLat, targetLng);
                getDriversCoord();
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

            async function startLocationTracking() {
                const onLocationUpdate = (pos) => {
                    targetLat = pos.coords.latitude;
                    targetLng = pos.coords.longitude;
                    geoWarningEl.classList.add('hidden');

                    // Automatically center the map on the driver continuously if we have a lock
                    if (!window.hasInitiallyZoomed) {
                        map.setView([targetLat, targetLng], 17);
                        window.hasInitiallyZoomed = true;
                    }
                    
                    // Instantly move and rotate the current driver's jeepney icon based on Live GPS hardware
                    if (jeepneyMarkers && jeepneyMarkers[CURRENT_DRIVER_ID] && !window.amISnapped) {
                        smoothMoveWithRotation(jeepneyMarkers[CURRENT_DRIVER_ID], targetLat, targetLng, 2000);
                    }
                };

                const onLocationError = (err) => {
                    console.error('Geolocation error:', err);
                    geoWarningEl.classList.remove('hidden');
                    geoWarningEl.innerHTML =
                        '<i class="bi bi-exclamation-triangle-fill"></i> Location permission needed to update the map.';
                };

                // Capacitor Native Geolocation Support
                if (window.Capacitor && window.CapacitorGeolocation) {
                    try {
                        const status = await window.CapacitorGeolocation.checkPermissions();
                        if (status.location !== 'granted') {
                            const requestStatus = await window.CapacitorGeolocation.requestPermissions();
                            if (requestStatus.location !== 'granted') throw new Error('Permission denied');
                        }

                        await window.CapacitorGeolocation.watchPosition({
                                enableHighAccuracy: true
                            },
                            (pos, err) => {
                                if (err) onLocationError(err);
                                else if (pos) onLocationUpdate(pos);
                            }
                        );
                    } catch (err) {
                        onLocationError(err);
                    }
                }
                // Fallback to HTML5 Geolocation (Browser)
                else if (navigator.geolocation) {
                    navigator.geolocation.watchPosition(onLocationUpdate, onLocationError, {
                        enableHighAccuracy: true,
                        maximumAge: 5000,
                        timeout: 10000
                    });
                } else {
                    geoWarningEl.classList.remove('hidden');
                    geoWarningEl.innerHTML =
                        '<i class="bi bi-exclamation-triangle-fill"></i> This browser doesn\'t support location sharing.';
                }
            }

            if (isOnline) {
                startLocationTracking();

                                // Listen for compass heading to rotate the marker securely via Native Capacitor Motion
                                
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
        });
    </script>
    @include('partials.notifications')
    
    <!-- Destination Selection Modal -->
    <div class="modal fade" id="destinationModal" tabindex="-1" aria-labelledby="destinationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--card); border: 1px solid var(--line); border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title" id="destinationModalLabel" style="color: var(--ink); font-weight: 700;">Select Destination</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0 pb-4">
                    <p style="color: var(--text-muted); font-size: 0.95rem;">Please select where you are heading. This sets your route for the current drive session.</p>
                    <form action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_online" value="1">
                        <div class="d-grid gap-3">
                            <button type="submit" name="first_destination" value="Uling" class="btn btn-lg d-flex align-items-center justify-content-between px-4 py-3" style="background: var(--card); border: 2px solid var(--route-uling); border-radius: 12px; color: var(--ink); font-weight: 600; text-align: left;">
                                <div>
                                    <span class="route-dot uling me-2"></span>
                                    Naga &rarr; Uling
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </button>
                            
                            <button type="submit" name="first_destination" value="Naga" class="btn btn-lg d-flex align-items-center justify-content-between px-4 py-3" style="background: var(--card); border: 2px solid var(--route-naga); border-radius: 12px; color: var(--ink); font-weight: 600; text-align: left;">
                                <div>
                                    <span class="route-dot naga me-2"></span>
                                    Uling &rarr; Naga
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="background: var(--card); border: 1px solid var(--line); border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-2 pt-3 px-3">
                    <div class="search-bar w-100 position-relative m-0">
                        <i class="bi bi-search"></i>
                        <input type="search" id="driverSearch" class="form-control w-100" placeholder="Search by driver name or plate number" autocomplete="off" autofocus style="border-radius: 12px; padding: 0.75rem 1rem 0.75rem 2.8rem; background: var(--ink); color: #F4F5F1; border: 1px solid var(--line); box-shadow: none !important; outline: none !important;">
                    </div>
                </div>
                <div class="modal-body pt-0 px-3 pb-3">
                    <div id="searchResults" class="list-group w-100 shadow-none border-0 gap-2" style="overflow-y: auto; max-height: 60vh;">
                        <!-- Results injected here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('searchModal').addEventListener('shown.bs.modal', function () {
            document.getElementById('driverSearch').focus();
        });
    </script>

    

    <!-- Start Drive Modal -->
    <div class="modal fade dark-modal" id="startDriveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Initial Destination</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-4">Where are you heading first?</p>
                    <div class="d-grid gap-3">
                        <form class="drive-form" action="{{ route('online.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="is_online" value="1">
                            <input type="hidden" name="first_destination" value="Uling">
                            <button type="submit" class="btn btn-primary w-100 py-3">Heading to Uling</button>
                        </form>
                        <form class="drive-form" action="{{ route('online.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="is_online" value="1">
                            <input type="hidden" name="first_destination" value="Naga">
                            <button type="submit" class="btn btn-success w-100 py-3">Heading to Naga</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>




