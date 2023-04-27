<?php

namespace App\Domain\Controllers\AdminPanels;

final class RecipesManagement extends AdminPanel {
    private const RECIPES_MANAGEMENT_SCRIPTS = [
        'classes/KitchenManager.model',
        'classes/RecipesManager.model',
        'classes/RecipeEditor.model.js',
        'recipesManagementApp'
    ];

    public function renderRecipesManagementPage(object $twig) {
        echo $twig->render('admin_panels/recipes-management.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => 'Gestion des recettes',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'apiToken' => $_SESSION['api_token'],
            'pageScripts' => $this->_getRecipesManagementScript()
        ]);
    }

    private function _getRecipesManagementScript() {
        return self::RECIPES_MANAGEMENT_SCRIPTS;
    }
}