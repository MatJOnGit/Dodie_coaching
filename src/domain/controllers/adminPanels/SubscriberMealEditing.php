<?php

namespace App\Domain\Controllers\AdminPanels;

final class SubscriberMealEditing extends AdminPanel {
    private const SUBSCRIBER_MEAL_EDITING_SCRIPTS = [
        'classes/ElementFader.model'
    ];

    public function renderSubscriberMealEditing(object $twig, object $subscriber, object $program, object $meal, int $subscriberId, string $mealParam, array $mealParsedParams) {
        echo $twig->render('admin_panels/subscriber-meal-editing.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => "Edition",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-meal&id=' . $subscriberId . '&meal=' . $mealParam, 'Information sur le repas'],
            'subscriberHeaders' => $subscriber->getSubscriberHeaders($subscriberId),
            'mealNutrientsData' => $program->getConfirmedIngredientsData($mealParsedParams['day'], $mealParsedParams['meal'], $subscriberId),
            'subscriberId' => $subscriberId,
            'mealParams' => $program->getTranslatedMealParams($meal, $mealParsedParams),
            'mealDetails' => $program->getMealDetails($mealParsedParams, $subscriberId),
            'pageScripts' => $this->_getSubscriberMealEditingScripts()
        ]);
    }
    
    private function _getSubscriberMealEditingScripts(): array {
        return self::SUBSCRIBER_MEAL_EDITING_SCRIPTS;
    }
}