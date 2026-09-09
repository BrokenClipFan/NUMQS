@extends('layouts.app')
@section('content')
<div class="container-fluid p-0 d-flex flex-column" style="height: 100vh; background: var(--bg); overflow: hidden;">
    <!-- Modern Navbar -->
    <header class="navbar navbar-light bg-white shadow-sm px-3 py-2" style="border-bottom: 1px solid var(--line); z-index: 1050; position: relative;">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            <a class="navbar-brand brand-mark m-0 d-flex align-items-center" href="{{ route('driver.map') }}">
                <i class="bi bi-arrow-left text-amber me-2 fs-4"></i>
                <img src="{{ asset('Logo.png') }}" alt="Logo" style="height: 28px; width: auto; object-fit: contain;">
                <span class="ms-2 fw-bold text-ink">Queue Lineup</span>
            </a>
        </div>
    </header>

    <div class="flex-grow-1 overflow-auto p-3 p-md-4">
        <div class="max-w-md mx-auto" style="max-width: 600px;">
            <ul class="nav nav-fill" id="queueTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link route-chip active" id="naga-uling-tab" data-bs-toggle="tab"
                        data-bs-target="#naga-uling" type="button" role="tab">
                        <span class="route-dot naga"></span> Naga → Uling
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link route-chip" id="uling-naga-tab" data-bs-toggle="tab"
                        data-bs-target="#uling-naga" type="button" role="tab">
                        <span class="route-dot uling"></span> Uling → Naga
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="queueTabsContent">
                <div class="tab-pane fade show active" id="naga-uling" role="tabpanel"
                    aria-labelledby="naga-uling-tab">
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="queue-section-label">Queue Lineup · FIFO</span>
                        <span class="badge queue-count-badge" id="nagaToUlingQueueCount">0 Active</span>
                    </div>

                    <div class="d-flex flex-column gap-2" id="nagaToUlingQueue">
                        <div class="empty-state">Loading queue…</div>
                    </div>
                </div>

                <div class="tab-pane fade" id="uling-naga" role="tabpanel" aria-labelledby="uling-naga-tab">
                    <div class="alert strict-window-note py-2 px-2 rounded-3 mb-2 d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                        <div><strong>Strict Window:</strong> Max 10 mins to clear dispatch.</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="queue-section-label">Dispatch Order</span>
                        <span class="badge queue-count-badge" id="ulingToNagaQueueCount">0 Active</span>
                    </div>

                    <div class="d-flex flex-column gap-2" id="ulingToNagaQueue">
                        <div class="empty-state">Loading queue…</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Add any required queue styles here if they were scoped to map */
    .route-chip { border: 2px solid transparent; background: rgba(0,0,0,0.05); color: var(--ink); border-radius: 12px; font-weight: 600; padding: 0.75rem 1rem; flex: 1; text-align: center; }
    .route-chip.active { background: white; border-color: var(--amber); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .route-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; }
    .route-dot.naga { background: var(--route-naga); }
    .route-dot.uling { background: var(--route-uling); }
    .queue-section-label { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
    .queue-count-badge { background: var(--ink); color: #fff; padding: 0.35rem 0.6rem; border-radius: 6px; font-weight: 600; font-size: 0.75rem; }
    .queue-card { background: white; border: 1px solid var(--line); border-radius: 12px; padding: 1rem; transition: transform 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .queue-card.current-user { border: 2px solid var(--amber); background: rgba(245, 158, 11, 0.05); }
    .pos-circle { width: 32px; height: 32px; border-radius: 50%; background: var(--bg); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--ink); border: 1px solid var(--line); }
    .queue-card.current-user .pos-circle { background: var(--amber); color: white; border: none; }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    'use strict';
    
    const CURRENT_DRIVER_ID = "{{ Auth::id() }}";
    const nagaToUlingQueueEl = document.getElementById('nagaToUlingQueue');
    const ulingToNagaQueueEl = document.getElementById('ulingToNagaQueue');
    const nagaToUlingCountEl = document.getElementById('nagaToUlingQueueCount');
    const ulingToNagaCountEl = document.getElementById('ulingToNagaQueueCount');
    const POLL_INTERVAL_MS = 2000;

    
    
    // Start polling
    getAllQueues();
    setInterval(getAllQueues, POLL_INTERVAL_MS);
});
</script>
@endsection
