<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Subscribers;
use Dodie_Coaching\Models\Appliances;
use Dodie_Coaching\Models\Meetings;

class AdminDashboard extends AdminPanels {
    public function renderAdminDashboardPage(object $twig) {
        echo $twig->render('admin_panels/dashboard.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Tableau de bord',
            'appSection' => 'userPanels',
            'appliancesCount' => $this->_getAppliancesCount(),
            'subscribersCount' => $this->_getSubscribersCount(),
            'todayMeetingsData' => $this->_getTodayMeetingsData()
        ]);
    }
    
    private function _filterTodayMeetingsData(array $incomingMeetings) {
        $this->_setTimeZone();
        $currentDate = date('d/m/Y');
        $todayMeetingsData = [];

        if ($incomingMeetings) {
            foreach ($incomingMeetings as $incomingMeeting) {
                $incomingMeetingDay = $incomingMeeting['day'];
                $incomingMeetingTime = $incomingMeeting['starting_time'];
                $costumerName = $incomingMeeting['name'];

                if ($incomingMeetingDay === $currentDate) {
                    array_push($todayMeetingsData, [
                        'meetingTime' => $incomingMeetingTime,
                        'costumerName' => $costumerName
                    ]);
                }
            }
        }
        
        return $todayMeetingsData;
    }

    private function _getAppliancesCount() {
        $appliances = new Appliances;

        return $appliances->selectAppliancesCount();
    }

    private function _getSubscribersCount() {
        $subscribers = new Subscribers;

        return $subscribers->selectSubscribersCount();
    }
    
    private function _getTodayMeetingsData() {
        $meetings = new Meetings;

        $incomingMeetings = $meetings->selectNextBookedMeetings();

        return $this->_filterTodayMeetingsData($incomingMeetings);
    }
}