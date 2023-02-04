<?php

namespace App\Domain\Controllers\CostumerPanels;

final class Subscription extends CostumerPanel {
    public function renderSubscriptionPage(object $twig): void {
        echo $twig->render('user_panels/subscriptions.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'abonnements',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord']
        ]);
    }
}