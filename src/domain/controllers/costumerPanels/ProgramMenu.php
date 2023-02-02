<?php

namespace App\Domain\Controllers\CostumerPanels;

use App\Domain\Models\Program as ProgramModel;

class ProgramMenu extends ProgramPanel {
    public function renderNutritionMenuPage(object $twig, object $meal, object $programFile, int $subscriberId): void {
        echo $twig->render('user_panels/nutrition.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'Nutrition',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord'],
            'nextDays' => $this->_getNextDates(),
            'meals' => $this->_getProgramMeals($subscriberId),
            'mealsTranslations' => $meal->getMealsTranslations(),
            'programFilePath' => $programFile->getProgramsFilePath($subscriberId)
        ]);
    }
    
    /*****************************************************
    Transforms the list of meals in a specific subscriber
    program into an associated array containing each meals
    *****************************************************/ 
    protected function _getProgramMeals(int $subscriberId) {
        $program = new ProgramModel;
        
        $generatedMeals = $program->selectProgramMeals($subscriberId);
        
        return strlen($generatedMeals['meals_list']) ? explode(', ', $generatedMeals['meals_list']) : NULL;
    }
}