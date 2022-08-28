<?php

require ('app/src/php/controllers/MainController.php');
require('app/src/php/model/DashboardManager.php');
require ('app/src/php/model/AccountManager.php');

class MemberPanelController extends MainController {
    public $memberPanelPagesStyles = [
        'pages/member-panels',
        'components/header',
        'components/form',
        'components/buttons',
        'components/footer'
    ];

    public $meetingScripts = [
        'Meetings.model',
        'meetingsApp'
    ];

    public $memberPanelsURL = array(
        'login' => 'index.php?page=login',
        'dashboard' => 'index.php?page=dashboard',
        'progress' => 'index.php?page=progress',
        'getToKnowYou' => 'index.php?page=get-to-know-you',
        'nutritionProgram' => 'index.php?page=nutrition-program',
        'meetings' => 'index.php?page=meetings',
        'subscription' => 'index.php?page=subscription'
    );

    public $memberPanelsSubtitles = array(
        'nutritionProgram' => 'Programme nutrition',
        'progress' => 'Progression',
        'meetings' => 'Rendez-vous',
        'subscriptions' => 'Abonnement'
    );

    public $memberPanels = ['get-to-know-you', 'dashboard', 'nutrition-program', 'progress', 'meetings', 'subscription'];

    public $appointmentDelay = 24;

    public function verifySessionData() {
        $isMemberVerified = ((isset($_SESSION['user-email'])) && (isset($_SESSION['user-password']))) ? true : false;

        return $isMemberVerified;
    }

    public function getMeetingScripts() {
        return $this->meetingScripts;
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

    public function verifyAddWeightFormData() {
        $weightReport = floatval(htmlspecialchars($_POST['user-weight']));
        $weightDateType = htmlspecialchars($_POST['report-date']);
        $weightDateTypes = ["current-weight", "old-weight"];

        // add a test if the weightDateType is set to 'old-weight'
        $isWeightReportVerified = ((is_numeric($weightReport)) && ($weightReport != 0) && (in_array($weightDateType, $weightDateTypes))) ? true : false;

        return $isWeightReportVerified;
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

    public function getMemberPanelSubtitles($page) {
        return $this->memberPanelsSubtitles[$page];
    }

    public function storeMemberIdentity() {
        $accountManager = new AccountManager;
        $memberIdentity = $accountManager->getMemberIdentity($_SESSION['user-email']);

        $_SESSION['user-first-name'] = $memberIdentity['first_name'];
        $_SESSION['user-last-name'] = $memberIdentity['last_name'];
    }

    public function getProgressHistory() {
        $dashboardManager = new DashboardManager;
        $memberProgressHistory = $dashboardManager->getMemberProgressHistory($_SESSION['user-email']);

        return $memberProgressHistory;
    }

    public function getMeetingSlots() {
        $dashboardManager = new DashboardManager;
        $availableMeetingSlots = $dashboardManager->getAvailableMeetingsSlots($this->appointmentDelay);
        $meetingSlotsArray = $this->getMeetingSlotsArray($availableMeetingSlots);
        $sortedMeetingSlots = $this->getSortedMeetingSlots($meetingSlotsArray);

        return $sortedMeetingSlots;
    }

    public function getMeetingSlotsArray($meetings) {
        $meetingsSlotsArray = [];
        foreach($meetings as $meeting) {
            array_push($meetingsSlotsArray, $meeting['slot_date']);
        }

        return $meetingsSlotsArray;
    }

    public function getSortedMeetingSlots($meetings) {
        $sortedMeetings = [];

        foreach ($meetings as $key => $meeting) {
            $createDate = new DateTime($meeting);
            $meetingDay = $createDate->format('Y-m-d');
            $meetingSlot = explode(' ', $meetings[$key])[1];

            if (!array_key_exists($meetingDay, $sortedMeetings)) {
                $sortedMeetings += [$meetingDay => array($meetingSlot)];
            }
            else {
                array_push($sortedMeetings[$meetingDay], $meetingSlot);
            }
        }

        return $sortedMeetings;
    }

    public function getMemberScheduledMeeting() {
        $dashboardManager = new DashboardManager;
        $memberScheduledMeeting = $dashboardManager->getMemberNextMeetingSlots($_SESSION['user-email']);

        return (!empty($memberScheduledMeeting) ? $memberScheduledMeeting[0] : NULL);
    }

    public function addWeightReport() {
        $dashboardManager = new DashboardManager;
        date_default_timezone_set('Europe/Paris');

        // add a test if the weightDateType is set to 'old-weight'
        $reportDate = (!isset($_POST['report-past-date'])) ? date('Y-m-d H-i-s') : ($_POST['report-past-date']);
        $userId = $dashboardManager->getUserId($_SESSION['user-email']);
        $userWeight = floatval(number_format($_POST['user-weight'], 2));

        $dashboardManager->addNewWeightReport($userId, $userWeight, $reportDate);
    }

    public function getReportDate() {
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d h:i:s');
        $reportDate = ($_POST['report-date'] === 'current-weight') ? $date : false; 
        
        return $reportDate;
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

    public function renderMemberNutritionProgram($twig) {
        $subMenuPage = 'nutritionProgram';

        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanels' => $this->memberPanels, 'subtitle' => $this->getMemberPanelSubtitles($subMenuPage)]);
        echo $twig->render('member_panels/nutrition-program.html.twig', ['dashboardMenuItems' => $this->getDashboardMenu()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderMemberProgress($twig) {
        $subMenuPage = 'progress';

        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanels' => $this->memberPanels, 'subPanel' => $this->getMemberPanelSubtitles($subMenuPage)]);
        echo $twig->render('member_panels/progress.html.twig', ['progressHistory' => $this->getProgressHistory()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderMeetings($twig) {
        $subMenuPage = 'meetings';

        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanels' => $this->memberPanels, 'subPanel' => $this->getMemberPanelSubtitles($subMenuPage)]);
        echo $twig->render('member_panels/meetings.html.twig', ['meetingSlots' => $this->getMeetingSlots(), 'memberScheduledMeeting' => $this->getMemberScheduledMeeting()]);

        echo $twig->render('components/footer.html.twig', ['pageScripts' => $this->getMeetingScripts()]);
    }
}