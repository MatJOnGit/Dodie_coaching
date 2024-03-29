<?php

namespace App\Domain\Controllers\AdminPanels;

use App\Domain\Models\SubscriberData;

final class SubscriberProfile extends AdminPanel {
    public function renderSubscriberProfilePage(object $twig, int $subscriberId): void {
        echo $twig->render('admin_panels/subscriber-profile.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => "Profil abonné",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscribers-list', 'Liste des abonnés'],
            'subscriberDetails' => $this->_getSubscriberDetails($subscriberId)[0],
            'accountDetails' => $this->_getAccountDetails($subscriberId)
        ]);
    }
    
    private function _getAccountDetails(int $subscriberId) {
        $subscriberData = new SubscriberData;
        
        return $subscriberData->selectAccountDetails($subscriberId);
    }
    
    private function _getSubscriberDetails(int $subscriberId) {
        $subscriberData = new SubscriberData;
        
        return $subscriberData->selectSubscriberDetails($subscriberId);
    }
}