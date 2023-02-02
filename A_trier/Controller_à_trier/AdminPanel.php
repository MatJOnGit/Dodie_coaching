<?php

namespace Dodie_Coaching\Controllers;

class AdminPanel extends Main {
    protected const ROUTING_URLS = [
        'meetingsManagement' => 'index.php?page=meetings-management'
    ];
    
    protected const ADMIN_PANELS_SUBPANELS = [
        'pending-appliances' => 'Demandes en attente',
        'meetings' => 'Rendez-vous',
        'programs' => 'Programmes',
        'users' => 'Utilisateurs'
    ];
    
    private const ADMIN_PANELS_STYLES = [
        'pages/admin-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];
    
    protected function _getAdminPanelsStyle(): array {
        return self::ADMIN_PANELS_STYLES;
    }
}