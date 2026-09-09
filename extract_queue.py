import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

# Add Queue Button to NavBar
nav_btn = '''
                <!-- Queue Button -->
                <a href="{{ route('driver.queue') }}"
                    class="btn p-0 border-0 text-amber d-flex align-items-center justify-content-center"
                    title="Queue Lineup" style="background: transparent; font-size: 1.25rem;">
                    <i class="bi bi-list-ol"></i>
                </a>
'''
c = c.replace('<!-- Profile Button -->', nav_btn + '\n                <!-- Profile Button -->')

# Remove the Queue Lineup HTML
html_pattern = r'<ul class="nav nav-fill" id="queueTabs".*?</div>\s*</div>\s*</div>\s*</div>'
html_match = re.search(html_pattern, c, re.DOTALL)
queue_html = ""
if html_match:
    queue_html = html_match.group(0)
    c = c.replace(queue_html, "</div>\n    </div>") # close the scrollable-panel correctly

# Remove the DOM bindings
dom_pattern = r'const nagaToUlingQueueEl.*?const ulingToNagaCountEl = document\.getElementById\(\'ulingToNagaQueueCount\'\);\s*'
dom_match = re.search(dom_pattern, c, re.DOTALL)
if dom_match:
    c = c.replace(dom_match.group(0), "")

# Remove the getAllQueues and buildQueueCard functions
# Find from Network polling all the way down to startPolling
js_pattern = r'// \-+\s*// Network polling\s*// \-+\s*function getAllQueues\(\).*?function initClock\(\)'
js_match = re.search(js_pattern, c, re.DOTALL)
queue_js = ""
if js_match:
    queue_js = js_match.group(0).replace("function initClock()", "")
    c = c.replace(queue_js, "")

# Remove getAllQueues() from setInterval inside startPolling
c = c.replace("getAllQueues();\n", "")
c = c.replace("getAllQueues();", "")

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)

with io.open('resources/views/driver/queue.blade.php', 'w', encoding='utf-8') as f:
    f.write(r'''@extends('layouts.app')
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
            ''' + queue_html + '''
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

    ''' + queue_js + '''
    
    // Start polling
    getAllQueues();
    setInterval(getAllQueues, POLL_INTERVAL_MS);
});
</script>
@endsection
''')
