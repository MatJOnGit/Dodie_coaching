<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Nutrition;
use DatePeriod, DateTime, DateInterval;

class Program extends Subscriber {
    private const PROGRAM_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ProgramDisplayer.model',
        'classes/ProgramInitializer.model',
        'programManagementApp'
    ];
    
    public function buildProgramData(int $subscriberId) {
        $weekDays = $this->_getNextWeekDays();
        $mealsIndexes = $this->_getMealsIndexes($subscriberId);
        
        return $this->_buildProgramIngredients($subscriberId, $weekDays, $mealsIndexes);
    }
    
    /*********************************************************************
    Builds an array containing meals selected for the subscriber's program
    *********************************************************************/
    public function getCheckedMeals(): array {
        $validMeals = $this->_getMealsTranslations();
        $checkedMeals = [];
        
        foreach($validMeals as $mealKey => $knownMeal) {
            if (isset($_POST[`meal-` . $mealKey])) {
                array_push($checkedMeals, $knownMeal['english']);
            }
        }
        
        return $checkedMeals;
    }
    
    public function renderSubscriberProgramPage(object $twig, int $subscriberId): void {
        echo $twig->render('admin_panels/subscriber-program.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Programme',
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-profile&id=' . $subscriberId, 'Profil abonnés'],
            'subscriberHeaders' => $this->getSubscriberHeaders($subscriberId),
            'programData' => $this->buildProgramData($subscriberId),
            'programMeals' => $this->_getProgramMeals($subscriberId),
            'isProgramFileUpdatable' => $this->isProgramFileUpdatable($subscriberId),
            'weekDaysTranslations' => $this->_buildWeekDaysTranslations(),
            'mealsTranslations' => $this->_getMealsTranslations(),
            'pageScripts' => $this->_getProgramScripts()
        ]);
    }
    
    /**************************************************************************************
    Converts an array of meals into a string separated with commas, then set it in database
    **************************************************************************************/
    public function saveProgramMeals(int $subscriberId, array $mealsList) {
        $nutrition = new Nutrition;
        
        $meals = '';
        
        foreach($mealsList as $mealItem) {
            $meals = empty($meals) ? $mealItem : $meals . ', ' . $mealItem;
        }
        
        return $nutrition->updateMealsList($subscriberId, $meals);
    }
    
    /********************************************************************************************
    Builds an associative array containing ingredients for each meal and for each day of the week
    ********************************************************************************************/
    private function _buildProgramIngredients (int $subscriberId, array $weekDays, array $meals): array {
        $nutrition = new Nutrition;
        
        $programIngredients = [];
        
        foreach($weekDays as $weekDay) {
            $programIngredients += [$weekDay['englishWeekDay'] => []];
            
            foreach($meals as $meal) {
                $programIngredients[$weekDay['englishWeekDay']] += [$meal['meal_index'] => []];
                
                $mealIngredients = $nutrition->selectMealIngredients($subscriberId, $weekDay['englishWeekDay'], $meal['meal_index']);
                
                foreach($mealIngredients as $ingredientKey => $ingredient) {
                    $programIngredients[$weekDay['englishWeekDay']][$meal['meal_index']] += [$ingredientKey => $ingredient];
                }
            }
        }
        
        return $programIngredients;
    }
    
    /***********************************************************************************
    Builds an associative array containing the translation of meal in english and french
    ***********************************************************************************/
    protected function _buildWeekDaysTranslations(): array {
        $weekDays = $this->_getWeekDays();
        
        $orderedEnglishWeekDaysList = [];
        $orderedWeekIndex = [1, 2, 3, 4, 5, 6, 0];
        foreach($orderedWeekIndex as $key => $dayIndex) {
            $orderedEnglishWeekDaysList += [$key => ['english' => $weekDays[$dayIndex]['english'], 'french' => $weekDays[$dayIndex]['french']]];
        }
        
        return $orderedEnglishWeekDaysList;
    }
    
    private function _getFrenchWeekDay(string $date): string {
        return $this->_getWeekDays()[explode(' ', $date)[0]]['french'];
    }
    
    private function _getMealsIndexes(int $subscriberId) {
        $nutrition = new Nutrition;
        
        return $nutrition->selectMealsIndexes($subscriberId);
    }
    
    /************************************************************************************
    Builds an array of associative arrays containing next week days in english and french
    ************************************************************************************/
    private function _getNextWeekDays(): array {
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
            $weekDays[$key] = [
                'englishWeekDay' => $englishWeekDay,
                'frenchFullDate' => $frenchWeekDay
            ];
        }
        
        return $weekDays;
    }
    
    private function _getProgramScripts(): array {
        return self::PROGRAM_SCRIPTS;
    }
}