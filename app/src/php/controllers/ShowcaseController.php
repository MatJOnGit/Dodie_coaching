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
        $programsData = $this->getProgramsList();

        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage' => 'programslist']);
        echo $twig->render('showcase/programslist.html.twig', ['programs' => $programsData]);
        echo $twig->render('templates/footer.html.twig');
    }

    public function renderProgramDetailsPage($twig) {
        $programDetails = $this->getProgramDetails();
        
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage'=> 'programdetails']);
        echo $twig->render('showcase/programdetails.html.twig', ['requestedPage' => 'programdetails', 'program' => $programDetails]);
        echo $twig->render('templates/footer.html.twig');
    }

    public function render404Page($twig) {
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->showcasePagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage'=> 'showcase-404']);
        echo $twig->render('showcase/404.html.twig');
        echo $twig->render('templates/footer.html.twig');
    }
}