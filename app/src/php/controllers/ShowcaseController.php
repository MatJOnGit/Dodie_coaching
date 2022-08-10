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

    public $showcasePages = ['presentation', 'coaching', 'programslist', 'programdetails', 'showcase-404'];

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
        echo $twig->render('components/header.html.twig', ['requestedPage'=> 'presentation', 'showcasePages' => $this->showcasePages]);
        echo $twig->render('showcase/presentation.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderCoachingPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'coaching', 'showcasePages' => $this->showcasePages]);
        echo $twig->render('showcase/coaching.html.twig');
        echo $twig->render('components/footer.html.twig');
    }
    
    public function renderProgramsListPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programslist', 'showcasePages' => $this->showcasePages]);
        echo $twig->render('showcase/programslist.html.twig', ['programs' => $this->getProgramsList()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderProgramDetailsPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programdetails', 'showcasePages' => $this->showcasePages]);
        echo $twig->render('showcase/programdetails.html.twig', ['requestedPage' => 'programdetails', 'program' => $this->getProgramDetails()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function render404Page($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'showcase-404', 'showcasePages' => $this->showcasePages]);
        echo $twig->render('showcase/404.html.twig');
        echo $twig->render('components/footer.html.twig');
    }
}