<?php

namespace App\Domain\Controllers\AdminPanels;

final class SubscriberMeal extends AdminPanel {
    private const SUBSCRIBER_MEAL_SCRIPT = [
        'classes/ElementFader.model',
        'classes/MealNutrientsDisplayer.model',
        'mealManagementApp'
    ];

    public function renderSubscriberMeal(object $twig, object $subscriber, object $program, object $meal, int $subscriberId, array $mealParams, string $latestMealStatus) {
        echo $twig->render('admin_panels/subscriber-meal.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => "Informations sur le repas",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-program&id=' . $subscriberId, 'Programme'],
            'latestMealStatus' => $latestMealStatus,
            'subscriberHeaders' => $subscriber->getSubscriberHeaders($subscriberId),
            'mealNutrientsData' => $program->buildMealNutrientsData($mealParams['day'], $mealParams['meal'], $subscriberId),
            'mealParams' => $program->getTranslatedMealParams($meal, $mealParams),
            'mealDetails' => $program->getMealDetails($mealParams, $subscriberId),
            'pageScripts' => $this->_getSubscriberMealScripts()
        ]);
    }
    
    private function _getSubscriberMealScripts(): array {
        return self::SUBSCRIBER_MEAL_SCRIPT;
    }
}