<?php

namespace App\Domain\Controllers\AdminPanels;

use App\Domain\Models\Appliance;

final class AppliancesList extends AdminPanel {
    public function renderAppliancesListPage(object $twig): void {
        echo $twig->render('admin_panels/appliances-list.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => 'Liste des demandes',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'appliancesHeaders' => $this->_getAppliancesHeaders()
        ]);
    }
    
    private function _getAppliancesHeaders() {
        $appliance = new Appliance;
        
        return $appliance->selectAppliancesHeaders();
    }
}