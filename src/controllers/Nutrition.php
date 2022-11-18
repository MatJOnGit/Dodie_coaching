<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Nutrition as NutritionModel, DatePeriod, DateTime, DateInterval;

class Nutrition extends UserPanels {
    private $_meals = [
        ['english' => 'breakfast', 'french' => 'Petit déjeuner'],
        ['english' => 'lunch', 'french' => 'Déjeuner'],
        ['english' => 'diner', 'french' => 'Dîner'],
        ['english' => 'snacks', 'french' => 'Snacks']
    ];

    private $_programsFolderRoute = './../var/nutrition_programs/';

    private $_weekDays = [
        ['english' => 'sunday', 'french' => 'Dimanche'],
        ['english' => 'monday', 'french' => 'Lundi'],
        ['english' => 'tuesday', 'french' => 'Mardi'],
        ['english' => 'wednesday', 'french' => 'Mercredi'],
        ['english' => 'thursday', 'french' => 'Jeudi'],
        ['english' => 'friday', 'french' => 'Vendredi'],
        ['english' => 'saturday', 'french' => 'Samedi'],
        ['english' => 'sunday', 'french' => 'Dimanche']
    ];

    public function areMealParamsValid(array $mealData): bool {
        $requestedDay = $mealData['day'];
        $requestedMeal = $mealData['meal'];
        $isDayValid = false;
        $isMealValid = false;

        foreach($this->_getWeekDays() as $weekDay) {
            if ($requestedDay === $weekDay['english']) {
                $isDayValid = true;
            }
        }

        foreach($this->_getMeals() as $meal) {
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

    public function renderNutritionMenu(object $twig) {
        echo $twig->render('user_panels/nutrition.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'Nutrition',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord'],
            'nextDays' => $this->_getNextDates(),
            'meals' => $this->_getMeals(),
            'programFilePath' => $this->_getProgramsFilePath()
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

    private function _getEnglishWeekDay(string $date): string {
        return $this->_getWeekDays()[explode(' ', $date)[0]]['english'];
    }

    private function _getFrenchDate(string $date): string {
        $frenchDateWeekDay = $this->_getWeekDays()[explode(' ', $date)[0]]['french'];
        $dateDay = explode(' ', $date)[1];
        $dateMonth = $this->_getMonths()[explode(' ',  $date)[2] -1];

        return "{$frenchDateWeekDay} {$dateDay} {$dateMonth}";
    }

    private function _getMealDetails(array $mealData) {
        $nutrition = new NutritionModel;

        return $nutrition->selectMealDetails($mealData['day'], $mealData['meal'], $_SESSION['email']);
    }

    private function _getMeals(): array {
        return $this->_meals;
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

    private function _getProgramsFilePath() {
        $nutrition = new NutritionModel;
        $fileName = $nutrition->selectProgramFileName($_SESSION['email']);

        return $fileName ? $this->_getProgramsFolderRoute() . $fileName[0] . '.txt' : null;
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

        foreach($this->_getMeals() as $meal) {
            if ($mealData['meal'] === $meal['english']) {
                $mealData['meal'] = $meal['french'];
            }
        }

        return $mealData;
    }

    private function _getWeekDays(): array {
        return $this->_weekDays;
    }
}