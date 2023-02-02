<?php

namespace App\Entities;

final class Timezone {
    private const TIMEZONE = 'Europe/Paris';

    public function setTimeZone() {
        date_default_timezone_set(self::TIMEZONE);
    }
}