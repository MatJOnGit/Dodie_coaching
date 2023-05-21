<?php

namespace App\Domain\Controllers\CostumerPanels;

final class NutritionMenu extends CostumerPanel {

    public function renderNutritionMenuPage(object $twig, object $program, object $meal, object $programFile, int $subscriberId): void {
        echo $twig->render('user_panels/nutrition.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'Nutrition',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord'],
            'nextDays' => $program->getNextDates(),
            'meals' => $program->getProgramMeals($subscriberId),
            'mealsTranslations' => $meal->getMealsTranslations(),
            'programFilePath' => $programFile->getProgramsFilePath($subscriberId)
        ]);
    }
}