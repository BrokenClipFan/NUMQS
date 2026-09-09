import io
with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

c = c.replace("const routeLatLngs = FULL_ROUTE_POINTS.map(coord => [coord.lat, coord.lng]);",
              "const routeLatLngs = FULL_ROUTE_POINTS.map(coord => [coord.lat, coord.lng]);\n            console.log('routeLatLngs:', routeLatLngs); console.log('DRIVER_DESTINATION:', DRIVER_DESTINATION);")

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
