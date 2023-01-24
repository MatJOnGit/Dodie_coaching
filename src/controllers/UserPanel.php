<?php

namespace Dodie_Coaching\Controllers;

class UserPanel extends Main {
    protected $_routingURLs = [
        'dashboard' => 'index.php?page=dashboard',
        'meetingsBooking' => 'index.php?page=meetings-booking',
        'nutrition' => 'index.php?page=nutrition',
        'progress' => 'index.php?page=progress',
        'subscription' => 'index.php?page=subscription',
    ];
    
    protected $_userPanelsStyles = [
        'pages/user-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];
    
    protected $_userPanelsSubpanels = [
        'nutrition' => 'Programme nutritionnel',
        'progress' => 'Progression',
        'meetings' => 'Rendez-vous',
        'subscriptions' => 'Abonnement'
    ];
    
    private $_userPanels = ['dashboard', 'nutrition', 'progress', 'meetings', 'subscription'];
    
    protected function _getUserPanels(): array {
        return $this->_userPanels;
    }
    
    protected function _getUserPanelsStyles(): array {
        return $this->_userPanelsStyles;
    }
    
    protected function _getUserPanelsSubpanels(string $page): string {
        return $this->_userPanelsSubpanels[$page];
    }
}