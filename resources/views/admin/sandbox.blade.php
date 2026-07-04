<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>System Simulation Sandbox</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-light: rgb(247, 251, 252);
            --neutral-tint: rgb(214, 230, 242);
            --primary-accent: rgb(185, 215, 234);
            --main-dark: rgb(118, 159, 205);
            --text-dark: #2c3e50;
            --admin-warning: #fd7e14;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Custom Colors & Borders */
        .bg-custom-dark { background-color: var(--main-dark) !important; color: white; }
        .bg-custom-tint { background-color: var(--neutral-tint); }
        .border-custom { border-color: var(--primary-accent) !important; }

        /* Mobile-First Layout Structure */
        .nav-sticky-top {
            background-color: #ffffff;
            border-bottom: 2px solid var(--admin-warning); /* Distinguish as Admin area */
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
            flex: 0 0 40dvh;
            border-bottom: 3px solid var(--admin-warning);
            z-index: 1;
            position: relative;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        .crosshair {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            pointer-events: none;
            z-index: 1000;
        }

        .scrollable-panel {
            flex-grow: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            background-color: var(--bg-light);
            padding: 1rem;
        }

        @media (min-width: 768px) {
            .main-wrapper { flex-direction: row; }
            #map-container {
                flex: 0 0 55%;
                border-bottom: none;
                border-right: 3px solid var(--admin-warning);
            }
        }

        /* Form Controls & Components */
        .sandbox-card {
            background: white;
            border: 1px solid var(--primary-accent);
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1rem;
        }

        .sandbox-header {
            background-color: var(--neutral-tint);
            border-bottom: 1px solid var(--primary-accent);
            padding: 0.75rem 1rem;
            font-weight: bold;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-switch .form-check-input:checked {
            background-color: var(--main-dark);
            border-color: var(--main-dark);
        }

        /* Toast container */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1055;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            <a class="navbar-brand fw-bold d-flex align-items-center m-0" href="#">
                <i class="bi bi-tools me-2" style="color: var(--admin-warning);"></i>
                <span class="fs-5 text-dark">Simulation Sandbox</span>
            </a>
            <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-exclamation-triangle-fill"></i> Admin Mode
            </span>
        </div>
    </nav>

    <div class="main-wrapper">
        
        <!-- Map Section -->
        <div id="map-container">
            <div id="map"></div>
            <!-- Visual crosshair for center of map -->
            <svg class="crosshair" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <line x1="50" y1="0" x2="50" y2="100" stroke="rgba(253, 126, 20, 0.5)" stroke-width="2"/>
                <line x1="0" y1="50" x2="100" y2="50" stroke="rgba(253, 126, 20, 0.5)" stroke-width="2"/>
                <circle cx="50" cy="50" r="5" fill="none" stroke="rgba(253, 126, 20, 0.8)" stroke-width="2"/>
            </svg>
        </div>

        <!-- Controls Section -->
        <div class="scrollable-panel">
            
            <!-- Driver Selection -->
            <div class="sandbox-card">
                <div class="sandbox-header">
                    <span><i class="bi bi-person-badge-fill me-2 text-custom-dark"></i>Target Entity</span>
                </div>
                <div class="p-3">
                    <select class="form-select border-custom" id="simulatedDriver">
                        <option value="1">Driver: Juan Dela Cruz (GHI-7890)</option>
                        <option value="2">Driver: Pedro Penduko (JKL-1234)</option>
                        <option value="3" selected>Driver: Test Dummy (TST-0000)</option>
                    </select>
                </div>
            </div>

            <!-- GPS Location Control -->
            <div class="sandbox-card">
                <div class="sandbox-header">
                    <span><i class="bi bi-geo-alt-fill me-2 text-custom-dark"></i>Location Override</span>
                    <span class="badge bg-primary rounded-pill bg-custom-dark">Live GPS</span>
                </div>
                <div class="p-3">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Latitude</label>
                            <input type="text" class="form-control form-control-sm border-custom font-monospace" id="latInput" readonly>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted">Longitude</label>
                            <input type="text" class="form-control form-control-sm border-custom font-monospace" id="lngInput" readonly>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-dark border-custom fw-bold d-flex justify-content-between" onclick="teleportDriver(10.2089, 123.7582, 'Naga Terminal')">
                            <span>Move to Naga Terminal</span> <i class="bi bi-geo"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-dark border-custom fw-bold d-flex justify-content-between" onclick="teleportDriver(10.2300, 123.7350, 'Mid-Route Area')">
                            <span>Move to Mid-Route Area</span> <i class="bi bi-car-front"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-dark border-custom fw-bold d-flex justify-content-between" onclick="teleportDriver(10.2500, 123.7100, 'Uling Terminal')">
                            <span>Move to Uling Terminal</span> <i class="bi bi-sign-stop"></i>
                        </button>
                    </div>
                    <div class="alert alert-info mt-3 mb-0 py-2 small border-info-subtle">
                        <i class="bi bi-info-circle me-1"></i> You can also drag the marker directly on the map.
                    </div>
                </div>
            </div>

            <!-- Anti-Cheat & Wi-Fi Controls -->
            <div class="sandbox-card">
                <div class="sandbox-header" style="background-color: rgba(253, 126, 20, 0.1);">
                    <span><i class="bi bi-router-fill me-2 text-warning"></i>Anti-Cheating Trigger</span>
                </div>
                <div class="p-3">
                    <div class="form-check form-switch fs-5 mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="wifiToggle" checked>
                        <label class="form-check-label fs-6 fw-bold ms-2" for="wifiToggle">Uling Dead-Spot Wi-Fi</label>
                    </div>
                    <p class="small text-muted mb-3">Toggle to simulate a driver disconnecting from the verification router prematurely.</p>
                    <button class="btn btn-warning w-100 fw-bold shadow-sm" onclick="triggerGeofenceViolation()">
                        <i class="bi bi-exclamation-octagon me-1"></i> Force "Geofence Skip" Violation
                    </button>
                </div>
            </div>

            <!-- Queue Dispatch Controls -->
            <div class="sandbox-card">
                <div class="sandbox-header">
                    <span><i class="bi bi-stoplights-fill me-2 text-custom-dark"></i>Queue Triggers</span>
                </div>
                <div class="p-3 d-flex gap-2">
                    <button class="btn bg-custom-dark text-white flex-grow-1 fw-bold shadow-sm" onclick="triggerEvent('Simulated Dispatch Executed')">
                        <i class="bi bi-send me-1"></i> Dispatch Driver
                    </button>
                    <button class="btn btn-danger flex-grow-1 fw-bold shadow-sm" onclick="triggerEvent('Driver Kicked from Queue')">
                        <i class="bi bi-x-circle me-1"></i> Drop from Queue
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- UI Toast for Feedback -->
    <div class="toast-container">
        <div id="sysToast" class="toast align-items-center border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-bold d-flex align-items-center" id="toastMessage">
                    <!-- Message injected here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Init Map
        const map = L.map('map', { zoomControl: false }).setView([10.2350, 123.7350], 13);
        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Fix mobile tile loading bug
        setTimeout(() => { map.invalidateSize(); }, 300);
        window.addEventListener('resize', () => { map.invalidateSize(); });

        // Input References
        const latInput = document.getElementById('latInput');
        const lngInput = document.getElementById('lngInput');

        // Draggable Marker for Simulator
        let simMarker = L.marker([10.2300, 123.7350], {
            draggable: true,
            title: "Drag me to simulate movement!"
        }).addTo(map);

        // Bind popup to marker
        simMarker.bindPopup("<b>Simulated Vehicle</b><br>Drag me around!").openPopup();

        // Update inputs on marker drag
        simMarker.on('dragend', function(e) {
            const position = simMarker.getLatLng();
            updateInputs(position.lat, position.lng);
            showToast(`Location manually set to: ${position.lat.toFixed(4)}, ${position.lng.toFixed(4)}`, 'bg-info');
        });

        // Initialize inputs
        updateInputs(10.2300, 123.7350);

        function updateInputs(lat, lng) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }

        // Quick Teleport Function
        function teleportDriver(lat, lng, locationName) {
            const newLatLng = new L.LatLng(lat, lng);
            simMarker.setLatLng(newLatLng);
            map.flyTo(newLatLng, 15);
            updateInputs(lat, lng);
            
            // Simulating an Axios call to update backend DB
            console.log(`Payload sent: { driver_id: ${document.getElementById('simulatedDriver').value}, lat: ${lat}, lng: ${lng} }`);
            showToast(`Teleported to ${locationName}`, 'bg-success text-white');
        }

        // Wi-Fi Toggle Logic
        document.getElementById('wifiToggle').addEventListener('change', function(e) {
            const isConnected = e.target.checked;
            if(!isConnected) {
                showToast("Wi-Fi Connection Dropped. Timer started for anti-cheat verification.", 'bg-warning text-dark');
            } else {
                showToast("Wi-Fi Connected. Ping successful.", 'bg-success text-white');
            }
        });

        // Forced Violation Trigger
        function triggerGeofenceViolation() {
            // Mock backend call
            showToast("Geofence Cheating Violation logged to driver's profile.", 'bg-danger text-white');
        }

        // Generic Event Trigger
        function triggerEvent(message) {
            showToast(message, 'bg-dark text-white');
        }

        // Bootstrap Toast Wrapper
        function showToast(message, colorClass) {
            const toastEl = document.getElementById('sysToast');
            const toastMsg = document.getElementById('toastMessage');
            
            // Reset classes
            toastEl.className = 'toast align-items-center border-0 shadow-lg ' + colorClass;
            
            // Set message
            toastMsg.innerHTML = `<i class="bi bi-info-circle-fill me-2"></i> ${message}`;
            
            // Show
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
        }
    </script>
</body>
</html>