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

    public $showcasePanelsURL = array(
        'presentation' => 'index.php',
        'coaching' => 'index.php?page=coaching',
        'programsList' => 'index.php?page=programslist',
        'programDetails' => 'index.php?page=programdetails',
        'showcase404' => 'index.php?page=showcase-404'
    );

    public function verifyProgramsListAvailability() {
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
        echo $twig->render('components/header.html.twig', ['requestedPage'=> 'presentation', 'showcasePanels' => array_keys($this->showcasePanelsURL)]);
        echo $twig->render('showcase_panels/presentation.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderCoachingPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'coaching', 'showcasePanels' => array_keys($this->showcasePanelsURL)]);
        echo $twig->render('showcase_panels/coaching.html.twig');
        echo $twig->render('components/footer.html.twig');
    }
    
    public function renderProgramsListPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programsList', 'showcasePanels' => array_keys($this->showcasePanelsURL)]);
        echo $twig->render('showcase_panels/programs-list.html.twig', ['programs' => $this->getProgramsList()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderProgramDetailsPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programDetails', 'showcasePanels' => array_keys($this->showcasePanelsURL)]);
        echo $twig->render('showcase_panels/program-details.html.twig', ['requestedPage' => 'programdetails', 'program' => $this->getProgramDetails()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function render404Page($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'showcase404', 'showcasePanels' => array_keys($this->showcasePanelsURL)]);
        echo $twig->render('showcase_panels/404.html.twig');
        echo $twig->render('components/footer.html.twig');
    }
}