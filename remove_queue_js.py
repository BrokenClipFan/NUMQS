import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

# I want to remove everything from 'function formatCountdownParts' or 'function makeFlapTile' down to 'function updateQueue(drivers)' until 'Network polling'
# Let's search for function makeFlapTile(digit)
start_str = "            function makeFlapTile(digit) {"
end_str = "            // ---------------------------------------------------------------\n            // Network polling"

start_idx = c.find(start_str)
end_idx = c.find(end_str)

if start_idx != -1 and end_idx != -1:
    c = c[:start_idx] + c[end_idx:]

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
