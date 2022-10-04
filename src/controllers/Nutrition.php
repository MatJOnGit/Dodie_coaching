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

    private $_nutritionMenuPage = 'nutrition';
    
    private $_panelFrenchName = [
        'mealDetails' => "Liste d'ingrédients",
        'shoppingList' => "Liste de courses"
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

    public function getRequest(): string {
        return htmlspecialchars($_GET['request']);
    }

    public function renderMealIngredients(object $twig, array $mealData) {
        echo $twig->render('components/head.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles()
        ]);
        echo $twig->render('components/header.html.twig', [
            'userPanels' => $this->_getUserPanels(),
            'subPanel' => $this->_getUserPanelsSubpanels($this->_getNutritionMenuPage()),
            'nutritionPanel' => $this->_getPanelFrenchName('mealDetails')
        ]);
        echo $twig->render('user_panels/meal-details.html.twig', [
            'meal' => $this->_getTranslatedMealData($mealData),
            'ingredients' => $this->_getMealIngredients($mealData)
        ]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderNutritionMenu(object $twig) {
        echo $twig->render('components/head.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles()
        ]);
        echo $twig->render('components/header.html.twig', [
            'userPanels' => $this->_getUserPanels(),
            'subPanel' => $this->_getUserPanelsSubpanels($this->_getNutritionMenuPage())]);
        echo $twig->render('user_panels/nutrition.html.twig', [
            'nextDays' => $this->_getNextDates(),
            'meals' => $this->_getMeals(),
            'programFilePath' => $this->_getProgramsFilePath()
        ]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderShoppingList(object $twig) {
        echo $twig->render('components/head.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles()
        ]);
        echo $twig->render('components/header.html.twig', [
            'userPanels' => $this->_getUserPanels(),
            'subPanel' => $this->_getUserPanelsSubpanels($this->_getNutritionMenuPage()),
            'nutritionPanel' => $this->_getPanelFrenchName('shoppingList')
        ]);
        echo $twig->render('user_panels/shopping-list.html.twig', [
            'shoppingList' => $this->_getShoppingList()
        ]);
        echo $twig->render('components/footer.html.twig');
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

    private function _getMealIngredients(array $mealData) {
        $nutrition = new NutritionModel;

        return $nutrition->selectMealDetails($mealData['day'], $mealData['meal'], $_SESSION['user-email']);
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

    private function _getNutritionMenuPage(): string {
        return $this->_nutritionMenuPage;
    }

    private function _getPanelFrenchName($page) {
        return $this->_panelFrenchName[$page];
    }

    private function _getProgramsFilePath() {
        $nutrition = new NutritionModel;
        $fileName = $nutrition->selectFileName($_SESSION['user-email']);

        return $fileName[0] ? $this->_getProgramsFolderRoute() . $fileName[0] . '.txt' : null;
    }

    private function _getProgramsFolderRoute(): string {
        return $this->_programsFolderRoute;
    }

    private function _getShoppingList() {
        $nutrition = new NutritionModel;

        return $nutrition->selectMealsIngredients($_SESSION['user-email']);
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