<?php

namespace App\Domain\Controllers\CostumerPanels;

use App\Entities\Program;
use App\Entities\Timezone;
use DatePeriod, DateTime, DateInterval;

class ProgramPanel extends CostumerPanel {
    /*****************************************************************************
    Builds an array of associative arrays containing the 7 days to come (including
    the actual day) with the language as key and the formated date as value
    *****************************************************************************/
    protected function _getNextDates(): array {
        $timezone = new Timezone;
        $timezone->setTimezone();

        $program = new Program;

        $nextDates[] = [];
        
        $period = new DatePeriod (
            new DateTime(),
            new DateInterval('P1D'),
            6
        );
        
        foreach ($period as $key => $day) {
            $date = $day->format('w d n Y H:i:s');
            $englishWeekDay = $program->getEnglishWeekDay($date);
            $frenchFullDate = $program->getFrenchDate($date);
            $nextDates[$key] = [
                'englishWeekDay' => $englishWeekDay,
                'frenchFullDate' => $frenchFullDate
            ];
        };

        return $nextDates;
    }
    
    
    
    
}
