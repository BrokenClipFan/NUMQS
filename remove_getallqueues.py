import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

# Try to find getAllQueues function
c = re.sub(r'function getAllQueues\(\) \{.*?\n            \}\n', '', c, flags=re.DOTALL)

# Remove startPolling calling getAllQueues
c = c.replace('getAllQueues();\n', '')
c = c.replace('setInterval(getAllQueues, POLL_INTERVAL_MS);\n', '')

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
