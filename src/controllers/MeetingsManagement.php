<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Meetings as MeetingsModel;

class MeetingsManagement extends AdminPanels {
    private $_meetingsManagementScripts = [
        'classes/UserPanels.model',
        'classes/MeetingsManagementHelper.model',
        'meetingsManagementHelper'
    ];
    
    public function addMeetingSlot(array $meetingData) {
        $meetings = new MeetingsModel;
        
        $meetingDate = $meetingData['meeting-day'] . ' ' . $meetingData['meeting-time'] . ':00';
        
        return $meetings->insertMeeting($meetingDate);
    }
    
    public function areDateDataValid(array $meetingData): bool {
        $meetingDate = $meetingData['meeting-day'] . ' ' . $meetingData['meeting-time'];
        
        return date('Y-m-d H:i', strtotime($meetingDate)) === $meetingDate;
    }
    
    public function eraseMeetingSlot(string $meetingId) {
        $meetings = new MeetingsModel;
        
        return $meetings->deleteMeeting($meetingId);
    }
    
    public function isMeetingIdValid(string $meetingId): bool {
        $meetings = new MeetingsModel;
        
        $isMeetingIdValid = false;
        
        foreach ($meetings->selectNextMeetings() as $meeting) {
            if ($meeting['slot_id'] === $meetingId) {
                $isMeetingIdValid = true;
            }
        }
        
        return $isMeetingIdValid;
    }
    
    public function getAttendeeData(string $meetingId) {
        $meetings = new MeetingsModel;
        
        return $meetings->selectAttendeeData($meetingId);
    }
    
    public function isMeetingBooked(array $attendeeData): bool {
        return $attendeeData[0]['user_id'] > 0;
    }
    
    public function renderMeetingsManagementPage(object $twig): void {        
        echo $twig->render('admin_panels/meetings-management.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Gestion des rendez-vous',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'meetingSlots' => $this->_getSortedMeetingSlots(),
            'pageScripts' => $this->_getMeetingsManagementScripts()
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
    
    private function _getSortedMeetingSlots() {
        $meetings = new MeetingsModel;
        
        $nextMeetings = $meetings->selectNextMeetings();
        
        return $this->_sortNextMeetings($nextMeetings);
    }
    
    private function _getMeetingsManagementScripts(): array {
        return $this->_meetingsManagementScripts;
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
    
    private function _sortNextMeetings($nextMeetings) {
        $meetingsDatesArray = $this->_getMeetingsUniqueDates($nextMeetings);
        
        return $this->_completeSortedNextMeetings($nextMeetings, $meetingsDatesArray);
    }
}