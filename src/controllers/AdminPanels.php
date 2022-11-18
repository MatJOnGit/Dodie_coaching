<?php

namespace Dodie_Coaching\Controllers;

class AdminPanels extends Main {
    protected $_adminPanelsStyles = [
        'pages/admin-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];

    protected $_adminPanelsSubpanels = [
        'pending-applications' => 'Demandes en attente',
        'meetings' => 'Rendez-vous',
        'programs' => 'Programmes',
        'users' => 'Utilisateurs'
    ];
    
    protected $_routingURLs = [
        'dashboard' => 'index.php?page=admin-dashboard',
        'applicationsList' => 'index.php?page=applications-list',
        'subscribersList' => 'index.php?page=subscribers-list'
    ];
    
    private $_progressScripts = [
        'classes/ApplicationDetails.model',
        'applicationDetailsApp'
    ];

    public function areParamSet(array $params): bool {
        $areParamSet = true;

        foreach ($params as $param) {
            if (!isset($_GET[$param])) {
                $areParamSet = false;
            }
        }

        return $areParamSet;
    }

    public function isApplicationDetailsRequested(string $page): bool {
        return $page === 'application-details';
    }

    public function isApplicationsListRequested(string $page): bool {
        return $page === 'applications-list';
    }

    public function isDashboardPageRequested($page): bool {
        return $page === 'admin-dashboard';
    }

    public function isSubscriberProfileRequested(string $page): bool {
        return $page === 'subscriber-profile';
    }

    public function isSubscribersListRequested(string $page): bool {
        return $page === 'subscribers-list';
    }

    protected function _getAdminPanelsStyle() {
        return $this->_adminPanelsStyles;
    }

    protected function _getAdminPanelsSubpanels(string $page) {
        return $this->_adminPanelsSubpanels[$page];
    }

    protected function _getProgressScripts(): array {
        return $this->_progressScripts;
    }
}