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

    public function isDashboardPageRequested($page) {
        return $page === 'admin-dashboard';
    }

    public function isApplicationsListRequested(string $page): bool {
        return $page === 'applications-list';
    }

    public function renderApplicationsListPage(object $twig) {
        echo $twig->render('admin_panels/applications-list.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Liste des demandes',
            'appSection' => 'privatePanels',
            'subPanel' => 'Demandes en attente',
            'applicationsHeaders' => $this->_getApplicationsHeaders()
        ]);
    }

    protected function _getAdminPanelsSubpanels(string $page) {
        return $this->_adminPanelsSubpanels[$page];
    }

    protected function _getAdminPanelsStyle() {
        return $this->_adminPanelsStyles;
    }

    private function _getApplicationsHeaders() {
        $admin = new AdminModel;

        return $admin->selectApplicationsHeaders();
    }
}