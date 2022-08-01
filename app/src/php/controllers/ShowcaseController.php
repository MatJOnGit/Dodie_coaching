<?php

require ('app/src/php/controllers/MainController.php');
require('app/src/php/model/ProgramManager.php');

class ShowcaseController {
    public $showcasePagesStyles = [
        'pages/showcase',
        'components/header',
        'components/static-menu',
        'components/buttons',
        'components/footer',
        'themes/blue-theme'
    ];

    public function requestProgramList() {
        $programsManager = new ProgramManager;
        $programsList = $programsManager->getProgramsList();
        return $programsList;
    }

    public function requestProgramDetails() {
        $programsManager = new ProgramManager;
        $programDetails = $programsManager->getProgramDetails($_GET['program']);
        return $programDetails;
    }

    public function renderPresentationPage($twig) {
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage'=> 'presentation']);
        echo $twig->render('showcase/presentation.html.twig');
        echo $twig->render('templates/footer.html.twig');
    }

    public function renderCoachingPage($twig) {
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage'=> 'coaching']);
        echo $twig->render('showcase/coaching.html.twig');
        echo $twig->render('templates/footer.html.twig');
    }
    
    public function renderProgramsListPage($twig) {
        $programsList = $this->requestProgramList();

        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage' => 'programslist']);
        echo $twig->render('showcase/programslist.html.twig', ['programsList' => $programsList]);
        echo $twig->render('templates/footer.html.twig');
    }

    public function renderProgramDetailsPage($twig) {
        $programsDetails = $this->requestProgramDetails();
        
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage'=> 'programdetails']);
        echo $twig->render('showcase/programdetails.html.twig', ['requestedPage' => 'programdetails', 'program' => $programsDetails]);
        echo $twig->render('templates/footer.html.twig');
    }
}