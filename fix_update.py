import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

func = """
            function updateRouteLine(lat, lng, destination) {
                if (!routeLatLngs || routeLatLngs.length === 0 || !destination || destination === 'none') {
                    if(window.routeCasing) window.routeCasing.setLatLngs([]);
                    if(window.routePolyline) window.routePolyline.setLatLngs([]);
                    return;
                }
                
                let minDistance = Infinity;
                let closestIdx = 0;
                const currentPos = L.latLng(lat, lng);

                for (let i = 0; i < routeLatLngs.length; i++) {
                    const d = currentPos.distanceTo(L.latLng(routeLatLngs[i][0], routeLatLngs[i][1]));
                    if (d < minDistance) {
                        minDistance = d;
                        closestIdx = i;
                    }
                }

                let newPath = [];
                const dest = destination.toLowerCase().trim();
                if (dest === 'uling') {
                    newPath = routeLatLngs.slice(closestIdx);
                } else if (dest === 'naga') {
                    newPath = routeLatLngs.slice(0, closestIdx + 1).reverse();
                } else {
                    newPath = routeLatLngs;
                }

                if(window.routeCasing) window.routeCasing.setLatLngs(newPath);
                if(window.routePolyline) window.routePolyline.setLatLngs(newPath);
                if(window.routeArrows) window.routeArrows.setPaths(newPath);
            }
"""

c = c.replace("function updateIconScale() {", func + "\n            function updateIconScale() {")

with io.open('resources/views/driver/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(c)
