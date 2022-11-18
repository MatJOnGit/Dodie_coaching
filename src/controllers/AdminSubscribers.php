<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Admin as AdminModel;

class AdminSubscribers extends AdminPanels {
    public function isSubscriberIdAvailable(int $subscriberId) {
        $admin = new AdminModel;

        return $admin->selectSubscriberId($subscriberId);
    }
  
    public function renderSubscriberProfilePage(object $twig, int $subscriberId) {
        echo $twig->render('admin_panels/subscriber-profile.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Programme d'abonné",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscribers-list', 'Liste des abonnés'],
            'subscriberDetails' => $this->_getSubscriberDetails($subscriberId)[0],
            'accountDetails' => $this->_getAccountDetails($subscriberId)
        ]);
    }

    public function renderSubscribersListPage(object $twig) {
        echo $twig->render('admin_panels/subscribers-list.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Liste des abonnés',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'subscribersHeaders' => $this->_getSubscribersHeaders()
        ]);
    }

    private function _getAccountDetails(int $subscriberId) {
        $admin = new AdminModel;

        return $admin->selectAccountDetails($subscriberId);
    }

    private function _getSubscriberDetails(int $subscriberId) {
        $admin = new AdminModel;

        return $admin->selectSubscriberDetails($subscriberId);
    }

    private function _getSubscribersHeaders() {
        $admin = new AdminModel;

        return $admin->selectSubscribersHeaders();
    }
}