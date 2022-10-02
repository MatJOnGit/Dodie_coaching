<?php

namespace Dodie_Coaching\Controllers;

class UserDashboard extends UserPanels {
    private $_dashboardMenuItems = [
        'nutrition' => [
            'frenchTitle' => 'Programme nutritionnel',
            'iconClass' => 'bowl-food',
            'link' => 'nutrition'
        ],
        'progress' => [
            'frenchTitle' => 'Progression',
            'iconClass' => 'person-running',
            'link' => 'progress'
        ],
        'meetings' => [
            'frenchTitle' => 'Rendez-vous',
            'iconClass' => 'calendar',
            'link' => 'meetings'
        ],
        'subscription' => [
            'frenchTitle' => 'Abonnement',
            'iconClass' => 'star',
            'link' => 'subscription'
        ]
    ];

    public function renderUserDashboardPage(object $twig) {
        // Définition de userPanels à revoir
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getUserPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'userPanels' => $this->_getUserPanels()]);
        echo $twig->render('user_panels/dashboard.html.twig', ['dashboardMenuItems' => $this->_getDashboardMenu()]);
        echo $twig->render('components/footer.html.twig');
    }

    private function _getDashboardMenu() {
        return $this->_dashboardMenuItems;
    }
}