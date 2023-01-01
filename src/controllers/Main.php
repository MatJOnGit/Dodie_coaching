<?php

namespace Dodie_Coaching\Controllers;

class Main {
    private $_meals = [
        ['english' => 'breakfast', 'french' => 'petit-déjeuner'],
        ['english' => 'lunch', 'french' => 'déjeuner'],
        ['english' => 'diner', 'french' => 'diner'],
        ['english' => 'snacks', 'french' => 'snacks']
    ];

    private $_months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    private $_timeZone = 'Europe/Paris';

    private $_weekDays = [
        ['english' => 'sunday', 'french' => 'Dimanche'],
        ['english' => 'monday', 'french' => 'Lundi'],
        ['english' => 'tuesday', 'french' => 'Mardi'],
        ['english' => 'wednesday', 'french' => 'Mercredi'],
        ['english' => 'thursday', 'french' => 'Jeudi'],
        ['english' => 'friday', 'french' => 'Vendredi'],
        ['english' => 'saturday', 'french' => 'Samedi']
    ];

    public function isRequestMatching(string $request, string $toMatch) {
        return $request === $toMatch;
    }

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

    public function getParam(string $param) {
        return htmlspecialchars($_GET[$param]);
    }

    public function routeTo(string $page) {
        header("location:{$this->_getRoutingURL($page)}");
    }
    
    protected function _setTimeZone() {
        date_default_timezone_set($this->_timeZone);
    }
    
    protected function _getEnglishWeekDay(string $date): string {
        return $this->_getWeekDays()[explode(' ', $date)[0]]['english'];
    }
    
    protected function _getFrenchDate(string $date): string {
        $frenchDateWeekDay = $this->_getWeekDays()[explode(' ', $date)[0]]['french'];
        $dateDay = explode(' ', $date)[1];
        $dateMonth = $this->_getMonths()[explode(' ',  $date)[2] -1];

        return "{$frenchDateWeekDay} {$dateDay} {$dateMonth}";
    }

    protected function _getMonths() {
        return $this->_months;
    }

    protected function _getMeals() {
        return $this->_meals;
    }

    private function _getRoutingURL(string $panel): string {
        return $this->_routingURLs[$panel];
    }

    protected function _getWeekDays(): array {
        return $this->_weekDays;
    }
}