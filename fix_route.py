import io
with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

injection = """const CURRENT_DRIVER_ID = "{{ Auth::id() }}";
            let rawPoints = {!! isset() ? ->path : '[]' !!};
            const FULL_ROUTE_POINTS = typeof rawPoints === 'string' ? JSON.parse(rawPoints) : rawPoints;
            const routeLatLngs = FULL_ROUTE_POINTS.map(coord => [coord.lat, coord.lng]);"""

c = c.replace('const CURRENT_DRIVER_ID = "{{ Auth::id() }}";', injection)

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
