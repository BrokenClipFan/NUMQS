import io
with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

injection = """const CURRENT_DRIVER_ID = "{{ Auth::id() }}";
            const DRIVER_DESTINATION = "{{ ->status->going_to ?? 'none' }}";"""

c = c.replace('const CURRENT_DRIVER_ID = "{{ Auth::id() }}";', injection)

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
