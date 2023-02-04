<?php

namespace App\Domain\Controllers\AdminPanels;

use App\Domain\Models\Appliance;

final class ApplianceDetails extends AdminPanel {
    private const APPLIANCE_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ApplianceManager.model',
        'applianceManagementApp'
    ];
    
    public function renderApplianceDetailsPage(object $twig, int $applianceId): void {
        echo $twig->render('admin_panels/appliance-details.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => 'Profil du demandeur',
            'appSection' => 'userPanels',
            'prevPanel' => ['appliances-list', 'Liste des demandes'],
            'applianceDetails' => $this->_getApplianceDetails($applianceId)[0],
            'pageScripts' => $this->_getAppliancesScripts()
        ]);
    }
    
    private function _getApplianceDetails(string $applianceId) {
        $appliance = new Appliance;
        
        return $appliance->selectApplianceDetails($applianceId);
    }
    
    private function _getAppliancesScripts(): array {
        return self::APPLIANCE_SCRIPTS;
    }
}