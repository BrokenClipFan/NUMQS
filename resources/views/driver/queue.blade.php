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
            flex: 0 0 45dvh;
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
            flex-grow: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            background-color: var(--stone);
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
                border-left: 1px solid var(--line);
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

        .text-amber, .text-amber:hover, .text-amber:focus {
            color: var(--amber) !important;
        }
        .text-amber:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body data-driver-id="{{ $driver->id }}" data-is-online="{{ $driver->is_online ? '1' : '0' }}">
    <nav class="navbar navbar-expand-lg nav-sticky-top px-2 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            <a class="navbar-brand brand-mark m-0" href="{{ route('driver.map') }}"><i class="bi bi-arrow-left text-amber me-2 fs-4"></i>
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
<div class="container-fluid p-0 d-flex flex-column" style="height: calc(100vh - 60px); background: var(--bg); overflow: hidden;">
    <!-- Modern Navbar -->
    

    <div class="flex-grow-1 overflow-auto p-3 p-md-4">
        <div class="max-w-md mx-auto" style="max-width: 600px;">
            <ul class="nav nav-fill" id="queueTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link route-chip active" id="naga-uling-tab" data-bs-toggle="tab"
                        data-bs-target="#naga-uling" type="button" role="tab">
                        <span class="route-dot naga"></span> Naga → Uling
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link route-chip" id="uling-naga-tab" data-bs-toggle="tab"
                        data-bs-target="#uling-naga" type="button" role="tab">
                        <span class="route-dot uling"></span> Uling → Naga
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="queueTabsContent">
                <div class="tab-pane fade show active" id="naga-uling" role="tabpanel"
                    aria-labelledby="naga-uling-tab">
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
</div>

<style>
    /* Add any required queue styles here if they were scoped to map */
    .route-chip { border: 2px solid transparent; background: rgba(0,0,0,0.05); color: var(--ink); border-radius: 12px; font-weight: 600; padding: 0.75rem 1rem; flex: 1; text-align: center; }
    .route-chip.active { background: white; border-color: var(--amber); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .route-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; }
    .route-dot.naga { background: var(--route-naga); }
    .route-dot.uling { background: var(--route-uling); }
    .queue-section-label { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
    .queue-count-badge { background: var(--ink); color: #fff; padding: 0.35rem 0.6rem; border-radius: 6px; font-weight: 600; font-size: 0.75rem; }
    .queue-card { background: white; border: 1px solid var(--line); border-radius: 12px; padding: 1rem; transition: transform 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .queue-card.current-user { border: 2px solid var(--amber); background: rgba(245, 158, 11, 0.05); }
    .pos-circle { width: 32px; height: 32px; border-radius: 50%; background: var(--bg); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--ink); border: 1px solid var(--line); }
    .queue-card.current-user .pos-circle { background: var(--amber); color: white; border: none; }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    'use strict';
    
    const CURRENT_DRIVER_ID = "{{ Auth::id() }}";
    const nagaToUlingQueueEl = document.getElementById('nagaToUlingQueue');
    const ulingToNagaQueueEl = document.getElementById('ulingToNagaQueue');
    const nagaToUlingCountEl = document.getElementById('nagaToUlingQueueCount');
    const ulingToNagaCountEl = document.getElementById('ulingToNagaQueueCount');
    const POLL_INTERVAL_MS = 2000;
    const FILL_WINDOW_MS = {{ \App\Models\Setting::getValue('queue_timer_minutes', 15) }} * 60 * 1000;
    const navPositionBadge = null;

    function getAllQueues() {
        fetch('/queue')
            .then(res => res.json())
            .then(data => {
                if(typeof updateQueue === "function") {
                    updateQueue(data);
                }
            })
            .catch(err => console.error(err));
    }
    
    // utility functions missing from map.blade.php extract
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
                        const isCurrentUser = String(driver.driver_profile_id) === CURRENT_DRIVER_ID;
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
                        const isCurrentUser = String(driver.driver_profile_id) === CURRENT_DRIVER_ID;
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

    
    
    // Start polling
    getAllQueues();
    setInterval(getAllQueues, POLL_INTERVAL_MS);
});
</script>

</body>
</html>





