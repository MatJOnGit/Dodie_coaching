<?php

namespace App\Controllers;

require ('./../src/controllers/MemberPanelsController.php');
use App\Models\DashboardManager as DashboardManager;
use DateTime;

class MeetingsController extends MemberPanelsController {
    private $_appointmentDelay = 24;

    private $_meetingsScripts = [
        'classes/MemberPanels.model',
        'classes/Meetings.model',
        'meetingsApp'
    ];

    private $_subMenuPage = 'meetings';

    public function addAppointment(string $meetingDate) {
        $dashboardManager = new DashboardManager;
        $dashboardManager->bookMemberMeeting($_SESSION['user-email'], $meetingDate);
    }

    public function cancelMemberNextMeeting() {
        $this->_setTimeZone();
        $dashboardManager = new DashboardManager;
        $dashboardManager->releaseMemberAppointmentMeetingSlot($_SESSION['user-email']);
    }

    public function getMeetingDate() {
        $meetingFormInputValue = htmlspecialchars($_POST['meeting-date']);

        $meetingDay = explode(' ', $meetingFormInputValue)[1];
        $meetingMonth = explode(' ', $meetingFormInputValue)[2];
        $meetingTime = explode(' ', $meetingFormInputValue)[4];

        $meetingHour = explode('h', $meetingTime)[0];
        $meetingMinute = explode('h', $meetingTime)[1];

        if (is_numeric($meetingDay) && in_array($meetingMonth, $this->_getMonths()) && is_numeric($meetingHour) && is_numeric($meetingMinute)) {
            $meetingDate = $this->_buildMeetingDate($meetingDay, $meetingMonth, $meetingHour, $meetingMinute);
        }

        else {
            $meetingDate = NULL;
        }

        return $meetingDate;
    }

    public function getMeetings() {
        $dashboardManager = new DashboardManager;
        $availableMeetings = $dashboardManager->getAvailableMeetingsSlots($this->_getAppointmentDelay());

        return $this->_getMeetingsSlotsArray($availableMeetings);
    }

    public function getMemberScheduledMeeting() {
        $dashboardManager = new DashboardManager;
        $memberScheduledMeeting = $dashboardManager->getMemberNextMeetingSlots($_SESSION['user-email']);

        return (!empty($memberScheduledMeeting) ? $memberScheduledMeeting[0] : NULL);
    }

    public function renderMeetings(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->_getMemberPanels(), 'subPanel' => $this->_getMemberPanelsSubpanels($this->_subMenuPage)]);
        echo $twig->render('member_panels/meetings.html.twig', ['meetingSlots' => $this->_getSortedMeetingsSlots(), 'memberScheduledMeeting' => $this->getMemberScheduledMeeting()]);
        echo $twig->render('components/footer.html.twig', ['pageScripts' => $this->_getMeetingsScripts()]);
    }
    
    private function _buildMeetingDate(string $meetingDay, string $meetingMonth, string $meetingHour, string $meetingMinute) {
        $this->_setTimeZone();
        $meetingMonth = array_search($meetingMonth, $this->_getMonths())+1;
        $meetingPotentialDate = date('Y') . '-' . $this->_getTwoDigitsNumber($meetingMonth) . '-' . $this->_getTwoDigitsNumber($meetingDay) . ' ' . $this->_getTwoDigitsNumber($meetingHour) . ':' . $this->_getTwoDigitsNumber($meetingMinute) . ':00';
        $bookingLimitDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+' . $this->_getAppointmentDelay() . 'hours'));
        $meetingDate = $meetingPotentialDate > $bookingLimitDate ? $meetingPotentialDate : NULL;

        return $meetingDate;
    }

    private function _getAppointmentDelay() {
        return $this->_appointmentDelay;
    }

    private function _getMeetingsScripts() {
        return $this->_meetingsScripts;
    }

    private function _getMeetingsSlotsArray(array $meetings) {
        $meetingsSlotsArray = [];
        foreach($meetings as $meeting) {
            array_push($meetingsSlotsArray, $meeting['slot_date']);
        }

        return $meetingsSlotsArray;
    }

    private function _getSortedMeetingsSlots() {
        $dashboardManager = new DashboardManager;
        $availableMeetingsSlots = $dashboardManager->getAvailableMeetingsSlots($this->_getAppointmentDelay());
        $meetingsSlotsArray = $this->_getMeetingsSlotsArray($availableMeetingsSlots);
        $sortedMeetingsSlots = $this->_sortMeetingsSlots($meetingsSlotsArray);

        return $sortedMeetingsSlots;
    }

    private function _getTwoDigitsNumber(int $dateValue) {
        $morphedDateValue = $dateValue < 10 ? str_pad($dateValue, 2, '0', STR_PAD_LEFT) : $dateValue;

        return $morphedDateValue;
    }

    private function _sortMeetingsSlots(array $meetings) {
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