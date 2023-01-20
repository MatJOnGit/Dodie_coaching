<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Accounts as AccountsModel;
use Dodie_Coaching\Models\Nutrition as NutritionModel;
use Dodie_Coaching\Models\ProgramFiles as ProgramFilesModel;
use DatePeriod, DateTime, DateInterval;

class Nutrition extends UserPanels {
    private $_programsFolderRoute = './../var/nutrition_programs/';
    
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
    
    public function isMealRequested(): bool {
        return (isset($_GET['meal']) && !isset($_GET['request']));
    }
    
    public function isMenuRequested(): bool {
        return (!isset($_GET['meal']) && !isset($_GET['request']));
    }
    
    public function isRequestSet(): bool {
        return (!isset($_GET['day']) && !isset($_GET['meal']) && isset($_GET['request']));
    }
    
    public function isShoppingListRequested($request): bool {
        return $request === 'shopping-list';
    }
    
    public function renderMealDetails(object $twig, array $mealData) {
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
    
    public function renderNutritionMenu(object $twig, $subscriberId) {
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
    
    public function renderShoppingList(object $twig) {
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

        return $nutrition->selectMealDetails($mealData['day'], str_replace('_', ' #', $mealData['meal']), $_SESSION['email']);
    }
    
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
            $nextDates[$key] = ['englishWeekDay' => $englishWeekDay, 'frenchFullDate' => $frenchFullDate];
        }

        return $nextDates;
    }
    
    private function _getProgramsFilePath($subscriberId) {
        $programFile = new ProgramFilesModel;
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

    public function getUserId() {
        $account = new AccountsModel;

        return $account->selectId($_SESSION['email']);
    }
}