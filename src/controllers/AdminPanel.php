<?php

namespace Dodie_Coaching\Controllers;

class AdminPanel extends Main {
    protected $_routingURLs = [
        'meetingsManagement' => 'index.php?page=meetings-management'
    ];
    
    protected $_adminPanelsSubpanels = [
        'pending-appliances' => 'Demandes en attente',
        'meetings' => 'Rendez-vous',
        'programs' => 'Programmes',
        'users' => 'Utilisateurs'
    ];
    
    private $_adminPanelsStyles = [
        'pages/admin-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];
    
    protected function _getAdminPanelsStyle(): array {
        return $this->_adminPanelsStyles;
    }
}