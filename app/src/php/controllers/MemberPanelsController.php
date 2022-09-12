<?php

require('app/src/php/model/DashboardManager.php');
require_once ('app/src/php/model/AccountManager.php');

class MemberPanelsController {
    private $memberPanelsStyles = [
        'pages/member-panels',
        'components/header',
        'components/form',
        'components/buttons',
        'components/footer'
    ];

    private $timeZone = 'Europe/Paris';

    private $memberPanels = ['get-to-know-you', 'dashboard', 'nutrition-program', 'progress', 'meetings', 'subscription'];

    private $memberPanelsSubpanels = array(
        'nutritionProgram' => 'Programme nutritionnel',
        'progress' => 'Progression',
        'meetings' => 'Rendez-vous',
        'subscriptions' => 'Abonnement'
    );

    private $memberPanelsURLs = array(
        'login' => 'index.php?page=login',
        'dashboard' => 'index.php?page=dashboard',
        'progress' => 'index.php?page=progress',
        'getToKnowYou' => 'index.php?page=get-to-know-you',
        'nutritionProgram' => 'index.php?page=nutrition-program',
        'meetings' => 'index.php?page=meetings',
        'subscription' => 'index.php?page=subscription'
    );

    private function getDashboardMenu() {
        $dashboardManager = new DashboardManager;
        
        return $dashboardManager->getDashboardMenuItems();
    }

    public function getMemberPanelsStyles() {
        return $this->memberPanelsStyles;
    }

    public function getMemberPanels() {
        return $this->memberPanels;
    }

    public function getMemberPanelsSubpanels($page) {
        return $this->memberPanelsSubpanels[$page];
    }

    public function getMemberPanelURL($requestedPage) {
        return $this->memberPanelsURLs[$requestedPage];
    }

    public function renderUserStaticDataForm($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanelPages' => $this->getMemberPanels()]);
        echo $twig->render('member_panels/personal-data-form.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderMemberDashboard($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanels' => $this->getMemberPanels()]);
        echo $twig->render('member_panels/dashboard.html.twig', ['dashboardMenuItems' => $this->getDashboardMenu()]);
        echo $twig->render('components/footer.html.twig');
    }
    
    public function setTimeZone() {
        date_default_timezone_set($this->timeZone);
    }
}