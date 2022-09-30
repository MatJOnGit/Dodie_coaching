<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\MemberPanelsManager as MemberPanelsManager;

class MemberPanelsController {
    private $_memberPanels = ['get-to-know-you', 'dashboard', 'nutrition', 'progress', 'meetings', 'subscription'];

    private $_memberPanelsStyles = [
        'pages/member-panels',
        'components/header',
        'components/form',
        'components/buttons',
        'components/footer'
    ];

    private $_memberPanelsSubpanels = [
        'nutrition' => 'Programme nutritionnel',
        'progress' => 'Progression',
        'meetings' => 'Rendez-vous',
        'subscriptions' => 'Abonnement'
    ];

    private $_memberPanelsURLs = [
        'login' => 'index.php?page=login',
        'dashboard' => 'index.php?page=dashboard',
        'progress' => 'index.php?page=progress',
        'getToKnowYou' => 'index.php?page=get-to-know-you',
        'nutrition' => 'index.php?page=nutrition',
        'meetings' => 'index.php?page=meetings',
        'subscription' => 'index.php?page=subscription'
    ];

    private $_months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    private $_timeZone = 'Europe/Paris';

    public function getMemberPanelURL(string $requestedPage) {
        return $this->_memberPanelsURLs[$requestedPage];
    }

    public function renderUserStaticDataForm(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanelPages' => $this->_getMemberPanels()]);
        echo $twig->render('member_panels/personal-data-form.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderMemberDashboard(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanels' => $this->_getMemberPanels()]);
        echo $twig->render('member_panels/dashboard.html.twig', ['dashboardMenuItems' => $this->_getDashboardMenu()]);
        echo $twig->render('components/footer.html.twig');
    }

    protected function _getMemberPanels() {
        return $this->_memberPanels;
    }

    protected function _getMemberPanelsStyles() {
        return $this->_memberPanelsStyles;
    }

    protected function _getMemberPanelsSubpanels(string $page) {
        return $this->_memberPanelsSubpanels[$page];
    }

    protected function _getMonths() {
        return $this->_months;
    }
    
    protected function _setTimeZone() {
        date_default_timezone_set($this->_timeZone);
    }

    private function _getDashboardMenu() {
        $dashboardManager = new MemberPanelsManager;
        
        return $dashboardManager->getDashboardMenuItems();
    }
}