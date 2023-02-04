<?php

namespace App\Domain\Controllers\AdminPanels;

use App\Domain\Models\MeetingSlot;

final class MeetingManagement extends AdminPanel {
    private const MEETING_MANAGEMENT_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/MeetingManager.model',
        'meetingManagementApp'
    ];
    
    public function renderMeetingsManagementPage(object $twig): void {        
        echo $twig->render('admin_panels/meetings-management.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => 'Gestion des rendez-vous',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'meetingSlots' => $this->_getSortedMeetingSlots(),
            'pageScripts' => $this->_getMeetingManagementScripts()
        ]);
    }
    
    /********************************************************************
    Completes sorted incoming meetings associative array with associative
    array containing incoming meeting slots data
    ********************************************************************/
    private function _completeSortedNextMeetings(array $nextMeetings, array $sortedIncomingMeetings) {
        foreach ($nextMeetings as $key => $incomingMeeting) {
            if (array_key_exists($incomingMeeting['day'], $sortedIncomingMeetings)) {
                $sortedIncomingMeetings[$incomingMeeting['day']] += [$key => [
                    'starting_time' => $incomingMeeting['starting_time'],
                    'name' => $incomingMeeting['name'],
                    'slot_id' => $incomingMeeting['slot_id']
                ]];
            }
            
            $sortedIncomingMeetings[$incomingMeeting['day']] = array_values($sortedIncomingMeetings[$incomingMeeting['day']]);
        }
        
        return $sortedIncomingMeetings;
    }
    
    private function _getMeetingManagementScripts(): array {
        return self::MEETING_MANAGEMENT_SCRIPTS;
    }
    
    /**************************************************************************************
    Builds an associative array containing every different incoming unique dates of meeting
    **************************************************************************************/
    private function _getMeetingsUniqueDates(array $nextMeetings) {
        $arrayOfMeetingsDates = [];
        
        foreach ($nextMeetings as $incomingMeeting) {
            array_push($arrayOfMeetingsDates, [$incomingMeeting['day'] => []]);
        }
        
        return (array_merge(...$arrayOfMeetingsDates));
    }
    
    private function _getSortedMeetingSlots() {
        $meetingSlot = new MeetingSlot;
        
        $nextMeetings = $meetingSlot->selectNextMeetings();
        
        return $this->_sortNextMeetings($nextMeetings);
    }
    
    private function _sortNextMeetings($nextMeetings) {
        $meetingsDatesArray = $this->_getMeetingsUniqueDates($nextMeetings);
        
        return $this->_completeSortedNextMeetings($nextMeetings, $meetingsDatesArray);
    }
}