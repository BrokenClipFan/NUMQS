<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Driver Profile | Admin Dashboard</title>
    
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
            --success-accent: #198754;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100dvh;
        }

        /* Utilities */
        .bg-custom-dark { background-color: var(--main-dark) !important; color: white; }
        .bg-custom-tint { background-color: var(--neutral-tint); }
        .border-custom { border-color: var(--primary-accent) !important; }
        .text-custom-dark { color: var(--main-dark) !important; }

        /* Admin Navbar */
        .nav-sticky-top {
            background-color: #ffffff;
            border-bottom: 2px solid var(--admin-warning);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Base Card Styling */
        .admin-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--primary-accent);
            box-shadow: 0 4px 15px rgba(118, 159, 205, 0.08);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .admin-card-header {
            background-color: var(--neutral-tint);
            border-bottom: 1px solid var(--primary-accent);
            padding: 1rem;
            font-weight: 700;
        }

        /* Profile Hero Component */
        .profile-hero {
            background: linear-gradient(to bottom, var(--neutral-tint) 0%, #ffffff 100%);
            border-bottom: 1px solid var(--primary-accent);
            text-align: center;
            padding: 2rem 1rem 1.5rem 1rem;
        }

        .driver-avatar-lg {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            background-color: var(--main-dark);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        /* Input Overrides */
        .form-control {
            background-color: var(--bg-light);
            border: 1px solid var(--primary-accent);
            font-size: 0.9rem;
        }
        .form-control:focus {
            border-color: var(--main-dark);
            box-shadow: 0 0 0 0.25rem rgba(118, 159, 205, 0.25);
            background-color: #ffffff;
        }

        /* Image Gallery */
        .vehicle-img-wrapper {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--primary-accent);
            aspect-ratio: 4/3;
            background-color: var(--neutral-tint);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .vehicle-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Table Styling */
        .table-custom { margin-bottom: 0; }
        .table-custom thead th {
            background-color: var(--bg-light);
            color: var(--text-dark);
            border-bottom: 2px solid var(--primary-accent);
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .table-custom tbody td {
            border-bottom: 1px solid var(--primary-accent);
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .badge-violation {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-accent);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        /* Action Buttons */
        .btn-main {
            background-color: var(--main-dark);
            color: white;
            transition: all 0.2s;
        }
        .btn-main:hover {
            background-color: #638ab5;
            color: white;
        }
        .btn-delete-driver {
            color: var(--danger-accent);
            border: 2px solid var(--danger-accent);
            background-color: transparent;
            transition: all 0.2s;
        }
        .btn-delete-driver:hover {
            background-color: var(--danger-accent);
            color: white;
        }
    </style>
</head>
<body>

    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2 shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0 max-w-md mx-auto" style="max-width: 900px;">
            <a class="navbar-brand fw-bold d-flex align-items-center m-0" href="{{-- route('admin.fleet') --}}#">
                <i class="bi bi-arrow-left-short fs-3 text-dark me-1"></i>
                <span class="fs-5 text-dark">Edit Driver</span>
            </a>
            <span class="badge bg-success rounded-pill py-2 px-3"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Active</span>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 900px;">
        
        <!-- Editable Profile Card -->
        <div class="admin-card">
            <div class="profile-hero">
                <div class="driver-avatar-lg">
                    <i class="bi bi-person"></i>
                </div>
                <h4 class="fw-bold mb-0">Pedro Sanchez</h4>
                <p class="text-muted small mb-0">Driver ID: #TR-9021</p>
            </div>

            <!-- Edit Form -->
            <form action="{{-- route('admin.drivers.update', $driver->id) --}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="p-3 p-md-4">
                    
                    <div class="row g-3 mb-4">
                        <!-- Personal Info -->
                        <div class="col-12 col-md-6">
                            <h6 class="text-muted small fw-bold text-uppercase border-bottom pb-2 mb-3"><i class="bi bi-person-vcard me-2"></i>Personal Info</h6>
                            
                            <div class="mb-2">
                                <label class="form-label small fw-bold mb-1">Full Name</label>
                                <input type="text" name="name" class="form-control" value="Pedro Sanchez" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold mb-1">Phone Number</label>
                                <input type="tel" name="phone_number" class="form-control" value="0912-345-6789" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold mb-1">Email Address</label>
                                <input type="email" name="email" class="form-control" value="pedro.sanchez@example.com">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold mb-1">Home Address</label>
                                <textarea name="address" class="form-control" rows="2">Block 4, Lot 12, Naga City, Cebu</textarea>
                            </div>
                        </div>

                        <!-- License & Emergency -->
                        <div class="col-12 col-md-6">
                            <h6 class="text-muted small fw-bold text-uppercase border-bottom pb-2 mb-3"><i class="bi bi-shield-check me-2"></i>License & Emergency</h6>
                            
                            <div class="mb-2">
                                <label class="form-label small fw-bold mb-1">Driver's License No.</label>
                                <input type="text" name="license_number" class="form-control font-monospace" value="N01-23-456789" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold mb-1">Plate Number</label>
                                <input type="text" name="plate_number" class="form-control font-monospace text-uppercase fw-bold" value="ABC-1234" required>
                            </div>
                            <div class="mb-2 mt-3">
                                <label class="form-label small fw-bold mb-1">Emergency Contact Name</label>
                                <input type="text" name="emergency_name" class="form-control" value="Maria Sanchez">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold mb-1">Emergency Contact No.</label>
                                <input type="tel" name="emergency_phone" class="form-control" value="0998-765-4321">
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Images Section -->
                    <h6 class="text-muted small fw-bold text-uppercase border-bottom pb-2 mb-3"><i class="bi bi-images me-2"></i>Vehicle Photos</h6>
                    <div class="row g-2 mb-3">
                        <!-- Image 1 -->
                        <div class="col-4 col-md-3">
                            <div class="vehicle-img-wrapper">
                                <!-- <img src="path/to/front.jpg" alt="Front View"> -->
                                <span class="text-muted small"><i class="bi bi-camera me-1"></i>Front</span>
                            </div>
                        </div>
                        <!-- Image 2 -->
                        <div class="col-4 col-md-3">
                            <div class="vehicle-img-wrapper">
                                <!-- <img src="path/to/side.jpg" alt="Side View"> -->
                                <span class="text-muted small"><i class="bi bi-camera me-1"></i>Side</span>
                            </div>
                        </div>
                        <!-- Image 3 -->
                        <div class="col-4 col-md-3">
                            <div class="vehicle-img-wrapper">
                                <!-- <img src="path/to/plate.jpg" alt="Plate View"> -->
                                <span class="text-muted small"><i class="bi bi-123 me-1"></i>Plate</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Upload New Photos (Replaces current)</label>
                        <input class="form-control" type="file" name="vehicle_images[]" multiple accept="image/png, image/jpeg, image/jpg">
                    </div>

                    <hr class="my-4 border-custom">

                    <!-- Main Form Actions -->
                    <div class="d-flex flex-column gap-3">
                        <button type="submit" class="btn btn-main py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center text-uppercase">
                            <i class="bi bi-floppy-fill fs-5 me-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Delete Driver Separated Form -->
            <div class="px-3 px-md-4 pb-4">
                <form action="{{-- route('admin.drivers.destroy', $driver->id) --}}" method="POST" onsubmit="return confirm('CRITICAL WARNING: Are you sure you want to permanently delete this driver and all their records? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete-driver w-100 py-3 fw-bold rounded-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-person-x-fill fs-5 me-2"></i> Delete Driver Account
                    </button>
                </form>
            </div>
        </div>

        <!-- Violations & Warnings Card -->
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center bg-danger-subtle border-danger-subtle">
                <span class="text-danger-emphasis"><i class="bi bi-exclamation-triangle-fill me-2"></i>Violation Logs</span>
                <span class="badge bg-danger rounded-pill">2 Incidents</span>
            </div>

            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col" class="ps-4">Date & Time</th>
                            <th scope="col">Route Path</th>
                            <th scope="col">Violation Type</th>
                            <th scope="col" class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Violation Row 1 -->
                        <tr>
                            <td class="ps-4">
                                <span class="d-block fw-bold text-dark">Oct 12, 2023</span>
                                <span class="small text-muted">02:45 PM</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><i class="bi bi-arrow-right-circle me-1"></i>Naga to Uling</span>
                            </td>
                            <td>
                                <span class="badge badge-violation fw-bold">
                                    <i class="bi bi-wifi-off me-1"></i> Wi-Fi Bypass
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <!-- Delete Violation Action -->
                                <form action="{{-- route('admin.violations.destroy', $violation->id) --}}" method="POST" class="d-inline" onsubmit="return confirm('Remove this violation record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete Violation">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Violation Row 2 -->
                        <tr>
                            <td class="ps-4">
                                <span class="d-block fw-bold text-dark">Sep 28, 2023</span>
                                <span class="small text-muted">09:15 AM</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><i class="bi bi-arrow-left-circle me-1"></i>Uling to Naga</span>
                            </td>
                            <td>
                                <span class="badge badge-violation fw-bold">
                                    <i class="bi bi-wifi-off me-1"></i> Wi-Fi Bypass
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <!-- Delete Violation Action -->
                                <form action="#" method="POST" class="d-inline" onsubmit="return confirm('Remove this violation record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete Violation">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="p-3 border-top border-custom bg-light text-end">
                <button class="btn btn-sm btn-outline-secondary fw-bold shadow-sm">
                    <i class="bi bi-journal-plus me-1"></i> Add Manual Warning
                </button>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>