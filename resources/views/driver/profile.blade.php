<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Profile & Violations</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-light: rgb(247, 251, 252);
            --neutral-tint: rgb(214, 230, 242);
            --primary-accent: rgb(185, 215, 234);
            --main-dark: rgb(118, 159, 205);
            --text-dark: #2c3e50;
            --danger-accent: #dc3545;
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

        /* Nav & Core Layout */
        .nav-sticky-top {
            background-color: #ffffff;
            border-bottom: 2px solid var(--primary-accent);
            flex-shrink: 0;
            z-index: 1030;
        }

        .scrollable-content {
            flex-grow: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 2rem;
        }

        /* Custom Colors & Borders */
        .bg-custom-dark { background-color: var(--main-dark) !important; color: white; }
        .bg-custom-tint { background-color: var(--neutral-tint); }
        .border-custom { border-color: var(--primary-accent) !important; }
        .text-custom-dark { color: var(--main-dark) !important; }

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
            border: 4px solid var(--main-dark);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: opacity 0.2s;
        }

        .upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: var(--main-dark);
            color: white;
            border: 2px solid white;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        }

        .upload-btn:active {
            transform: scale(0.9);
        }

        /* Form Controls */
        .form-control:focus {
            border-color: var(--main-dark);
            box-shadow: 0 0 0 0.25rem rgba(118, 159, 205, 0.25);
        }

        /* Violation List Styling */
        .violation-card {
            border-left: 5px solid var(--danger-accent);
            background-color: #fff;
            transition: transform 0.2s;
        }
        
        .violation-card:hover {
            transform: translateX(2px);
        }

        .violation-icon {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-accent);
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
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            <div style="width: 80px;">
                <a href="{{ route('driver.map') }}" class="btn btn-sm btn-light border-custom d-flex align-items-center d-inline-flex">
                    <i class="bi bi-chevron-left me-1"></i> Back
                </a>
            </div>
            
            <span class="fw-bold fs-5 text-custom-dark text-center">Driver Profile</span>
            
            <div style="width: 80px;" class="d-flex justify-content-end">
                <a href="{{-- route('home') --}}#" class="btn btn-sm btn-light border-custom d-flex align-items-center justify-content-center" title="Home">
                    <i class="bi bi-house-door-fill text-custom-dark mb-0"></i>
                </a>
            </div> 
        </div>
    </nav>

    <div class="scrollable-content container py-4 max-w-md mx-auto" style="max-width: 800px;">
        
        <div class="row g-4">
            
            <div class="col-12 col-md-5">
                <div class="card shadow-sm border-custom rounded-4 mb-4">
                    <div class="card-body p-4">
                        
                        <form action="{{-- route('profile.update') --}}" method="POST" enctype="multipart/form-data">
                            {{-- @csrf @method('PATCH') --}}
                            
                            <div class="text-center mb-4">
                                <div class="profile-img-container">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80" 
                                         id="profilePreview" 
                                         class="profile-img" 
                                         alt="Driver Photo">
                                    
                                    <label for="photoUpload" class="upload-btn" title="Upload new photo">
                                        <i class="bi bi-camera-fill"></i>
                                    </label>
                                    <input type="file" id="photoUpload" name="photo" class="d-none" accept="image/*" onchange="previewImage(event)">
                                </div>
                                <h5 class="mt-3 mb-0 fw-bold">Alex Santos</h5>
                                <span class="badge bg-custom-dark mt-1 font-monospace fs-6">PLATE: ABC-1234</span>
                            </div>

                            <hr class="border-custom">

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Driver Bio / Status</label>
                                <textarea class="form-control bg-light" name="bio" rows="3" placeholder="Tell dispatch something about your daily routine...">Regular route from Naga to Uling. Always on time.</textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Contact Number</label>
                                <input type="tel" class="form-control bg-light" name="phone" value="+63 912 345 6789">
                            </div>

                            <button type="submit" class="btn bg-custom-dark w-100 fw-bold shadow-sm py-2">
                                <i class="bi bi-floppy me-1"></i> Save Profile Details
                            </button>
                        </form>

                        <hr class="border-custom my-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Account Management</label>
                            
                            <div class="d-flex flex-column gap-2">
                                <form method="POST" action="{{ route('logout') }}" class="w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary w-100 fw-bold shadow-sm py-2">
                                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                                    </button>
                                </form>

                                <form action="{{-- route('profile.destroy') --}}" method="POST" class="w-100" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                                    {{-- @csrf @method('DELETE') --}}
                                    <button type="submit" class="btn btn-outline-danger w-100 fw-bold shadow-sm py-2">
                                        <i class="bi bi-trash3 me-1"></i> Delete Account
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-7">
                
                <div class="alert alert-danger shadow-sm border-danger-subtle rounded-4 mb-4 d-flex gap-3">
                    <i class="bi bi-shield-exclamation fs-2 text-danger"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Anti-Cheating System Active</h6>
                        <p class="mb-0 small">Bypassing the Uling Wi-Fi Router dead-spot verification (Geofence Cheating) will result in automatic warnings and potential queue suspensions.</p>
                    </div>
                </div>

                <div class="card shadow-sm border-custom rounded-4 bg-custom-tint">
                    <div class="card-header bg-white border-custom d-flex justify-content-between align-items-center py-3 rounded-top-4">
                        <h6 class="mb-0 fw-bold text-custom-dark">
                            <i class="bi bi-cone-striped me-2 text-warning"></i> Warnings & Violations Log
                        </h6>
                        <span class="badge bg-danger rounded-pill">2 Incidents</span>
                    </div>
                    
                    <div class="card-body p-3">
                        <div class="d-flex flex-column gap-3">
                            
                            {{-- @forelse ($violations as $violation) --}}
                            
                            <div class="card violation-card shadow-sm p-3 rounded-3 border-top-0 border-end-0 border-bottom-0">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="violation-icon">
                                            <i class="bi bi-geo-alt-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-danger">Geofence Cheating</h6>
                                            <small class="text-muted fw-bold">Naga → Uling Route</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Penalty</span>
                                </div>
                                <div class="bg-light p-2 rounded small text-muted font-monospace mt-2 d-flex justify-content-between">
                                    <span><i class="bi bi-calendar-event me-1"></i> Oct 12, 2024</span>
                                    <span><i class="bi bi-clock me-1"></i> 08:45 AM</span>
                                </div>
                                <div class="small mt-2 text-dark">
                                    <strong>System Note:</strong> Failed to ping Uling Wi-Fi Router verification point. Arrived at terminal abnormally fast.
                                </div>
                            </div>

                            <div class="card violation-card shadow-sm p-3 rounded-3 border-top-0 border-end-0 border-bottom-0" style="border-left-color: #ffc107;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="violation-icon" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107;">
                                            <i class="bi bi-router fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-warning-emphasis">Connection Dropped</h6>
                                            <small class="text-muted fw-bold">Uling → Naga Route</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Warning</span>
                                </div>
                                <div class="bg-light p-2 rounded small text-muted font-monospace mt-2 d-flex justify-content-between">
                                    <span><i class="bi bi-calendar-event me-1"></i> Sep 28, 2024</span>
                                    <span><i class="bi bi-clock me-1"></i> 02:15 PM</span>
                                </div>
                                <div class="small mt-2 text-dark">
                                    <strong>System Note:</strong> Dispatch delayed. Driver disconnected from Uling router without clearing the queue gate.
                                </div>
                            </div>

                            {{-- @empty --}}
                            {{-- @endforelse --}}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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