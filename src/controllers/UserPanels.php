<?php

namespace Dodie_Coaching\Controllers;

class UserPanels extends Main {
    private $_months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    protected $_routingURLs = [
        'dashboard' => 'index.php?page=dashboard',
        'progress' => 'index.php?page=progress',
        'nutrition' => 'index.php?page=nutrition',
        'meetings' => 'index.php?page=meetings',
        'subscription' => 'index.php?page=subscription'
    ];

    private $_userPanels = ['dashboard', 'nutrition', 'progress', 'meetings', 'subscription'];
    
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

    public function isMeetingsPageRequested($page): bool {
        return $page === 'meetings';
    }

    public function isNutritionPageRequested($page): bool {
        return $page === 'nutrition';
    }

    public function isProgressPageRequested($page): bool {
        return $page === 'progress';
    }

    public function isSubscriptionPageRequested($page): bool {
        return $page === 'subscription';
    }

    public function isDashboardPageRequested($page): bool {
        return $page === 'dashboard';
    }

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