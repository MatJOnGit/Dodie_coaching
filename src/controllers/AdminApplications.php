<?php

namespace Dodie_Coaching\Controllers;

class AdminApplications extends AdminPanels {
    public function renderApplicationDetailsPage(object $twig, string $applicationId) {
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
}