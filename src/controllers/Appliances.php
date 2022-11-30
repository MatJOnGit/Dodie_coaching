<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Appliances as AppliancesModel;

class Appliances extends AdminPanels {
    private $_appliancesScripts = [
        'classes/UserPanels.model',
        'classes/ApplianceDetails.model',
        'applianceDetailsApp'
    ];
    
    public function acceptAppliance(string $applianceId, string $newApplianceStatus) {
        $appliance = new AppliancesModel;
        
        return $appliance->updateApplianceStatus($applianceId, $newApplianceStatus);
    }
    
    public function eraseAppliance(string $applicantId) {
        $appliance = new AppliancesModel;
        
        return $appliance->deleteAppliance($applicantId);
    }
    
    public function getApplicantData(string $applicantId) {
        $appliance = new AppliancesModel;
        
        return $appliance->selectApplicantData($applicantId);
    }
    
    public function getMessageType() {
        return empty($_POST['rejection-message']) ? 'default' : 'custom';
    }
    
    public function isApplianceAvailable(string $applianceId) {
        $appliance = new AppliancesModel;
        
        return $appliance->selectApplicantId($applianceId);
    }
    
    public function isApplianceIdValid(string $applicantId) {
        $appliance = new AppliancesModel;
        
        return $appliance->selectApplicantId($applicantId);
    }
    
    public function isRejectionMessageEmpty() {
        return empty($_POST['rejection-message']);
    }
    
    public function renderApplianceDetailsPage(object $twig, string $applianceId) {
        echo $twig->render('admin_panels/appliance-details.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Profil du demandeur',
            'appSection' => 'userPanels',
            'prevPanel' => ['appliances-list', 'Liste des demandes'],
            'applianceDetails' => $this->_getApplianceDetails($applianceId)[0],
            'pageScripts' => $this->_getAppliancesScripts()
        ]);
    }
    
    public function renderAppliancesListPage(object $twig) {
        echo $twig->render('admin_panels/appliances-list.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Liste des demandes',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'appliancesHeaders' => $this->_getAppliancesHeaders()
        ]);
    }
    
    private function _getApplianceDetails(string $applianceId) {
        $appliance = new AppliancesModel;

        return $appliance->selectApplianceDetails($applianceId);
    }
    
    private function _getAppliancesHeaders() {
        $appliance = new AppliancesModel;
        
        return $appliance->selectAppliancesHeaders();
    }
    
    private function _getAppliancesScripts(): array {
        return $this->_appliancesScripts;
    }
}