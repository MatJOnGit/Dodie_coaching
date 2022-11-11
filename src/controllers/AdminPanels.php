<?php

namespace Dodie_Coaching\Controllers;

class AdminPanels extends Main {
    protected $_adminPanelsSubpanels = [
        'pending-applications' => 'Demandes en attente',
        'meetings' => 'Rendez-vous',
        'programs' => 'Programmes',
        'users' => 'Utilisateurs'
    ];
    
    protected $_adminPanelsStyles = [
        'pages/admin-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];

    public function isDashboardPageRequested($page) {
        return $page === 'admin-dashboard';
    }

    protected function _getAdminPanelsSubpanels(string $page) {
        return $this->_adminPanelsSubpanels[$page];
    }

    protected function _getAdminPanelsStyle() {
        return $this->_adminPanelsStyles;
    }
}