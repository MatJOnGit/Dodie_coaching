<?php

namespace App\Entities;

use App\Domain\Models\Program as ProgramModel;
use App\Domain\Models\FoodPlan;
use DateTime, DatePeriod, DateInterval;

final class Program {
    public function isMenuRequested(): bool {
        return (!isset($_GET['meal']) && !isset($_GET['request']));
    }
    
    public function isMealRequested(): bool {
        return (isset($_GET['meal']) && !isset($_GET['request']));
    }
    
    public function isRequestSet(): bool {
        return (!isset($_GET['day']) && !isset($_GET['meal']) && isset($_GET['request']));
    }
    
    public function getRequest(): string {
        return htmlspecialchars($_GET['request']);
    }
    
    public function isShoppingListRequested(string $request): bool {
        return $request === 'shopping-list';
    }

    /*************************************************************************************
    Converts url parameters into a associative array containing the day and meal requested
    *************************************************************************************/
    public function getMealData(): array {
        $meal = htmlspecialchars($_GET['meal']);
        $mealData = [
            'day' => false,
            'meal' => false
        ];
        
        if (is_string($meal) && strpos($meal, '-')) {
            $mealData['day'] = explode('-', htmlspecialchars($_GET['meal']))[0];
            $mealData['meal'] = explode('-', htmlspecialchars($_GET['meal']))[1];
        }
        
        return $mealData;
    }
    
    /***************************************************************
    Tests if 'day' and 'meal' url parameters are known (respectively
    in weekDays and mealsTranslations of Main controller variables)
    ***************************************************************/
    public function areMealParamsValid(array $mealData): bool {
        $calendar = new Calendar;
        $meal = new Meal;

        $requestedDay = $mealData['day'];
        $requestedMeal = str_replace('_', ' #', $mealData['meal']);
        
        $isDayValid = false;
        $isMealValid = false;
        
        foreach($calendar->getWeekDays() as $weekDay) {
            if ($requestedDay === $weekDay['english']) {
                $isDayValid = true;
            }
        }
        
        foreach($meal->getMealsTranslations() as $meal) {
            if ($requestedMeal === $meal['english']) {
                $isMealValid = true;
            }
        }
        
        return ($isDayValid && $isMealValid);
    }

    public function getEnglishWeekDay(string $date): string {
        $calendar = new Calendar;

        return $calendar->getWeekDays()[explode(' ', $date)[0]]['english'];
    }

    /******************************************************************
    Transforms a date into a string of weekday, day and month in french
    ******************************************************************/ 
    public function getFrenchDate(string $date): string {
        $calendar = new Calendar;

        $frenchDateWeekDay = $calendar->getWeekDays()[explode(' ', $date)[0]]['french'];
        $dateDay = explode(' ', $date)[1];
        $dateMonth = $calendar->getMonths()[explode(' ',  $date)[2] -1];
        
        return "{$frenchDateWeekDay} {$dateDay} {$dateMonth}";
    }
    
    /*****************************************************
    Transforms the list of meals in a specific subscriber
    program into an associated array containing each meals
    *****************************************************/ 
    public function getProgramMeals(int $subscriberId) {
        $program = new ProgramModel;
        
        $generatedMeals = $program->selectProgramMeals($subscriberId);
        
        return strlen($generatedMeals['meals_list']) ? explode(', ', $generatedMeals['meals_list']) : NULL;
    }
    
    /***********************************************************************************
    Builds an associative array containing the translation of meal in english and french
    ***********************************************************************************/
    public function buildWeekDaysTranslations(): array {
        $calendar = new Calendar;

        $orderedEnglishWeekDaysList = [];
        $orderedWeekIndex = [1, 2, 3, 4, 5, 6, 0];

        $weekDays = $calendar->getWeekDays();
        
        foreach($orderedWeekIndex as $key => $dayIndex) {
            $orderedEnglishWeekDaysList += [$key => ['english' => $weekDays[$dayIndex]['english'], 'french' => $weekDays[$dayIndex]['french']]];
        }
        
        return $orderedEnglishWeekDaysList;
    }
    
    public function buildProgramData(int $subscriberId) {
        $weekDays = $this->_getNextWeekDays();
        $mealsIndexes = $this->_getMealsIndexes($subscriberId);

        return $this->_buildProgramIngredients($subscriberId, $weekDays, $mealsIndexes);
    }
    
    private function _getFrenchWeekDay(string $date): string {
        $calendar = new Calendar;

        return $calendar->getWeekDays()[explode(' ', $date)[0]]['french'];
    }
    
    private function _getMealsIndexes(int $subscriberId) {
        $foodPlan = new FoodPlan;
        
        return $foodPlan->selectMealsIndexes($subscriberId);
    }
    
    /********************************************************************************************
    Builds an associative array containing ingredients for each meal and for each day of the week
    ********************************************************************************************/
    private function _buildProgramIngredients (int $subscriberId, array $weekDays, array $meals): array {
        $foodPlan = new FoodPlan;
        
        $programIngredients = [];
        
        foreach($weekDays as $weekDay) {
            $programIngredients += [$weekDay['englishWeekDay'] => []];
            
            foreach($meals as $meal) {
                $programIngredients[$weekDay['englishWeekDay']] += [$meal['meal_index'] => []];
                
                $mealIngredients = $foodPlan->selectMealIngredients($subscriberId, $weekDay['englishWeekDay'], $meal['meal_index']);
                
                foreach($mealIngredients as $ingredientKey => $ingredient) {
                    $programIngredients[$weekDay['englishWeekDay']][$meal['meal_index']] += [$ingredientKey => $ingredient];
                }
            }
        }
        
        return $programIngredients;
    }
    
    /************************************************************************************
    Builds an array of associative arrays containing next week days in english and french
    ************************************************************************************/
    private function _getNextWeekDays(): array {
        $timezone = new Timezone;
        $timezone->setTimezone();
        
        $lastMonday = new DateTime();
        $lastMonday->modify('last Monday');

        $program = new Program;
        
        $weekDays[] = [];
        
        $period = new DatePeriod (
            $lastMonday,
            new DateInterval('P1D'),
            6
        );
        
        foreach($period as $key => $day) {
            $date = $day->format('w d');
            $englishWeekDay = $program->getEnglishWeekDay($date);
            $frenchWeekDay = $this->_getFrenchWeekDay($date);
            $weekDays[$key] = [
                'englishWeekDay' => $englishWeekDay,
                'frenchFullDate' => $frenchWeekDay
            ];
        }
        
        return $weekDays;
    }
}