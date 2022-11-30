<?php

namespace Dodie_Coaching\Controllers;

class UserPanels extends Main {
    protected $_routingURLs = [
        'dashboard' => 'index.php?page=dashboard',
        'progress' => 'index.php?page=progress',
        'nutrition' => 'index.php?page=nutrition',
        'meetingsBooking' => 'index.php?page=meetings-booking',
        'subscription' => 'index.php?page=subscription'
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

    private $_months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    private $_userPanels = ['dashboard', 'nutrition', 'progress', 'meetings', 'subscription'];

    protected function _getMonths() {
        return $this->_months;
    }

    protected function _getUserPanels() {
        return $this->_userPanels;
    }

    protected function _getUserPanelsStyles() {
        return $this->_userPanelsStyles;
    }

    protected function _getUserPanelsSubpanels(string $page) {
        return $this->_userPanelsSubpanels[$page];
    }
}