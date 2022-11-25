<?php

namespace Dodie_Coaching\Controllers;

class AdminPanels extends Main {
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

    protected function _getAdminPanelsStyle() {
        return $this->_adminPanelsStyles;
    }
}