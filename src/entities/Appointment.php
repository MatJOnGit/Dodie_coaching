<?php

namespace App\Entities;

use App\Domain\Models\MeetingSlot;

final class Appointment {
    private const APPOINTMENT_DELAY = 24;
    
    public function bookAppointment(string $meetingDate): bool {
        $meetingSlot = new MeetingSlot;
        
        return $meetingSlot->updateMeetingToBooked($_SESSION['email'], $meetingDate);
    }
    
    public function cancelAppointment(): bool {
        $meetingSlot = new MeetingSlot;
        
        return $meetingSlot->updateMeetingToAvailable($_SESSION['email']);
    }
    
    public function getAppointmentDelay(): int {
        return self::APPOINTMENT_DELAY;
    }
    
    public function getMeetingsSlotsArray(array $meetings): array {
        $meetingsSlotsArray = [];
        
        foreach($meetings as $meeting) {
            array_push($meetingsSlotsArray, $meeting['slot_date']);
        }
        
        return $meetingsSlotsArray;
    }
    
    public function isMeetingsSlotAvailable(string $formatedDate): bool {
        $timezone = new Timezone;
        $timezone->setTimezone();
        
        $bookingLimitDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+' . $this->getAppointmentDelay() . 'hours'));
        
        return (
            $formatedDate > $bookingLimitDate && 
            in_array($formatedDate, $this->_getAvailableMeetings())
        );
    }
    
    private function _getAvailableMeetings() {
        $meetingSlot = new MeetingSlot;
        
        $availableMeetings = $meetingSlot->selectAvailableMeetings($this->getAppointmentDelay());
        
        return $this->getMeetingsSlotsArray($availableMeetings);
    }
}