<?php

namespace App\Domain\Controllers\AdminPanels;

final class IngredientsManagement extends AdminPanel {
    private const INGREDIENTS_MANAGEMENT_SCRIPTS = [
        'classes/KitchenManager.model',
        'classes/IngredientsManager.model',
        'ingredientsManagementApp'
    ];
    
    public function renderIngredientsManagementPage(object $twig) {
        echo $twig->render('admin_panels/ingredients-management.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => 'Gestion des ingrédients',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'apiToken' => $_SESSION['api_token'],
            'pageScripts' => $this->_getIngredientsManagementScripts()
        ]);
    }
    
    private function _getIngredientsManagementScripts() {
        return self::INGREDIENTS_MANAGEMENT_SCRIPTS;
    }
}