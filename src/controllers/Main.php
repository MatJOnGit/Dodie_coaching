<?php

namespace Dodie_Coaching\Controllers;

class Main {
    private $_timeZone = 'Europe/Paris';

    private $_weekDays = [
        ['english' => 'monday', 'french' => 'Lundi'],
        ['english' => 'tuesday', 'french' => 'Mardi'],
        ['english' => 'wednesday', 'french' => 'Mercredi'],
        ['english' => 'thursday', 'french' => 'Jeudi'],
        ['english' => 'friday', 'french' => 'Vendredi'],
        ['english' => 'saturday', 'french' => 'Samedi'],
        ['english' => 'sunday', 'french' => 'Dimanche']
    ];

    public function areDataPosted(array $postedData) {
        $areDataPosted = true;

        foreach($postedData as $postedDataItem) {
            if (!isset($_POST[$postedDataItem])) {
                $areDataPosted = false;
            }
        }
        
        return $areDataPosted;
    }

    public function areParamsSet(array $params): bool {
        $areParamsSet = true;

        foreach ($params as $param) {
            if (!isset($_GET[$param])) {
                $areParamsSet = false;
            }
        }

        return $areParamsSet;
    }

    public function destroySessionData() {
        session_destroy();
    }

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

    protected function _getWeekDays(): array {
        return $this->_weekDays;
    }
}