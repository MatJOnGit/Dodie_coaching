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

    public $meetingsScripts = [
        'Meetings.model',
        'meetingsApp'
    ];

    public $progressScripts = [
        'Progress.model',
        'progressApp'
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

    public $months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    public $memberPanelsSubtitles = array(
        'nutritionProgram' => 'Programme nutrition',
        'progress' => 'Progression',
        'meetings' => 'Rendez-vous',
        'subscriptions' => 'Abonnement'
    );

    public $memberPanels = ['get-to-know-you', 'dashboard', 'nutrition-program', 'progress', 'meetings', 'subscription'];

    public $appointmentDelay = 24;

    public $timeZone = 'Europe/Paris';

    public function setTimeZone() {
        date_default_timezone_set($this->timeZone);
    }

    public function verifySessionData() {
        $isMemberVerified = ((isset($_SESSION['user-email'])) && (isset($_SESSION['user-password']))) ? true : false;

        return $isMemberVerified;
    }

    public function getMeetingsScripts() {
        return $this->meetingsScripts;
    }

    public function buildReportDate() {
        return htmlspecialchars($_POST['report-date']) . ' ' . htmlspecialchars($_POST['report-time']);
    }

    public function getProgressScripts() {
        return $this->progressScripts;
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

    public function verifyWeightReportDate($date, $format) {
        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) == $date;
    }

    public function verifyAddWeightFormData() {
        $weightReport = floatval(htmlspecialchars($_POST['user-weight']));
        $isWeightReportValid = is_numeric($weightReport) && ($weightReport != 0);

        $reportDateType = htmlspecialchars($_POST['date-type']);
        $reportDateTypes = ["current-weight", "old-weight"];
        $isWeightDateTypeValid = in_array($reportDateType, $reportDateTypes);

        if (isset($_POST['report-date']) && (isset($_POST['report-time']))) {
            $reportDate = htmlspecialchars($_POST['report-date']) . ' ' . htmlspecialchars($_POST['report-time']);
            $isReportDateValid = $this->verifyWeightReportDate($reportDate, 'Y-m-d H:i');
        }

        if ($reportDateType !== 'old-weight') {
            $isWeightReportVerified = $isWeightReportValid && $isWeightDateTypeValid;
        }
        else {
            $isWeightReportVerified = $isWeightReportValid && $isWeightDateTypeValid && $isReportDateValid;
        }

        return $isWeightReportVerified;
    }

    public function addWeightReport() {
        $this->setTimeZone();
        $dashboardManager = new DashboardManager;
        $reportDate = $this->verifyWeightReportDate($this->buildReportDate(), 'Y-m-d H:i') ? $this->buildReportDate() : date('Y-m-d H-i-s');

        $userId = $dashboardManager->getUserId($_SESSION['user-email']);
        $userWeight = floatval(number_format($_POST['user-weight'], 2));

        $dashboardManager->addNewWeightReport($userId, $userWeight, $reportDate);
    }

    public function verifyMeetingFormData() {
        $meetingFormInputValue = htmlspecialchars($_POST['meeting-date']);
        $meetingDay = explode(' ', $meetingFormInputValue)[1];
        $meetingMonth = explode(' ', $meetingFormInputValue)[2];
        $meetingTime = explode(' ', $meetingFormInputValue)[4];
        $meetingHour = explode('h', $meetingTime)[0];
        $meetingMinute = explode('h', $meetingTime)[1];

        if (is_numeric($meetingDay) && in_array($meetingMonth, $this->months) && is_numeric($meetingHour) && is_numeric($meetingMinute)) {
            $meetingDate = $this->buildMeetingDateFromInputValue($meetingDay, $meetingMonth, $meetingHour, $meetingMinute);
        }
        else {
            $meetingDate = NULL;
        }

        return $meetingDate;
    }

    public function morphDateValues($value) {
        $value = $value < 10 ? str_pad($value, 2, '0', STR_PAD_LEFT) : $value;

        return $value;
    }

    public function buildMeetingDateFromInputValue($meetingDay, $meetingMonth, $meetingHour, $meetingMinute) {
        $meetingMonth = array_search($meetingMonth, $this->months)+1;

        $meetingPotentialDate = date('Y') . '-' . $this->morphDateValues($meetingMonth) . '-' . $this->morphDateValues($meetingDay) . ' ' . $this->morphDateValues($meetingHour) . ':' . $this->morphDateValues($meetingMinute) . ':00';

        date_default_timezone_set('Europe/Paris');

        // a vérifier
        $bookingLimitDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+' . $this->appointmentDelay . 'hours'));

        $meetingDate = $meetingPotentialDate > $bookingLimitDate ? $meetingPotentialDate : NULL;

        return $meetingDate;
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

    public function getMeetings() {
        $dashboardManager = new DashboardManager;
        $availableMeetings = $dashboardManager->getAvailableMeetingsSlots($this->appointmentDelay);

        echo '<pre>';
        var_dump($availableMeetings);
        echo '</pre>';
        


        $meetingsArray = $this->getMeetingSlotsArray($availableMeetings);

        return $meetingsArray;
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

    public function addAppointment($meetingDate) {
        $dashboardManager = new DashboardManager;
        $dashboardManager->bookMemberMeeting($_SESSION['user-email'], $meetingDate);
    }

    public function getReportDate() {
        $this->setTimeZone();
        $date = date('Y-m-dTh:i:s');
        $reportDate = ($_POST['report-date'] === 'current-weight') ? $date : false; 
        
        return $reportDate;
    }

    public function cancelMemberNextMeeting() {
        $this->setTimeZone();
        $dashboardManager = new DashboardManager;
        $dashboardManager->releaseNextMemberMeetingSlot($_SESSION['user-email']);
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
        echo $twig->render('components/footer.html.twig', ['pageScripts' => $this->getProgressScripts()]);
    }

    public function renderMeetings($twig) {
        $subMenuPage = 'meetings';

        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanels' => $this->memberPanels, 'subPanel' => $this->getMemberPanelSubtitles($subMenuPage)]);
        echo $twig->render('member_panels/meetings.html.twig', ['meetingSlots' => $this->getMeetingSlots(), 'memberScheduledMeeting' => $this->getMemberScheduledMeeting()]);

        echo $twig->render('components/footer.html.twig', ['pageScripts' => $this->getMeetingsScripts()]);
    }
}