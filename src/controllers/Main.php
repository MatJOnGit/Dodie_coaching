<?php

namespace Dodie_Coaching\Controllers;

class Main {
    public function routeTo(string $page) {
        header("location:{$this->_getRoutingURL($page)}");
    }

    private function _getRoutingURL(string $panel): string {
        return $this->_routingURLs[$panel];
    }
}