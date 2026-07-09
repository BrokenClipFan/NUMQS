<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Map & Queue Board</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/js/app.js', 'resources/sass/app.scss'])

    <style>
        :root {
            --bg-light: rgb(247, 251, 252);
            --neutral-tint: rgb(214, 230, 242);
            --primary-accent: rgb(185, 215, 234);
            --main-dark: rgb(118, 159, 205);
            --text-dark: #2c3e50;
            --danger-soft: #e57373;
        }

        /* Mobile-First Layout */
        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            height: 100dvh; /* dvh fixes mobile URL bar jump issues */
            display: flex;
            flex-direction: column;
            overflow: hidden; /* prevent full-page scroll, allow panel scroll */
        }

        .bg-custom-dark { background-color: var(--main-dark) !important; color: white; }
        .bg-custom-tint { background-color: var(--neutral-tint); }
        .border-custom { border-color: var(--primary-accent) !important; }

        .nav-sticky-top {
            background-color: #ffffff;
            border-bottom: 2px solid var(--primary-accent);
            flex-shrink: 0;
            z-index: 1030;
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            overflow: hidden;
        }

        #map-container {
            flex: 0 0 45dvh;
            border-bottom: 3px solid var(--primary-accent);
            z-index: 1;
            position: relative;
        }

        #map { height: 100%; width: 100%; }

        /* Small banner shown when the browser denies/lacks geolocation */
        #geoWarning {
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 500;
            font-size: 0.75rem;
        }

        .scrollable-panel {
            flex-grow: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            background-color: var(--bg-light);
        }

        @media (min-width: 768px) {
            .main-wrapper { flex-direction: row; }
            #map-container {
                flex: 0 0 60%;
                border-bottom: none;
                border-right: 3px solid var(--primary-accent);
            }
        }

        .queue-card {
            border: 1px solid var(--primary-accent);
            background-color: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .queue-card.active-driver {
            border-left: 5px solid var(--main-dark);
            background-color: var(--bg-light);
        }

        .timer-badge {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            font-weight: 600;
        }

        .timer-badge.overdue {
            background-color: #f8d7da;
            color: #842029;
            border-color: #f1aeb5;
        }

        .empty-state {
            font-size: 0.85rem;
            text-align: center;
            padding: 1.5rem 0.5rem;
            color: #8a97a3;
        }

        .hidden { display: none; }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 4px;
            border: 2px solid var(--main-dark);
        }

        .driver-popup-img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--main-dark);
        }

        @media (max-width: 400px) {
            .hide-on-mobile-xs { display: none !important; }
        }

        .jeepney-marker-container {
            background: transparent !important;
            border: none !important;
        }

        .jeepney-sprite {
            display: block;
            transform-origin: center center;
            transition: transform 0.25s ease-out;
        }

        /* Button loading state for the drive controls */
        .btn.is-submitting {
            opacity: 0.65;
            pointer-events: none;
        }
    </style>
</head>
<body data-driver-id="{{ $driver->id }}" data-is-online="{{ $driver->is_online ? '1' : '0' }}">
    <nav class="navbar navbar-expand-lg nav-sticky-top px-2 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            <a class="navbar-brand fw-bold d-flex align-items-center m-0" href="#" style="color: var(--text-dark);">
                <i class="bi bi-bus-front-fill me-1" style="color: var(--main-dark);"></i>
                <span class="fs-6 fs-md-5">ParaTrack</span>
            </a>

            <div class="d-flex align-items-center gap-1 gap-md-2">
                <span class="badge px-2 py-2 rounded-pill d-flex align-items-center bg-custom-dark shadow-sm" style="font-size: 0.8rem;">
                    <i class="bi bi-stack me-1"></i> <span class="hide-on-mobile-xs">Position: </span><span id="navPositionBadge">—</span>
                </span>

                <a href="{{ route('profile') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 px-2" title="Dashboard">
                    <i class="bi bi-speedometer2"></i> <span class="d-none d-md-inline">Dashboard</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="main-wrapper">
        <div id="map-container">
            <div id="map"></div>
            <div id="geoWarning" class="alert alert-warning py-1 px-2 mb-0 shadow-sm hidden">
                <i class="bi bi-exclamation-triangle-fill"></i> Location sharing is off — turn it on to update the map.
            </div>
        </div>

        <div class="scrollable-panel p-3">

            <div class="card card-body shadow-sm border-custom mb-3 bg-custom-tint">
                <h6 class="fw-bold text-uppercase mb-2" style="color: var(--text-dark); font-size: 0.85rem; letter-spacing: 0.05em;">
                    <i class="bi bi-sliders me-1"></i> Driver Controls
                </h6>
                <div class="row g-2">
                    <form class="col-6 drive-form" action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_online" value="1">
                        <button type="submit" id="btnStartDrive" class="btn bg-custom-dark w-100 py-2 fw-bold text-white shadow-sm border-0 d-flex flex-column align-items-center justify-content-center" @if($driver->is_online) disabled @endif>
                            <i class="bi bi-play-circle-fill fs-4 mb-1"></i>
                            <span style="font-size: 0.9rem;">Start Drive</span>
                        </button>
                    </form>
                    <form class="col-6 drive-form" action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_online" value="0">
                        <button type="submit" id="btnEndDrive" class="btn btn-light border-custom w-100 py-2 fw-bold text-muted d-flex flex-column align-items-center justify-content-center" @unless($driver->is_online) disabled @endunless>
                            <i class="bi bi-stop-circle-fill fs-4 mb-1 text-danger"></i>
                            <span style="font-size: 0.9rem;">End Drive</span>
                        </button>
                    </form>
                </div>
                <div id="driveStatusAlert" class="text-center mt-2 small fw-bold text-muted">Status: {{ $driver->state }}</div>
            </div>

            <ul class="nav nav-pills nav-fill mb-3 p-1 rounded bg-white border-custom border" id="queueTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-2 btn-sm rounded" id="naga-uling-tab" data-bs-toggle="tab" data-bs-target="#naga-uling" type="button" role="tab" style="font-size: 0.85rem;">
                        Naga → Uling
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-2 btn-sm rounded" id="uling-naga-tab" data-bs-toggle="tab" data-bs-target="#uling-naga" type="button" role="tab" style="font-size: 0.85rem;">
                        Uling → Naga
                    </button>
                </li>
            </ul>

            <div class="tab-content pb-4" id="queueTabsContent">
                <div class="tab-pane fade show active" id="naga-uling" role="tabpanel" aria-labelledby="naga-uling-tab">
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="small fw-bold text-muted">Queue Lineup (FIFO)</span>
                        <span class="badge bg-secondary rounded-pill" id="nagaToUlingQueueCount">0 Active</span>
                    </div>

                    <div class="d-flex flex-column gap-2" id="nagaToUlingQueue">
                        <div class="empty-state">Loading queue…</div>
                    </div>
                </div>

                <div class="tab-pane fade" id="uling-naga" role="tabpanel" aria-labelledby="uling-naga-tab">
                    <div class="alert alert-warning py-2 px-2 border-warning-subtle rounded-3 mb-2 d-flex align-items-start gap-2" style="font-size: 0.75rem;">
                        <i class="bi bi-exclamation-triangle-fill mt-1 text-warning"></i>
                        <div><strong>Strict Window:</strong> Max 10 mins to clear dispatch.</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="small fw-bold text-muted">Dispatch Order</span>
                        <span class="badge bg-secondary rounded-pill" id="ulingToNagaQueueCount">0 Active</span>
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

            function formatCountdown(msRemaining) {
                const clamped = Math.max(0, msRemaining);
                const totalSeconds = Math.floor(clamped / 1000);
                const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
                const seconds = String(totalSeconds % 60).padStart(2, '0');
                return `${minutes}:${seconds}`;
            }

            // ---------------------------------------------------------------
            // Map setup
            // ---------------------------------------------------------------
            const map = L.map('map', { zoomControl: false }).setView([10.2350, 123.7350], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            @if(config('services.openweathermap.key'))
            L.tileLayer('https://tile.openweathermap.org/map/precipitation_new/{z}/{x}/{y}.png?appid={{ config('services.openweathermap.key') }}', {
                maxZoom: 18,
                opacity: 0.6,
                attribution: '&copy; OpenWeatherMap'
            }).addTo(map);
            @endif

            const driversPinLayer = L.layerGroup().addTo(map);
            L.control.zoom({ position: 'bottomleft' }).addTo(map);

            // Force a resize once after layout settles, to avoid partial
            // gray tiles on mobile where the container starts at 0 height.
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
                                    <h6 class="m-0 fw-bold" style="color: var(--text-dark); font-size:0.9rem;">${escapeHtml(fullName)}</h6>
                                    <span class="badge bg-light text-dark font-monospace border" style="font-size:0.7rem;">${escapeHtml(plate)}</span>
                                </div>
                            </div>
                            <hr class="my-1 opacity-25">
                            <div class="d-flex align-items-center gap-1 text-muted" style="font-size:0.75rem;">
                                <i class="bi bi-info-circle-fill text-primary"></i>
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
            function statusBadge(isFilling) {
                const badge = document.createElement('span');
                badge.style.fontSize = '0.7rem';
                if (isFilling) {
                    badge.className = 'badge bg-success-subtle text-success border border-success-subtle';
                    badge.textContent = 'Filling Up';
                } else {
                    badge.className = 'badge bg-secondary-subtle text-secondary border';
                    badge.textContent = 'In Queue';
                }
                return badge;
            }

            function timerBadge(fillingAt) {
                const badge = document.createElement('span');
                badge.className = 'badge timer-badge d-flex align-items-center gap-1';
                badge.style.fontSize = '0.7rem';

                if (!fillingAt) {
                    badge.classList.remove('timer-badge');
                    badge.className = 'badge bg-secondary-subtle text-secondary border';
                    badge.textContent = 'In Queue';
                    return badge;
                }

                const deadline = new Date(fillingAt).getTime() + FILL_WINDOW_MS;
                const remaining = deadline - Date.now();

                if (remaining <= 0) {
                    badge.classList.add('overdue');
                }

                const icon = document.createElement('i');
                icon.className = `bi ${remaining <= 0 ? 'bi-exclamation-octagon-fill' : 'bi-hourglass-split'}`;
                badge.appendChild(icon);
                badge.appendChild(document.createTextNode(remaining <= 0 ? 'Overdue' : formatCountdown(remaining)));

                return badge;
            }

            function buildQueueCard({ position, name, plate, isCurrentUser, badgeEl }) {
                const card = document.createElement('div');
                card.className = `card queue-card rounded-3 shadow-sm p-3${isCurrentUser ? ' active-driver' : ''}`;

                const row = document.createElement('div');
                row.className = 'd-flex align-items-center justify-content-between';

                const left = document.createElement('div');
                left.className = 'd-flex align-items-center gap-2';

                const pos = document.createElement('div');
                pos.className = `fw-bold fs-5 px-1 ${isCurrentUser ? 'text-dark' : 'text-muted'}`;
                pos.textContent = position;

                const info = document.createElement('div');

                const nameEl = document.createElement('h6');
                nameEl.className = `mb-0 fw-bold fs-6${isCurrentUser ? ' text-primary-emphasis' : ''}`;
                nameEl.textContent = name;
                if (isCurrentUser) {
                    const youBadge = document.createElement('span');
                    youBadge.className = 'badge bg-primary text-white ms-1';
                    youBadge.style.fontSize = '9px';
                    youBadge.textContent = 'You';
                    nameEl.appendChild(document.createTextNode(' '));
                    nameEl.appendChild(youBadge);
                }

                const plateEl = document.createElement('span');
                plateEl.className = 'small text-muted font-monospace';
                plateEl.style.fontSize = '0.75rem';
                plateEl.textContent = `PLATE: ${plate}`;

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
                el.textContent = message;
                container.appendChild(el);
            }

            // ---------------------------------------------------------------
            // Queue rendering
            // ---------------------------------------------------------------
            function updateQueue(drivers) {
                const nagaQueue = drivers.filter(d => d.status.dispatched_to === 'Naga');
                const ulingQueue = drivers.filter(d => d.status.dispatched_to === 'Uling');

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
                            myQueueLabel = 'Naga';
                        }

                        const card = buildQueueCard({
                            position: index + 1,
                            name: fullNameOf(driver.profile),
                            plate: driver.profile.plate_number,
                            isCurrentUser,
                            badgeEl: statusBadge(isFilling),
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
                            myQueueLabel = 'Uling';
                        }

                        const card = buildQueueCard({
                            position: index + 1,
                            name: fullNameOf(driver.profile),
                            plate: driver.profile.plate_number,
                            isCurrentUser,
                            badgeEl: timerBadge(driver.filling_at),
                        });
                        ulingToNagaQueueEl.appendChild(card);
                    });
                }

                navPositionBadge.textContent = myPosition ? `#${myPosition}` : '—';
                navPositionBadge.title = myQueueLabel ? `${myQueueLabel} queue` : '';
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

            // Pause polling while the tab is hidden to save battery/data,
            // and resume immediately when it becomes visible again.
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
            let geoWatchId = null;

            function startLocationTracking() {
                if (!navigator.geolocation) {
                    geoWarningEl.classList.remove('hidden');
                    geoWarningEl.textContent = 'This browser doesn\'t support location sharing.';
                    return;
                }

                geoWatchId = navigator.geolocation.watchPosition(
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