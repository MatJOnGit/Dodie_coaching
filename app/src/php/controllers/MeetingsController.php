<?php

require ('app/src/php/controllers/MemberPanelsController.php');

class MeetingsController extends MemberPanelsController {
    public $appointmentDelay = 24;

    public $meetingsScripts = [
        'Meetings.model',
        'meetingsApp'
    ];

    public $months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    public function addAppointment($meetingDate) {
        $dashboardManager = new DashboardManager;
        $dashboardManager->bookMemberMeeting($_SESSION['user-email'], $meetingDate);
    }

    public function buildMeetingDate($meetingDay, $meetingMonth, $meetingHour, $meetingMinute) {
        $this->setTimeZone();
        $meetingMonth = array_search($meetingMonth, $this->months)+1;
        $meetingPotentialDate = date('Y') . '-' . $this->getTwoDigitsNumber($meetingMonth) . '-' . $this->getTwoDigitsNumber($meetingDay) . ' ' . $this->getTwoDigitsNumber($meetingHour) . ':' . $this->getTwoDigitsNumber($meetingMinute) . ':00';
        $bookingLimitDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+' . $this->appointmentDelay . 'hours'));
        $meetingDate = $meetingPotentialDate > $bookingLimitDate ? $meetingPotentialDate : NULL;

        return $meetingDate;
    }

    public function cancelMemberNextMeeting() {
        $this->setTimeZone();
        $dashboardManager = new DashboardManager;
        $dashboardManager->releaseNextMemberMeetingSlot($_SESSION['user-email']);
    }

    private function getTwoDigitsNumber($dateValue) {
        $morphedDateValue = $dateValue < 10 ? str_pad($dateValue, 2, '0', STR_PAD_LEFT) : $dateValue;

        return $morphedDateValue;
    }

    public function getMeetingDate() {
        $meetingFormInputValue = htmlspecialchars($_POST['meeting-date']);

        $meetingDay = explode(' ', $meetingFormInputValue)[1];
        $meetingMonth = explode(' ', $meetingFormInputValue)[2];
        $meetingTime = explode(' ', $meetingFormInputValue)[4];

        $meetingHour = explode('h', $meetingTime)[0];
        $meetingMinute = explode('h', $meetingTime)[1];

        if (is_numeric($meetingDay) && in_array($meetingMonth, $this->months) && is_numeric($meetingHour) && is_numeric($meetingMinute)) {
            $meetingDate = $this->buildMeetingDate($meetingDay, $meetingMonth, $meetingHour, $meetingMinute);
        }

        else {
            $meetingDate = NULL;
        }

        return $meetingDate;
    }

    public function getMeetings() {
        $dashboardManager = new DashboardManager;
        $availableMeetings = $dashboardManager->getAvailableMeetingsSlots($this->appointmentDelay);
        $meetings = $this->getMeetingsSlotsArray($availableMeetings);

        return $meetings;
    }

    public function getMeetingsScripts() {
        return $this->meetingsScripts;
    }

    public function getMeetingsSlotsArray($meetings) {
        $meetingsSlotsArray = [];
        foreach($meetings as $meeting) {
            array_push($meetingsSlotsArray, $meeting['slot_date']);
        }

        return $meetingsSlotsArray;
    }

    public function getMemberScheduledMeeting() {
        $dashboardManager = new DashboardManager;
        $memberScheduledMeeting = $dashboardManager->getMemberNextMeetingSlots($_SESSION['user-email']);

        return (!empty($memberScheduledMeeting) ? $memberScheduledMeeting[0] : NULL);
    }

    public function getSortedMeetingsSlots() {
        $dashboardManager = new DashboardManager;
        $availableMeetingsSlots = $dashboardManager->getAvailableMeetingsSlots($this->appointmentDelay);
        $meetingsSlotsArray = $this->getMeetingsSlotsArray($availableMeetingsSlots);
        $sortedMeetingsSlots = $this->sortMeetingsSlots($meetingsSlotsArray);

        return $sortedMeetingsSlots;
    }

    public function renderMeetings($twig) {
        $subMenuPage = 'meetings';

        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->getmemberPanels(), 'subPanel' => $this->getMemberPanelsSubpanels($subMenuPage)]);
        echo $twig->render('member_panels/meetings.html.twig', ['meetingSlots' => $this->getSortedMeetingsSlots(), 'memberScheduledMeeting' => $this->getMemberScheduledMeeting()]);
        echo $twig->render('components/footer.html.twig', ['pageScripts' => $this->getMeetingsScripts()]);
    }

    public function sortMeetingsSlots($meetings) {
        $sortedMeetingsSlots = [];

        foreach ($meetings as $key => $meeting) {
            $createDate = new DateTime($meeting);
            $meetingDay = $createDate->format('Y-m-d');
            $meetingSlot = explode(' ', $meetings[$key])[1];

            if (!array_key_exists($meetingDay, $sortedMeetingsSlots)) {
                $sortedMeetingsSlots += [$meetingDay => array($meetingSlot)];
            }

            else {
                array_push($sortedMeetingsSlots[$meetingDay], $meetingSlot);
            }
        }

        return $sortedMeetingsSlots;
    }
}