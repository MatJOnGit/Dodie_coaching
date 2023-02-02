<?php

namespace App\Domain\Controllers\CostumerPanels;

use App\Domain\Models\Program;

class MealDetails extends ProgramPanel {
    public function renderMealDetailsPage(object $twig, object $meal, array $mealData): void {
        echo $twig->render('user_panels/meal-details.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'Ingrédients',
            'appSection' => 'userPanels',
            'prevPanel' => ['nutrition', 'Nutrition'],
            'subPanel' => 'Composition du repas',
            'meal' => $this->_getTranslatedMealData($meal, $mealData),
            'ingredients' => $this->_getMealDetails($mealData)
        ]);
    }

    /*************************************************************************************
    Builds an associative array containing the french date and the translation of the meal
    *************************************************************************************/
    private function _getTranslatedMealData(object $meal, array $mealData): array {
        foreach($this->_getNextDates() as $day) {
            if ($mealData['day'] === $day['englishWeekDay']) {
                $mealData['day'] = $day['frenchFullDate'];
            }
        }
        
        foreach($meal->getMealsTranslations() as $meal) {
            if (str_replace('_', ' #', $mealData['meal']) === $meal['english']) {
                $mealData['meal'] = $meal['french'];
            }
        }
        
        return $mealData;
    }
    
    private function _getMealDetails(array $mealData) {
        $program = new Program;
        
        $day = $mealData['day'];
        $meal = str_replace('_', ' #', $mealData['meal']);
        
        return $program->selectMealDetails($day, $meal, $_SESSION['email']);
    }
}