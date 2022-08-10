<?php

require ('app/src/php/controllers/MainController.php');
require('app/src/php/model/ProgramManager.php');
require('app/src/php/model/DashboardManager.php');
require ('app/src/php/model/AccountManager.php');

class MemberPanelController extends MainController {
    public $memberPanelPagesStyles = [
        'pages/member-panels',
        'components/header',
        'components/buttons',
        'components/footer'
    ];

    public $memberPanelPages = ['dashboard', 'get-to-know-you', 'meetings'];

    public function verifySessionData() {
        $isMemberVerified = ((isset($_SESSION['user-email'])) && (isset($_SESSION['user-password']))) ? true : false;

        return $isMemberVerified;
    }

    public function verifyAccountPassword() {
        $accountManager = new AccountManager;
        $isPasswordCorrect = ($_SESSION['user-password'] === $accountManager->getUserPassword($_SESSION['user-email'])[0]) ? true : false;

        return $isPasswordCorrect;
    }

    public function verifyAccount() {
        $isMemberPanelDisplayable = ($this->verifySessionData() && $this->verifyAccountPassword());

        return $isMemberPanelDisplayable;
    }

    public function verifyUserStaticData() {
        $accountManager = new AccountManager;
        $accountManager->getUserStaticData($_SESSION['user-email']);

        $userStaticData = $accountManager->getUserStaticData($_SESSION['user-email']);

        return $userStaticData;
    }

    public function getMissingUserStaticDataKey($userStaticData) {
        $missingStaticData = [];
        foreach ($userStaticData as $key => $staticDataItem)
        {
            if (is_null($staticDataItem)) {
                $missingStaticData[] = $key;
            }
        }

        return $missingStaticData;
    }

    public function getDashboardMenu() {
        $dashboardManager = new DashboardManager;
        $dashboardMenu = $dashboardManager->dashboardMenuItems;

        return $dashboardMenu;
    }

    // A retirer, l'utilisateur aura besoin de get son prénom et nom au chargement de la page anyway
    public function storeMemberIdentity() {
        $accountManager = new AccountManager;
        $memberIdentity = $accountManager->getMemberIdentity($_SESSION['user-email']);

        $_SESSION['user-first-name'] = $memberIdentity['first_name'];
        $_SESSION['user-last-name'] = $memberIdentity['last_name'];
    }

    public function renderMemberDataForm($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanelPages' => $this->memberPanelPages]);
        echo $twig->render('member-panels/personal-data-form.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderMemberDashboard($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanelPages' => $this->memberPanelPages]);
        echo $twig->render('member-panels/dashboard.html.twig', ['dashboardMenuItems' => $this->getDashboardMenu()]);
        echo $twig->render('components/footer.html.twig');
    }
}