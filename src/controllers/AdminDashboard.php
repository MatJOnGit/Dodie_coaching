<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Subscriber;
use Dodie_Coaching\Models\Appliance;
use Dodie_Coaching\Models\Meeting;

class AdminDashboard extends AdminPanel {
    public function renderAdminDashboardPage(object $twig): void {
        echo $twig->render('admin_panels/dashboard.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Tableau de bord',
            'appSection' => 'userPanels',
            'appliancesCount' => $this->_getAppliancesCount(),
            'subscribersCount' => $this->_getSubscribersCount(),
            'todayMeetingsData' => $this->_getTodayMeetingsData()
        ]);
    }
    
    /******************************************************
    Remove data from an array containing incoming meetings
    to keep only those that are attended on the current day
    ******************************************************/
    private function _filterTodayMeetingsData(array $incomingMeetings): array {
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
        $appliance = new Appliance;
        
        return $appliance->selectAppliancesCount();
    }
    
    private function _getSubscribersCount() {
        $subscriber = new Subscriber;
        
        return $subscriber->selectSubscribersCount();
    }
    
    private function _getTodayMeetingsData() {
        $meeting = new Meeting;
        
        $incomingMeetings = $meeting->selectNextBookedMeetings();
        
        return $this->_filterTodayMeetingsData($incomingMeetings);
    }
}