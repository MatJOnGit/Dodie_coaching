<?php

namespace App\Entities;

use App\Domain\Models\SubscriberData;
use App\Domain\Models\FoodPlan;
use DateTime, DatePeriod, DateInterval;

final class Program {
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
    
    public function buildMealNutrientsData(string $day, string $meal, int $subscriberId) {
        $foodPlan = new FoodPlan;
        
        return $foodPlan->selectMealIntakes($meal, $day, 'confirmed', $subscriberId);
    }
    
    public function buildProgramData(int $subscriberId) {
        $weekDays = $this->_getNextWeekDays();
        $confirmedMealsData = $this->_getConfirmedMealsData($subscriberId);
        
        return $this->_buildProgramIngredients($subscriberId, $weekDays, $confirmedMealsData);
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
    
    /*************************************************************************************
    Converts url parameters into a associative array containing the day and meal requested
    *************************************************************************************/
    public function getMealParams(): array {
        $meal = htmlspecialchars($_GET['meal']);
        $mealParams = [
            'day' => false,
            'meal' => false
        ];
        
        if (is_string($meal) && strpos($meal, '-')) {
            $mealParams['day'] = explode('-', htmlspecialchars($_GET['meal']))[0];
            $mealParams['meal'] = str_replace('_', ' #', explode('-', htmlspecialchars($_GET['meal']))[1]);
        }
        
        return $mealParams;
    }
    
    /*****************************************************************************
    Builds an array of associative arrays containing the 7 days to come (including
    the actual day) with the language as key and the formated date as value
    *****************************************************************************/
    public function getNextDates(): array {
        $timezone = new Timezone;
        $timezone->setTimezone();
        
        $program = new Program;
        
        $nextDates[] = [];
        
        $period = new DatePeriod (
            new DateTime(),
            new DateInterval('P1D'),
            6
        );
        
        foreach ($period as $key => $day) {
            $date = $day->format('w d n Y H:i:s');
            $englishWeekDay = $program->_getEnglishWeekDay($date);
            $frenchFullDate = $program->_getFrenchDate($date);
            $nextDates[$key] = [
                'englishWeekDay' => $englishWeekDay,
                'frenchFullDate' => $frenchFullDate
            ];
        };
        
        return $nextDates;
    }
    
    /*****************************************************
    Transforms the list of meals in a specific subscriber
    program into an associated array containing each meals
    *****************************************************/ 
    public function getProgramMeals(int $subscriberId) {
        $subscriberData = new SubscriberData;
        
        $generatedMeals = $subscriberData->selectProgramMeals($subscriberId);
        
        return strlen($generatedMeals['meals_list']) ? explode(', ', $generatedMeals['meals_list']) : NULL;
    }
    
    public function getRequest(): string {
        return htmlspecialchars($_GET['request']);
    }
    
    public function getMealDetails(array $mealParams, int $subscriberId) {
        $foodPlan = new FoodPlan;
        
        $day = $mealParams['day'];
        $meal = str_replace('_', ' #', $mealParams['meal']);
        
        return $foodPlan->selectMealDetails($day, $meal, $subscriberId);
    }
    
    public function getLatestMealStatus(string $meal, string $day, int $subscriberId) {
        $foodPlan = new FoodPlan;
        
        $meal = str_replace('_', ' #', $meal);
        
        $mealPendingIntakes = $foodPlan->selectMealIntakes($meal, $day, 'pending', $subscriberId);

        return $mealPendingIntakes ? 'pending' : 'confirmed';
    }
    
    public function isMealRequested(): bool {
        return (isset($_GET['meal']) && !isset($_GET['request']));
    }
    
    public function isMenuRequested(): bool {
        return (!isset($_GET['meal']) && !isset($_GET['request']));
    }
    
    public function isRequestSet(): bool {
        return (!isset($_GET['day']) && !isset($_GET['meal']) && isset($_GET['request']));
    }
    
    public function isShoppingListRequested(string $request): bool {
        return $request === 'shopping-list';
    }
    
    /********************************************************************************************
    Builds an associative array containing ingredients for each meal and for each day of the week
    ********************************************************************************************/

    private function _buildProgramIngredients (int $subscriberId, array $weekDays, array $confirmedMealsData): array {
        $foodPlan = new FoodPlan;
        
        $programIngredients = [];
        
        foreach($weekDays as $weekDay) {
            $programIngredients += [$weekDay['englishWeekDay'] => []];
            
            foreach($confirmedMealsData as $confirmedMealData) {
                $programIngredients[$weekDay['englishWeekDay']] += [$confirmedMealData['meal_index'] => []];
                
                $mealIngredients = $foodPlan->selectMealDetails($weekDay['englishWeekDay'], $confirmedMealData['meal_name'], $subscriberId);
                
                foreach($mealIngredients as $ingredientKey => $ingredient) {
                    $programIngredients[$weekDay['englishWeekDay']][$confirmedMealData['meal_index']] += [$ingredientKey => $ingredient];
                };
            };
        };
        
        return $programIngredients;
    }
    
    private function _getEnglishWeekDay(string $date): string {
        $calendar = new Calendar;
        
        return $calendar->getWeekDays()[explode(' ', $date)[0]]['english'];
    }
    
    /******************************************************************
    Transforms a date into a string of weekday, day and month in french
    ******************************************************************/ 
    private function _getFrenchDate(string $date): string {
        $calendar = new Calendar;
        
        $frenchDateWeekDay = $this->_getFrenchWeekDay($date);
        $dateDay = explode(' ', $date)[1];
        $dateMonth = $calendar->getMonths()[explode(' ',  $date)[2] -1];
        
        return "{$frenchDateWeekDay} {$dateDay} {$dateMonth}";
    }
    
    private function _getFrenchWeekDay(string $date): string {
        $calendar = new Calendar;
        
        return $calendar->getWeekDays()[explode(' ', $date)[0]]['french'];
    }
    
    private function _getConfirmedMealsData(int $subscriberId) {
        $foodPlan = new FoodPlan;
        
        return $foodPlan->selectConfirmedMeals($subscriberId);
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
            $englishWeekDay = $program->_getEnglishWeekDay($date);
            $frenchWeekDay = $this->_getFrenchWeekDay($date);
            $weekDays[$key] = [
                'englishWeekDay' => $englishWeekDay,
                'frenchFullDate' => $frenchWeekDay
            ];
        }
        
        return $weekDays;
    }
    
    /*************************************************************************************
    Builds an associative array containing the french date and the translation of the meal
    *************************************************************************************/
    public function getTranslatedMealParams(object $meal, array $mealParams): array {
        $program = new Program;
        
        foreach($program->getNextDates() as $day) {
            if ($mealParams['day'] === $day['englishWeekDay']) {
                $mealParams['day'] = $day['frenchFullDate'];
            }
        }
        
        foreach($meal->getMealsTranslations() as $meal) {
            if (str_replace('_', ' #', $mealParams['meal']) === $meal['english']) {
                $mealParams['meal'] = $meal['french'];
            }
        }
        
        return $mealParams;
    }
}