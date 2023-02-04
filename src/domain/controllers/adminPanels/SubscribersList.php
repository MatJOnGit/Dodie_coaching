<?php

namespace App\Domain\Controllers\AdminPanels;

use App\Domain\Models\Subscriber;

final class SubscribersList extends AdminPanel {
    public function renderSubscribersListPage(object $twig): void {
        echo $twig->render('admin_panels/subscribers-list.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => 'Liste des abonnés',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'subscribersHeaders' => $this->_getSubscribersHeaders()
        ]);
    }
    
    private function _getSubscribersHeaders() {
        $subscriber = new Subscriber;
        
        return $subscriber->selectSubscribersHeaders();
    }
}