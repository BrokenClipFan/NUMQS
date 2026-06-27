<?php

namespace App\Services;

use App\Models\Terminal;

class TerminalService {
  public function getTerminalByBssid($bssid) {
    return Terminal::where('bssid', $bssid)->first();
  }
}