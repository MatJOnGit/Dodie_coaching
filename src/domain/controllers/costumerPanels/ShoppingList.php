<?php

namespace App\Domain\Controllers\CostumerPanels;

use App\Domain\Models\FoodPlan;

final class ShoppingList extends CostumerPanel {
    public function renderShoppingListPage(object $twig, int $subscriberId): void {
        echo $twig->render('user_panels/shopping-list.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'Liste de courses',
            'appSection' => 'userPanels',
            'prevPanel' => ['nutrition', 'Nutrition'],
            'subPanel' => 'Liste de courses',
            'shoppingList' => $this->_getShoppingList($subscriberId)
        ]);
    }
    
    private function _getShoppingList($subscriberId) {
        $foodPlan = new FoodPlan;
        
        return $foodPlan->selectWeeklyIngredients($subscriberId);
    }
}