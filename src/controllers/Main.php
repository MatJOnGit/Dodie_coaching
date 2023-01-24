<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Subscriber;
use Dodie_Coaching\Models\ProgramFile;

class Main {
    private $_mealsTranslations = [
        ['english' => 'breakfast', 'french' => 'petit-déjeuner'],
        ['english' => 'snack #1', 'french' => 'en-cas de 10h'],
        ['english' => 'lunch', 'french' => 'déjeuner'],
        ['english' => 'snack #2', 'french' => 'goûté'],
        ['english' => 'diner', 'french' => 'dîner']
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
    
    public function getProgramFileStatus($subscriberId) {
        $programFile = new ProgramFile;
        
        $programFileStatus = $programFile->selectFileStatus($subscriberId);
        return $programFileStatus ? $programFileStatus['file_status'] : NULL;
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
    
    /******************************************************************
    Transforms a date into a string of weekday, day and month in french
    ******************************************************************/ 
    protected function _getFrenchDate(string $date): string {
        $frenchDateWeekDay = $this->_getWeekDays()[explode(' ', $date)[0]]['french'];
        $dateDay = explode(' ', $date)[1];
        $dateMonth = $this->_getMonths()[explode(' ',  $date)[2] -1];
        
        return "{$frenchDateWeekDay} {$dateDay} {$dateMonth}";
    }
    
    protected function _getMonths() {
        return $this->_months;
    }
    
    protected function _getMealsTranslations() {
        return $this->_mealsTranslations;
    }
    
    /*****************************************************
    Transforms the list of meals in a specific subscriber
    program into an associated array containing each meals
    *****************************************************/ 
    protected function _getProgramMeals(int $subscriberId) {
        $subscriber = new Subscriber;
        
        $generatedMeals = $subscriber->selectProgramMeals($subscriberId);
        
        return strlen($generatedMeals['meals_list']) ? explode(', ', $generatedMeals['meals_list']) : NULL;
    }
    
    private function _getRoutingURL(string $panel): string {
        return $this->_routingURLs[$panel];
    }
    
    protected function _getWeekDays(): array {
        return $this->_weekDays;
    }
}