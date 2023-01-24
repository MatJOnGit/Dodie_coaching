<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Meetings as MeetingsModel, DateTime;

class MeetingsBooking extends UserPanels {
    private $_appointmentDelay = 24;
    
    private $_dateNeededSpaces = 4;
    
    private $_meetingsScripts = [
        'classes/UserPanels.model',
        'classes/MeetingsBooking.model',
        'meetingsBookingHelper'
    ];
    
    private $_subMenuPage = 'meetings';
    
    public function areDateDataValid(array $dateData): bool {
        return (
            is_numeric($dateData['day']) && 
            in_array($dateData['month'], $this->_getMonths()) && 
            is_numeric($dateData['hour']) && 
            is_numeric($dateData['minute'])
        );
    }
    
    public function bookAppointment(string $meetingDate): bool {
        $meetings = new MeetingsModel;
        
        return $meetings->updateToBooked($_SESSION['email'], $meetingDate);
    }
    
    public function cancelAppointment(): bool {
        $dashboard = new MeetingsModel;
        
        return $dashboard->updateToAvailableMeeting($_SESSION['email']);
    }
    
    public function getDateData(): array {
        $date = htmlspecialchars($_POST['meeting-date']);
        $dateData = [
            'day' => '',
            'month' => '',
            'hour' => '',
            'minute' => ''
        ];
        
        $hasDateRequiredSpaces = substr_count($date, ' ') === $this->_getDateNeededSpaces();
        
        if ($hasDateRequiredSpaces) {
            $dateData['day'] = explode(' ', $date)[1];
            $dateData['month'] = explode(' ', $date)[2];
            $time = explode(' ', $date)[4];
            
            if (substr_count($time, 'h') === 1) {
                $dateData['hour'] = explode('h', $time)[0];
                $dateData['minute'] = explode('h', $time)[1];
            }
        }
        
        return $dateData;
    }
    
    public function getFormatedDate(array $dateData): string {
        $this->_setTimeZone();
        
        $day = $dateData['day'];
        $formatedMonth = array_search($dateData['month'], $this->_getMonths())+1;
        $hour = $dateData['hour'];
        $minute = $dateData['minute'];
        
        $date = date('Y') . '-' . $this->_getTwoDigitsNumber($formatedMonth) . '-' . $this->_getTwoDigitsNumber($day) . ' ' . $this->_getTwoDigitsNumber($hour) . ':' . $this->_getTwoDigitsNumber($minute) . ':00';
        
        return $date;
    }
    
    public function isMeetingsSlotAvailable(string $formatedDate): bool {
        $this->_setTimeZone();
        
        $bookingLimitDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+' . $this->_getAppointmentDelay() . 'hours'));
        
        return (
            $formatedDate > $bookingLimitDate && 
            in_array($formatedDate, $this->_getAvailableMeetings())
        );
    }
    
    public function renderMeetingsBookingPage(object $twig): void {
        echo $twig->render('user_panels/meetings-booking.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'rendez-vous',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord'],
            'subPanel' => $this->_getUserPanelsSubpanels($this->_subMenuPage),
            'meetingSlots' => $this->_getSortedMeetingsSlots(),
            'scheduledMeetingDate' => $this->_getBookedMeetingDate(),
            'pageScripts' => $this->_getMeetingsScripts()
        ]);
    }
    
    private function _getAppointmentDelay(): int {
        return $this->_appointmentDelay;
    }
    
    private function _getAvailableMeetings() {
        $dashboard = new MeetingsModel;
        
        $availableMeetings = $dashboard->selectAvailableMeetings($this->_getAppointmentDelay());
        
        return $this->_getMeetingsSlotsArray($availableMeetings);
    }
    
    private function _getBookedMeetingDate() {
        $dashboard = new MeetingsModel;
        
        $userScheduledMeeting = $dashboard->selectScheduledMeeting($_SESSION['email']);
        
        return (!empty($userScheduledMeeting) ? $userScheduledMeeting[0] : NULL);
    }
    
    private function _getDateNeededSpaces(): int {
        return $this->_dateNeededSpaces;
    }
    
    private function _getMeetingsScripts(): array {
        return $this->_meetingsScripts;
    }
    
    private function _getMeetingsSlotsArray(array $meetings): array {
        $meetingsSlotsArray = [];
        
        foreach($meetings as $meeting) {
            array_push($meetingsSlotsArray, $meeting['slot_date']);
        }
        
        return $meetingsSlotsArray;
    }
    
    private function _getSortedMeetingsSlots(): array {
        $dashboard = new MeetingsModel;
        
        $availableMeetingsSlots = $dashboard->selectAvailableMeetings($this->_getAppointmentDelay());
        $meetingsSlotsArray = $this->_getMeetingsSlotsArray($availableMeetingsSlots);
        
        return $this->_sortMeetingsSlots($meetingsSlotsArray);
    }
    
    private function _getTwoDigitsNumber(int $dateValue): string {
        return $dateValue < 10 ? str_pad($dateValue, 2, '0', STR_PAD_LEFT) : strval($dateValue);
    }
    
    /************************************************************************
    Converts an associative array of meetings into an associative array of
    associative arrays containing each meeting of a day, with the date as key
    ************************************************************************/
    private function _sortMeetingsSlots(array $meetings): array {
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