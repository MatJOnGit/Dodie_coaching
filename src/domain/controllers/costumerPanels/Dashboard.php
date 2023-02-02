<?php

namespace App\Domain\Controllers\CostumerPanels;

class Dashboard extends CostumerPanel {
    private const DASHBOARD_MENU_ITEMS = [
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
    
    public function renderCostumerDashboardPage(object $twig): void {
        echo $twig->render('user_panels/dashboard.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'tableau de bord',
            'appSection' => 'userPanels',
            'dashboardMenuItems' => $this->_getDashboardMenuItems()
        ]);
    }
    
    private function _getDashboardMenuItems(): array {
        return self::DASHBOARD_MENU_ITEMS;
    }
}