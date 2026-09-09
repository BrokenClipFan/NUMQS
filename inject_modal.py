import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

# Replace the start drive form with a button that triggers the modal
start_drive_btn = '''<button type="button" id="btnStartDrive" class="btn btn-deck btn-deck-start w-100"
                            data-bs-toggle="modal" data-bs-target="#destinationModal"
                            @if (->is_online) disabled @endif>
                            <i class="bi bi-play-circle-fill fs-4"></i>
                            <span>Start Drive</span>
                        </button>'''

c = re.sub(r'<form class="col-6 drive-form" action="{{ route\(''online\.update''\) }}" method="POST">\s*@csrf\s*<input type="hidden" name="is_online" value="1">\s*<button type="submit" id="btnStartDrive".*?<span>Start Drive</span>\s*</button>\s*</form>', 
           f'<div class="col-6 drive-form">\n                        {start_drive_btn}\n                    </div>', 
           c, flags=re.DOTALL)

# Insert the destination modal at the end of the body
modal_html = '''
    <!-- Destination Selection Modal -->
    <div class="modal fade" id="destinationModal" tabindex="-1" aria-labelledby="destinationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--card); border: 1px solid var(--line); border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title" id="destinationModalLabel" style="color: var(--ink); font-weight: 700;">Select Destination</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0 pb-4">
                    <p style="color: var(--text-muted); font-size: 0.95rem;">Please select where you are heading. This sets your route for the current drive session.</p>
                    <form action="{{ route('online.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_online" value="1">
                        <div class="d-grid gap-3">
                            <button type="submit" name="first_destination" value="Uling" class="btn btn-lg d-flex align-items-center justify-content-between px-4 py-3" style="background: var(--card); border: 2px solid var(--route-uling); border-radius: 12px; color: var(--ink); font-weight: 600; text-align: left;">
                                <div>
                                    <span class="route-dot uling me-2"></span>
                                    Naga &rarr; Uling
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </button>
                            
                            <button type="submit" name="first_destination" value="Naga" class="btn btn-lg d-flex align-items-center justify-content-between px-4 py-3" style="background: var(--card); border: 2px solid var(--route-naga); border-radius: 12px; color: var(--ink); font-weight: 600; text-align: left;">
                                <div>
                                    <span class="route-dot naga me-2"></span>
                                    Uling &rarr; Naga
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
'''

c = c.replace('<!-- Search Modal -->', modal_html + '\n    <!-- Search Modal -->')

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
