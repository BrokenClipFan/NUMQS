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
                        <span class="badge bg-secondary rounded-pill">3 Active</span>
                    </div>
                    
                    <div class="d-flex flex-column gap-2">
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

                    <div class="d-flex flex-column gap-2">
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
        let jeepneyMarkers = {}
        const map = L.map('map', {
            zoomControl: false 
        }).setView([10.2350, 123.7200], 13);

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

        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

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

                if(jeepneyMarkers[driver.id]){
                    let marker = jeepneyMarkers[driver.id];
                    const driverLat = driver.status.latitude;
                    const driverLng = driver.status.longitude;

                    smoothMove(marker, driverLat, driverLng, 2000);

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
                    
                    let iconFilePath = '/images/';
                    if(dispatchedTo == "to_naga")
                        iconFilePath += 'jeepToRight.png'
                    else
                        iconFilePath += 'jeepToLeft.png'

                    const jeepIcon = L.icon({
                        iconUrl: iconFilePath, 
                        iconSize: [38, 38],             
                        iconAnchor: [19, 38],           
                        popupAnchor: [0, -38]           
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
            })
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
        }, 2000);
        
        // sendLocationToServer();
        
        function smoothMove(marker, targetLat, targetLng, duration) {
            const startPos = marker.getLatLng();
            const startTime = performance.now();

            function animate(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1); // 0 to 1

                // Calculate current position (Linear Interpolation)
                const lat = startPos.lat + (targetLat - startPos.lat) * progress;
                const lng = startPos.lng + (targetLng - startPos.lng) * progress;

                marker.setLatLng([lat, lng]);

                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            }
            requestAnimationFrame(animate);
        }
    </script>
    @include('partials.notifications')
</body>
</html>