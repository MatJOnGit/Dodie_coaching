<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Subscribers;

class AdminPanels extends Main {
    protected $_adminPanelsStyles = [
        'pages/admin-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];

    protected $_adminPanelsSubpanels = [
        'pending-applications' => 'Demandes en attente',
        'meetings' => 'Rendez-vous',
        'programs' => 'Programmes',
        'users' => 'Utilisateurs'
    ];
    
    protected $_routingURLs = [
        'dashboard' => 'index.php?page=admin-dashboard',
        'applicationsList' => 'index.php?page=applications-list',
        'subscribersList' => 'index.php?page=subscribers-list'
    ];
    
    private $_progressScripts = [
        'classes/ApplicationDetails.model',
        'applicationDetailsApp'
    ];

    public function isApplicationDetailsPageRequested(string $page): bool {
        return $page === 'application-details';
    }

    public function isApplicationsListPageRequested(string $page): bool {
        return $page === 'applications-list';
    }

    public function isDashboardPageRequested($page): bool {
        return $page === 'admin-dashboard';
    }
    
    public function isSubscriberIdAvailable(int $subscriberId) {
        $subscriber = new Subscribers;

        return $subscriber->selectSubscriberId($subscriberId);
    }

    public function isSubscriberNotesPageRequested(string $page) {
        return $page === 'subscriber-notes';
    }

    public function isSubscriberProfilePageRequested(string $page): bool {
        return $page === 'subscriber-profile';
    }

    public function isSubscriberProgramPageRequested(string $page): bool {
        return $page === 'subscriber-program';
    }

    public function isSubscribersListPageRequested(string $page): bool {
        return $page === 'subscribers-list';
    }

    protected function _getAdminPanelsStyle() {
        return $this->_adminPanelsStyles;
    }

    protected function _getProgressScripts(): array {
        return $this->_progressScripts;
    }
}