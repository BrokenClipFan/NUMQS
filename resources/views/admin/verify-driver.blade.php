<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Verify Driver | Admin Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-light: rgb(247, 251, 252);
            --neutral-tint: rgb(214, 230, 242);
            --primary-accent: rgb(185, 215, 234);
            --main-dark: rgb(118, 159, 205);
            --text-dark: #2c3e50;
            --admin-warning: #fd7e14;
            --danger-accent: #dc3545;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100dvh;
        }

        /* Custom Colors & Borders */
        .bg-custom-dark { background-color: var(--main-dark) !important; color: white; }
        .bg-custom-tint { background-color: var(--neutral-tint); }
        .border-custom { border-color: var(--primary-accent) !important; }

        /* Admin Navbar */
        .nav-sticky-top {
            background-color: #ffffff;
            border-bottom: 2px solid var(--admin-warning);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Form Card Styling */
        .form-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--primary-accent);
            box-shadow: 0 4px 15px rgba(118, 159, 205, 0.1);
            overflow: hidden;
        }

        .form-header {
            background-color: var(--neutral-tint);
            border-bottom: 1px solid var(--primary-accent);
            padding: 1rem;
        }

        /* Input Overrides */
        .form-control, .form-select {
            background-color: var(--bg-light);
            border: 1px solid var(--primary-accent);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--main-dark);
            box-shadow: 0 0 0 0.25rem rgba(118, 159, 205, 0.25);
            background-color: #ffffff;
        }

        /* File Upload Customization */
        input[type=file]::file-selector-button {
            background-color: var(--neutral-tint);
            color: var(--main-dark);
            border: none;
            border-right: 1px solid var(--primary-accent);
            padding: 0.375rem 0.75rem;
            margin-right: 1rem;
            font-weight: bold;
            transition: all 0.2s;
        }
        
        input[type=file]::file-selector-button:hover {
            background-color: var(--primary-accent);
        }

        /* Primary Button */
        .btn-main {
            background-color: var(--main-dark);
            color: white;
            border: none;
            transition: all 0.2s;
        }
        .btn-main:hover {
            background-color: #638ab5;
            color: white;
            transform: translateY(-1px);
        }

        /* Delete/Reject Button */
        .btn-reject {
            background-color: transparent;
            color: var(--danger-accent);
            border: 2px solid var(--danger-accent);
            transition: all 0.2s;
        }
        .btn-reject:hover {
            background-color: var(--danger-accent);
            color: white;
            transform: translateY(-1px);
        }

        .profile-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--neutral-tint);
            color: var(--main-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            border: 2px solid var(--primary-accent);
        }
    </style>
</head>
<body>

    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2 shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0 max-w-md mx-auto" style="max-width: 800px;">
            <a class="navbar-brand fw-bold d-flex align-items-center m-0" href="#">
                <i class="bi bi-shield-lock-fill me-2" style="color: var(--admin-warning);"></i>
                <span class="fs-5 text-dark">Admin Portal</span>
            </a>
            <a href="#" class="btn btn-sm btn-outline-secondary fw-bold">
                <i class="bi bi-x-lg"></i> Exit
            </a>
        </div>
    </nav>

    <div class="container py-4 max-w-md mx-auto" style="max-width: 800px;">
        
        <!-- Next / Previous Driver Pagination -->
        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
            @if($driver->currentPage() > 1)
                <a href="?page={{ $driver->currentPage() - 1 }}"
                class="btn btn-sm btn-light border-custom fw-bold text-main-dark shadow-sm">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
            @else
                <button class="btn btn-sm btn-light border-custom fw-bold shadow-sm" disabled>
                    <i class="bi bi-chevron-left"></i> Previous
                </button>
            @endif
            <span class="small fw-bold text-muted bg-white px-3 py-1 border border-custom rounded-pill">Driver {{ $driver->currentPage() }} of {{ $driver->lastPage() }}</span>
            @if($driver->hasMorePages())
            <a href="?page={{ $driver->currentPage() + 1 }}" class="btn btn-sm btn-light border-custom fw-bold text-main-dark shadow-sm">
                Next <i class="bi bi-chevron-right"></i>
            </a>
            @endif
        </div>

        <!-- Target User Header -->
        <div class="d-flex align-items-center bg-white p-3 rounded-top-3 border-custom border-bottom-0 shadow-sm mt-2">
            @if($currentDriver->avatar)
                <img src="{{ $currentDriver->avatar }}"
                    alt="Driver Avatar"
                    class="rounded-circle me-3 flex-shrink-0 profile-placeholder"
                    width="45"
                    height="45"
                    style="object-fit: cover;">
            @else
                <div class="profile-placeholder me-3 flex-shrink-0">
                    <i class="bi bi-person-fill"></i>
                </div>
            @endif
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="fw-bold mb-0">{{ $currentDriver->name }}</h5>
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Pending</span>
                </div>
                @if($currentDriver->email)
                    <small class="text-muted">
                        <i class="bi bi-envelope me-1"></i>
                        {{ $currentDriver->email }}
                    </small>
                @endif
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="form-card rounded-top-0 mb-4 shadow-sm border-top-0">
            <div class="form-header rounded-top-0 border-top border-custom">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-clipboard2-data-fill me-2 text-custom-dark"></i>Complete Registration Profile</h6>
            </div>
            
            <div class="p-3 p-md-4">
                <form action="{{ route('driver.store', $currentDriver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h6 class="text-muted fw-bold small text-uppercase mb-3 border-bottom pb-2">1. Personal Information</h6>
                    <!-- Section 1: Driver Information -->
                    <div class="row g-3 mb-4">
                        <div class=" col-12 col-md-6">
                            <label class="form-label small fw-bold">First Name</label><span class="text-danger">*</span>
                            @error('license_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="text" name="first_name" class="form-control" placeholder="Pedro">
                        </div>
                        <div class=" col-12 col-md-6">
                            <label class="form-label small fw-bold">Last Name</label><span class="text-danger">*</span>
                            @error('last_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="text" name="last_name" class="form-control" placeholder="Pendoko">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">
                                Middle Name
                            </label>
                            @error('middle_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="text" name="middle_name" class="form-control" placeholder="Lopez">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">
                                Birth Date
                            </label>
                            @error('birthdate')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="date" name="birthdate" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Contact Number <span class="text-danger">*</span></label>
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-group">
                            <span class="input-group-text bg-white border-custom text-muted"><i class="bi bi-phone"></i></span>
                            <input type="tel" name="phone" class="form-control" placeholder="09XX-XXX-XXXX" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Home Address</label>
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <textarea name="address" class="form-control" rows="2" placeholder="House/Block No., Street, Barangay, City"></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Emergency Contact Name</label>
                            @error('emergency_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="text" name="emergency_name" class="form-control" placeholder="Full Name">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Emergency Contact No.</label>
                            @error('emergency_phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input type="tel" name="emergency_phone" class="form-control" placeholder="09XX-XXX-XXXX">
                        </div>
                    </div>

                    <!-- Section 2: Licensing & Vehicle Data -->
                    <h6 class="text-muted fw-bold small text-uppercase mb-3 mt-4 border-bottom pb-2">2. Licensing & Vehicle Specs</h6>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Driver's License Number <span class="text-danger">*</span></label>
                        @error('license_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-group">
                            <span class="input-group-text bg-white border-custom text-muted"><i class="bi bi-card-heading"></i></span>
                            <input type="text" name="license_number" class="form-control font-monospace" placeholder="e.g. N01-23-456789" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Plate Number <span class="text-danger">*</span></label>
                        @error('plate_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="input-group">
                            <span class="input-group-text bg-white border-custom text-muted"><i class="bi bi-123"></i></span>
                            <input type="text" name="plate_number" class="form-control font-monospace fw-bold text-uppercase" placeholder="ABC-1234" required>
                        </div>
                    </div>

                    <!-- Add Images Section -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Vehicle Photos <span class="text-danger">*</span></label>
                        @error('vehicleImages')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <input class="form-control" type="file" id="vehicleImages" name="vehicle_images[]" multiple accept="image/png, image/jpeg, image/jpg" required>
                        <div class="form-text small mt-1">
                            <i class="bi bi-info-circle me-1"></i>Upload images showing the front, side, and plate number.
                        </div>
                    </div>

                    <hr class="my-4 border-custom">
                    <!-- Approve & Save Button -->
                    <button type="submit" class="btn btn-main w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center text-uppercase">
                        <i class="bi bi-check-circle-fill fs-5 me-2"></i> Approve Account
                    </button>
                </form>
                <!-- Actions Area -->
                <div class="d-flex flex-column flex-md-row mt-3">
                    <!-- Reject / Delete Button -->
                    <form action="{{-- route('admin.verify.reject', $user->id) --}}" method="POST" class="w-100 m-0">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-reject w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center" onclick="confirm('Are you sure you want to permanently delete this application?')">
                            <i class="bi bi-trash3-fill me-2"></i> Reject & Delete
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
    @include('partials.notifications')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>