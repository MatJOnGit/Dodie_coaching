<?php

namespace Dodie_Coaching\Controllers;

class AdminDashboard extends AdminPanels {
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

    public function renderAdminDashboardPage(object $twig) {
        echo $twig->render('admin_panels/dashboard.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Tableau de bord',
            'appSection' => 'adminPanels',
            'userPanelLandingPage' => 'Tableau de bord',
            'dashboardMenuItems' => $this->_getDashboardMenuItems()
        ]);
    }

    private function _getDashboardMenuItems() {
        return $this->_dashboardMenuItems;
    }
}