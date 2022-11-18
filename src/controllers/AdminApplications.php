<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Admin as AdminModel;

class AdminApplications extends AdminPanels {
    public function acceptApplication (int $applicationId, string $newApplicationStatus) {
        $admin = new AdminModel;

        return $admin->updateApplicationStatus($applicationId, $newApplicationStatus);
    }

    public function areParamsSet(array $params) {
        $areParamsSet = true;

        foreach ($params as $param) {
            if (!isset($_GET[$param])) {
                $areParamsSet = false;
            }
        }

        return $areParamsSet;
    }

    public function eraseApplication (int $applicationId) {
        $admin = new AdminModel;

        return $admin->deleteApplication($applicationId);
    }

    public function getApplicantData(int $applicationId) {
        $admin = new AdminModel;

        return $admin->selectApplicantData($applicationId);
    }

    public function getMessageType() {
        return empty($_POST['rejection-message']) ? 'default' : 'custom';
    }
  
    public function isApplicationAvailable(int $applicationId) {
        $admin = new AdminModel;

        return $admin->selectApplicationDate($applicationId);
    }

    public function isApplicationIdValid(int $applicationId) {
        $admin = new AdminModel;

        return $admin->selectApplicationDate($applicationId);
    }

    public function isApproveApplicationActionRequested(string $action): bool {
        return $action === 'approve-application';
    }

    public function isRejectApplicationActionRequested(string $action): bool {
        return $action === 'reject-application';
    }

    public function isRejectionMessageEmpty() {
        return empty($_POST['rejection-message']);
    }

    public function renderApplicationDetailsPage(object $twig, int $applicationId) {
        echo $twig->render('admin_panels/application-details.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Profil du demandeur',
            'appSection' => 'userPanels',
            'prevPanel' => ['applications-list', 'Liste des demandes'],
            'applicationDetails' => $this->_getApplicationsDetails($applicationId)[0],
            'pageScripts' => $this->_getProgressScripts()
        ]);
    }

    public function renderApplicationsListPage(object $twig) {
        echo $twig->render('admin_panels/applications-list.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Liste des demandes',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'applicationsHeaders' => $this->_getApplicationsHeaders()
        ]);
    }

    protected function _getApplicationsDetails(string $applicationId) {
        $admin = new AdminModel;

        return $admin->selectApplicationDetails($applicationId);
    }

    private function _getApplicationsHeaders() {
        $admin = new AdminModel;

        return $admin->selectApplicationsHeaders();
    }
}