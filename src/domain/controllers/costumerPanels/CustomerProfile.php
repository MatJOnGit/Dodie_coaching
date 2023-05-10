<?php

namespace App\Domain\Controllers\CostumerPanels;

final class CustomerProfile extends CostumerPanel {
    public function renderCustomerProfilePage(object $twig): void {
        echo $twig->render('user_panels/subscriptions.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'Profil utilisateur',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord']
        ]);
    }
}