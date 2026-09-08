<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Debugger</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-900 text-white p-6 font-mono">
    <h1 class="text-2xl font-bold mb-4 text-blue-400">Capacitor GPS Debugger</h1>
    
    <div class="space-y-4">
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
            <h2 class="text-gray-400 text-sm font-semibold mb-1">Capacitor Status</h2>
            <div id="capStatus" class="text-yellow-400">Checking...</div>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
            <h2 class="text-gray-400 text-sm font-semibold mb-1">Permissions</h2>
            <div id="permStatus" class="text-yellow-400">Checking...</div>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
            <h2 class="text-gray-400 text-sm font-semibold mb-1">Live Coordinates</h2>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="text-gray-500 text-xs">LATITUDE</span>
                    <div id="lat" class="text-lg font-bold text-green-400">---</div>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">LONGITUDE</span>
                    <div id="lng" class="text-lg font-bold text-green-400">---</div>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">ACCURACY</span>
                    <div id="acc" class="text-lg font-bold text-green-400">---</div>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">COMPASS</span>
                    <div id="heading" class="text-lg font-bold text-green-400">---</div>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
            <h2 class="text-gray-400 text-sm font-semibold mb-1">Wi-Fi Connection</h2>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="text-gray-500 text-xs">SSID</span>
                    <div id="wifiSsid" class="text-lg font-bold text-green-400">---</div>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">BSSID (Terminal Drop)</span>
                    <div id="wifiBssid" class="text-lg font-bold text-green-400">---</div>
                </div>
            </div>
            <button id="btnWifi" class="mt-3 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Get Wi-Fi Info
            </button>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
            <h2 class="text-gray-400 text-sm font-semibold mb-1">System Logs</h2>
            <div id="logs" class="text-xs text-gray-300 h-48 overflow-y-auto font-mono space-y-1"></div>
        </div>

        <button id="btnStart" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
            Force Request Location
        </button>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const logsEl = document.getElementById('logs');
            const log = (msg, color = 'text-gray-300') => {
                const d = new Date();
                const time = d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
                logsEl.innerHTML += '<div class="' + color + '">[' + time + '] ' + msg + '</div>';
                logsEl.scrollTop = logsEl.scrollHeight;
            };

            log('DOM Loaded. Waiting for Capacitor...');

            const capStatus = document.getElementById('capStatus');
            const permStatus = document.getElementById('permStatus');

            setTimeout(async () => {
                if (window.Capacitor && window.CapacitorGeolocation) {
                    capStatus.textContent = 'Native Capacitor + Plugin Found! ?';
                    capStatus.className = 'text-green-400';
                    log('Capacitor object verified.', 'text-green-400');
                } else if (window.Capacitor) {
                    capStatus.textContent = 'Capacitor Found (NO GEOLOCATION PLUGIN) ?';
                    capStatus.className = 'text-red-400';
                    log('CRITICAL: Capacitor is present but Geolocation plugin is missing.', 'text-red-400');
                } else {
                    capStatus.textContent = 'Running in Standard Browser / No Capacitor ??';
                    capStatus.className = 'text-yellow-400';
                    log('window.Capacitor is null. Falling back to navigator.geolocation.', 'text-yellow-400');
                }

                                if (window.CapacitorCompass) {
                    log('Native Compass Plugin Found! Starting...', 'text-green-400');
                    window.CapacitorCompass.start();
                    window.CapacitorCompass.addListener('heading', (event) => {
                        let heading = event.heading; // 0-360 true/magnetic heading
                        if (heading !== null && heading !== undefined) {
                            document.getElementById('heading').textContent = heading.toFixed(1) + '°';
                        }
                    });
                } else {
                    log('Falling back to web deviceorientation...', 'text-yellow-400');
                    window.addEventListener('deviceorientationabsolute', (e) => {
                        if (e.alpha !== null) {
                            let heading = e.webkitCompassHeading || (360 - e.alpha);
                            document.getElementById('heading').textContent = heading.toFixed(1) + '°';
                        }
                    }, true);
                }
            }, 1000);

            document.getElementById('btnStart').addEventListener('click', async () => {
                log('Start button clicked.');
                
                if (window.Capacitor && window.CapacitorGeolocation) {
                    try {
                        log('Requesting native permissions...');
                        const perm = await window.CapacitorGeolocation.requestPermissions();
                        permStatus.textContent = perm.location.toUpperCase();
                        
                        if (perm.location === 'granted') {
                            permStatus.className = 'text-green-400';
                            log('Permission GRANTED. Fetching position...', 'text-green-400');
                            
                            window.CapacitorGeolocation.watchPosition({ enableHighAccuracy: true }, (pos, err) => {
                                if (err) {
                                    log('Watch Error: ' + err.message, 'text-red-400');
                                    return;
                                }
                                document.getElementById('lat').textContent = pos.coords.latitude.toFixed(6);
                                document.getElementById('lng').textContent = pos.coords.longitude.toFixed(6);
                                document.getElementById('acc').textContent = pos.coords.accuracy.toFixed(1) + 'm';
                                log('Native GPS updated!', 'text-green-400');
                            });
                        } else {
                            permStatus.className = 'text-red-400';
                            log('Permission DENIED by user.', 'text-red-400');
                        }
                    } catch (e) {
                        log('Native Error: ' + e.message, 'text-red-400');
                    }
                } else {
                    log('Using standard HTML5 navigator.geolocation...');
                    navigator.geolocation.watchPosition((pos) => {
                        document.getElementById('lat').textContent = pos.coords.latitude.toFixed(6);
                        document.getElementById('lng').textContent = pos.coords.longitude.toFixed(6);
                        document.getElementById('acc').textContent = pos.coords.accuracy.toFixed(1) + 'm';
                        log('HTML5 GPS updated!', 'text-blue-400');
                    }, (err) => {
                        log('HTML5 Error: ' + err.message, 'text-red-400');
                    }, { enableHighAccuracy: true });
                }
            });

            document.getElementById('btnWifi').addEventListener('click', async () => {
                log('Wi-Fi button clicked.');
                if (window.CapacitorWifiNetwork) {
                    try {
                        const info = await window.CapacitorWifiNetwork.getWifiInfo();
                        log('Wi-Fi Check Success!', 'text-green-400');
                        document.getElementById('wifiSsid').textContent = info.ssid || 'N/A';
                        document.getElementById('wifiBssid').textContent = info.bssid || info.ip || 'N/A';
                        log('SSID: ' + info.ssid);
                        log('BSSID: ' + info.bssid);
                    } catch (e) {
                        log('Wi-Fi Error: ' + e.message, 'text-red-400');
                    }
                } else {
                    log('CapacitorWifiNetwork plugin not found!', 'text-red-400');
                }
            });
        });
    </script>
</body>
</html>


