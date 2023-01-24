<?php

namespace Dodie_Coaching\Controllers;

class Subscriptions extends UserPanels {
    public function renderSubscriptionPage(object $twig): void {
        echo $twig->render('user_panels/subscriptions.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'abonnements',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord']
        ]);
    }
}