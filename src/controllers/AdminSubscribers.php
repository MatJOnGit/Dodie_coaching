<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Subscribers;

class AdminSubscribers extends AdminPanels {
    public function renderSubscriberProfilePage(object $twig, int $subscriberId) {
        echo $twig->render('admin_panels/subscriber-profile.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Profil abonné",
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

    public function getSubscriberData(int $subscriberId) {
        $subscriber = new Subscribers;

        return $subscriber->selectSubscriberData($subscriberId);
    }

    public function getMessageType() {
        return empty($_POST['rejection-message']) ? 'default' : 'custom';
    }

    public function isSubscriberIdValid(int $subscriberId) {
        $subscriber = new Subscribers;

        return $subscriber->selectSubscriberId($subscriberId);
    }

    protected function _getSubscriberHeaders(int $subscriberId) {
        $subscriber = new Subscribers;

        return $subscriber->selectSubscriberHeader($subscriberId);
    }

    private function _getAccountDetails(int $subscriberId) {
        $subscriber = new Subscribers;

        return $subscriber->selectAccountDetails($subscriberId);
    }

    private function _getSubscriberDetails(int $subscriberId) {
        $subscriber = new Subscribers;

        return $subscriber->selectSubscriberDetails($subscriberId);
    }

    private function _getSubscribersHeaders() {
        $subscriber = new Subscribers;

        return $subscriber->selectSubscribersHeaders();
    }
}