import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

injection = """
            map.on('popupclose', () => {
                window.viewingOtherDriverId = null;
                if(typeof updateRouteLine === 'function' && typeof targetLat !== 'undefined' && typeof targetLng !== 'undefined') {
                    updateRouteLine(targetLat, targetLng, DRIVER_DESTINATION);
                }
            });

            window.routeCasing = L.polyline([], {color: '#172554', weight: 9, opacity: 0.9, lineCap: 'round', lineJoin: 'round', interactive: false}).addTo(map);
            window.routePolyline = L.polyline([], {color: '#3b82f6', weight: 5, opacity: 1.0, lineCap: 'round', lineJoin: 'round', interactive: false}).addTo(map);
            
            if (DRIVER_DESTINATION && DRIVER_DESTINATION !== 'none' && routeLatLngs && routeLatLngs.length > 0) {
                let initPath = [];
                if (DRIVER_DESTINATION.toLowerCase() === 'uling') {
                    initPath = routeLatLngs;
                } else if (DRIVER_DESTINATION.toLowerCase() === 'naga') {
                    initPath = [...routeLatLngs].reverse();
                }
                window.routeCasing.setLatLngs(initPath);
                window.routePolyline.setLatLngs(initPath);
            }
            
            if (typeof L.polylineDecorator === 'function') {
                window.routeArrows = L.polylineDecorator(window.routePolyline, {
                    patterns: [
                        { offset: 50, repeat: 100, symbol: L.Symbol.arrowHead({pixelSize: 12, polygon: false, pathOptions: {stroke: true, color: '#ffffff', weight: 3, opacity: 0.9, lineCap: 'round'}}) }
                    ]
                }).addTo(map);
            }
"""

c = c.replace("window.addEventListener('resize', () => map.invalidateSize());", 
              "window.addEventListener('resize', () => map.invalidateSize());" + injection)

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
