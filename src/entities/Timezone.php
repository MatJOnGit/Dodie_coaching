<?php

namespace App\Entities;

final class Timezone {
    private const TIMEZONE = 'Europe/Paris';
    
    public function setTimezone() {
        date_default_timezone_set(self::TIMEZONE);
    }
}