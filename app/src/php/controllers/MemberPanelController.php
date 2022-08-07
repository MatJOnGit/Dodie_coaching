<?php

require ('app/src/php/controllers/MainController.php');
require('app/src/php/model/ProgramManager.php');
require ('app/src/php/model/AccountManager.php');

class MemberPanelController extends MainController {
    public $memberPanelPagesStyles = [
        'pages/member-panel',
        'components/header',
        'components/static-menu',
        'components/buttons',
        'components/footer',
        'themes/purple-theme'
    ];

    public function verifySessionData() {
        $isMemberVerified = ((isset($_SESSION['user-email'])) && (isset($_SESSION['user-password']))) ? true : false;

        return $isMemberVerified;
    }

    public function verifyAccountPassword() {
        $accountManager = new AccountManager;
        $isPasswordCorrect = ($_SESSION['user-password'] === $accountManager->getUserPasswordFromEmail($_SESSION['user-email'])[0]) ? true : false;

        return $isPasswordCorrect;
    }

    public function verifyAccount() {
        $isMemberPanelDisplayable = ($this->verifySessionData() && $this->verifyAccountPassword());

        return $isMemberPanelDisplayable;
    }

    public function verifyUserStaticData() {
        $accountManager = new AccountManager;
        $userStaticData = $accountManager->getUserStaticData($_SESSION['user-email']);

        return $userStaticData;
    }

    public function getMissingUserStaticDataKey($userStaticData) {
        $missingStaticData = [];
        foreach ($userStaticData as $key => $staticDataItem)
        {
            if (!is_int($key)) {
                if (is_null($staticDataItem)) {
                    $missingStaticData[] = $key;
                }
            }
        }

        return $missingStaticData;
    }

    public function renderMemberDataForm($twig) {
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage' => 'dashboard']);
        echo $twig->render('member-panels/memberDataForm.html.twig');
        echo $twig->render('templates/footer.html.twig');
    }

    public function renderMemberDashboard($twig) {
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage' => 'dashboard']);
        echo $twig->render('member-panels/dashboard.html.twig');
        echo $twig->render('templates/footer.html.twig');
    }
}