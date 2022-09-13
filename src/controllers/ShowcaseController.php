<?php

require ('./../src/model/ProgramManager.php');

class ShowcaseController {
    private $showcasePanelsStyles = [
        'pages/showcase-panels',
        'components/header',
        'components/buttons',
        'components/footer'
    ];

    private $showcasePanelsURLs = array(
        'presentation' => 'index.php?page=presentation',
        'coaching' => 'index.php?page=coaching',
        'programsList' => 'index.php?page=programslist',
        'programDetails' => 'index.php?page=programdetails',
        'showcase404' => 'index.php?page=showcase-404'
    );

    private function getProgramDetails($program) {
        $programManager = new ProgramManager;
        $programsList = $programManager->programs;

        return $programsList[$program];
    }

    private function getProgramsList() {
        $programManager = new ProgramManager;

        return $programManager->programs;
    }

    private function getShowcasePanelsStyles() {
        return $this->showcasePanelsStyles;
    }

    public function getShowcasePanelURL($requestedPage) {
        return $this->showcasePanelsURLs[$requestedPage];
    }

    private function getShowcasePanelsURLS() {
        return $this->showcasePanelsURLs;
    }

    public function renderCoachingPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'coaching', 'showcasePanels' => array_keys($this->getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/coaching.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderPresentationPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage'=> 'presentation', 'showcasePanels' => array_keys($this->getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/presentation.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderProgramDetailsPage($twig, $program) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programDetails', 'showcasePanels' => array_keys($this->getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/program-details.html.twig', ['requestedPage' => 'programdetails', 'program' => $this->getProgramDetails($program)]);
        echo $twig->render('components/footer.html.twig');
    }
    
    public function renderProgramsListPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programsList', 'showcasePanels' => array_keys($this->getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/programs-list.html.twig', ['programs' => $this->getProgramsList()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function render404Page($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'showcase404', 'showcasePanels' => array_keys($this->getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/404.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function verifyProgramDetailsAvailability($program) {
        $programManager = new ProgramManager;

        return in_array($program, array_keys($programManager->programs));
    }

    public function verifyProgramsListAvailability() {
        $programManager = new ProgramManager;

        return (count($programManager->programs) > 0);
    }
}