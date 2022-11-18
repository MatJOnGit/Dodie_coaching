<?php

namespace Dodie_Coaching\Controllers;

class Main {
    private $_timeZone = 'Europe/Paris';

    public function getParam(string $param): int {
        return intval(htmlspecialchars($_GET[$param]));
    }

    public function routeTo(string $page) {
        header("location:{$this->_getRoutingURL($page)}");
    }
    
    protected function _setTimeZone() {
        date_default_timezone_set($this->_timeZone);
    }

    private function _getRoutingURL(string $panel): string {
        return $this->_routingURLs[$panel];
    }
}