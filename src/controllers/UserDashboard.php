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
        echo $twig->render('user_panels/dashboard.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'tableau de bord',
            'appSection' => 'userPanels',
            'userPanelLandingPage' => 'tableau de bord',
            'dashboardMenuItems' => $this->_getDashboardMenuItems()
        ]);
    }

    private function _getDashboardMenuItems() {
        return $this->_dashboardMenuItems;
    }
}