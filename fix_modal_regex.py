import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

pattern = r'<form class="col-6 drive-form" action="{{ route\(''online\.update''\) }}" method="POST">\s*@csrf\s*<input type="hidden" name="is_online" value="1">\s*<button type="submit" id="btnStartDrive".*?</form>'

new_start_btn = '''<div class="col-6 drive-form">
                        <button type="button" id="btnStartDrive" class="btn btn-deck btn-deck-start w-100"
                            data-bs-toggle="modal" data-bs-target="#destinationModal"
                            @if (->is_online) disabled @endif>
                            <i class="bi bi-play-circle-fill fs-4"></i>
                            <span>Start Drive</span>
                        </button>
                    </div>'''

c = re.sub(pattern, new_start_btn, c, flags=re.DOTALL)

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
