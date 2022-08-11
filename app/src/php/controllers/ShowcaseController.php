<?php

require ('app/src/php/controllers/MainController.php');
require('app/src/php/model/ProgramManager.php');

class ShowcaseController {
    public $showcasePagesStyles = [
        'pages/showcase-panels',
        'components/header',
        'components/buttons',
        'components/footer'
    ];

    public $showcasePanels = ['presentation', 'coaching', 'programslist', 'programdetails', 'showcase-404'];

    public function verifyProgramsList() {
        $programManager = new ProgramManager;
        $isProgramsListAvailable = (count($programManager->programs) > 0);

        return $isProgramsListAvailable;
    }

    public function verifyProgramDetails($program) {
        $programManager = new ProgramManager;
        $areProgramDetailsAvailable = in_array($program, array_keys($programManager->programs));

        return $areProgramDetailsAvailable;
    }

    public function getProgramsList() {
        $programManager = new ProgramManager;
        $programsList = $programManager->programs;

        return $programsList;
    }

    public function getProgramDetails() {
        $programManager = new ProgramManager;
        $programsList = $programManager->programs;
        $programDetails = $programsList[htmlspecialchars($_GET['program'])];

        return $programDetails;
    }

    public function renderPresentationPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage'=> 'presentation', 'showcasePanels' => $this->showcasePanels]);
        echo $twig->render('showcase_panels/presentation.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderCoachingPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'coaching', 'showcasePanels' => $this->showcasePanels]);
        echo $twig->render('showcase_panels/coaching.html.twig');
        echo $twig->render('components/footer.html.twig');
    }
    
    public function renderProgramsListPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programslist', 'showcasePanels' => $this->showcasePanels]);
        echo $twig->render('showcase_panels/programs-list.html.twig', ['programs' => $this->getProgramsList()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderProgramDetailsPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programdetails', 'showcasePanels' => $this->showcasePanels]);
        echo $twig->render('showcase_panels/program-details.html.twig', ['requestedPage' => 'programdetails', 'program' => $this->getProgramDetails()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function render404Page($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'showcase-404', 'showcasePanels' => $this->showcasePanels]);
        echo $twig->render('showcase_panels/404.html.twig');
        echo $twig->render('components/footer.html.twig');
    }
}