<?php

namespace App\Domain\Controllers\AdminPanels;

use App\Domain\Models\Subscriber;

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
    
    private function _getSubscriberDetails(int $subscriberId) {
        $subscriber = new Subscriber;
        
        return $subscriber->selectSubscriberDetails($subscriberId);
    }
    
    private function _getAccountDetails(int $subscriberId) {
        $subscriber = new Subscriber;
        
        return $subscriber->selectAccountDetails($subscriberId);
    }
}