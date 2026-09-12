<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Landmarks | Admin Dashboard</title>

    <!-- Standardized Bootstrap & Leaflet Integration -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #171B21;
            --ink-soft: #262C36;
            --stone: #F3F4F0;
            /* Softened stone for better background contrast */
            --card: #FFFFFF;
            --line: #E2E4DC;
            --amber: #F2A63C;
            --amber-hover: #D9902A;
            --alert: #D1495B;
            --primary: transparent;
            --amber-ink: #4A2E05;
            --font-display: 'Space Grotesk', 'Segoe UI', sans-serif;
            --font-body: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background-color: var(--stone);
            color: var(--ink);
            font-family: var(--font-body);
        }


        /* Navbar Fix */
        .nav-sticky-top {
            background-color: var(--ink);
            border-bottom: 3px solid var(--amber);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Layout & Cards */
        .admin-card {
            background-color: var(--card);
            border-radius: 16px;
            border: 1px solid var(--line);
        }

        #admin-map {
            height: 100%;
            min-height: 500px;
            border-radius: 16px;
            border: 1px solid var(--line);
            z-index: 1;
            /* Prevent map controls from overlapping modals/nav */
        }

        /* Form & Input Enhancements */
        .form-control,
        .form-select {
            border-color: var(--line);
            border-radius: 8px;
            padding: 0.6rem 0.75rem;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 0.25rem rgba(242, 166, 60, 0.25);
        }

        .btn-custom-primary {
            background-color: var(--amber);
            color: var(--amber-ink);
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-custom-primary:hover {
            background-color: var(--amber-hover);
            color: var(--amber-ink);
            transform: translateY(-1px);
        }

        /* Custom Landmark Popup */
        .custom-landmark-popup .leaflet-popup-content-wrapper {
            padding: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .custom-landmark-popup .leaflet-popup-tip-container {
            display: none;
        }

        .custom-landmark-popup .leaflet-popup-content {
            margin: 0;
            width: 260px !important;
        }

        .custom-landmark-popup .leaflet-popup-close-button {
            display: none !important;
        }

        .lm-popup-container {
            position: relative;
            background: var(--card);
            padding: 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-top: 15px;
            /* space for protruding buttons */
            margin-right: 15px;
        }

        .lm-actions {
            position: absolute;
            top: -14px;
            right: -14px;
            display: flex;
            gap: 4px;
            z-index: 100;
        }

        .lm-action-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }

        .lm-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.3);
        }

        .lm-btn-edit {
            background: var(--amber);
            color: var(--amber-ink);
        }

        .lm-btn-delete {
            background: var(--alert);
        }

        .lm-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .lm-icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--stone);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            border: 1px solid var(--line);
        }

        .lm-name {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 14px;
            color: var(--ink);
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 6px 10px;
            flex-grow: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .lm-image {
            width: 100%;
            height: 120px;
            border-radius: 6px;
            border: 1px solid var(--line);
            object-fit: cover;
            background: var(--stone);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Permanent Map Labels & Animations */
        .landmark-label {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            font-weight: 700 !important;
            color: var(--ink) !important;
            font-size: 12px !important;
            text-shadow: 2px 2px 0 #FFF, -2px -2px 0 #FFF, 2px -2px 0 #FFF, -2px 2px 0 #FFF !important;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }
    </style>
</head>

<body>
    @include('partials.admin-nav')

    <div class="container-fluid mt-4 pb-5 px-4">
        <!-- Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <h2 class="fw-bold m-0 fs-3"><i class="bi bi-geo-alt-fill text-warning me-2"></i>Map & Landmarks</h2>
            <span class="badge bg-white text-dark border p-2 shadow-sm"><i
                    class="bi bi-info-circle text-primary me-2"></i>Click map to drop a pin</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Map Container -->
            <div class="col-lg-8" style="height: calc(100vh - 160px); min-height: 500px;">
                <div id="admin-map" class="shadow-sm"></div>
            </div>

            <!-- Controls Panel -->
            <div class="col-lg-4 d-flex flex-column gap-4"
                style="height: calc(100vh - 160px); overflow-y: auto; padding-right: 10px;">

                <!-- Add New Landmark Form -->
                <div class="card admin-card shadow-sm border-0">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                        <h5 class="fw-bold m-0"><i class="bi bi-pin-map-fill text-warning me-2"></i>Location Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="landmarkForm" action="{{ route('admin.landmarks.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod" value="POST">

                                                        <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Location Name</label>
                                <input type="text" name="name" id="nameInput" class="form-control" required
                                    placeholder="e.g. Minglanilla Public Market">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Category</label>
                                <select name="type" id="typeInput" class="form-select" required>
                                    <option value="gas_station">Gas Station</option>
                                    <option value="school">School</option>
                                    <option value="market">Public Market</option>
                                    <option value="mini_stop">Mini Stop</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Hidden coordinates populated via map click -->
                            <input type="hidden" name="latitude" id="latInput" required>
                            <input type="hidden" name="longitude" id="lngInput" required>

                            <div class="mb-4">
                                <label class="form-label text-muted small fw-semibold">Location Image</label>
                                <input type="file" name="image" id="imageInput" class="form-control"
                                    accept="image/*">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" id="submitBtn"
                                    class="btn btn-custom-primary w-100 fw-bold shadow-sm">Save Landmark</button>
                                <button type="button" id="cancelBtn" class="btn btn-light border d-none fw-bold"
                                    onclick="resetForm()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List of Landmarks -->
                <div class="card admin-card shadow-sm border-0">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2">
                        <h5 class="fw-bold m-0"><i class="bi bi-list-ul text-muted me-2"></i>Existing Database</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    @forelse($landmarks as $landmark)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark">{{ $landmark->name }}</div>
                                                <div class="text-muted small">
                                                    <i class="bi {{ $landmark->icon ?? 'bi-geo-alt' }} me-1"
                                                        style="color: {{ $landmark->color ?? '#6c757d' }}"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $landmark->type)) }}
                                                </div>
                                            </td>
                                            <td class="text-end pe-4 py-3">
                                                <form action="{{ route('admin.landmarks.destroy', $landmark->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this landmark?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger px-2 py-1 rounded">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-2 d-block mb-2 text-light"></i>
                                                No landmarks pinned yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function escapeHtml(value) {
            const div = document.createElement('div');
            div.innerText = value;
            return div.innerHTML;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('admin-map').setView([10.2350, 123.7350], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const existingLandmarks = @json($landmarks);

            existingLandmarks.forEach(landmark => {
                let color = landmark.color || '#f2a63c';
                let innerIcon = landmark.icon || 'bi-geo-alt-fill';

                const iconHtml = `
                                        <div style="position: relative; width: 32px; height: 32px; display: flex; justify-content: center;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 32px; line-height: 1; color: ${color}; filter: drop-shadow(0px 3px 3px rgba(0,0,0,0.4)); -webkit-text-stroke: 1px #171B21;"></i>
                        <!-- Solid white circle to cover the messy inner stroke of the hole -->
                        <div style="position: absolute; top: 4.5px; left: 50%; transform: translateX(-50%); width: 13px; height: 13px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;">
                            <i class="bi ${innerIcon}" style="font-size: 9px; color: #171B21;"></i>
                        </div>
                    </div>
                `;

                const m = L.marker([landmark.latitude, landmark.longitude], {
                    icon: L.divIcon({
                        className: 'existing-marker',
                        html: iconHtml,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    })
                }).addTo(map);

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const popupHtml = `
                    <div class="lm-popup-container">
                        <div class="lm-actions">
                            <button class="lm-action-btn lm-btn-edit" onclick="editLandmark(${landmark.id})" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <form action="/admin/landmarks/${landmark.id}" method="POST" class="m-0 p-0" onsubmit="return confirm('Delete this landmark?');">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="lm-action-btn lm-btn-delete" title="Delete">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
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
                            : `<div class="lm-image"><i class="bi bi-image text-muted fs-2"></i></div>`
                        }
                    </div>
                `;

                m.bindPopup(popupHtml, {
                    className: 'custom-landmark-popup',
                    minWidth: 260
                }).bindTooltip(landmark.name, {
                    permanent: true,
                    direction: 'bottom',
                    className: 'landmark-label',
                    offset: [0, 15]
                });
            });

            const form = document.getElementById('landmarkForm');
            const formMethod = document.getElementById('formMethod');
            const submitBtn = document.getElementById('submitBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            window.editLandmark = function(id) {
                const landmark = existingLandmarks.find(l => l.id === id);
                if (!landmark) return;

                form.action = `/admin/landmarks/${id}`;
                formMethod.value = "PUT";

                document.getElementById('nameInput').value = landmark.name;
                document.getElementById('typeInput').value = landmark.type;
                
                
                document.getElementById('latInput').value = landmark.latitude;
                document.getElementById('lngInput').value = landmark.longitude;

                

                submitBtn.innerHTML = "Update Landmark";
                cancelBtn.classList.remove('d-none');

                if (window.tempMarker) {
                    window.tempMarker.setLatLng([landmark.latitude, landmark.longitude]);
                } else {
                    map.fireEvent('click', {
                        latlng: L.latLng(landmark.latitude, landmark.longitude)
                    });
                }
            };

            window.resetForm = function() {
                form.action = "{{ route('admin.landmarks.store') }}";
                formMethod.value = "POST";
                form.reset();
                submitBtn.innerHTML = "Save Landmark";
                cancelBtn.classList.add('d-none');

                if (window.tempMarker) {
                    map.removeLayer(window.tempMarker);
                    window.tempMarker = null;
                }
            };

            window.tempMarker = null;
            const latInput = document.getElementById('latInput');
            const lngInput = document.getElementById('lngInput');

            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                latInput.value = lat.toFixed(7);
                lngInput.value = lng.toFixed(7);

                if (window.tempMarker) {
                    window.tempMarker.setLatLng(e.latlng);
                } else {
                    const tempIconHtml = `
                        <div style="position: relative; width: 40px; height: 40px; display: flex; justify-content: center;">
                            <i class="bi bi-geo-alt-fill text-success" style="font-size: 40px; line-height: 1; filter: drop-shadow(0px 4px 6px rgba(0,0,0,0.8)); animation: bounce 2s infinite; -webkit-text-stroke: 1px #171B21;"></i>
                        </div>
                    `;
                    window.tempMarker = L.marker(e.latlng, {
                        draggable: true,
                        icon: L.divIcon({
                            className: 'temp-marker',
                            html: tempIconHtml,
                            iconSize: [40, 40],
                            iconAnchor: [20, 40]
                        })
                    }).addTo(map);

                    window.tempMarker.on('dragend', function(event) {
                        const marker = event.target;
                        const position = marker.getLatLng();

                        latInput.value = position.lat.toFixed(7);
                        lngInput.value = position.lng.toFixed(7);
                    });
                }
            });
        });
    </script>
</body>

</html>
