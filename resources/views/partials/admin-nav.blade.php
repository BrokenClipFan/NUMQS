<!-- Admin Navigation Header -->
<nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2">
    <div class="container-fluid d-flex justify-content-between align-items-center p-0" style="width: 100%;">
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <a class="back-link" href="{{ route('driver.map') }}" style="display: flex; align-items: center; gap: 0.25rem; color: #F4F5F1; font-family: var(--font-display); font-weight: 700; text-decoration: none;">
                <i class="bi bi-arrow-left-short" style="font-size: 1.5rem; color: var(--amber);"></i>
                <span class="fs-6 d-none d-sm-inline">Map</span>
            </a>
            <a href="{{ route('fleet.management') }}" class="btn {{ request()->routeIs('fleet.management') ? 'btn-light text-primary' : 'btn-primary text-white' }} btn-sm d-inline-flex align-items-center gap-2 rounded-3 shadow-sm px-3 fw-bold" style="{{ request()->routeIs('fleet.management') ? '' : 'background-color: var(--primary); border: none;' }}">
                <i class="bi bi-speedometer2"></i> <span class="d-none d-md-inline">Dashboard</span>
            </a>
            <a href="{{ route('admin.queues') }}" class="btn {{ request()->routeIs('admin.queues') ? 'btn-light text-primary' : 'btn-primary text-white' }} btn-sm d-inline-flex align-items-center gap-2 rounded-3 shadow-sm px-3 fw-bold" style="{{ request()->routeIs('admin.queues') ? '' : 'background-color: var(--primary); border: none;' }}">
                <i class="bi bi-list-ol"></i> <span class="d-none d-md-inline">Live Queues</span>
            </a>
            <a href="{{ route('admin.dispatchers') }}" class="btn {{ request()->routeIs('admin.dispatchers') ? 'btn-light text-primary' : 'btn-primary text-white' }} btn-sm d-inline-flex align-items-center gap-2 rounded-3 shadow-sm px-3 fw-bold" style="{{ request()->routeIs('admin.dispatchers') ? '' : 'background-color: var(--primary); border: none;' }}">
                <i class="bi bi-person-badge-fill"></i> <span class="d-none d-md-inline">Dispatchers</span>
            </a>
            <a href="{{ route('admin.violations.resolved') }}" class="btn {{ request()->routeIs('admin.violations.resolved') ? 'btn-light text-primary' : 'btn-primary text-white' }} btn-sm d-inline-flex align-items-center gap-2 rounded-3 shadow-sm px-3 fw-bold border-0" style="{{ request()->routeIs('admin.violations.resolved') ? '' : 'background-color: var(--primary); border: none;' }}">
                <i class="bi bi-check-all"></i> <span class="d-none d-md-inline">Resolved Warnings</span>
            </a>
            <a href="{{ route('admin.landmarks.index') }}" class="btn {{ request()->routeIs('admin.landmarks.index') ? 'btn-light text-primary' : 'btn-primary text-white' }} btn-sm d-inline-flex align-items-center gap-2 rounded-3 shadow-sm px-3 fw-bold border-0" style="{{ request()->routeIs('admin.landmarks.index') ? '' : 'background-color: var(--primary); border: none;' }}">
                <i class="bi bi-geo-alt-fill"></i> <span class="d-none d-md-inline">Landmarks</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="btn {{ request()->routeIs('admin.settings') ? 'btn-light text-primary' : 'btn-primary text-white' }} btn-sm d-inline-flex align-items-center gap-2 rounded-3 shadow-sm px-3 fw-bold border-0" style="{{ request()->routeIs('admin.settings') ? '' : 'background-color: var(--primary); border: none;' }}">
                <i class="bi bi-gear-fill"></i> <span class="d-none d-md-inline">Settings</span>
            </a>
        </div>
        <a class="navbar-brand m-0 d-none d-sm-flex" href="{{ route('fleet.management') }}">
            <img src="{{ asset('Logo.png') }}" alt="Logo" style="height: 38px; width: auto;">
        </a>
    </div>
</nav>
