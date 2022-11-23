<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Subscribers;
use Dodie_Coaching\Models\Applications;
use Dodie_Coaching\Models\Meetings;

class AdminDashboard extends AdminPanels {
    public function renderAdminDashboardPage(object $twig) {
        echo $twig->render('admin_panels/dashboard.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Tableau de bord',
            'appSection' => 'userPanels',
            'applicationsCount' => $this->_getApplicationsCount(),
            'subscribersCount' => $this->_getSubscribersCount(),
            'todayMeetingsData' => $this->_getTodayMeetingsData()
        ]);
    }

    private function _filterTodayMeetingsData(array $incomingMeetings) {
        $this->_setTimeZone();
        $currentDate = date('Y-m-d');
        $todayMeetingsData = [];

        if ($incomingMeetings != false) {
            foreach ($incomingMeetings as $key=>$incomingMeeting) {
                $incomingMeetingDay = explode(' ', $incomingMeeting['slot_date'])[0];
                $incomingMeetingTime = explode(' ', $incomingMeeting['slot_date'])[1];
                $costumerFirstName = $incomingMeeting['first_name'];
                $costumerLastName = strtoupper($incomingMeeting['last_name']);

                if ($incomingMeetingDay === $currentDate) {
                    array_push($todayMeetingsData, [
                        'meetingTime' => $incomingMeetingTime,
                        'costumerName' => $costumerFirstName . ' ' . $costumerLastName
                    ]);
                }
            }
        }

        return $todayMeetingsData;
    }

    private function _getApplicationsCount() {
        $application = new Applications;

        return $application->selectApplicationsCount();
    }

    private function _getSubscribersCount() {
        $subscriber = new Subscribers;

        return $subscriber->selectSubscribersCount();
    }

    private function _getTodayMeetingsData() {
        $meeting = new Meetings;

        $incomingMeetings = $meeting->selectIncomingMeetings();

        return $this->_filterTodayMeetingsData($incomingMeetings);
    }
}