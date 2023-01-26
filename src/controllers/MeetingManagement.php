<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\MeetingManagement as MeetingManagementModel;

class MeetingManagement extends AdminPanel {
    private $_meetingManagementScripts = [
        'classes/Fader.model',
        'classes/MeetingManager.model',
        'meetingManagementApp'
    ];
    
    public function addMeetingSlot(array $meetingData) {
        $meetingManagement = new MeetingManagementModel;
        
        $meetingDate = $meetingData['meeting-day'] . ' ' . $meetingData['meeting-time'] . ':00';
        
        return $meetingManagement->insertMeeting($meetingDate);
    }
    
    public function areDateDataValid(array $meetingData): bool {
        $meetingDate = $meetingData['meeting-day'] . ' ' . $meetingData['meeting-time'];
        
        return date('Y-m-d H:i', strtotime($meetingDate)) === $meetingDate;
    }
    
    public function eraseMeetingSlot(string $meetingId) {
        $meetingManagement = new MeetingManagementModel;
        
        return $meetingManagement->deleteMeeting($meetingId);
    }
    
    public function isMeetingIdValid(string $meetingId): bool {
        $meetingManagement = new MeetingManagementModel;
        
        $isMeetingIdValid = false;
        
        foreach ($meetingManagement->selectNextMeetings() as $meetingManagement) {
            if ($meetingManagement['slot_id'] === $meetingId) {
                $isMeetingIdValid = true;
            }
        }
        
        return $isMeetingIdValid;
    }
    
    public function getAttendeeData(string $meetingId) {
        $meetingManagement = new MeetingManagementModel;
        
        return $meetingManagement->selectAttendeeData($meetingId);
    }
    
    public function isMeetingBooked(array $attendeeData): bool {
        return !empty($attendeeData) ? $attendeeData[0]['user_id'] > 0 : false;
    }
    
    public function renderMeetingsManagementPage(object $twig): void {        
        echo $twig->render('admin_panels/meetings-management.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
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
    
    private function _getSortedMeetingSlots() {
        $meetingManagement = new MeetingManagementModel;
        
        $nextMeetings = $meetingManagement->selectNextMeetings();
        
        return $this->_sortNextMeetings($nextMeetings);
    }
    
    private function _getMeetingManagementScripts(): array {
        return $this->_meetingManagementScripts;
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