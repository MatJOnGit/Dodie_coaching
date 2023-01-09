<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Nutrition as NutritionModel;
use Dodie_Coaching\Models\Subscribers as SubscribersModel;
use DatePeriod, DateTime, DateInterval;

class Programs extends Subscribers {
    private $_programScripts = [
        'classes/UserPanels.model',
        'classes/ProgramDisplayer.model',
        'classes/ProgramBuildingHelper.model',
        'programsApp'
    ];
    
    public function renderSubscriberProgramPage(object $twig, int $subscriberId) {
        echo $twig->render('admin_panels/subscriber-program.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Programme",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-profile&id=' . $subscriberId, 'Profil abonnés'],
            'subscriberHeaders' => $this->_getSubscriberHeaders($subscriberId),
            'program' => $this->_getProgram($subscriberId),
            'programMeals' => $this->_getProgramMeals($subscriberId),
            'weekDaysTranslations' => $this->_buildWeekDaysTranslations(),
            'mealsTranslations' => $this->_getMealsTranslations(),
            'pageScripts' => $this->_getProgramScripts()
        ]);
    }

    // Generate an array out of a subscriber's program meals list. Return NULL if no meal is found. 
    private function _getProgramMeals($subscriberId) {
        $subscribers = new SubscribersModel;

        $generatedMeals = $subscribers->selectProgramMeals($subscriberId);

        return strlen($generatedMeals['meals_list']) ? explode(', ', $generatedMeals['meals_list']) : NULL;
    }

    // Build an associative array containing the translation of meal in english and french
    private function _buildWeekDaysTranslations() {
        $weekDays = $this->_getWeekDays();

        $orderedEnglishWeekDaysList = [];
        $orderedWeekIndex = [1, 2, 3, 4, 5, 6, 0];
        foreach($orderedWeekIndex as $key => $dayIndex) {

            $orderedEnglishWeekDaysList += [$key => ['english' => $weekDays[$dayIndex]['english'], 'french' => $weekDays[$dayIndex]['french']]];
        }

        return $orderedEnglishWeekDaysList;
    }

    // Build an associative array containing ingredients for each meal and for each day of the week
    private function _buildProgramIngredients ($subscriberId, $weekDays, $meals) {
        $nutrition = new NutritionModel;

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

        foreach($period as $key => $day) {
            $date = $day->format('w d');
            $englishWeekDay = $this->_getEnglishWeekDay($date);
            $frenchWeekDay = $this->_getFrenchWeekDay($date);
            $weekDays[$key] = ['englishWeekDay' => $englishWeekDay, 'frenchFullDate' => $frenchWeekDay];
        }

        return $weekDays;
    }

    private function _getMealsIndexes($subscriberId) {
        $nutrition = new NutritionModel;

        return $nutrition->selectSubscriberMealsIndexes($subscriberId);
    }

    private function _getProgram($subscriberId) {
        $weekDays = $this->_getRegularWeekDays();
        $mealsIndexes = $this->_getMealsIndexes($subscriberId);
        
        return $this->_buildProgramIngredients($subscriberId, $weekDays, $mealsIndexes);
    }

    // Build an array of checked meals
    public function getCheckedMeals() {
        $validMeals = $this->_getMealsTranslations();
        $checkedMeals = [];

        foreach($validMeals as $mealKey => $knownMeal) {
            if (isset($_POST['meal-' . $mealKey])) {
                array_push($checkedMeals, $knownMeal['english']);
            }
        }

        return $checkedMeals;
    }

    // Convert an array of meals into a string separated with commas, then set it in database
    public function addProgramMeals($subscriberId, $mealsList) {
        $nutrition = new NutritionModel;

        $meals = '';

        foreach($mealsList as $mealItem) {
            $meals = empty($meals) ? $mealItem : $meals . ', ' . $mealItem;
        }

        return $nutrition->updateMealsList($subscriberId, $meals);
    }
}