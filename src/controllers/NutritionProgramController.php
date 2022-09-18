<?php

require ('./../src/controllers/MemberPanelsController.php');

class NutritionProgramController extends MemberPanelsController {
    private $subMenuPage = 'nutritionProgram';

    private $weekDays = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private $meals = array(
        'breakfast' => 'Petit déjeuner',
        'lunch' => 'Déjeuner',
        'diner' => 'Dîner',
        'snacks' => 'Snacks'
    );

    private function getNextDays() {
        $this->setTimeZone();
        $nextDays   = [];
        $period = new DatePeriod (
            new DateTime(),
            new DateInterval('P1D'),
            6
        );

        foreach ($period as $day) {
            $date = $day->format('w d n Y H:i:s');
            $translatedDate = $this->translateDate($date);
            $nextDays[] = $translatedDate;
        }

        return $nextDays;
    }

    private function getMeals() {
        return $this->meals;
    }

    private function translateDate($date) {
        $dateWeekDay = $this->weekDays[explode(' ', $date)[0]];
        $dateDay = explode(' ', $date)[1];
        $dateMonth = $this->months[explode(' ',  $date)[2]];

        return "{$dateWeekDay} {$dateDay} {$dateMonth}";
    }

    public function renderMemberNutritionProgram($twig) {
        $this->getNextDays();
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->getMemberPanels(), 'subPanel' => $this->getMemberPanelsSubpanels($this->subMenuPage)]);
        echo $twig->render('member_panels/nutrition-program.html.twig', ['nextDays' => $this->getNextDays(), 'meals' => $this->getMeals()]);
        echo $twig->render('components/footer.html.twig');
    }
}