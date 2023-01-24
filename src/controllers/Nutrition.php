<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Account;
use Dodie_Coaching\Models\Nutrition as NutritionModel;
use Dodie_Coaching\Models\ProgramFile;
use DatePeriod, DateTime, DateInterval;

class Nutrition extends UserPanel {
    private $_programsFolderRoute = './../var/nutrition_programs/';
    
    /***************************************************************
    Tests if 'day' and 'meal' url parameters are known (respectively
    in weekDays and mealsTranslations of Main controller variables)
    ***************************************************************/
    public function areMealParamsValid(array $mealData): bool {
        $requestedDay = $mealData['day'];
        $requestedMeal = str_replace('_', ' #', $mealData['meal']);
        
        $isDayValid = false;
        $isMealValid = false;
        
        foreach($this->_getWeekDays() as $weekDay) {
            if ($requestedDay === $weekDay['english']) {
                $isDayValid = true;
            }
        }
        
        foreach($this->_getMealsTranslations() as $meal) {
            if ($requestedMeal === $meal['english']) {
                $isMealValid = true;
            }
        }
        
        return ($isDayValid && $isMealValid);
    }
    
    /*************************************************************************************
    Converts url parameters into a associative array containing the day and meal requested
    *************************************************************************************/
    public function getMealData(): array {
        $meal = htmlspecialchars($_GET['meal']);
        $mealData = [
            'day' => false,
            'meal' => false
        ];
        
        if (is_string($meal) && strpos($meal, '-')) {
            $mealData['day'] = explode('-', htmlspecialchars($_GET['meal']))[0];
            $mealData['meal'] = explode('-', htmlspecialchars($_GET['meal']))[1];
        }
        
        return $mealData;
    }
    
    public function getRequest(): string {
        return htmlspecialchars($_GET['request']);
    }
    
    public function getUserId() {
        $account = new Account;
        
        return $account->selectId($_SESSION['email']);
    }
    
    public function isMealRequested(): bool {
        return (isset($_GET['meal']) && !isset($_GET['request']));
    }
    
    public function isMenuRequested(): bool {
        return (!isset($_GET['meal']) && !isset($_GET['request']));
    }
    
    public function isRequestSet(): bool {
        return (!isset($_GET['day']) && !isset($_GET['meal']) && isset($_GET['request']));
    }
    
    public function isShoppingListRequested(string $request): bool {
        return $request === 'shopping-list';
    }
    
    public function renderMealDetailsPage(object $twig, array $mealData): void {
        echo $twig->render('user_panels/meal-details.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'Ingrédients',
            'appSection' => 'userPanels',
            'prevPanel' => ['nutrition', 'Nutrition'],
            'subPanel' => 'Composition du repas',
            'meal' => $this->_getTranslatedMealData($mealData),
            'ingredients' => $this->_getMealDetails($mealData)
        ]);
    }
    
    public function renderNutritionMenuPage(object $twig, int $subscriberId): void {
        echo $twig->render('user_panels/nutrition.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'Nutrition',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord'],
            'nextDays' => $this->_getNextDates(),
            'meals' => $this->_getProgramMeals($subscriberId),
            'mealsTranslations' => $this->_getMealsTranslations(),
            'programFilePath' => $this->_getProgramsFilePath($subscriberId)
        ]);
    }
    
    public function renderShoppingListPage(object $twig): void {
        echo $twig->render('user_panels/shopping-list.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'Liste de courses',
            'appSection' => 'userPanels',
            'prevPanel' => ['nutrition', 'Nutrition'],
            'subPanel' => 'Liste de courses',
            'shoppingList' => $this->_getShoppingList()
        ]);
    }
    
    private function _getMealDetails(array $mealData) {
        $nutrition = new NutritionModel;
        
        $day = $mealData['day'];
        $meal = str_replace('_', ' #', $mealData['meal']);
        
        return $nutrition->selectMealDetails($day, $meal, $_SESSION['email']);
    }
    
    /*****************************************************************************
    Builds an array of associative arrays containing the 7 days to come (including
    the actual day) with the language as key and the formated date as value
    *****************************************************************************/
    private function _getNextDates(): array {
        $this->_setTimeZone();
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
        }
        
        return $nextDates;
    }
    
    /*****************************************************************************************
    Builds the full path to subscriber's program file if existing and if the file has a status
    *****************************************************************************************/
    private function _getProgramsFilePath(int $subscriberId) {
        $programFile = new ProgramFile;
        $fileName = $programFile->selectFileName($_SESSION['email']);
        $fileStatus = $this->getProgramFileStatus($subscriberId);
        
        return ($fileName && $fileStatus) ? $this->_getProgramsFolderRoute() . $fileName[0] . '.pdf' : null;
    }
    
    private function _getProgramsFolderRoute(): string {
        return $this->_programsFolderRoute;
    }
    
    private function _getShoppingList() {
        $nutrition = new NutritionModel;
        
        return $nutrition->selectMealsIngredients($_SESSION['email']);
    }
    
    /*************************************************************************************
    Builds an associative array containing the french date and the translation of the meal
    *************************************************************************************/
    private function _getTranslatedMealData(array $mealData): array {
        foreach($this->_getNextDates() as $day) {
            if ($mealData['day'] === $day['englishWeekDay']) {
                $mealData['day'] = $day['frenchFullDate'];
            }
        }
        
        foreach($this->_getMealsTranslations() as $meal) {
            if (str_replace('_', ' #', $mealData['meal']) === $meal['english']) {
                $mealData['meal'] = $meal['french'];
            }
        }
        
        return $mealData;
    }
}