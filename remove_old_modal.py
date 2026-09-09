import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

# Remove the old destination modal
c = re.sub(r'<!-- Destination Modal -->.*?</div>\s*</div>\s*</div>\s*</div>', '', c, flags=re.DOTALL)

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
