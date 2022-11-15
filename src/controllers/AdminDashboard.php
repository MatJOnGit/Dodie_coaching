<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Admin as AdminModel;

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

    private function _getApplicationsCount() {
        $admin = new AdminModel;

        return $admin->selectApplicationsCount();
    }

    private function _getSubscribersCount() {
        $admin = new AdminModel;

        return $admin->selectSubscribersCount();
    }

    private function _getTodayMeetingsData() {
        $admin = new AdminModel;

        $incomingMeetings = $admin->selectIncomingMeetings();

        return $this->_filterTodayMeetingsData($incomingMeetings);
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
}