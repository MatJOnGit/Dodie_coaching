<?php

namespace App\Domain\Controllers\CostumerPanels;

use App\Domain\Models\Program as ProgramModel;

final class ShoppingList extends CostumerPanel {
    public function renderShoppingListPage(object $twig): void {
        echo $twig->render('user_panels/shopping-list.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'Liste de courses',
            'appSection' => 'userPanels',
            'prevPanel' => ['nutrition', 'Nutrition'],
            'subPanel' => 'Liste de courses',
            'shoppingList' => $this->_getShoppingList()
        ]);
    }
    
    private function _getShoppingList() {
        $program = new ProgramModel;
        
        return $program->selectMealsIngredients($_SESSION['email']);
    }
}