<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Admin as AdminModel;

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

    protected $_routingURLs = [
        'dashboard' => 'index.php?page=admin-dashboard',
        'applicationsList' => 'index.php?page=applications-list'
    ];
    
    private $_progressScripts = [
        'classes/ApplicationDetails.model',
        'applicationDetailsApp'
    ];

    public function getApplicationId(): string {
        return htmlspecialchars($_GET['id']);
    }
  
    public function isApplicationAvailable(string $applicationId) {
        $admin = new AdminModel;

        return $admin->selectApplicationDate($applicationId);
    }

    public function isApplicationDetailsRequested(string $page): bool {
        return $page === 'application-details';
    }

    public function isApplicationsListRequested(string $page): bool {
        return $page === 'applications-list';
    }

    public function isDashboardPageRequested($page) {
        return $page === 'admin-dashboard';
    }

    public function isRejectApplicationActionRequested(string $action): bool {
        return $action === 'reject-application';
    }

    public function isRequestedApplicationIdSet() : bool {
        return isset($_GET['id']);
    }

    protected function _getAdminPanelsSubpanels(string $page) {
        return $this->_adminPanelsSubpanels[$page];
    }

    protected function _getAdminPanelsStyle() {
        return $this->_adminPanelsStyles;
    }

    protected function _getApplicationsHeaders() {
        $admin = new AdminModel;

        return $admin->selectApplicationsHeaders();
    }

    protected function _getApplicationsDetails(string $applicationId) {
        $admin = new AdminModel;

        return $admin->selectApplicationDetails($applicationId);
    }

    protected function _getProgressScripts(): array {
        return $this->_progressScripts;
    }
}