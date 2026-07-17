<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Verify Driver | Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        * { box-sizing: border-box; }

        body {
            background-color: var(--stone);
            color: var(--text-primary);
            font-family: var(--font-body);
            min-height: 100dvh;
        }

        ::selection { background: var(--amber); color: var(--amber-ink); }

        a:focus-visible, button:focus-visible, input:focus-visible, textarea:focus-visible {
            outline: 2px solid var(--amber);
            outline-offset: 2px;
            border-radius: 4px;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
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
            box-shadow: 0 2px 14px rgba(0,0,0,0.25);
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #F4F5F1;
            font-family: var(--font-display);
            font-weight: 700;
        }
        .brand-mark .bi { color: var(--amber); font-size: 1.1rem; }

        .btn-exit {
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 0.8rem;
            background: transparent;
            color: #D8DBD2;
            border: 1px solid rgba(255,255,255,0.16);
        }
        .btn-exit:hover { border-color: var(--amber); color: var(--amber); }

        /* ---------------------------------------------------------------
           Target user header strip
        ----------------------------------------------------------------*/
        .target-user-strip {
            background: var(--card);
            border: 1px solid var(--line);
            border-bottom: none;
            border-radius: 14px 14px 0 0;
            padding: 1rem;
        }

        .avatar-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.95rem;
            background: rgba(242,166,60,0.15);
            color: #A5691B;
            border: 1px solid rgba(242,166,60,0.35);
            overflow: hidden;
        }
        .avatar-circle img { width: 100%; height: 100%; object-fit: cover; }

        .status-pill {
            font-family: var(--font-mono);
            font-size: 0.66rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            background: rgba(242,166,60,0.15);
            color: #A5691B;
            border: 1px solid rgba(242,166,60,0.4);
            white-space: nowrap;
        }

        /* ---------------------------------------------------------------
           Form card
        ----------------------------------------------------------------*/
        .form-card {
            background: var(--card);
            border-radius: 0 0 14px 14px;
            border: 1px solid var(--line);
            border-top: none;
            box-shadow: 0 4px 16px rgba(23,27,33,0.06);
            overflow: hidden;
        }

        .form-header {
            background-color: var(--stone);
            border-bottom: 1px solid var(--line);
            padding: 1rem;
        }
        .form-header h6 { font-family: var(--font-body); font-weight: 700; margin: 0; }
        .form-header .bi { color: var(--amber-ink); }

        .section-heading {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--line);
            padding-bottom: 0.6rem;
            margin-bottom: 1rem;
        }

        .field-label {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: var(--text-muted);
            display: block;
            margin-bottom: 0.3rem;
        }
        .field-label .req { color: var(--alert); margin-left: 0.15rem; }

        .form-control, .form-select {
            background-color: var(--stone);
            border: 1.5px solid var(--line);
            font-size: 0.88rem;
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--amber);
            box-shadow: 0 0 0 3px rgba(242,166,60,0.18);
            background-color: var(--card);
        }

        .input-group-text {
            background-color: var(--stone) !important;
            border: 1.5px solid var(--line) !important;
            color: var(--text-muted) !important;
        }

        input[type=file]::file-selector-button {
            background-color: var(--ink);
            color: #F4F5F1;
            border: none;
            border-radius: 6px;
            padding: 0.4rem 0.85rem;
            margin-right: 1rem;
            font-weight: 600;
            font-size: 0.82rem;
            transition: background-color 0.15s ease;
        }
        input[type=file]::file-selector-button:hover { background-color: var(--ink-soft); }

        .field-error {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--alert);
            display: block;
            margin-top: 0.25rem;
        }

        /* ---------------------------------------------------------------
           Vehicle photo previews
        ----------------------------------------------------------------*/
        .photo-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 0.5rem;
            margin-top: 0.75rem;
        }
        .photo-preview-grid .thumb {
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--line);
        }
        .photo-preview-grid .thumb img { width: 100%; height: 100%; object-fit: cover; }

        .file-hint {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--text-muted);
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
        .btn-main:hover { filter: brightness(1.04); color: var(--amber-ink); }
        .btn-main:active:not(:disabled) { transform: scale(0.99); }
        .btn-main.is-submitting { opacity: 0.65; pointer-events: none; }

        .btn-reject {
            background-color: transparent;
            color: var(--alert);
            border: 1.5px solid var(--alert);
            font-weight: 700;
            transition: background-color 0.15s ease, color 0.15s ease;
        }
        .btn-reject:hover { background-color: var(--alert); color: white; }

        .modal-content { border-radius: 14px; border: 1px solid var(--line); }
        .modal-header, .modal-footer { border-color: var(--line) !important; }
        .modal-title { font-family: var(--font-display); }
    </style>
</head>
<body>

    @php $backRoute = \Illuminate\Support\Facades\Route::has('fleet.management') ? route('fleet.management') : '#'; @endphp

    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0" style="max-width: 800px; margin: 0 auto;">
            <a class="navbar-brand brand-mark m-0" href="{{ $backRoute }}">
                <i class="bi bi-shield-lock-fill"></i>
                <span class="fs-6">Admin Portal</span>
            </a>
            <a href="{{ $backRoute }}" class="btn btn-sm btn-exit fw-bold rounded-2">
                <i class="bi bi-x-lg"></i> Exit
            </a>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 800px;">

        <!-- Target User Header -->
        <div class="d-flex align-items-center target-user-strip mt-2">
            @if($user->avatar)
                <div class="avatar-circle me-3">
                    <img src="{{ $user->avatar }}" alt="">
                </div>
            @else
                <div class="avatar-circle me-3">
                    <i class="bi bi-person-fill"></i>
                </div>
            @endif
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                    <span class="status-pill"><i class="bi bi-clock-history me-1"></i>Pending</span>
                </div>
                @if($user->email)
                    <small class="text-muted">
                        <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                    </small>
                @endif
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="form-card mb-4">
            <div class="form-header">
                <h6><i class="bi bi-clipboard2-data-fill me-2"></i>Complete Registration Profile</h6>
            </div>

            <div class="p-3 p-md-4">
                <form action="{{ route('driver.store', $user->id) }}" method="POST" enctype="multipart/form-data" id="verifyForm">
                    @csrf

                    <h6 class="section-heading">1. Personal Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="field-label">First Name<span class="req">*</span></label>
                            @error('first_name')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" placeholder="Pedro" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="field-label">Last Name<span class="req">*</span></label>
                            @error('last_name')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Pendoko" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="field-label">Middle Name</label>
                            @error('middle_name')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-control" placeholder="Lopez">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="field-label">Birth Date</label>
                            @error('birthdate')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                            <input type="date" name="birthdate" value="{{ old('birthdate') }}" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="field-label">Contact Number<span class="req">*</span></label>
                        @error('phone')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-phone"></i></span>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="09XX-XXX-XXXX" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="field-label">Home Address</label>
                        @error('address')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                        <textarea name="address" class="form-control" rows="2" placeholder="House/Block No., Street, Barangay, City">{{ old('address') }}</textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="field-label">Emergency Contact Name</label>
                            @error('emergency_name')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                            <input type="text" name="emergency_name" value="{{ old('emergency_name') }}" class="form-control" placeholder="Full Name">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="field-label">Emergency Contact No.</label>
                            @error('emergency_phone')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                            <input type="tel" name="emergency_phone" value="{{ old('emergency_phone') }}" class="form-control" placeholder="09XX-XXX-XXXX">
                        </div>
                    </div>

                    <h6 class="section-heading mt-4">2. Licensing & Vehicle Specs</h6>

                    <div class="mb-3">
                        <label class="field-label">Driver's License Number<span class="req">*</span></label>
                        @error('license_number')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                            <input type="text" name="license_number" value="{{ old('license_number') }}" class="form-control font-monospace" placeholder="e.g. N01-23-456789" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="field-label">Plate Number<span class="req">*</span></label>
                        @error('plate_number')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-123"></i></span>
                            <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="form-control font-monospace fw-bold text-uppercase" placeholder="ABC-1234" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="field-label">Vehicle Photos<span class="req">*</span></label>
                        @error('image')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                        <input class="form-control" type="file" id="vehicleImages" name="image[]"
                               multiple accept="image/png, image/jpeg, image/jpg" required>
                        <div class="file-hint mt-1">
                            <i class="bi bi-info-circle me-1"></i>Upload images showing the front, side, and plate number.
                        </div>
                        <div class="photo-preview-grid" id="photoPreviewGrid"></div>
                    </div>

                    <hr class="my-4" style="border-color: var(--line);">

                    <button type="submit" class="btn btn-main w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center text-uppercase" id="approveBtn">
                        <i class="bi bi-check-circle-fill fs-5 me-2"></i> Approve Account
                    </button>
                </form>

                <!-- Reject / Delete trigger -->
                <div class="d-flex flex-column flex-md-row mt-3">
                    <button type="button" class="btn btn-reject w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center"
                            data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-trash3-fill me-2"></i> Reject & Delete
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Reject Confirmation Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel"><i class="bi bi-exclamation-triangle-fill me-2" style="color: var(--alert);"></i>Reject Application?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        You're about to permanently delete <strong>{{ $user->name }}</strong>'s application and all
                        submitted information. This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.verify.reject', $user->id) }}" method="POST" class="m-0" id="rejectForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-reject fw-bold px-3" id="rejectConfirmBtn">
                            <i class="bi bi-trash3-fill me-1"></i> Yes, Reject & Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('partials.notifications')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            'use strict';

            // ---------------------------------------------------------------
            // Live thumbnail previews for selected vehicle photos
            // ---------------------------------------------------------------
            const fileInput = document.getElementById('vehicleImages');
            const previewGrid = document.getElementById('photoPreviewGrid');

            if (fileInput && previewGrid) {
                fileInput.addEventListener('change', () => {
                    previewGrid.replaceChildren();
                    Array.from(fileInput.files || []).forEach((file) => {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const thumb = document.createElement('div');
                            thumb.className = 'thumb';
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = file.name;
                            thumb.appendChild(img);
                            previewGrid.appendChild(thumb);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }

            // ---------------------------------------------------------------
            // Prevent double submits on both the main form and the
            // reject-confirmation form inside the modal
            // ---------------------------------------------------------------
            const verifyForm = document.getElementById('verifyForm');
            const approveBtn = document.getElementById('approveBtn');
            if (verifyForm && approveBtn) {
                verifyForm.addEventListener('submit', () => {
                    approveBtn.classList.add('is-submitting');
                    approveBtn.disabled = true;
                });
            }

            const rejectForm = document.getElementById('rejectForm');
            const rejectConfirmBtn = document.getElementById('rejectConfirmBtn');
            if (rejectForm && rejectConfirmBtn) {
                rejectForm.addEventListener('submit', () => {
                    rejectConfirmBtn.disabled = true;
                    rejectConfirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Deleting…';
                });
            }
        })();
    </script>
</body>
</html>