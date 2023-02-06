<?php

namespace App\Domain\Controllers\AdminPanels;

final class MealEditing extends AdminPanel {
    public function renderMealEdition(object $twig, object $subscriber, object $program, object $meal, array $mealData, int $subscriberId) {
        echo $twig->render('admin_panels/meal-editing.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => "Edition de menu",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-program&id=' . $subscriberId, 'Programme'],
            'subscriberHeaders' => $subscriber->getSubscriberHeaders($subscriberId),
            'mealData' => $mealData,
            'weekDaysTranslations' => $program->buildWeekDaysTranslations(),
            'mealsTranslations' => $meal->getMealsTranslations()
        ]);
    }
}