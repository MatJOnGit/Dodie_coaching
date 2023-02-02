<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Account;
use Dodie_Coaching\Models\Nutrition as NutritionModel;
use Dodie_Coaching\Models\ProgramFile;
use DatePeriod, DateTime, DateInterval;

class Nutrition extends UserPanel {    
    public function renderMealDetailsPage(object $twig, array $mealData): void {
        echo $twig->render('user_panels/meal-details.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'Ingrédients',
            'appSection' => 'userPanels',
            'prevPanel' => ['nutrition', 'Nutrition'],
            'subPanel' => 'Composition du repas',
            'meal' => $this->getTranslatedMealData($mealData),
            'ingredients' => $this->_getMealDetails($mealData)
        ]);
    }
    
    private function _getMealDetails(array $mealData) {
        $nutrition = new NutritionModel;
        
        $day = $mealData['day'];
        $meal = str_replace('_', ' #', $mealData['meal']);
        
        return $nutrition->selectMealDetails($day, $meal, $_SESSION['email']);
    }
    
    private function _getShoppingList() {
        $nutrition = new NutritionModel;
        
        return $nutrition->selectMealsIngredients($_SESSION['email']);
    }
    
    
}