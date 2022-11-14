<?php

namespace Dodie_Coaching\Controllers;

class UserDashboard extends UserPanels {
    private $_dashboardMenuItems = [
        'nutrition' => [
            'frenchTitle' => 'Programme nutritionnel',
            'link' => 'nutrition'
        ],
        'progress' => [
            'frenchTitle' => 'Progression',
            'link' => 'progress'
        ],
        'meetings' => [
            'frenchTitle' => 'Rendez-vous',
            'link' => 'meetings'
        ],
        'subscription' => [
            'frenchTitle' => 'Abonnement',
            'link' => 'subscription'
        ]
    ];

    public function renderUserDashboardPage(object $twig) {
        echo $twig->render('user_panels/dashboard.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'tableau de bord',
            'appSection' => 'privatePanels',
            'dashboardMenuItems' => $this->_getDashboardMenuItems()
        ]);
    }

    private function _getDashboardMenuItems() {
        return $this->_dashboardMenuItems;
    }
}