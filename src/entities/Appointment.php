<?php

namespace App\Entities;

use App\Domain\Models\Meeting;

class Appointment {
    private const APPOINTMENT_DELAY = 24;

    public function isMeetingsSlotAvailable(string $formatedDate): bool {
        $timezone = new Timezone;
        $timezone->setTimeZone();
        
        $bookingLimitDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+' . $this->_getAppointmentDelay() . 'hours'));
        
        return (
            $formatedDate > $bookingLimitDate && 
            in_array($formatedDate, $this->_getAvailableMeetings())
        );
    }
    
    public function _getAppointmentDelay(): int {
        return self::APPOINTMENT_DELAY;
    }
    
    private function _getAvailableMeetings() {
        $meeting = new Meeting;
        
        $availableMeetings = $meeting->selectAvailableMeetings($this->_getAppointmentDelay());

        return $this->_getMeetingsSlotsArray($availableMeetings);
    }

    public function _getMeetingsSlotsArray(array $meetings): array {
        $meetingsSlotsArray = [];
        
        foreach($meetings as $meeting) {
            array_push($meetingsSlotsArray, $meeting['slot_date']);
        }
        
        return $meetingsSlotsArray;
    }
    
    public function bookAppointment(string $meetingDate): bool {
        $meetingBookingModel = new Meeting;
        
        return $meetingBookingModel->updateMeetingToBooked($_SESSION['email'], $meetingDate);
    }

    public function cancelAppointment(): bool {
        $meetingBookingModel = new Meeting;
        
        return $meetingBookingModel->updateMeetingToAvailable($_SESSION['email']);
    }
}