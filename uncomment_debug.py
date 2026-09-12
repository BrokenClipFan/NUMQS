import io

with io.open('routes/web.php', 'r', encoding='utf-8') as f:
    c = f.read()

c = c.replace("// Route::get('/debug-gps', function () {\n    // return view('debug-gps');\n// });", "Route::get('/debug-gps', function () {\n    return view('debug-gps');\n});")
c = c.replace("// Route::get('/debug-gps', function () {\r\n    // return view('debug-gps');\r\n// });", "Route::get('/debug-gps', function () {\n    return view('debug-gps');\n});")

with io.open('routes/web.php', 'w', encoding='utf-8') as f:
    f.write(c)
