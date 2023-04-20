<?php

namespace App\Domain\Controllers\AdminPanels;

final class IngredientsManagement extends AdminPanel {
    private const INGREDIENTS_MANAGEMENT_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/IngredientManager.model',
        'ingredientManagementApp'
    ];
    
    public function renderIngredientsManagementPage(object $twig, object $ingredient) {
        echo $twig->render('admin_panels/ingredients-management.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => 'Gestion des ingrédients',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'apiToken' => $_SESSION['api_token'],
            'ingredientsTypes' => $ingredient->selectIngredientsTypes(),
            'pageScripts' => $this->_getIngredientsManagementScripts()
        ]);
    }
    
    private function _getIngredientsManagementScripts() {
        return self::INGREDIENTS_MANAGEMENT_SCRIPTS;
    }
}