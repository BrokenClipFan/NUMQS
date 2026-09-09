import io

with io.open('routes/web.php', 'r', encoding='utf-8') as f:
    c = f.read()

route = '''
        Route::get('/queue-lineup', function() {
            return view('driver.queue');
        })->name('driver.queue');
'''

if 'driver.queue' not in c:
    c = c.replace("Route::get('/', [DriverController::class, 'index'])->name('driver.map');", "Route::get('/', [DriverController::class, 'index'])->name('driver.map');\n" + route)

    with io.open('routes/web.php', 'w', encoding='utf-8') as f:
        f.write(c)
