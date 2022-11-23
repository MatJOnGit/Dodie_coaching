<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Applications;

class AdminApplications extends AdminPanels {
    public function acceptApplication (int $applicationId, string $newApplicationStatus) {
        $application = new Applications;

        return $application->updateApplicationStatus($applicationId, $newApplicationStatus);
    }

    public function eraseApplication (int $applicantId) {
        $application = new Applications;

        return $application->deleteApplication($applicantId);
    }

    public function getApplicantData(int $applicantId) {
        $application = new Applications;

        return $application->selectApplicantData($applicantId);
    }

    public function getMessageType() {
        return empty($_POST['rejection-message']) ? 'default' : 'custom';
    }
  
    public function isApplicationAvailable(int $applicationId) {
        $application = new Applications;

        return $application->selectApplicantId($applicationId);
    }

    public function isApplicationIdValid(int $applicantId) {
        $application = new Applications;

        return $application->selectApplicantId($applicantId);
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
            'applicationDetails' => $this->_getApplicationDetails($applicationId)[0],
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

    protected function _getApplicationDetails(string $applicationId) {
        $application = new Applications;

        return $application->selectApplicationDetails($applicationId);
    }

    private function _getApplicationsHeaders() {
        $application = new Applications;

        return $application->selectApplicationsHeaders();
    }
}