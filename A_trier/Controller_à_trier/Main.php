<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Subscriber;
use Dodie_Coaching\Models\ProgramFile;

class Main {
    private const MEALS_TRANSLATIONS = [
        ['english' => 'breakfast', 'french' => 'petit-déjeuner'],
        ['english' => 'snack #1', 'french' => 'en-cas de 10h'],
        ['english' => 'lunch', 'french' => 'déjeuner'],
        ['english' => 'snack #2', 'french' => 'goûté'],
        ['english' => 'diner', 'french' => 'dîner']
    ];
    
    private const MONTHS = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    
    
    private const WEEKDAYS = [
        ['english' => 'sunday', 'french' => 'Dimanche'],
        ['english' => 'monday', 'french' => 'Lundi'],
        ['english' => 'tuesday', 'french' => 'Mardi'],
        ['english' => 'wednesday', 'french' => 'Mercredi'],
        ['english' => 'thursday', 'french' => 'Jeudi'],
        ['english' => 'friday', 'french' => 'Vendredi'],
        ['english' => 'saturday', 'french' => 'Samedi']
    ];
    
    public function getProgramFileStatus($subscriberId) {
        $programFile = new ProgramFile;
        
        $programFileStatus = $programFile->selectFileStatus($subscriberId);
        return $programFileStatus ? $programFileStatus['file_status'] : NULL;
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
        return self::MONTHS;
    }
    
    protected function _getMealsTranslations() {
        return self::MEALS_TRANSLATIONS;
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
    
    protected function _getWeekDays(): array {
        return self::WEEKDAYS;
    }
}