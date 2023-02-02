<?php

namespace App\Domain\Controllers\CostumerPanels;

use App\Domain\Models\Meeting;
use App\Entities\Appointment;
use DateTime;

class MeetingBooking extends CostumerPanel {
    private const MEETING_BOOKING_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/MeetingBooker.model',
        'meetingBookingApp'
    ];

    public function renderMeetingsBookingPage(object $twig): void {
        echo $twig->render('user_panels/meetings-booking.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'rendez-vous',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord'],
            'meetingSlots' => $this->_getSortedMeetingsSlots(),
            'scheduledMeetingDate' => $this->_getBookedMeetingDate(),
            'pageScripts' => $this->_getMeetingBookingScripts()
        ]);
    }
    
    private function _getMeetingBookingScripts(): array {
        return self::MEETING_BOOKING_SCRIPTS;
    }
    
    private function _getBookedMeetingDate() {
        $meetingBookingModel = new Meeting;
        
        $userScheduledMeeting = $meetingBookingModel->selectScheduledMeeting($_SESSION['email']);
        
        return (!empty($userScheduledMeeting) ? $userScheduledMeeting[0] : NULL);
    }
    
    private function _getSortedMeetingsSlots(): array {
        $meeting = new Meeting;
        $appointment = new Appointment;

        $appointmentDelay = $appointment->_getAppointmentDelay();
        
        $availableMeetingsSlots = $meeting->selectAvailableMeetings($appointmentDelay);
        $meetingsSlotsArray = $appointment->_getMeetingsSlotsArray($availableMeetingsSlots);
        
        return $this->_sortMeetingsSlots($meetingsSlotsArray);
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