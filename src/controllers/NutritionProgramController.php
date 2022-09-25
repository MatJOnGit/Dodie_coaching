<?php

require_once ('./../src/model/NutritionProgramManager.php');
require ('./../src/controllers/MemberPanelsController.php');

class NutritionProgramController extends MemberPanelsController {
    private $meals = [
        ['english' => 'breakfast', 'french' => 'Petit déjeuner'],
        ['english' => 'lunch', 'french' => 'Déjeuner'],
        ['english' => 'diner', 'french' => 'Dîner'],
        ['english' => 'snacks', 'french' => 'Snacks']
    ];

    private $nutritionMenuPage = 'nutritionProgram';

    private $programsFilesRoot = './../var/nutrition_programs/';

    public $weekDays = [
        ['english' => 'sunday', 'french' => 'Dimanche'],
        ['english' => 'monday', 'french' => 'Lundi'],
        ['english' => 'tuesday', 'french' => 'Mardi'],
        ['english' => 'wednesday', 'french' => 'Mercredi'],
        ['english' => 'thursday', 'french' => 'Jeudi'],
        ['english' => 'friday', 'french' => 'Vendredi'],
        ['english' => 'saturday', 'french' => 'Samedi'],
        ['english' => 'sunday', 'french' => 'Dimanche']
    ];



    /********************************************************************/

    private function getProgramFilePath() {
        $nutritionProgramManager = new NutritionProgramManager;
        $nutritionFileName = $nutritionProgramManager->getNutritionFileName($_SESSION['user-email']);

        return $nutritionFileName[0] ? $this->programsFilesRoot . $nutritionFileName[0] . '.txt' : null;
    }

    /********************************************************************/





    public function areMealParamsValid($mealData) {
        $requestedDay = $mealData['day'];
        $requestedMeal = $mealData['meal'];
        $isDayValid = false;
        $isMealValid = false;

        foreach($this->getWeekDays() as $weekDay) {
            if ($requestedDay === $weekDay['english']) {
                $isDayValid = true;
            }
        }

        foreach($this->getMeals() as $meal) {
            if ($requestedMeal === $meal['english']) {
                $isMealValid = true;
            }
        }

        return ($isDayValid && $isMealValid);
    }

    private function getEnglishWeekDay($date) {
        return $this->getWeekDays()[explode(' ', $date)[0]]['english'];
    }

    private function getFrenchDate($date) {
        $frenchDateWeekDay = $this->getWeekDays()[explode(' ', $date)[0]]['french'];
        $dateDay = explode(' ', $date)[1];
        $dateMonth = $this->getMonths()[explode(' ',  $date)[2] -1];

        return "{$frenchDateWeekDay} {$dateDay} {$dateMonth}";
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

    private function getNextDates() {
        $this->setTimeZone();
        $nextDates[] = [];

        $period = new DatePeriod (
            new DateTime(),
            new DateInterval('P1D'),
            6
        );

        foreach ($period as $key => $day) {
            $date = $day->format('w d n Y H:i:s');
            $englishWeekDay = $this->getEnglishWeekDay($date);
            $frenchFullDate = $this->getFrenchDate($date);
            $nextDates[$key] = ['englishWeekDay' => $englishWeekDay, 'frenchFullDate' => $frenchFullDate];
        }

        return $nextDates;
    }

    private function getNutritionMenuPage() {
        return $this->nutritionMenuPage;
    }

    public function isMealRequested() {
        return (isset($_GET['meal']) && !isset($_GET['request']));
    }

    public function isMenuRequested() {
        return (!isset($_GET['meal']) && !isset($_GET['request']));
    }

    public function getMealIngredients($mealData) {
        $nutritionProgramManager = new NutritionProgramManager;

        return $nutritionProgramManager->getMealDetails($mealData['day'], $mealData['meal'], $_SESSION['user-email']);
    }

    private function getMeals() {
        return $this->meals;
    }

    public function getShoppingList() {
        $nutritionProgramManager = new NutritionProgramManager;

        return $nutritionProgramManager->getWeeklyMealsIngredients($_SESSION['user-email']);
    }

    private function getTranslatedMealData($mealData) {
        foreach($this->getNextDates() as $day) {
            if ($mealData['day'] === $day['englishWeekDay']) {
                $mealData['day'] = $day['frenchFullDate'];
            }
        }

        foreach($this->getMeals() as $meal) {
            if ($mealData['meal'] === $meal['english']) {
                $mealData['meal'] = $meal['french'];
            }
        }
        return $mealData;
    }

    private function getWeekDays() {
        return $this->weekDays;
    }

    public function renderMealComposition($twig, $mealData) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->getMemberPanels(), 'subPanel' => $this->getMemberPanelsSubpanels($this->getNutritionMenuPage()), 'nutritionPanel' => 'mealPage']);
        echo $twig->render('member_panels/meal-composition.html.twig', ['meal' => $this->getTranslatedMealData($mealData), 'ingredients' => $this->getMealIngredients($mealData)]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderNutritionProgramMenu($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->getMemberPanels(), 'subPanel' => $this->getMemberPanelsSubpanels($this->getNutritionMenuPage()), 'page' => true]);
        echo $twig->render('member_panels/nutrition-program.html.twig', ['nextDays' => $this->getNextDates(), 'meals' => $this->getMeals(), 'programFilePath' => $this->getProgramFilePath()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderShoppingList($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->getMemberPanels(), 'subPanel' => $this->getMemberPanelsSubpanels($this->getNutritionMenuPage()), 'nutritionPanel' => 'mealPage']);
        echo $twig->render('member_panels/shopping-list.html.twig', ['shoppingList' => $this->getShoppingList()]);
        echo $twig->render('components/footer.html.twig');
    }
}