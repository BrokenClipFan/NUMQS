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
    
    @vite('resources/js/app.js', 'resources/sass/app.scss');

    <style>
        :root {
            --bg-light: rgb(247, 251, 252);
            --neutral-tint: rgb(214, 230, 242);
            --primary-accent: rgb(185, 215, 234);
            --main-dark: rgb(118, 159, 205);
            --text-dark: #2c3e50;
        }

        /* Mobile-First Layout Fixes */
        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            height: 100dvh; /* dvh fixes mobile URL bar jump issues */
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Prevent full-page scroll, allow panel scroll */
        }

        /* Custom UI/UX Color Enhancements */
        .bg-custom-dark { background-color: var(--main-dark) !important; color: white; }
        .bg-custom-tint { background-color: var(--neutral-tint); }
        .border-custom { border-color: var(--primary-accent) !important; }
        
        /* Floating Badge Design for Mobile Navigation */
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

        /* Map Sizing for Mobile & Desktop */
        #map-container {
            flex: 0 0 45dvh; /* Strict 45% height on mobile */
            border-bottom: 3px solid var(--primary-accent);
            z-index: 1;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        .scrollable-panel {
            flex-grow: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch; /* Smooth scroll on iOS */
            background-color: var(--bg-light);
        }

        /* Desktop Layout Override */
        @media (min-width: 768px) {
            .main-wrapper {
                flex-direction: row;
            }
            #map-container {
                flex: 0 0 60%;
                border-bottom: none;
                border-right: 3px solid var(--primary-accent);
            }
        }

        /* Queue Styling & Elements */
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

        .hidden {
            display: none;
        }

        /* Leaflet Popup Styling Override */
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

        /* Mobile specific text truncation */
        @media (max-width: 400px) {
            .hide-on-mobile-xs { display: none !important; }
        }

        .jeepney-marker-container {
            background: transparent !important;
            border: none !important;
        }

        /* Ensure the sprite transitions smoothly when making turns */
        .jeepney-sprite {
            display: block;
            transform-origin: center center;
            transition: transform 0.25s ease-out; /* Makes your turns look amazingly fluid instead of rigid snap adjustments */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg nav-sticky-top px-2 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            <a class="navbar-brand fw-bold d-flex align-items-center m-0" href="#" style="color: var(--text-dark);">
                <i class="bi bi-bus-front-fill me-1" style="color: var(--main-dark);"></i>
                <span class="fs-6 fs-md-5">ParaTrack</span>
            </a>
            
            <div class="d-flex align-items-center gap-1 gap-md-2">
                <span class="badge px-2 py-2 rounded-pill d-flex align-items-center bg-custom-dark shadow-sm" style="font-size: 0.8rem;">
                    <i class="bi bi-stack me-1"></i> <span class="hide-on-mobile-xs">Position: </span>#3
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
        </div>

        <div class="scrollable-panel p-3">
            
            <div class="card card-body shadow-sm border-custom mb-3 bg-custom-tint">
                <h6 class="fw-bold text-uppercase mb-2" style="color: var(--text-dark); font-size: 0.85rem; letter-spacing: 0.05em;">
                    <i class="bi bi-sliders me-1"></i> Driver Controls
                </h6>
                <div class="row g-2">
                    <form class="col-6" action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="number" class="hidden" name="is_online" value="1">
                        @if($driver->is_online)
                            <button type="submit" id="btnStartDrive" class="btn bg-custom-dark w-100 py-2 fw-bold text-white shadow-sm border-0 d-flex flex-column align-items-center justify-content-center" disabled>
                        @else
                            <button type="submit" id="btnStartDrive" class="btn bg-custom-dark w-100 py-2 fw-bold text-white shadow-sm border-0 d-flex flex-column align-items-center justify-content-center">
                        @endif
                            <i class="bi bi-play-circle-fill fs-4 mb-1"></i>
                            <span style="font-size: 0.9rem;">Start Drive</span>
                        </button>
                    </form>
                    <form class="col-6" action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="number" value="0" name="is_online" class="hidden">
                        @if($driver->is_online)
                            <button type="submit" id="btnEndDrive" class="btn btn-light border-custom w-100 py-2 fw-bold text-muted d-flex flex-column align-items-center justify-content-center">
                        @else
                            <button type="submit" id="btnEndDrive" class="btn btn-light border-custom w-100 py-2 fw-bold text-muted d-flex flex-column align-items-center justify-content-center" disabled>
                        @endif
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
                        <span class="badge bg-secondary rounded-pill" id="nagaToUlingQueueCount">3 Active</span>
                    </div>
                    
                    <div class="d-flex flex-column gap-2" id="NagaToUlingQueue">
                        <div class="card queue-card rounded-3 shadow-sm p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fw-bold fs-5 text-muted px-1">1</div>
                                    <div>
                                        <h6 class="mb-0 fw-bold fs-6">Juan Dela Cruz</h6>
                                        <span class="small text-muted font-monospace" style="font-size: 0.75rem;">PLATE: GHI-7890</span>
                                    </div>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem;">Filling Up</span>
                            </div>
                        </div>

                        <div class="card queue-card rounded-3 shadow-sm p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fw-bold fs-5 text-muted px-1">2</div>
                                    <div>
                                        <h6 class="mb-0 fw-bold fs-6">Pedro Penduko</h6>
                                        <span class="small text-muted font-monospace" style="font-size: 0.75rem;">PLATE: JKL-1234</span>
                                    </div>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">In Queue</span>
                            </div>
                        </div>

                        <div class="card queue-card active-driver rounded-3 shadow-sm p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fw-bold fs-5 text-dark px-1">3</div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-primary-emphasis fs-6">Alex Santos <span class="badge bg-primary text-white ms-1" style="font-size: 9px;">You</span></h6>
                                        <span class="small text-muted font-monospace" style="font-size: 0.75rem;">PLATE: ABC-1234</span>
                                    </div>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">In Queue</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="uling-naga" role="tabpanel" aria-labelledby="uling-naga-tab">
                    <div class="alert alert-warning py-2 px-2 border-warning-subtle rounded-3 mb-2 d-flex align-items-start gap-2" style="font-size: 0.75rem;">
                        <i class="bi bi-exclamation-triangle-fill mt-1 text-warning"></i>
                        <div><strong>Strict Window:</strong> Max 10 mins to clear dispatch.</div>
                    </div>

                    <div class="d-flex flex-column gap-2 ulingToNagaQueue">
                        <div class="card queue-card rounded-3 shadow-sm p-3 border-danger-subtle">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fw-bold fs-5 text-muted px-1">1</div>
                                    <div>
                                        <h6 class="mb-0 fw-bold fs-6">Maria Clara</h6>
                                        <span class="small text-muted font-monospace" style="font-size: 0.75rem;">PLATE: XYZ-5678</span>
                                    </div>
                                </div>
                                <span class="badge timer-badge d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                    <i class="bi bi-hourglass-split text-danger"></i> 02:14
                                </span>
                            </div>
                        </div>

                        <div class="card queue-card rounded-3 shadow-sm p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fw-bold fs-5 text-muted px-1">2</div>
                                    <div>
                                        <h6 class="mb-0 fw-bold fs-6">Crisostomo Ibarra</h6>
                                        <span class="small text-muted font-monospace" style="font-size: 0.75rem;">PLATE: DEF-9012</span>
                                    </div>
                                </div>
                                <span class="badge timer-badge d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                    <i class="bi bi-hourglass-top"></i> 08:45
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let targetLat = null;
        let targetLng = null;
        const nagaToUlingQueue = document.getElementById('NagaToUlingQueue');
        const ulingToNagaQueue = document.querySelector('.ulingToNagaQueue');
        
        let jeepneyMarkers = {}
        const map = L.map('map', { zoomControl: false }).setView([10.2350, 123.7350], 13);

        // L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {
        //     attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        // }).addTo(map);

        // L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        //     attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        // }).addTo(map);
        // L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        // 1. Your standard background map layer
        const baseMapTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // 2. The real-time weather layer (Precipitation / Rain Radar)
        // You can change 'precipitation_new' to 'clouds_new', 'wind_new', or 'temp_new'
        const weatherRadarTiles = L.tileLayer('https://tile.openweathermap.org/map/precipitation_new/{z}/{x}/{y}.png?appid=YOUR_OPENWEATHERMAP_API_KEY', {
            maxZoom: 18,
            opacity: 0.6, // Transparent so you can still see roads underneath
            attribution: '&copy; OpenWeatherMap'
        }).addTo(map);

        setTimeout(() => { map.invalidateSize(); }, 300);
        window.addEventListener('resize', () => { map.invalidateSize(); });

        const permanentPinLayer = L.layerGroup().addTo(map);
        const driversPinLayer = L.layerGroup().addTo(map);
        
        // let tempRoute = [];
        // map.on('contextmenu', (e) => {
        //     tempRoute.push({lat: e.latlng.lat, lng: e.latlng.lng});
    
        //     // Add a visual marker so you know where you clicked
        //     L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            
        //     // Draw the line as you go
        //     L.polyline(tempRoute.map(p => [p.lat, p.lng]), {color: 'blue'}).addTo(map);

        //     console.log("COPY THIS FOR YOUR DB:", JSON.stringify(tempRoute));
        // });

        // map.on('contextmenu', function(e) {
        //     // 1. Get coordinates
        //     let lat = e.latlng.lat;
        //     let lng = e.latlng.lng;

        //     // 2. Add marker
        //     L.marker([lat, lng]).addTo(map)
        //         .bindPopup("Point: " + lat.toFixed(5) + ", " + lng.toFixed(5))
        //         .openPopup();

        //     // 3. Log to console so you can copy-paste for your route data
        //     console.log("Coordinate:", { lat: lat, lng: lng });
        // });

        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        // Force map resize when rendering to avoid partial gray tiles on mobile
        setTimeout(() => { map.invalidateSize(); }, 300);
        window.addEventListener('resize', () => {
            map.invalidateSize();
        });

        // function sendLocationToServer() {
        //     console.log("Checkpoint 1");
        //     if(navigator.geolocation) {
        //         console.log("Checkpoint 2");
        //         navigator.geolocation.getCurrentPosition(
        //             (position) => {
        //                 console.log("Checkpoint 3");
        //                 // const lat = position.coords.latitude;
        //                 // const lng = position.coords.longitude;
        //                 // console.log(lat,lng);
        //                 // sendLocationToDatabase(lat, lng);
        //             },
        //             (error) => {
        //                 console.error("checkpoint 1: Failed!");
        //                 console.error("Error Code: " + error.code);
        //                 console.error("Error Message: " + error.message);
        //             }
        //         ) 
        //     } else {
        //         console.log("Error Your Browser has no support geolocation")
        //     }
        // }

        function addPinsToAllDrivers(drivers) {
            drivers.forEach((driver) => {
                if (jeepneyMarkers[driver.id]) {
                    let marker = jeepneyMarkers[driver.id];
                    const driverLat = driver.status.latitude;
                    const driverLng = driver.status.longitude;

                    smoothMoveWithRotation(marker, driverLat, driverLng, 2000, driver.status.dispatched_to);

                } else {
                    const id = driver.id;
                    const firstName = driver.profile.first_name;
                    const lastName = driver.profile.last_name;
                    const fullName = firstName + " " + lastName;
                    const plate = driver.profile.plate_number;
                    const status = driver.status.state;
                    const dispatchedTo = driver.status.dispatched_to;
                    const driverLat = driver.status.latitude;
                    const driverLng = driver.status.longitude;
                    const driverCoords = [driverLat, driverLng];
                    const avatar = driver.avatar;
                    const iconFilePath = '/storage/' + driver.profile.jeep_icon;

                    // FIX: Use L.divIcon to wrap your image in an inner container.
                    // Leaflet handles the main marker wrapper, we safely rotate & flip the inside img element.
                    const jeepIcon = L.divIcon({
                        className: 'jeepney-marker-container',
                        html: `<img src="${iconFilePath}" class="jeepney-sprite" style="width:50px; height:50px;" alt="jeepney">`,
                        iconSize: [50, 50],             
                        iconAnchor: [25, 25], // Center anchor is usually best for rotating vehicles        
                        popupAnchor: [0, -25],   
                    });

                    const marker = L.marker(driverCoords, {
                        icon: jeepIcon
                    }).addTo(driversPinLayer);

                    const popupContent = `
                        <div class="p-1" style="min-width: 180px;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="${avatar}" class="driver-popup-img" alt="${driver.name}">
                                <div>
                                    <h6 class="m-0 fw-bold" style="color: var(--text-dark); font-size:0.9rem;">${fullName}</h6>
                                    <span class="badge bg-light text-dark font-monospace border" style="font-size:0.7rem;">${plate}</span>
                                </div>
                            </div>
                            <hr class="my-1 opacity-25">
                            <div class="d-flex align-items-center gap-1 text-muted" style="font-size:0.75rem;">
                                <i class="bi bi-info-circle-fill text-primary"></i>
                                <span>Status: <strong>${status}</strong></span>
                            </div>
                        </div>
                    `;  
                    marker.bindPopup(popupContent);

                    jeepneyMarkers[driver.id] = marker;
                }
            });
        }


        function getAllQueues() {
            fetch('/queue') 
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(updateQueue);
        }
        
        function getDriversCoord() {
            fetch('/drivers') 
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(addPinsToAllDrivers);
        }

        function saveLocationToDatabase(lat, lng) {
        fetch('/driver/location/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                'latitude': lat,   // Using the => operator to map keys
                'longitude': lng   // to the values we got from GPS
            })
        })       
        }

        setInterval(() => {
            if(targetLat && targetLng)
                saveLocationToDatabase(targetLat, targetLng);

            getDriversCoord();
            getAllQueues();
        }, 2000);
        
        // sendLocationToServer();

        function smoothMoveWithRotation(marker, targetLat, targetLng, duration, dispatchedTo) {
        // 1. Clear any active animation frame for this marker
        if (marker.animationFrameId) {
            cancelAnimationFrame(marker.animationFrameId);
        }

        // 2. Check if the actual destination has changed (fixes the straight-up snap bug)
        // We check against custom properties stored on the marker instead of its live moving position
        const hasNewDestination = (marker.lastTargetLat !== targetLat || marker.lastTargetLng !== targetLng);

        if (hasNewDestination && marker.lastTargetLat !== undefined) {
            // Calculate heading from the LAST target to the NEW target
            const lat1 = marker.lastTargetLat, lon1 = marker.lastTargetLng;
            const lat2 = targetLat, lon2 = targetLng;

            const dLon = (lon2 - lon1) * Math.PI / 180;
            const y = Math.sin(dLon) * Math.cos(lat2 * Math.PI / 180);
            const x = Math.cos(lat1 * Math.PI / 180) * Math.sin(lat2 * Math.PI / 180) -
                    Math.sin(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.cos(dLon);
            
            let angle = (Math.atan2(y, x) * 180 / Math.PI + 360) % 360;

            // ADJUST THIS OFFSET: If your image file naturally faces right, use -90. 
            // If it naturally faces up, use 0. If it faces left, use +90.
            angle = (angle - 90 + 360) % 360; 

            marker.lastValidAngle = angle;
        }

        // Save current targets for the next telemetry update comparison
        marker.lastTargetLat = targetLat;
        marker.lastTargetLng = targetLng;

        // Fallback to previous angle or 0 if it's the first render
        const finalAngle = marker.lastValidAngle !== undefined ? marker.lastValidAngle : 0;

        // 3. Apply rotation cleanly to the inner sprite (NO MORE scaleX FLIPPING)
        const container = marker.getElement();
        if (container) {
            const sprite = container.querySelector('.jeepney-sprite');
            if (sprite) {
                sprite.style.transform = `rotate(${finalAngle}deg)`;
            }
        }

        // 4. Smoothly slide coordinate positions
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

        function createNagaQueueCard(position, name, plate, is_filling) {
            // CARD
            const card = document.createElement("div");
            if(is_filling){
                card.className = "card queue-card rounded-3 shadow-sm p-3";
            } else{
                card.className = "card queue-card rounded-3 shadow-sm p-3 border-success-subtle";
            }

            // TOP ROW
            const row = document.createElement("div");
            row.className = "d-flex align-items-center justify-content-between";

            // LEFT SIDE WRAPPER
            const left = document.createElement("div");
            left.className = "d-flex align-items-center gap-2";

            // POSITION
            const pos = document.createElement("div");
            pos.className = "fw-bold fs-5 text-muted px-1";
            pos.textContent = position;

            // INFO WRAPPER
            const info = document.createElement("div");

            // NAME
            const nameEl = document.createElement("h6");
            nameEl.className = "mb-0 fw-bold fs-6";
            nameEl.textContent = name;

            // PLATE
            const plateEl = document.createElement("span");
            plateEl.className = "small text-muted font-monospace";
            plateEl.style.fontSize = "0.75rem";
            plateEl.textContent = `PLATE: ${plate}`;

            // APPEND INFO
            info.appendChild(nameEl);
            info.appendChild(plateEl);

            // LEFT BUILD
            left.appendChild(pos);
            left.appendChild(info);

            // STATUS BADGE
            const badge = document.createElement("span");
            if(is_filling){
                badge.className =
                    "badge bg-secondary-subtle text-secondary border";
                badge.textContent = "Queue";
            } else {
                badge.className =
                    "badge bg-success-subtle text-success border border-success-subtle";
                badge.textContent = "Filling Up";
            }
            badge.style.fontSize = "0.7rem";
            

            // ASSEMBLE ROW
            row.appendChild(left);
            row.appendChild(badge);

            // CARD FINAL
            card.appendChild(row);

            return card;
        }

        function createUlingQueueCard(position, name, plate, time, isFilling = false) {
            // 1. CARD
            const card = document.createElement("div");
            card.className = `card queue-card rounded-3 shadow-sm p-3 ${isFilling ? 'border-danger-subtle' : ''}`;

            // 2. TOP ROW
            const row = document.createElement("div");
            row.className = "d-flex align-items-center justify-content-between";

            // 3. LEFT SIDE WRAPPER
            const left = document.createElement("div");
            left.className = "d-flex align-items-center gap-2";

            // 4. POSITION
            const pos = document.createElement("div");
            pos.className = "fw-bold fs-5 text-muted px-1";
            pos.textContent = position;

            // 5. INFO WRAPPER
            const info = document.createElement("div");

            const nameEl = document.createElement("h6");
            nameEl.className = "mb-0 fw-bold fs-6";
            nameEl.textContent = name;

            const plateEl = document.createElement("span");
            plateEl.className = "small text-muted font-monospace";
            plateEl.style.fontSize = "0.75rem";
            plateEl.textContent = `PLATE: ${plate}`;

            info.appendChild(nameEl);
            info.appendChild(plateEl);

            // 6. LEFT BUILD
            left.appendChild(pos);
            left.appendChild(info);

            // 7. TIMER BADGE
            const badge = document.createElement("span");
            badge.className = "badge timer-badge d-flex align-items-center gap-1";
            badge.style.fontSize = "0.7rem";

            const icon = document.createElement("i");
            icon.className = `bi ${isFilling ? 'bi-hourglass-split text-danger' : 'bi-hourglass-top'}`;
            
            isFilling ? badge.appendChild(icon) : null;
            isFilling ? badge.appendChild(document.createTextNode(`10 Minutes`)) : badge.appendChild(document.createTextNode(`Queue`));

            // 8. ASSEMBLE ROW
            row.appendChild(left);
            row.appendChild(badge);

            // 9. CARD FINAL
            card.appendChild(row);

            return card;
        }

        function updateQueue(drivers) {
            const nagaQueueCount = document.getElementById('nagaToUlingQueueCount');
            nagaQueueCount.textContent = drivers.length + " Active";
            
            nagaToUlingQueue.replaceChildren();
            ulingToNagaQueue.replaceChildren();

            drivers
            .filter(driver => driver.status.dispatched_to === "Naga")
            .forEach((driver, index) => {
                if(driver.status.dispatched_to == "Naga"){
                    let is_filling = driver.filling_at == null;

                    const card = createNagaQueueCard(index + 1,
                    `${driver.profile.first_name} ${driver.profile.middle_name} ${driver.profile.last_name}`,
                    driver.profile.plate_number,
                    is_filling);
                    nagaToUlingQueue.appendChild(card);
                };
            });

            drivers
            .filter(driver => driver.status.dispatched_to === "Uling")
            .forEach((driver, index) => {
                let is_filling = driver.filling_at != null;

                const card = createUlingQueueCard(
                    index + 1, // Now index is always 0, 1, 2... for the Uling list
                    `${driver.profile.first_name} ${driver.profile.middle_name} ${driver.profile.last_name}`,
                    driver.profile.plate_number,
                    is_filling,
                    is_filling
                );
                ulingToNagaQueue.appendChild(card);
            });


            // const card = createQueueCard(
            //     1,
            //     "Juan Dela Cruz",
            //     "GHI-7890",
            //     "Filling Up"
            // );
            
        }
    </script>
    @include('partials.notifications')
</body>
</html>