<?php
require 'vendor/autoload.php';
\ = require_once 'bootstrap/app.php';
\->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
\ = json_decode(\App\Models\Route::first()->path, true);
echo json_encode(['first' => \[0], 'last' => end(\)]);
