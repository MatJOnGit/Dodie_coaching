<?php

require('app/src/php/model/ProgramsManager.php');
require ('app/src/php/controllers/MainController.php');

class ShowcaseController {
    public $introPageStyles = ['pages/showcase-intro', 'components/header', 'components/static-menu', 'components/internal-links', 'components/footer', 'themes/showcase-section'];

    public $programsPageStyles = ['pages/showcase-programs', 'components/header', 'components/static-menu', 'components/internal-links', 'components/footer', 'themes/showcase-section'];

    public function requestProgramList() {
        $programsManager = new ProgramsManager;
        $programsList = $programsManager->getProgramsList();
        return $programsList;
    }

    public function requestProgramDetails() {
        $programsManager = new ProgramsManager;
        $programDetails = $programsManager->getProgramDetails($_GET['program']);
        return $programDetails;
    }

    public function renderPresentationPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->introPageStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'presentation']);
        echo $twig->render('showcase/presentation.twig');
        echo $twig->render('templates/footer.twig');
    }

    public function renderCoachingPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->introPageStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'coaching']);
        echo $twig->render('showcase/coaching.twig');
        echo $twig->render('templates/footer.twig');
    }
    
    public function renderProgramsListPage($twig) {
        $programsList = $this->requestProgramList();

        echo $twig->render('templates/head.twig', ['stylePaths' => $this->programsPageStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage' => 'programslist']);
        echo $twig->render('showcase/programslist.twig', ['programsList' => $programsList]);
        echo $twig->render('templates/footer.twig');
    }

    public function renderProgramDetailsPage($twig) {
        $programsDetails = $this->requestProgramDetails();
        
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->programsPageStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'programdetails']);
        echo $twig->render('showcase/programdetails.twig', ['requestedPage' => 'programdetails', 'program' => $programsDetails]);
        echo $twig->render('templates/footer.twig');
    }
}