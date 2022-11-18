<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Admin as AdminModel;

class AdminSubscribers extends AdminPanels {
    public function renderSubscribersListPage(object $twig) {
        echo $twig->render('admin_panels/subscribers-list.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Liste des abonnés',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'subscribersHeaders' => $this->_getSubscribersHeaders()
        ]);
    }

    private function _getSubscribersHeaders() {
        $admin = new AdminModel;

        return $admin->selectSubscriberssHeaders();
    }
}