<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\MeetingBooking as MeetingBookingModel;
use DateTime;

final class MeetingBooking extends UserPanel {
    
    private const DATE_NEEDED_SPACES = 4;
    
    public function areDateDataValid(array $dateData): bool {
        return (
            is_numeric($dateData['day']) && 
            in_array($dateData['month'], $this->_getMonths()) && 
            is_numeric($dateData['hour']) && 
            is_numeric($dateData['minute'])
        );
    }
    
    public function bookAppointment(string $meetingDate): bool {
        $meetingBookingModel = new MeetingBookingModel;
        
        return $meetingBookingModel->updateMeetingToBooked($_SESSION['email'], $meetingDate);
    }
    
    public function cancelAppointment(): bool {
        $meetingBookingModel = new MeetingBookingModel;
        
        return $meetingBookingModel->updateMeetingToAvailable($_SESSION['email']);
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
    
    private function _getTwoDigitsNumber(int $dateValue): string {
        return $dateValue < 10 ? str_pad($dateValue, 2, '0', STR_PAD_LEFT) : strval($dateValue);
    }
    
    public function isMeetingsSlotAvailable(string $formatedDate): bool {
        $this->_setTimeZone();
        
        $bookingLimitDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+' . $this->_getAppointmentDelay() . 'hours'));
        
        return (
            $formatedDate > $bookingLimitDate && 
            in_array($formatedDate, $this->_getAvailableMeetings())
        );
    }
    
    
    
    private function _getAvailableMeetings() {
        $meetingBookingModel = new MeetingBookingModel;
        
        $availableMeetings = $meetingBookingModel->selectAvailableMeetings($this->_getAppointmentDelay());

        return $this->_getMeetingsSlotsArray($availableMeetings);
    }
    
    
    
    private function _getDateNeededSpaces(): int {
        return self::DATE_NEEDED_SPACES;
    }
    
    
}