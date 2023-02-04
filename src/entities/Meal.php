<?php

namespace App\Entities;

use App\Domain\Models\Subscriber;

final class Meal {
    private const MEALS_TRANSLATIONS = [
        ['english' => 'breakfast', 'french' => 'petit-déjeuner'],
        ['english' => 'snack #1', 'french' => 'en-cas de 10h'],
        ['english' => 'lunch', 'french' => 'déjeuner'],
        ['english' => 'snack #2', 'french' => 'goûté'],
        ['english' => 'diner', 'french' => 'dîner']
    ];
    
    public function getMealsTranslations() {
        return self::MEALS_TRANSLATIONS;
    }

    /*********************************************************************
    Builds an array containing meals selected for the subscriber's program
    *********************************************************************/
    public function getCheckedMeals(): array {
        $checkedMeals = [];
        
        foreach($this->getMealsTranslations() as $mealKey => $knownMeal) {
            if (isset($_POST[`meal-` . $mealKey])) {
                array_push($checkedMeals, $knownMeal['english']);
            }
        }
        
        return $checkedMeals;
    }

    /**************************************************************************************
    Converts an array of meals into a string separated with commas, then set it in database
    **************************************************************************************/
    public function saveProgramMeals(int $subscriberId, array $mealsList) {
        $subscriber = new Subscriber;
        
        $meals = '';
        
        foreach($mealsList as $mealItem) {
            $meals = empty($meals) ? $mealItem : $meals . ', ' . $mealItem;
        }
        
        return $subscriber->updateSubscriberMeals($subscriberId, $meals);
    }
}

