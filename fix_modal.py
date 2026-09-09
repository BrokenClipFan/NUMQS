import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

# Replace the Start Drive form with a button that opens the modal
old_start_form = '''<form class="col-6 drive-form" action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_online" value="1">
                        <button type="submit" id="btnStartDrive" class="btn btn-deck btn-deck-start w-100"
                            @if (->is_online) disabled @endif>
                            <i class="bi bi-play-circle-fill fs-4"></i>
                            <span>Start Drive</span>
                        </button>
                    </form>'''

new_start_btn = '''<div class="col-6 drive-form">
                        <button type="button" id="btnStartDrive" class="btn btn-deck btn-deck-start w-100"
                            data-bs-toggle="modal" data-bs-target="#destinationModal"
                            @if (->is_online) disabled @endif>
                            <i class="bi bi-play-circle-fill fs-4"></i>
                            <span>Start Drive</span>
                        </button>
                    </div>'''

c = c.replace(old_start_form, new_start_btn)

# Add the destinationModal HTML before the end of the body
modal_html = """
    <!-- Destination Modal -->
    <div class="modal fade" id="destinationModal" tabindex="-1" aria-labelledby="destinationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--card); border: 1px solid var(--line); border-radius: 16px;">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title text-light fw-bold" id="destinationModalLabel">Select Destination</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-4">
                    <p class="text-muted mb-4">Where are you heading to? This sets your route line for tracking.</p>
                    <form action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_online" value="1">
                        
                        <div class="d-grid gap-3">
                            <button type="submit" name="going_to" value="Naga" class="btn p-3 d-flex align-items-center justify-content-between" style="background: rgba(62, 124, 166, 0.1); border: 2px solid var(--route-naga); border-radius: 12px; color: #F4F5F1;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="route-dot naga"></span>
                                    <span class="fs-5 fw-bold">Naga &rarr; Uling</span>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </button>
                            
                            <button type="submit" name="going_to" value="Uling" class="btn p-3 d-flex align-items-center justify-content-between" style="background: rgba(47, 143, 107, 0.1); border: 2px solid var(--route-uling); border-radius: 12px; color: #F4F5F1;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="route-dot uling"></span>
                                    <span class="fs-5 fw-bold">Uling &rarr; Naga</span>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
"""

c = c.replace('</body>', modal_html + '\n</body>')

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
