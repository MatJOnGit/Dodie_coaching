<?php

require ('./../src/controllers/MemberPanelsController.php');

class MeetingsController extends MemberPanelsController {
    private $appointmentDelay = 24;

    private $meetingsScripts = [
        'classes/MemberPanels.model',
        'classes/Meetings.model',
        'meetingsApp'
    ];

    private $subMenuPage = 'meetings';

    public function addAppointment($meetingDate) {
        $dashboardManager = new DashboardManager;
        $dashboardManager->bookMemberMeeting($_SESSION['user-email'], $meetingDate);
    }

    public function buildMeetingDate($meetingDay, $meetingMonth, $meetingHour, $meetingMinute) {
        $this->setTimeZone();
        $meetingMonth = array_search($meetingMonth, $this->getMonths())+1;
        $meetingPotentialDate = date('Y') . '-' . $this->getTwoDigitsNumber($meetingMonth) . '-' . $this->getTwoDigitsNumber($meetingDay) . ' ' . $this->getTwoDigitsNumber($meetingHour) . ':' . $this->getTwoDigitsNumber($meetingMinute) . ':00';
        $bookingLimitDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+' . $this->getAppointmentDelay() . 'hours'));
        $meetingDate = $meetingPotentialDate > $bookingLimitDate ? $meetingPotentialDate : NULL;

        return $meetingDate;
    }

    public function cancelMemberNextMeeting() {
        $this->setTimeZone();
        $dashboardManager = new DashboardManager;
        $dashboardManager->releaseMemberAppointmentMeetingSlot($_SESSION['user-email']);
    }

    private function getAppointmentDelay() {
        return $this->appointmentDelay;
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

        if (is_numeric($meetingDay) && in_array($meetingMonth, $this->getMonths()) && is_numeric($meetingHour) && is_numeric($meetingMinute)) {
            $meetingDate = $this->buildMeetingDate($meetingDay, $meetingMonth, $meetingHour, $meetingMinute);
        }

        else {
            $meetingDate = NULL;
        }

        return $meetingDate;
    }

    public function getMeetings() {
        $dashboardManager = new DashboardManager;
        $availableMeetings = $dashboardManager->getAvailableMeetingsSlots($this->getAppointmentDelay());

        return $this->getMeetingsSlotsArray($availableMeetings);
    }

    private function getMeetingsScripts() {
        return $this->meetingsScripts;
    }

    private function getMeetingsSlotsArray($meetings) {
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

    private function getMonths() {
        return $this->months;
    }

    private function getSortedMeetingsSlots() {
        $dashboardManager = new DashboardManager;
        $availableMeetingsSlots = $dashboardManager->getAvailableMeetingsSlots($this->getAppointmentDelay());
        $meetingsSlotsArray = $this->getMeetingsSlotsArray($availableMeetingsSlots);
        $sortedMeetingsSlots = $this->sortMeetingsSlots($meetingsSlotsArray);

        return $sortedMeetingsSlots;
    }

    public function renderMeetings($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->getMemberPanels(), 'subPanel' => $this->getMemberPanelsSubpanels($this->subMenuPage)]);
        echo $twig->render('member_panels/meetings.html.twig', ['meetingSlots' => $this->getSortedMeetingsSlots(), 'memberScheduledMeeting' => $this->getMemberScheduledMeeting()]);
        echo $twig->render('components/footer.html.twig', ['pageScripts' => $this->getMeetingsScripts()]);
    }

    private function sortMeetingsSlots($meetings) {
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