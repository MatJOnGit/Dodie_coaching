<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Nutrition as NutritionModel, DatePeriod, DateTime, DateInterval;

class Programs extends Subscribers {
    private $_programScripts = [
        'classes/UserPanels.model',
        'classes/ProgramHelper.model',
        'programHelperApp'
    ];
    
    public function renderSubscriberProgramPage(object $twig, int $subscriberId) {
        echo $twig->render('admin_panels/subscriber-program.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Programme",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-profile&id=' . $subscriberId, 'Profil abonnés'],
            'subscriberHeaders' => $this->_getSubscriberHeaders($subscriberId),
            'subscriberMeals' => $this->_getSubscriberMeals($subscriberId),
            'weekDaysList' => $this->_getWeekDaysList(),
            'mealsIndex' => $this->_getMealsList($subscriberId),
            'mealsList' => $this->_getMeals(),
            'pageScripts' => $this->_getProgramScripts()
        ]);
    }

    private function _getWeekDaysList() {
        $weekDays = $this->_getWeekDays();

        $orderedEnglishWeekDaysList = [];
        $orderedWeekIndex = [1, 2, 3, 4, 5, 6, 0];
        foreach($orderedWeekIndex as $key => $dayIndex) {

            $orderedEnglishWeekDaysList += [$key => ['english' => $weekDays[$dayIndex]['english'], 'french' => $weekDays[$dayIndex]['french']]];
            
            // var_dump($orderedEnglishWeekDaysList);
        }

        return $orderedEnglishWeekDaysList;
    }

    private function _buildProgramIngredients($nutrition, $subscriberId, $weekDays, $meals) {
        $programIngredients = [];

        foreach($weekDays as $weekDay) {
            $programIngredients += [$weekDay["englishWeekDay"] => []];

            foreach($meals as $meal) {
                $programIngredients[$weekDay["englishWeekDay"]] += [$meal["meal_index"] => []];

                $mealIngredients = $nutrition->selectMealIngredients($subscriberId, $weekDay["englishWeekDay"], $meal["meal_index"]);

                foreach($mealIngredients as $ingredientKey => $ingredient) {
                    $programIngredients[$weekDay["englishWeekDay"]][$meal["meal_index"]] += [$ingredientKey => $ingredient];
                }
            }
        }
        
        return $programIngredients;
    }

    private function _getFrenchWeekDay(string $date): string {
        return $this->_getWeekDays()[explode(' ', $date)[0]]['french'];
    }
    
    private function _getProgramScripts(): array {
        return $this->_programScripts;
    }

    private function _getRegularWeekDays(): array {
        $this->_setTimeZone();
        
        $lastMonday = new DateTime();
        $lastMonday->modify('last Monday');
        
        $weekDays[] = [];

        $period = new DatePeriod (
            $lastMonday,
            new DateInterval('P1D'),
            6
        );

        foreach ($period as $key => $day) {
            $date = $day->format('w d');
            $englishWeekDay = $this->_getEnglishWeekDay($date);
            $frenchWeekDay = $this->_getFrenchWeekDay($date);
            $weekDays[$key] = ['englishWeekDay' => $englishWeekDay, 'frenchFullDate' => $frenchWeekDay];
        }

        return $weekDays;
    }

    private function _getMealsList($subscriberId) {
        $nutrition = new NutritionModel;

        return $nutrition->selectSubscriberMeals($subscriberId);
    }

    private function _getSubscriberMeals($subscriberId) {
        $nutrition = new NutritionModel;

        $weekDays = $this->_getRegularWeekDays();
        $meals = $this->_getMealsList($subscriberId);

        return $this->_buildProgramIngredients($nutrition, $subscriberId, $weekDays, $meals);
    }
}