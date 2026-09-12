<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Profile & Violations</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --ink: #171B21;
            --ink-soft: #262C36;
            --stone: #E7E9E3;
            --card: #FDFDFB;
            --line: #D8DBD2;
            --amber: #F2A63C;
            --amber-ink: #4A2E05;
            --alert: #D1495B;
            --text-primary: #1B1F26;
            --text-muted: #6B7280;

            --font-display: 'Space Grotesk', 'Segoe UI', sans-serif;
            --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
            --font-body: 'Inter', 'Segoe UI', sans-serif;
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

        /* Nav & Core Layout */
        .nav-sticky-top {
            background-color: var(--ink);
            border-bottom: 3px solid var(--amber);
            flex-shrink: 0;
            z-index: 1030;
            box-shadow: 0 2px 14px rgba(0, 0, 0, 0.25);
        }

        .scrollable-content {
            flex-grow: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 2rem;
        }

        /* Custom Colors & Borders */
        .card-custom {
            background-color: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(23, 27, 33, 0.06);
        }

        .bg-custom-tint {
            background-color: var(--stone);
        }

        .border-custom {
            border-color: var(--line) !important;
        }

        /* Profile Image Upload Styling */
        .profile-img-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid var(--card);
            background-color: var(--ink);
            box-shadow: 0 4px 14px rgba(23, 27, 33, 0.15);
            transition: opacity 0.2s;
        }

        .upload-btn {
            position: absolute;
            bottom: 2px;
            right: 2px;
            background-color: var(--amber);
            color: var(--amber-ink);
            border: 2px solid var(--card);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.12s ease;
        }

        .upload-btn:hover {
            transform: scale(1.08);
        }

        .upload-btn:active {
            transform: scale(0.95);
        }

        /* Form Controls */
        .form-control {
            background-color: var(--stone);
            border: 1.5px solid var(--line);
            border-radius: 8px;
            font-size: 0.88rem;
        }

        .form-control:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 3px rgba(242, 166, 60, 0.18);
            background-color: var(--card);
        }

        .field-label {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .btn-main {
            background-color: var(--amber);
            color: var(--amber-ink);
            font-weight: 700;
            border: none;
            box-shadow: 0 3px 0 #c78423;
            transition: filter 0.12s ease, transform 0.12s ease;
            font-family: var(--font-body);
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .btn-main:hover {
            filter: brightness(1.04);
            color: var(--amber-ink);
        }

        .btn-main:active {
            transform: scale(0.99);
        }

        .btn-nav-back {
            background: transparent;
            color: #D8DBD2;
            border: 1px solid rgba(255, 255, 255, 0.16);
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 0.8rem;
        }

        .btn-nav-back:hover {
            border-color: var(--amber);
            color: var(--amber);
        }

        /* Violation List Styling */
        .violation-card {
            border-left: 5px solid var(--alert);
            background-color: #fff;
            transition: transform 0.2s;
            border-radius: 8px;
        }

        .violation-card:hover {
            transform: translateX(2px);
        }

        .violation-icon {
            background-color: rgba(209, 73, 91, 0.1);
            color: var(--alert);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0"
            style="max-width: 1100px; margin: 0 auto;">
            <div style="width: 80px;">
                <a href="{{ route('driver.map') }}"
                    class="btn btn-sm btn-nav-back d-flex align-items-center d-inline-flex rounded-2">
                    <i class="bi bi-chevron-left me-1"></i> Back
                </a>
            </div>

            <span class="fw-bold fs-6 text-white text-center" style="font-family: var(--font-display);">Driver
                Profile</span>

            <div style="width: 80px;" class="d-flex justify-content-end">
                <a href="{{ route('driver.map') }}"
                    class="btn btn-sm btn-nav-back d-flex align-items-center justify-content-center rounded-2"
                    title="Home">
                    <i class="bi bi-house-door-fill mb-0"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="scrollable-content container py-4 mx-auto" style="max-width: 1100px;">

        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card-custom mb-4">
                    <div class="p-4">

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <div class="text-center mb-4">
                                <div class="profile-img-container">
                                    <img src="{{ asset('storage/' . $driver->image_profile_path) }}" id="profilePreview"
                                        class="profile-img" alt="Driver Photo" onclick="zoomImage(this.src)" style="cursor: zoom-in;">

                                    <label for="photoUpload" class="upload-btn" title="Upload new photo">
                                        <i class="bi bi-pencil-fill"></i>
                                    </label>
                                    <input type="file" id="photoUpload" name="photo" class="d-none"
                                        accept="image/*" onchange="previewImage(event)">
                                </div>
                                <h4 class="mt-3 mb-0 fw-bold"
                                    style="font-family: var(--font-display); color: var(--ink);">
                                    {{ $driver->first_name . ' ' . $driver->last_name }}</h4>
                                <span class="badge mt-2 font-monospace fs-6"
                                    style="background: rgba(242, 166, 60, 0.15); color: #A5691B; border: 1px solid rgba(242, 166, 60, 0.35);">PLATE:
                                    {{ $driver->plate_number }}</span>
                            </div>

                            <hr class="border-custom">

                            <div class="mb-3">
                                <label class="field-label mb-2">Driver Bio / Status</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" name="bio" rows="2"
                                    placeholder="Tell dispatch something about your daily routine...">{{ old('bio', 'Regular route from Naga to Uling. Always on time.') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback"
                                        style="font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <h6 class="mb-3 mt-4 fw-bold"
                                style="font-family: var(--font-mono); font-size: 0.72rem; letter-spacing: 0.05em; text-transform: uppercase; color: var(--text-muted);">
                                <i class="bi bi-person-vcard me-1"></i>Personal Info
                            </h6>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="field-label mb-1">First Name</label>
                                    <input type="text" class="form-control" style="background-color: var(--stone);"
                                        name="first_name" value="{{ $driver->first_name }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label class="field-label mb-1">Last Name</label>
                                    <input type="text" class="form-control" style="background-color: var(--stone);"
                                        name="last_name" value="{{ $driver->last_name }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="field-label mb-1">Birth Date</label>
                                <input type="text" class="form-control" style="background-color: var(--stone);"
                                    name="birthdate"
                                    value="{{ \Carbon\Carbon::parse($driver->birthdate)->format('F j, Y') }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="field-label mb-2">Contact Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone', $driver->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback"
                                        style="font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="field-label mb-2">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $driver->email) }}">
                                @error('email')
                                    <div class="invalid-feedback"
                                        style="font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="field-label mb-2">Home Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2">{{ old('address', $driver->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback"
                                        style="font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <h6 class="mb-3 mt-4 fw-bold"
                                style="font-family: var(--font-mono); font-size: 0.72rem; letter-spacing: 0.05em; text-transform: uppercase; color: var(--text-muted);">
                                <i class="bi bi-shield-check me-1"></i>License & Emergency
                            </h6>

                            <div class="mb-3">
                                <label class="field-label mb-1">Driver's License No.</label>
                                <input type="text" class="form-control font-monospace"
                                    style="background-color: var(--stone);" name="license_number"
                                    value="{{ $driver->license_number }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="field-label mb-1">Plate Number</label>
                                <input type="text" class="form-control font-monospace text-uppercase fw-bold"
                                    style="background-color: var(--stone);" name="plate_number"
                                    value="{{ $driver->plate_number }}" readonly>
                            </div>

                            <div class="mb-3 mt-4">
                                <label class="field-label mb-2">Emergency Contact Name</label>
                                <input type="text"
                                    class="form-control @error('emergency_name') is-invalid @enderror"
                                    name="emergency_name"
                                    value="{{ old('emergency_name', $driver->emergency_name) }}">
                                @error('emergency_name')
                                    <div class="invalid-feedback"
                                        style="font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="field-label mb-2">Emergency Contact No.</label>
                                <input type="tel"
                                    class="form-control @error('emergency_phone') is-invalid @enderror"
                                    name="emergency_phone"
                                    value="{{ old('emergency_phone', $driver->emergency_phone) }}">
                                @error('emergency_phone')
                                    <div class="invalid-feedback"
                                        style="font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700;">
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <h6 class="mb-3 mt-4 fw-bold"
                                style="font-family: var(--font-mono); font-size: 0.72rem; letter-spacing: 0.05em; text-transform: uppercase; color: var(--text-muted);">
                                <i class="bi bi-images me-1"></i>Vehicle Photos
                            </h6>

                            <div class="row g-2 mb-4">
                                <div class="col-4">
                                    <div class="rounded overflow-hidden"
                                        style="border: 1px solid var(--line); aspect-ratio: 4/3; background: var(--stone); position: relative;">
                                        <span class="position-absolute top-0 start-0 m-1 px-1 rounded text-white"
                                            style="font-size: 0.55rem; background: rgba(0,0,0,0.6); z-index: 2;">FRONT</span>
                                        <img src="{{ asset('storage/' . $driver->image_front_path) }}"
                                            style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8; cursor: zoom-in;"
                                            alt="Front view" onclick="zoomImage(this.src)">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="rounded overflow-hidden"
                                        style="border: 1px solid var(--line); aspect-ratio: 4/3; background: var(--stone); position: relative;">
                                        <span class="position-absolute top-0 start-0 m-1 px-1 rounded text-white"
                                            style="font-size: 0.55rem; background: rgba(0,0,0,0.6); z-index: 2;">SIDE</span>
                                        <img src="{{ asset('storage/' . $driver->image_side_path) }}"
                                            style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8; cursor: zoom-in;"
                                            alt="Side view" onclick="zoomImage(this.src)">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="rounded overflow-hidden"
                                        style="border: 1px solid var(--line); aspect-ratio: 4/3; background: var(--stone); position: relative;">
                                        <span class="position-absolute top-0 start-0 m-1 px-1 rounded text-white"
                                            style="font-size: 0.55rem; background: rgba(0,0,0,0.6); z-index: 2;">PLATE</span>
                                        <img src="{{ asset('storage/' . $driver->image_plate_path) }}"
                                            style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8; cursor: zoom-in;"
                                            alt="Plate view" onclick="zoomImage(this.src)">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-main w-100 py-2 rounded-3">
                                <i class="bi bi-floppy-fill me-2 fs-5"></i> Save Details
                            </button>
                        </form>

                        <hr class="border-custom my-4">
                        <div class="mb-3">
                            <label class="field-label mb-2">Account Management</label>

                            <div class="d-flex flex-column gap-2">
                                <form method="POST" action="{{ route('logout') }}" class="w-100">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-outline-secondary w-100 fw-bold py-2 rounded-3 shadow-sm">
                                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                                    </button>
                                </form>

                                <form action="{{-- route('profile.destroy') --}}" method="POST" class="w-100"
                                    onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                                    {{-- @csrf @method('DELETE') --}}
                                    <button type="submit"
                                        class="btn btn-outline-danger w-100 fw-bold py-2 rounded-3 shadow-sm">
                                        <i class="bi bi-trash3-fill me-1"></i> Delete Account
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-7">

                <div class="alert shadow-sm rounded-4 mb-4 d-flex gap-3 align-items-start"
                    style="background-color: rgba(209, 73, 91, 0.08); border: 1px solid rgba(209, 73, 91, 0.25); color: var(--alert);">
                    <i class="bi bi-shield-exclamation fs-3 mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1" style="font-family: var(--font-display);">Anti-Cheating System Active</h6>
                        <p class="mb-0 small" style="color: var(--ink-soft);">
                            Going to the wrong terminal or bypassing the proper route will automatically log a Cheating Warning on your record.
                        </p>
                    </div>
                </div>

                <div class="card-custom bg-custom-tint overflow-hidden">
                    <div class="bg-white border-bottom border-custom d-flex justify-content-between align-items-center py-3 px-3">
                        <h6 class="mb-0 fw-bold" style="color: var(--ink); font-family: var(--font-display);">
                            <i class="bi bi-cone-striped me-2" style="color: var(--amber-ink);"></i> Warnings & Violations
                        </h6>
                        @php $vCount = count($violations); @endphp
                        <span class="badge rounded-pill font-monospace" style="background-color: {{ $vCount > 0 ? 'var(--alert)' : '#198754' }};">
                            {{ $vCount }} {{ $vCount === 1 ? 'Warning' : 'Warnings' }}
                        </span>
                    </div>

                    <div class="p-3">
                        <div class="d-flex flex-column gap-3">

                            @forelse ($violations as $violation)
                                @php
                                    $isHighSeverity = strtolower($violation->severity) === 'high' || strtolower($violation->severity) === 'critical';
                                    $borderColor = $isHighSeverity ? 'var(--alert)' : 'var(--amber)';
                                    $iconBg = $isHighSeverity ? 'rgba(209, 73, 91, 0.1)' : 'rgba(242, 166, 60, 0.15)';
                                    $textColor = $isHighSeverity ? 'var(--alert)' : 'var(--amber-ink)';
                                    $badgeBorder = $isHighSeverity ? 'rgba(209, 73, 91, 0.25)' : 'rgba(242, 166, 60, 0.35)';
                                @endphp
                                <div class="card violation-card shadow-sm p-3 border-top-0 border-end-0 border-bottom-0" style="border-left-color: {{ $borderColor }};">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex gap-2 align-items-center">
                                            <div class="violation-icon" style="background-color: {{ $iconBg }}; color: {{ $textColor }};">
                                                <i class="bi {{ $isHighSeverity ? 'bi-geo-alt-fill' : 'bi-exclamation-circle' }} fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold" style="color: {{ $textColor }};">{{ $violation->name }}</h6>
                                                <small class="text-muted fw-bold font-monospace" style="font-size: 0.7rem;">{{ $violation->location ?? 'Unknown Location' }}</small>
                                            </div>
                                        </div>
                                        <span class="badge border font-monospace text-uppercase"
                                            style="background-color: {{ $iconBg }}; color: {{ $textColor }}; border-color: {{ $badgeBorder }} !important;">
                                            {{ $violation->severity }}
                                        </span>
                                    </div>
                                    <div class="p-2 rounded small font-monospace mt-2 d-flex justify-content-between"
                                        style="background-color: var(--stone); color: var(--text-muted); border: 1px solid var(--line);">
                                        <span><i class="bi bi-calendar-event me-1"></i> {{ $violation->created_at->format('M d, Y') }}</span>
                                        <span><i class="bi bi-clock me-1"></i> {{ $violation->created_at->format('h:i A') }}</span>
                                    </div>
                                    @if(!empty($violation->properties))
                                        <div class="small mt-2" style="color: var(--ink-soft);">
                                            <strong>System Note:</strong><br>
                                            @foreach($violation->properties as $key => $val)
                                                <span class="d-block">&bull; {{ $key }}: <span class="text-muted">{{ is_array($val) ? json_encode($val) : $val }}</span></span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center p-4">
                                    <i class="bi bi-shield-check text-success fs-1 mb-2 d-block"></i>
                                    <h6 class="fw-bold mb-0">No Active Warnings</h6>
                                    <small class="text-muted">Your record is clean.</small>
                                </div>
                            @endforelse

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Image Zoom Modal -->
    <div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close btn-close-white bg-white m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-0">
                    <img id="zoomedImage" src="" class="img-fluid rounded-3 shadow-lg" alt="Zoomed view" style="max-height: 85vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    @include('partials.notifications')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function zoomImage(src) {
            document.getElementById('zoomedImage').src = src;
            new bootstrap.Modal(document.getElementById('imageZoomModal')).show();
        }


        // Simple script to preview the profile image before submitting the form
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('profilePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    // Add a slight pulse animation to confirm the change
                    preview.style.opacity = '0.5';
                    setTimeout(() => preview.style.opacity = '1', 150);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>

