<?php

namespace App\Entities;

use App\Entities\Calendar;
use App\Entities\Meals;

class Program {
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
}