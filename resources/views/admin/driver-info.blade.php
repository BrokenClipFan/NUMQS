<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Driver Profile | Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            /* Same dispatch-board token system as the other admin pages */
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

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--stone);
            color: var(--text-primary);
            font-family: var(--font-body);
            min-height: 100dvh;
        }

        ::selection {
            background: var(--amber);
            color: var(--amber-ink);
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        textarea:focus-visible,
        label:focus-within {
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

        .visually-hidden-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* ---------------------------------------------------------------
           Header
        ----------------------------------------------------------------*/
        .nav-sticky-top {
            background-color: var(--ink);
            border-bottom: 3px solid var(--amber);
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 14px rgba(0, 0, 0, 0.25);
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            color: #F4F5F1;
            font-family: var(--font-display);
            font-weight: 700;
            text-decoration: none;
        }

        .back-link .bi-arrow-left-short {
            font-size: 1.5rem;
            color: var(--amber);
        }

        .back-link:hover {
            color: var(--amber);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .status-readout {
            font-family: var(--font-mono);
            font-weight: 600;
            font-size: 0.72rem;
            padding: 0.4rem 0.65rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            letter-spacing: 0.03em;
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #D8DBD2;
        }

        .status-readout.online {
            color: var(--route-uling);
            border-color: rgba(47, 143, 107, 0.4);
            background: rgba(47, 143, 107, 0.1);
        }

        .live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            display: inline-block;
        }

        .status-readout.online .live-dot {
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

        .btn-view-map {
            font-family: var(--font-body);
            font-weight: 700;
            font-size: 0.8rem;
            background: var(--amber);
            color: var(--amber-ink);
            border: none;
            border-radius: 20px;
            padding: 0.45rem 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            box-shadow: 0 2px 0 #c78423;
            transition: filter 0.12s ease, transform 0.12s ease;
        }

        .btn-view-map:hover {
            filter: brightness(1.04);
            color: var(--amber-ink);
        }

        .btn-view-map:active {
            transform: scale(0.97);
        }

        @media (max-width: 400px) {
            .hide-on-mobile-xs {
                display: none !important;
            }
        }

        /* ---------------------------------------------------------------
           Cards
        ----------------------------------------------------------------*/
        .admin-card {
            background: var(--card);
            border-radius: 14px;
            border: 1px solid var(--line);
            box-shadow: 0 4px 16px rgba(23, 27, 33, 0.06);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .admin-card-header {
            background-color: rgba(209, 73, 91, 0.08);
            border-bottom: 1px solid rgba(209, 73, 91, 0.25);
            padding: 1rem 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .section-tag {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            background: var(--alert);
            color: #FFF3F3;
        }

        /* ---------------------------------------------------------------
           Profile hero + editable avatar
        ----------------------------------------------------------------*/
        .profile-hero {
            background: linear-gradient(180deg, rgba(242, 166, 60, 0.08), rgba(242, 166, 60, 0.01));
            border-bottom: 1px solid var(--line);
            text-align: center;
            padding: 2.25rem 1rem 1.5rem 1rem;
        }

        .avatar-edit-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .driver-avatar-lg {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 4px solid var(--card);
            box-shadow: 0 4px 14px rgba(23, 27, 33, 0.15);
            background-color: var(--ink);
            color: var(--amber);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 700;
            overflow: hidden;
        }

        .driver-avatar-lg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-edit-btn {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--amber);
            color: var(--amber-ink);
            border: 2px solid var(--card);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.12s ease;
        }

        .photo-edit-btn:hover {
            transform: scale(1.08);
        }

        .photo-edit-btn:active {
            transform: scale(0.95);
        }

        .profile-name {
            font-family: var(--font-display);
            font-weight: 700;
            margin-bottom: 0.15rem;
        }

        .profile-id {
            font-family: var(--font-mono);
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .pending-change-note {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            color: var(--amber-ink);
            background: rgba(242, 166, 60, 0.18);
            border: 1px solid rgba(242, 166, 60, 0.4);
            border-radius: 20px;
            padding: 0.2rem 0.6rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-top: 0.5rem;
        }

        /* ---------------------------------------------------------------
           Form fields
        ----------------------------------------------------------------*/
        .field-label {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.3rem;
            display: block;
        }

        .section-heading {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--line);
            padding-bottom: 0.6rem;
            margin-bottom: 1rem;
        }

        .form-control,
        .form-select {
            background-color: var(--stone);
            border: 1.5px solid var(--line);
            font-size: 0.88rem;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 3px rgba(242, 166, 60, 0.18);
            background-color: var(--card);
        }

        .form-control:disabled {
            background-color: var(--stone);
            font-family: var(--font-mono);
            font-size: 0.82rem;
            color: var(--text-muted);
            opacity: 1;
        }

        /* ---------------------------------------------------------------
           Vehicle photo slots
        ----------------------------------------------------------------*/
        .photo-slot {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            border: 1.5px dashed var(--line);
            aspect-ratio: 4/3;
            background-color: var(--stone);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo-slot.has-image {
            border-style: solid;
        }

        .photo-slot.pending-upload {
            border-color: var(--amber);
            border-style: solid;
            box-shadow: 0 0 0 3px rgba(242, 166, 60, 0.18);
        }

        .photo-slot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-slot-placeholder {
            color: var(--text-muted);
            font-family: var(--font-mono);
            font-size: 0.72rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
        }

        .photo-slot-placeholder .bi {
            font-size: 1.3rem;
            opacity: 0.6;
        }

        .photo-slot-label {
            position: absolute;
            top: 6px;
            left: 6px;
            background: rgba(23, 27, 33, 0.75);
            color: #F4F5F1;
            font-family: var(--font-mono);
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
        }

        .photo-slot .photo-edit-btn {
            width: 34px;
            height: 34px;
            font-size: 0.9rem;
        }

        /* ---------------------------------------------------------------
           Buttons
        ----------------------------------------------------------------*/
        .btn-main {
            background-color: var(--amber);
            color: var(--amber-ink);
            font-weight: 700;
            border: none;
            box-shadow: 0 3px 0 #c78423;
            transition: filter 0.12s ease, transform 0.12s ease, opacity 0.12s ease;
        }

        .btn-main:hover {
            filter: brightness(1.04);
            color: var(--amber-ink);
        }

        .btn-main:active:not(:disabled) {
            transform: scale(0.99);
        }

        .btn-main.is-submitting {
            opacity: 0.65;
            pointer-events: none;
        }

        .btn-delete-driver {
            color: var(--alert);
            border: 1.5px solid var(--alert);
            background-color: transparent;
            font-weight: 700;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .btn-delete-driver:hover {
            background-color: var(--alert);
            color: white;
        }

        /* ---------------------------------------------------------------
           Violations table
        ----------------------------------------------------------------*/
        .table-custom {
            margin-bottom: 0;
            font-family: var(--font-body);
        }

        .table-custom thead th {
            background-color: var(--stone);
            color: var(--text-muted);
            border-bottom: 1px solid var(--line);
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .table-custom tbody td {
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .violation-date {
            font-family: var(--font-mono);
            font-weight: 700;
            display: block;
            font-size: 0.85rem;
        }

        .violation-time {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--text-muted);
        }

        .badge-route {
            background: var(--stone);
            color: var(--text-primary);
            border: 1px solid var(--line);
            font-family: var(--font-mono);
            font-weight: 600;
            font-size: 0.72rem;
        }

        .badge-violation {
            background-color: rgba(209, 73, 91, 0.1);
            color: var(--alert);
            border: 1px solid rgba(209, 73, 91, 0.25);
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
        }

        .btn-outline-danger {
            --bs-btn-color: var(--alert);
            --bs-btn-border-color: var(--alert);
            --bs-btn-hover-bg: var(--alert);
            --bs-btn-hover-border-color: var(--alert);
        }

        /* ---------------------------------------------------------------
           Mobile tweaks
        ----------------------------------------------------------------*/
        @media (max-width: 576px) {
            .profile-hero {
                padding: 1.75rem 1rem 1.25rem;
            }

            .nav-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>
</head>

<body>

    @php
        $isOnline = $user->is_online ?? false;
        $violationCount = isset($violations) ? count($violations) : 0;
        $avatarPath = !empty($user->avatar) ? asset('storage/' . ltrim($user->profile->image_profile_path, '/')) : null;
        $initials = collect([$user->profile->first_name ?? '', $user->profile->last_name ?? ''])
            ->filter()
            ->map(fn($n) => mb_strtoupper(mb_substr($n, 0, 1)))
            ->implode('');
    @endphp

    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0 flex-wrap gap-2"
            style="max-width: 900px; margin: 0 auto;">
            <a class="back-link" href="{{ route('fleet.management') }}">
                <i class="bi bi-arrow-left-short"></i>
                <span class="fs-6">Edit Driver</span>
            </a>

            <div class="nav-actions">
                <span class="status-readout {{ $isOnline ? 'online' : '' }}">
                    <span class="live-dot"></span>
                    {{ $isOnline ? 'Online' : 'Offline' }}
                </span>
                {{-- <a href="{{ route('admin.drivers.map', $user->id) }}" class="btn-view-map"> --}}
                <a href="#" class="btn-view-map">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span class="hide-on-mobile-xs">View Live on Map</span>
                    <span class="d-inline d-sm-none">Map</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 900px;">

        <!-- Editable Profile Card -->
        <div class="admin-card">
            <div class="profile-hero">
                <div class="avatar-edit-wrap">
                    <div class="driver-avatar-lg" id="avatarPreviewWrap">
                        @if ($avatarPath)
                            <img src="{{ $avatarPath }}" alt="" id="avatarPreviewImg">
                        @else
                            <span id="avatarInitials">{{ $initials ?: '?' }}</span>
                        @endif
                    </div>
                    <label for="avatarInput" class="photo-edit-btn" title="Change profile photo">
                        <i class="bi bi-pencil-fill"></i>
                        <span class="visually-hidden">Change profile photo</span>
                    </label>
                </div>
                <h4 class="profile-name">{{ $user->profile->first_name }} {{ $user->profile->last_name }}</h4>
                <p class="profile-id mb-0">Driver ID: {{ $user->id }}</p>
                <span class="pending-change-note d-none" id="avatarPendingNote">
                    <i class="bi bi-arrow-repeat"></i> New photo staged — save to apply
                </span>
                @error('profile_image')
                    <div class="text-danger small mt-2 fw-bold"><i
                            class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <!-- Edit Form -->
            <form action="{{ route('admin.drivers.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                id="driverEditForm">
                @csrf
                @method('PUT')

                <input type="file" name="profile_image" id="avatarInput" accept="image/png, image/jpeg, image/jpg"
                    class="visually-hidden-input">

                <div class="p-3 p-md-4">

                    <div class="row g-3 mb-4">
                        <!-- Personal Info -->
                        <div class="col-12 col-md-6">
                            <h6 class="section-heading"><i class="bi bi-person-vcard me-1"></i>Personal Info</h6>

                            <div class="mb-3">
                                <label class="field-label">First Name</label>
                                <input type="text" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name', $user->profile->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="field-label">Middle Name</label>
                                <input type="text" name="middle_name"
                                    class="form-control @error('middle_name') is-invalid @enderror"
                                    value="{{ old('middle_name', $user->profile->middle_name) }}">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="field-label">Last Name</label>
                                <input type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name', $user->profile->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="field-label">Birth Date</label>
                                <input type="date" name="birthdate"
                                    class="form-control @error('birthdate') is-invalid @enderror"
                                    value="{{ old('birthdate', $user->profile->birthdate->format('Y-m-d')) }}">

                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="field-label">Phone Number</label>
                                <input type="tel" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->profile->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="field-label">Email Address</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="field-label">Home Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $user->profile->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <label class="field-label">Registered At</label>
                                <input type="text" class="form-control"
                                    value="{{ optional($user->profile->created_at)->format('M d, Y \a\t h:i A') ?? '—' }}"
                                    disabled>
                            </div>
                        </div>

                        <!-- License & Emergency -->
                        <div class="col-12 col-md-6">
                            <h6 class="section-heading"><i class="bi bi-shield-check me-1"></i>License & Emergency
                            </h6>

                            <div class="mb-3">
                                <label class="field-label">Driver's License No.</label>
                                <input type="text" name="license_number"
                                    class="form-control font-monospace @error('license_number') is-invalid @enderror"
                                    value="{{ old('license_number', $user->profile->license_number) }}" required>
                                @error('license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="field-label">Plate Number</label>
                                <input type="text" name="plate_number"
                                    class="form-control font-monospace text-uppercase fw-bold @error('plate_number') is-invalid @enderror"
                                    value="{{ old('plate_number', $user->profile->plate_number) }}" required>
                                @error('plate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 mt-4">
                                <label class="field-label">Emergency Contact Name</label>
                                <input type="text" name="emergency_name"
                                    class="form-control @error('emergency_name') is-invalid @enderror"
                                    value="{{ old('emergency_name', $user->profile->emergency_name) }}">
                                @error('emergency_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="field-label">Emergency Contact No.</label>
                                <input type="tel" name="emergency_phone"
                                    class="form-control @error('emergency_phone') is-invalid @enderror"
                                    value="{{ old('emergency_phone', $user->profile->emergency_phone) }}">
                                @error('emergency_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Images Section -->
                    <h6 class="section-heading"><i class="bi bi-images me-1"></i>Vehicle Photos</h6>
                    <div class="row g-2 mb-4">
                        @php
                            $photoSlots = [
                                [
                                    'key' => 'front',
                                    'label' => 'Front',
                                    'field' => 'image_front',
                                    'icon' => 'bi-camera',
                                    'src' => $user->profile->image_front_path ?? null,
                                ],
                                [
                                    'key' => 'side',
                                    'label' => 'Side',
                                    'field' => 'image_side',
                                    'icon' => 'bi-camera',
                                    'src' => $user->profile->image_side_path ?? null,
                                ],
                                [
                                    'key' => 'plate',
                                    'label' => 'Plate',
                                    'field' => 'image_plate',
                                    'icon' => 'bi-123',
                                    'src' => $user->profile->image_plate_path ?? null,
                                ],
                            ];
                        @endphp

                        @foreach ($photoSlots as $slot)
                            <div class="col-4">
                                <div class="photo-slot {{ $slot['src'] ? 'has-image' : '' }} @error($slot['field']) border-danger @enderror"
                                    id="photoSlot-{{ $slot['key'] }}">
                                    <span class="photo-slot-label">{{ $slot['label'] }}</span>
                                    @if ($slot['src'])
                                        <img src="{{ asset('storage/' . ltrim($slot['src'], '/')) }}"
                                            alt="{{ $slot['label'] }} view" id="photoPreview-{{ $slot['key'] }}">
                                    @else
                                        <div class="photo-slot-placeholder" id="photoPreview-{{ $slot['key'] }}">
                                            <i class="bi {{ $slot['icon'] }}"></i>
                                            No photo
                                        </div>
                                    @endif
                                    <label for="photoInput-{{ $slot['key'] }}" class="photo-edit-btn"
                                        title="Change {{ strtolower($slot['label']) }} photo">
                                        <i class="bi bi-pencil-fill"></i>
                                        <span class="visually-hidden">Change {{ $slot['label'] }} photo</span>
                                    </label>
                                    <input type="file" name="{{ $slot['field'] }}"
                                        id="photoInput-{{ $slot['key'] }}" accept="image/png, image/jpeg, image/jpg"
                                        class="visually-hidden-input" data-slot="{{ $slot['key'] }}">
                                </div>
                                @error($slot['field'])
                                    <div class="text-danger small mt-1 text-center" style="font-size: 0.75rem;">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-4" style="border-color: var(--line);">

                    <!-- Main Form Actions -->
                    <div class="d-flex flex-column gap-3">
                        <button type="submit"
                            class="btn btn-main py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center text-uppercase"
                            id="saveChangesBtn">
                            <i class="bi bi-floppy-fill fs-5 me-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Driver Separated Form -->
            <div class="px-3 px-md-4 pb-4">
                {{-- <form action="{{ route('admin.drivers.destroy', $user->id) }}" method="POST" onsubmit="return confirm('CRITICAL WARNING: Are you sure you want to permanently delete this driver and all their records? This cannot be undone.')"> --}}
                <form action="#" method="POST"
                    onsubmit="return confirm('CRITICAL WARNING: Are you sure you want to permanently delete this driver and all their records? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="btn btn-delete-driver w-100 py-3 fw-bold rounded-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-person-x-fill fs-5 me-2"></i> Delete Driver Account
                    </button>
                </form>
            </div>
        </div>

        <!-- Violations & Warnings Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="text-danger-emphasis" style="color: var(--alert) !important;"><i
                        class="bi bi-exclamation-triangle-fill me-2"></i>Violation Logs</span>
                <span class="section-tag">{{ $violationCount }} Incident{{ $violationCount === 1 ? '' : 's' }}</span>
            </div>

            @if ($violationCount > 0)
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-4">Date &amp; Time</th>
                                <th scope="col">Route Path</th>
                                <th scope="col">Violation Type</th>
                                <th scope="col" class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($violations as $violation)
                                <tr>
                                    <td class="ps-4">
                                        <span
                                            class="violation-date">{{ optional($violation->created_at)->format('M d, Y') }}</span>
                                        <span
                                            class="violation-time">{{ optional($violation->created_at)->format('h:i A') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-route">
                                            <i
                                                class="bi bi-{{ ($violation->route_from ?? '') === 'Naga' ? 'arrow-right-circle' : 'arrow-left-circle' }} me-1"></i>
                                            {{ $violation->route_from ?? '—' }} to {{ $violation->route_to ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-violation">
                                            <i class="bi bi-flag-fill me-1"></i>
                                            {{ $violation->type ?? 'Unspecified' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        {{-- <form action="{{ route('admin.violations.destroy', $violation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this violation record?')"> --}}
                                        <form action="#" method="POST" class="d-inline"
                                            onsubmit="return confirm('Remove this violation record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2"
                                                title="Delete Violation">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 text-center" style="color: var(--text-muted); font-size: 0.88rem;">
                    <i class="bi bi-check-circle display-6 d-block mb-2" style="opacity: 0.35;"></i>
                    No violations on record for this driver.
                </div>
            @endif

            <div class="p-3 border-top text-end"
                style="border-color: var(--line) !important; background: var(--stone);">
                <button class="btn btn-sm btn-outline-secondary fw-bold shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#addWarningModal">
                    <i class="bi bi-journal-plus me-1"></i> Add Manual Warning
                </button>
            </div>
        </div>

    </div>

    <!-- Add Manual Warning Modal -->
    <div class="modal fade" id="addWarningModal" tabindex="-1" aria-labelledby="addWarningModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px; border: 1px solid var(--line);">
                {{-- <form action="{{ route('admin.violations.store', $user->id) }}" method="POST"> --}}
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-header" style="border-color: var(--line);">
                        <h5 class="modal-title" id="addWarningModalLabel" style="font-family: var(--font-display);">
                            Add Manual Warning</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="mb-3">
                            <label class="field-label">Violation Type</label>
                            <select name="type" class="form-select" required>
                                <option value="" selected disabled>Select a type…</option>
                                <option>Wi-Fi Bypass</option>
                                <option>Reckless Driving</option>
                                <option>Overloading</option>
                                <option>Route Deviation</option>
                                <option>Uniform Violation</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Route</label>
                            <select name="route_from" class="form-select">
                                <option value="Naga">Naga to Uling</option>
                                <option value="Uling">Uling to Naga</option>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="field-label">Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any additional context…"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-color: var(--line);">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-main fw-bold">Log Warning</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.notifications')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            'use strict';

            // ---------------------------------------------------------------
            // Live preview when a new avatar or vehicle photo is chosen.
            // Nothing uploads until the main "Save Changes" submit — this
            // just stages the file and shows what it'll look like.
            // ---------------------------------------------------------------
            function previewFile(input, onLoad) {
                const file = input.files && input.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (e) => onLoad(e.target.result);
                reader.readAsDataURL(file);
            }

            const avatarInput = document.getElementById('avatarInput');
            if (avatarInput) {
                avatarInput.addEventListener('change', () => {
                    previewFile(avatarInput, (dataUrl) => {
                        const wrap = document.getElementById('avatarPreviewWrap');
                        wrap.innerHTML = `<img src="${dataUrl}" alt="" id="avatarPreviewImg">`;
                        document.getElementById('avatarPendingNote').classList.remove('d-none');
                    });
                });
            }

            document.querySelectorAll('.photo-slot input[type="file"]').forEach((input) => {
                input.addEventListener('change', () => {
                    const slotKey = input.dataset.slot;
                    const slotEl = document.getElementById(`photoSlot-${slotKey}`);
                    const previewEl = document.getElementById(`photoPreview-${slotKey}`);
                    previewFile(input, (dataUrl) => {
                        if (previewEl && previewEl.tagName === 'IMG') {
                            previewEl.src = dataUrl;
                        } else {
                            const img = document.createElement('img');
                            img.id = `photoPreview-${slotKey}`;
                            img.alt = '';
                            img.src = dataUrl;
                            previewEl.replaceWith(img);
                        }
                        slotEl.classList.add('has-image', 'pending-upload');
                    });
                });
            });

            // ---------------------------------------------------------------
            // Prevent double-submitting the main save form
            // ---------------------------------------------------------------
            const form = document.getElementById('driverEditForm');
            const saveBtn = document.getElementById('saveChangesBtn');
            if (form && saveBtn) {
                form.addEventListener('submit', () => {
                    saveBtn.classList.add('is-submitting');
                    saveBtn.disabled = true;
                });
            }
        })();
    </script>
</body>

</html>
