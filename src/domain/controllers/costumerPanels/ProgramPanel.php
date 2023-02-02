<?php

namespace App\Domain\Controllers\CostumerPanels;

use App\Entities\Calendar;
use App\Entities\Timezone;
use DatePeriod, DateTime, DateInterval;

class ProgramPanel extends CostumerPanel {
    /*****************************************************************************
    Builds an array of associative arrays containing the 7 days to come (including
    the actual day) with the language as key and the formated date as value
    *****************************************************************************/
    protected function _getNextDates(): array {
        $timezone = new Timezone;
        $timezone->setTimeZone();

        $nextDates[] = [];
        
        $period = new DatePeriod (
            new DateTime(),
            new DateInterval('P1D'),
            6
        );
        
        foreach ($period as $key => $day) {
            $date = $day->format('w d n Y H:i:s');
            $englishWeekDay = $this->_getEnglishWeekDay($date);
            $frenchFullDate = $this->_getFrenchDate($date);
            $nextDates[$key] = [
                'englishWeekDay' => $englishWeekDay,
                'frenchFullDate' => $frenchFullDate
            ];
        };

        return $nextDates;
    }
    
    private function _getEnglishWeekDay(string $date): string {
        $calendar = new Calendar;

        return $calendar->getWeekDays()[explode(' ', $date)[0]]['english'];
    }
    
    /******************************************************************
    Transforms a date into a string of weekday, day and month in french
    ******************************************************************/ 
    private function _getFrenchDate(string $date): string {
        $calendar = new Calendar;

        $frenchDateWeekDay = $calendar->getWeekDays()[explode(' ', $date)[0]]['french'];
        $dateDay = explode(' ', $date)[1];
        $dateMonth = $calendar->getMonths()[explode(' ',  $date)[2] -1];
        
        return "{$frenchDateWeekDay} {$dateDay} {$dateMonth}";
    }
}
