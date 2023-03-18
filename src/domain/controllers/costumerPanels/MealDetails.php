<?php

namespace App\Domain\Controllers\CostumerPanels;

final class MealDetails extends CostumerPanel {
    public function renderMealDetailsPage(object $twig, object $program, object $meal, array $mealParams, $subscriberId): void {
        echo $twig->render('user_panels/meal-details.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'Ingrédients',
            'appSection' => 'userPanels',
            'prevPanel' => ['nutrition', 'Nutrition'],
            'subPanel' => 'Composition du repas',
            'mealParams' => $program->getTranslatedMealParams($meal, $mealParams),
            'mealItems' => $program->getMealDetails($mealParams, $subscriberId)
        ]);
    }
}