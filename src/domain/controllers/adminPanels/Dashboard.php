<?php

namespace App\Domain\Controllers\AdminPanels;

use App\Domain\Models\Account;
use App\Domain\Models\Appliance;
use App\Domain\Models\MeetingSlot;
use App\Entities\Timezone;

final class Dashboard extends AdminPanel {
    public function renderAdminDashboardPage(object $twig): void {
        echo $twig->render('admin_panels/dashboard.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
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
        $timezone = new Timezone;
        $timezone->setTimezone();
        
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
        $account = new Account;
        
        return $account->selectSubscribersCount();
    }
    
    private function _getTodayMeetingsData() {
        $meetingSlot = new MeetingSlot;
        
        $incomingMeetings = $meetingSlot->selectNextBookedMeetings();
        
        return $this->_filterTodayMeetingsData($incomingMeetings);
    }
}