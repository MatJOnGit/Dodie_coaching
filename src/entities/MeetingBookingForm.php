<?php

namespace App\Entities;

final class MeetingBookingForm extends Form {
    private const DATE_NEEDED_SPACES = 4;
    
    public function areDateDataValid(array $dateData): bool {
        $calendar = new Calendar;
        
        return (
            is_numeric($dateData['day']) && 
            in_array($dateData['month'], $calendar->getMonths()) && 
            is_numeric($dateData['hour']) && 
            is_numeric($dateData['minute'])
        );
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
        $calendar = new Calendar;
        
        $day = $dateData['day'];
        $formatedMonth = array_search($dateData['month'], $calendar->getMonths())+1;
        $hour = $dateData['hour'];
        $minute = $dateData['minute'];
        
        $date = date('Y') . '-' . $this->_getTwoDigitsNumber($formatedMonth) . '-' . $this->_getTwoDigitsNumber($day) . ' ' . $this->_getTwoDigitsNumber($hour) . ':' . $this->_getTwoDigitsNumber($minute) . ':00';
        
        return $date;
    }
    
    private function _getDateNeededSpaces(): int {
        return self::DATE_NEEDED_SPACES;
    }
    
    private function _getTwoDigitsNumber(int $dateValue): string {
        return $dateValue < 10 ? str_pad($dateValue, 2, '0', STR_PAD_LEFT) : strval($dateValue);
    }
}