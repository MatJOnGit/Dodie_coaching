<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\NutritionManager as NutritionManager, DatePeriod, DateTime, DateInterval;

class NutritionController extends MemberPanelsController {
    private $_meals = [
        ['english' => 'breakfast', 'french' => 'Petit déjeuner'],
        ['english' => 'lunch', 'french' => 'Déjeuner'],
        ['english' => 'diner', 'french' => 'Dîner'],
        ['english' => 'snacks', 'french' => 'Snacks']
    ];

    private $_nutritionMenuPage = 'nutrition';

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

    public function areMealParamsValid(array $mealData) {
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

    public function getMealData() {
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

    public function isMealRequested() {
        return (isset($_GET['meal']) && !isset($_GET['request']));
    }

    public function isMenuRequested() {
        return (!isset($_GET['meal']) && !isset($_GET['request']));
    }

    public function isShoppingListRequested() {
        return (!isset($_GET['day']) && !isset($_GET['meal']) && isset($_GET['request']));
    }

    public function renderMealComposition(object $twig, array $mealData) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->_getMemberPanels(), 'subPanel' => $this->_getMemberPanelsSubpanels($this->_getNutritionMenuPage()), 'nutritionPanel' => 'mealPage']);
        echo $twig->render('member_panels/meal-composition.html.twig', ['meal' => $this->_getTranslatedMealData($mealData), 'ingredients' => $this->_getMealIngredients($mealData)]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderNutritionMenu(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->_getMemberPanels(), 'subPanel' => $this->_getMemberPanelsSubpanels($this->_getNutritionMenuPage())]);
        echo $twig->render('member_panels/nutrition.html.twig', ['nextDays' => $this->_getNextDates(), 'meals' => $this->_getMeals(), 'programFilePath' => $this->_getProgramsFilePath()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderShoppingList(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->_getMemberPanels(), 'subPanel' => $this->_getMemberPanelsSubpanels($this->_getNutritionMenuPage()), 'nutritionPanel' => 'mealPage']);
        echo $twig->render('member_panels/shopping-list.html.twig', ['shoppingList' => $this->_getShoppingList()]);
        echo $twig->render('components/footer.html.twig');
    }

    private function _getEnglishWeekDay(string $date) {
        return $this->_getWeekDays()[explode(' ', $date)[0]]['english'];
    }

    private function _getFrenchDate(string $date) {
        $frenchDateWeekDay = $this->_getWeekDays()[explode(' ', $date)[0]]['french'];
        $dateDay = explode(' ', $date)[1];
        $dateMonth = $this->_getMonths()[explode(' ',  $date)[2] -1];

        return "{$frenchDateWeekDay} {$dateDay} {$dateMonth}";
    }

    private function _getMealIngredients(array $mealData) {
        $nutritionManager = new NutritionManager;

        return $nutritionManager->getMealDetails($mealData['day'], $mealData['meal'], $_SESSION['user-email']);
    }

    private function _getMeals() {
        return $this->_meals;
    }

    private function _getNextDates() {
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

    private function _getNutritionMenuPage() {
        return $this->_nutritionMenuPage;
    }

    private function _getProgramsFilePath() {
        $nutritionManager = new NutritionManager;
        $nutritionFileName = $nutritionManager->getNutritionFileName($_SESSION['user-email']);

        return $nutritionFileName[0] ? $this->_getProgramsFolderRoute() . $nutritionFileName[0] . '.txt' : null;
    }

    private function _getProgramsFolderRoute() {
        return $this->_programsFolderRoute;
    }

    private function _getShoppingList() {
        $nutritionManager = new NutritionManager;

        return $nutritionManager->getWeeklyMealsIngredients($_SESSION['user-email']);
    }

    private function _getTranslatedMealData(array $mealData) {
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

    private function _getWeekDays() {
        return $this->_weekDays;
    }
}