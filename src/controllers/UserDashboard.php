<?php

namespace Dodie_Coaching\Controllers;

class UserDashboard extends UserPanels {
    private $_dashboardMenuItems = [
        'nutrition' => [
            'icon' => 'fa-utensils',
            'frenchTitle' => 'Programme nutritionnel',
            'link' => 'nutrition'
        ],
        'progress' => [
            'icon' => 'fa-chart-line',
            'frenchTitle' => 'Progression',
            'link' => 'progress'
        ],
        'meetings-booking' => [
            'icon' => 'fa-calendar-days',
            'frenchTitle' => 'Rendez-vous',
            'link' => 'meetings-booking'
        ],
        'subscription' => [
            'icon' => 'fa-star',
            'frenchTitle' => 'Abonnement',
            'link' => 'subscription'
        ]
    ];

    public function renderUserDashboardPage(object $twig) {
        echo $twig->render('user_panels/dashboard.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'tableau de bord',
            'appSection' => 'userPanels',
            'dashboardMenuItems' => $this->_getDashboardMenuItems()
        ]);
    }

    private function _getDashboardMenuItems() {
        return $this->_dashboardMenuItems;
    }
}