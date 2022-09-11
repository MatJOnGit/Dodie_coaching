<?php

require('app/src/php/model/DashboardManager.php');
require_once ('app/src/php/model/AccountManager.php');

class MemberPanelsController {
    public $memberPanelPagesStyles = [
        'pages/member-panels',
        'components/header',
        'components/form',
        'components/buttons',
        'components/footer'
    ];

    public $timeZone = 'Europe/Paris';

    public function setTimeZone() {
        date_default_timezone_set($this->timeZone);
    }

    public $memberPanels = ['get-to-know-you', 'dashboard', 'nutrition-program', 'progress', 'meetings', 'subscription'];

    private $memberPanelsSubpanels = array(
        'nutritionProgram' => 'Programme nutritionnel',
        'progress' => 'Progression',
        'meetings' => 'Rendez-vous',
        'subscriptions' => 'Abonnement'
    );

    public $memberPanelsURLs = array(
        'login' => 'index.php?page=login',
        'dashboard' => 'index.php?page=dashboard',
        'progress' => 'index.php?page=progress',
        'getToKnowYou' => 'index.php?page=get-to-know-you',
        'nutritionProgram' => 'index.php?page=nutrition-program',
        'meetings' => 'index.php?page=meetings',
        'subscription' => 'index.php?page=subscription'
    );

    public function getDashboardMenu() {
        $dashboardManager = new DashboardManager;
        
        return $dashboardManager->dashboardMenuItems;
    }

    public function getmemberPanels() {
        return $this->memberPanels;
    }

    public function getMemberPanelsSubpanels($page) {
        return $this->memberPanelsSubpanels[$page];
    }

    public function getMemberPanelsURLs($requestedPage) {
        return $this->memberPanelsURLs[$requestedPage];
    }

    public function renderMemberDataForm($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanelPages' => $this->memberPanelPages]);
        echo $twig->render('member_panels/personal-data-form.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderMemberDashboard($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanels' => $this->memberPanels]);
        echo $twig->render('member_panels/dashboard.html.twig', ['dashboardMenuItems' => $this->getDashboardMenu()]);
        echo $twig->render('components/footer.html.twig');
    }
}