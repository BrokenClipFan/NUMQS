<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Fleet Management | Admin Dashboard</title>
    
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

        /* Utilities & Theme Colors */
        .bg-custom-dark { background-color: var(--main-dark) !important; color: white; }
        .bg-custom-tint { background-color: var(--neutral-tint); }
        .border-custom { border-color: var(--primary-accent) !important; }
        .text-custom-dark { color: var(--main-dark) !important; }

        /* Admin Header Sticky bar */
        .nav-sticky-top {
            background-color: #ffffff;
            border-bottom: 2px solid var(--admin-warning);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Dashboard Metric Cards */
        .metric-card {
            background: #ffffff;
            border: 1px solid var(--primary-accent);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(118, 159, 205, 0.08);
        }

        /* Driver Section Cards */
        .fleet-card {
            background: #ffffff;
            border: 1px solid var(--primary-accent);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(118, 159, 205, 0.08);
        }

        .fleet-card-header {
            padding: 1rem;
            font-weight: 700;
            border-bottom: 1px solid var(--primary-accent);
        }

        /* Unified Custom Account Item Rows */
        .account-item {
            border-bottom: 1px solid rgba(185, 215, 234, 0.4);
            transition: background-color 0.15s ease;
        }
        .account-item:last-child {
            border-bottom: none;
        }
        .account-item:hover {
            background-color: rgba(214, 230, 242, 0.2);
        }

        /* Round mini-avatars */
        .driver-avatar-mini {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: var(--neutral-tint);
            color: var(--main-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 1px solid var(--primary-accent);
        }

        /* Buttons Styling */
        .btn-verify {
            background-color: var(--main-dark);
            color: white;
            font-weight: bold;
            font-size: 0.85rem;
        }
        .btn-verify:hover {
            background-color: #638ab5;
            color: white;
        }

        .btn-delete-account {
            background-color: transparent;
            color: var(--danger-accent);
            border: 1px solid var(--danger-accent);
            font-size: 0.85rem;
        }
        .btn-delete-account:hover {
            background-color: var(--danger-accent);
            color: white;
        }
    </style>
</head>
<body>

    <!-- Admin Navigation Header -->
    <nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2 shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0 max-w-md mx-auto" style="max-width: 900px;">
            <a class="navbar-brand fw-bold d-flex align-items-center m-0" href="#">
                <i class="bi bi-shield-lock-fill me-2" style="color: var(--admin-warning);"></i>
                <span class="fs-5 text-dark">Admin Fleet Directory</span>
            </a>
            <span class="badge bg-dark rounded-pill py-2 px-3"><i class="bi bi-person-workspace me-1"></i> System Active</span>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 900px;">
        
        <!-- Quick Fleet Overview Section -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="metric-card p-3 d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-person-fill-exclamation fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">3</h4>
                        <small class="text-muted small">Pending Approval</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="metric-card p-3 d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-person-check-fill fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">28</h4>
                        <small class="text-muted small">Verified Fleet</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="metric-card p-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-info-subtle text-info p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">31</h4>
                            <small class="text-muted small">Total Registered</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 1: PENDING UNVERIFIED ACCOUNTS -->
        <div class="fleet-card mb-4">
            <div class="fleet-card-header bg-custom-tint border-custom text-dark d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2 text-warning-emphasis"></i>Pending Applications</span>
                <span class="badge bg-warning text-dark rounded-pill fw-bold small">Needs Verification</span>
            </div>
            
            <div class="list-group list-group-flush m-0">
                
                <!-- Sample Pending Item 1 -->
                <div class="p-3 account-item d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <div class="driver-avatar-mini me-3">JD</div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Juan Dela Cruz</h6>
                            <small class="text-muted d-block" style="font-size: 0.8rem;"><i class="bi bi-envelope me-1"></i>juan.delacruz@example.com</small>
                            <span class="badge bg-light text-muted border small mt-1"><i class="bi bi-facebook me-1 text-primary"></i>Linked via FB</span>
                        </div>
                    </div>
                    <div>
                        <!-- Links straight to the verification input card built in Page 2 -->
                        <a href="{{-- route('admin.verify.edit', $user->id) --}}#" class="btn btn-verify px-3 py-2 rounded-3 btn-sm shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> Verify & Complete Profile
                        </a>
                    </div>
                </div>

                <!-- Sample Pending Item 2 -->
                <div class="p-3 account-item d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <div class="driver-avatar-mini me-3">AM</div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Arnel Mangubat</h6>
                            <small class="text-muted d-block" style="font-size: 0.8rem;"><i class="bi bi-envelope me-1"></i>arnel.mangu@example.com</small>
                            <span class="badge bg-light text-muted border small mt-1"><i class="bi bi-envelope-fill me-1"></i>Standard Email</span>
                        </div>
                    </div>
                    <div>
                        <a href="#" class="btn btn-verify px-3 py-2 rounded-3 btn-sm shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> Verify & Complete Profile
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION 2: VERIFIED ACCOUNTS -->
        <div class="fleet-card">
            <div class="fleet-card-header bg-custom-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-shield-check me-2"></i>Verified Fleet Database</span>
                <span class="badge bg-white text-custom-dark rounded-pill fw-bold small">Active Trackers</span>
            </div>

            <div class="list-group list-group-flush m-0">
                
                <!-- Active Driver Row 1 -->
                <div class="p-3 account-item d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center flex-grow-1">
                        <div class="driver-avatar-mini me-3 bg-light text-success border-success">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="row w-100 g-0 align-items-center">
                            <div class="col-12 col-md-5">
                                <h6 class="fw-bold mb-0 text-dark">Pedro Sanchez</h6>
                                <small class="text-muted font-monospace" style="font-size: 0.75rem;">ID: #TR-9021</small>
                            </div>
                            <div class="col-6 col-md-4 mt-1 mt-md-0">
                                <span class="badge bg-light text-dark border border-custom font-monospace fw-bold px-2 py-1">
                                    <i class="bi bi-truck-front-fill me-1 text-custom-dark"></i>ABC-1234
                                </span>
                            </div>
                            <div class="col-6 col-md-3 mt-1 mt-md-0 text-md-end">
                                <span class="text-success small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i> In Queue</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- Deletion mechanism with secure form handling trigger -->
                        <form action="{{-- route('admin.drivers.destroy', $user->id) --}}" method="POST" class="m-0" onsubmit="return confirm('CRITICAL WARNING: Are you completely sure you want to permanently delete this driver account? This action removes all historical log coordinates, plate links, and queue metrics.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete-account px-3 py-2 rounded-3 btn-sm shadow-sm">
                                <i class="bi bi-trash3 me-1"></i> Delete Account
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Active Driver Row 2 -->
                <div class="p-3 account-item d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center flex-grow-1">
                        <div class="driver-avatar-mini me-3 bg-light text-success border-success">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="row w-100 g-0 align-items-center">
                            <div class="col-12 col-md-5">
                                <h6 class="fw-bold mb-0 text-dark">Danilo Remorosa</h6>
                                <small class="text-muted font-monospace" style="font-size: 0.75rem;">ID: #TR-4412</small>
                            </div>
                            <div class="col-6 col-md-4 mt-1 mt-md-0">
                                <span class="badge bg-light text-dark border border-custom font-monospace fw-bold px-2 py-1">
                                    <i class="bi bi-truck-front-fill me-1 text-custom-dark"></i>GHI-7890
                                </span>
                            </div>
                            <div class="col-6 col-md-3 mt-1 mt-md-0 text-md-end">
                                <span class="text-secondary small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i> Offline</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <form action="#" method="POST" class="m-0" onsubmit="return confirm('Confirm complete account deletion?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete-account px-3 py-2 rounded-3 btn-sm shadow-sm">
                                <i class="bi bi-trash3 me-1"></i> Delete Account
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>