<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dispatcher Queue Control</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-light: rgb(247, 251, 252);
            --neutral-tint: rgb(214, 230, 242);
            --primary-accent: rgb(185, 215, 234);
            --main-dark: rgb(118, 159, 205);
            --text-dark: #2c3e50;
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
        .text-custom-dark { color: var(--main-dark) !important; }

        .nav-sticky-top {
            background-color: #ffffff;
            border-bottom: 2px solid var(--primary-accent);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Draggable Queue Cards */
        .queue-container {
            background-color: var(--neutral-tint);
            border-radius: 12px;
            padding: 1rem;
            height: calc(100vh - 140px); /* Fits screen nicely on desktop */
            overflow-y: auto;
            border: 2px solid var(--primary-accent);
        }

        .draggable-item {
            background-color: #ffffff;
            border: 1px solid var(--primary-accent);
            border-radius: 8px;
            margin-bottom: 0.75rem;
            transition: box-shadow 0.2s;
            display: flex;
            align-items: center;
        }

        .draggable-item:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .drag-handle {
            cursor: grab;
            padding: 1rem 0.5rem;
            color: var(--main-dark);
            background-color: rgba(185, 215, 234, 0.2);
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .driver-info {
            flex-grow: 1;
            padding: 0.75rem 1rem;
        }

        .driver-photo {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--main-dark);
        }

        /* SortableJS Visual Feedback */
        .sortable-ghost {
            opacity: 0.4;
            background-color: var(--primary-accent);
            border: 2px dashed var(--main-dark);
        }

        .sortable-drag {
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            cursor: grabbing !important;
        }

        /* Toast positioning */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1055;
        }

        @media (max-width: 768px) {
            .queue-container {
                height: auto;
                max-height: 50vh;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Dispatcher Navbar -->
    <nav class="navbar navbar-expand-lg nav-sticky-top px-3 py-2 shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            <a class="navbar-brand fw-bold d-flex align-items-center m-0" href="#">
                <i class="bi bi-display me-2 text-custom-dark"></i>
                <span class="fs-5 text-dark">Dispatcher HQ</span>
            </a>
            
            <div class="d-flex gap-2">
                <span class="badge bg-custom-dark d-flex align-items-center px-3 py-2">
                    <i class="bi bi-broadcast me-1"></i> System Live
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4 max-w-md mx-auto" style="max-width: 1400px;">
        
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert bg-white border-custom shadow-sm d-flex justify-content-between align-items-center rounded-3">
                    <div>
                        <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle-fill text-warning me-2"></i>Manual Override Active</h6>
                        <small class="text-muted">Drag and drop driver cards using the left grip handles to reorder the dispatch queues in case of terminal incidents.</small>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary d-none d-md-block" onclick="location.reload()">Refresh Queues</button>
                </div>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- Column 1: Naga to Uling Queue -->
            <div class="col-12 col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                    <h5 class="fw-bold text-custom-dark m-0">Naga Terminal</h5>
                    <span class="badge bg-secondary rounded-pill">Total: <span id="nagaCount">3</span></span>
                </div>
                
                <div class="queue-container shadow-inner" id="nagaQueueList">
                    
                    {{-- @foreach ($nagaQueue as $index => $driver) --}}
                    
                    <!-- Draggable Item 1 -->
                    <div class="draggable-item shadow-sm" data-driver-id="1">
                        <div class="drag-handle">
                            <i class="bi bi-grip-vertical fs-4"></i>
                        </div>
                        <div class="driver-info d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Driver" class="driver-photo">
                                <div>
                                    <h6 class="mb-0 fw-bold">Juan Dela Cruz</h6>
                                    <span class="badge bg-light text-dark border font-monospace mt-1">GHI-7890</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="queue-number fs-4 fw-bold text-muted">#1</div>
                                <span class="badge bg-success-subtle text-success">Filling Up</span>
                            </div>
                        </div>
                    </div>

                    <!-- Draggable Item 2 -->
                    <div class="draggable-item shadow-sm" data-driver-id="2">
                        <div class="drag-handle">
                            <i class="bi bi-grip-vertical fs-4"></i>
                        </div>
                        <div class="driver-info d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=100&q=80" alt="Driver" class="driver-photo">
                                <div>
                                    <h6 class="mb-0 fw-bold">Pedro Penduko</h6>
                                    <span class="badge bg-light text-dark border font-monospace mt-1">JKL-1234</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="queue-number fs-4 fw-bold text-muted">#2</div>
                                <span class="badge bg-secondary-subtle text-secondary">Waiting</span>
                            </div>
                        </div>
                    </div>

                    <!-- Draggable Item 3 -->
                    <div class="draggable-item shadow-sm" data-driver-id="3">
                        <div class="drag-handle">
                            <i class="bi bi-grip-vertical fs-4"></i>
                        </div>
                        <div class="driver-info d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="Driver" class="driver-photo">
                                <div>
                                    <h6 class="mb-0 fw-bold text-primary-emphasis">Alex Santos</h6>
                                    <span class="badge bg-light text-dark border font-monospace mt-1">ABC-1234</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="queue-number fs-4 fw-bold text-muted">#3</div>
                                <span class="badge bg-secondary-subtle text-secondary">Waiting</span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- @endforeach --}}

                </div>
            </div>

            <!-- Column 2: Uling to Naga Queue -->
            <div class="col-12 col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                    <h5 class="fw-bold text-custom-dark m-0">Uling Terminal</h5>
                    <span class="badge bg-secondary rounded-pill">Total: <span id="ulingCount">2</span></span>
                </div>
                
                <div class="queue-container shadow-inner" id="ulingQueueList" style="border-color: #5a7b9c; background-color: #eef2f5;">
                    
                    <!-- Draggable Item 4 -->
                    <div class="draggable-item shadow-sm" data-driver-id="4">
                        <div class="drag-handle" style="background-color: rgba(90, 123, 156, 0.1);">
                            <i class="bi bi-grip-vertical fs-4"></i>
                        </div>
                        <div class="driver-info d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&q=80" alt="Driver" class="driver-photo">
                                <div>
                                    <h6 class="mb-0 fw-bold">Maria Clara</h6>
                                    <span class="badge bg-light text-dark border font-monospace mt-1">XYZ-5678</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="queue-number fs-4 fw-bold text-muted">#1</div>
                                <span class="badge bg-warning-subtle text-warning-emphasis d-block mb-1" style="font-size: 0.7rem;">02:14 Left</span>
                            </div>
                        </div>
                    </div>

                    <!-- Draggable Item 5 -->
                    <div class="draggable-item shadow-sm" data-driver-id="5">
                        <div class="drag-handle" style="background-color: rgba(90, 123, 156, 0.1);">
                            <i class="bi bi-grip-vertical fs-4"></i>
                        </div>
                        <div class="driver-info d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=100&q=80" alt="Driver" class="driver-photo">
                                <div>
                                    <h6 class="mb-0 fw-bold">Crisostomo Ibarra</h6>
                                    <span class="badge bg-light text-dark border font-monospace mt-1">DEF-9012</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="queue-number fs-4 fw-bold text-muted">#2</div>
                                <span class="badge bg-warning-subtle text-warning-emphasis d-block mb-1" style="font-size: 0.7rem;">08:45 Left</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Live Toast Notification System -->
    <div class="toast-container">
        <div id="saveToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-bold d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i> Queue successfully updated!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include SortableJS Library -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            const toastEl = document.getElementById('saveToast');
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });

            // Common Sortable Config
            const sortableOptions = {
                animation: 200,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function (evt) {
                    // This function fires when a drag-and-drop finishes
                    const listId = evt.to.id; // e.g., 'nagaQueueList'
                    updateQueueVisuals(evt.to);
                    
                    // Prepare data for Laravel Backend (Axios/Fetch)
                    const itemEls = evt.to.querySelectorAll('.draggable-item');
                    let newOrder = [];
                    itemEls.forEach((el, index) => {
                        newOrder.push({
                            id: el.getAttribute('data-driver-id'),
                            position: index + 1
                        });
                    });

                    console.log(`New Order for ${listId}:`, newOrder);
                    
                    /* 
                     * Mocking the backend update request:
                     * axios.post('/dispatcher/queue/update', { queue: listId, order: newOrder })
                     * .then(response => { toast.show(); })
                     */
                    
                    // Trigger success toast for visual feedback
                    toast.show();
                }
            };

            // Initialize Sortable on Naga Queue
            const nagaList = document.getElementById('nagaQueueList');
            if (nagaList) {
                new Sortable(nagaList, sortableOptions);
            }

            // Initialize Sortable on Uling Queue
            const ulingList = document.getElementById('ulingQueueList');
            if (ulingList) {
                new Sortable(ulingList, sortableOptions);
            }

            // Utility function to update the big queue position numbers visually after drop
            function updateQueueVisuals(listElement) {
                const items = listElement.querySelectorAll('.draggable-item');
                items.forEach((item, index) => {
                    const numberBadge = item.querySelector('.queue-number');
                    if (numberBadge) {
                        numberBadge.textContent = '#' + (index + 1);
                    }
                });
            }
        });
    </script>
</body>
</html>