<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Appliance as ApplianceModel;

class Appliance extends AdminPanel {
    private const APPLIANCE_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ApplianceManager.model',
        'applianceManagementApp'
    ];
    
    public function acceptAppliance(int $applianceId, string $newApplianceStatus) {
        $appliance = new ApplianceModel;
        
        return $appliance->updateApplianceStatus($applianceId, $newApplianceStatus);
    }
    
    public function eraseAppliance(int $applicantId) {
        $appliance = new ApplianceModel;
        
        return $appliance->deleteAppliance($applicantId);
    }
    
    public function getApplicantData(int $applicantId) {
        $appliance = new ApplianceModel;
        
        return $appliance->selectApplicantData($applicantId);
    }
    
    public function getMessageType(): string {
        return empty($_POST['rejection-message']) ? 'default' : 'custom';
    }
    
    public function isApplianceIdValid(int $applicantId) {
        $appliance = new ApplianceModel;
        
        return $appliance->selectApplicantId($applicantId);
    }
    
    public function isRejectionMessageEmpty(): bool {
        return empty($_POST['rejection-message']);
    }
    
    public function renderApplianceDetailsPage(object $twig, int $applianceId): void {
        echo $twig->render('admin_panels/appliance-details.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Profil du demandeur',
            'appSection' => 'userPanels',
            'prevPanel' => ['appliances-list', 'Liste des demandes'],
            'applianceDetails' => $this->_getApplianceDetails($applianceId)[0],
            'pageScripts' => $this->_getAppliancesScripts()
        ]);
    }
    
    public function renderAppliancesListPage(object $twig): void {
        echo $twig->render('admin_panels/appliances-list.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Liste des demandes',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'appliancesHeaders' => $this->_getAppliancesHeaders()
        ]);
    }
    
    private function _getApplianceDetails(string $applianceId) {
        $appliance = new ApplianceModel;
        
        return $appliance->selectApplianceDetails($applianceId);
    }
    
    private function _getAppliancesHeaders() {
        $appliance = new ApplianceModel;
        
        return $appliance->selectAppliancesHeaders();
    }
    
    private function _getAppliancesScripts(): array {
        return self::APPLIANCE_SCRIPTS;
    }
}