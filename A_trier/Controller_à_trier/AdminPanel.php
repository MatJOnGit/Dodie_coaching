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
}