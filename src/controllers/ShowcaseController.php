<?php

namespace App\Controllers;

require ('./../src/model/ProgramManager.php');
use App\Models\ProgramManager as ProgramManager;

class ShowcaseController {
    private $_showcasePanelsStyles = [
        'pages/showcase-panels',
        'components/header',
        'components/buttons',
        'components/footer'
    ];

    private $_showcasePanelsURLs = [
        'presentation' => 'index.php?page=presentation',
        'coaching' => 'index.php?page=coaching',
        'programsList' => 'index.php?page=programslist',
        'programDetails' => 'index.php?page=programdetails',
        'showcase404' => 'index.php?page=showcase-404'
    ];
  
    public function areProgramDetailsAvailable(string $program) {
        $programManager = new ProgramManager;
        return in_array($program, array_keys($programManager->programs));
    }

    public function getShowcasePanelURL(string $requestedPage) {
        return $this->_showcasePanelsURLs[$requestedPage];
    }

    public function isProgramsListAvailable() {
        $programManager = new ProgramManager;
        return (count($programManager->programs) > 0);
    }
    
    public function renderCoachingPage(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'coaching', 'showcasePanels' => array_keys($this->_getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/coaching.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderPresentationPage(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage'=> 'presentation', 'showcasePanels' => array_keys($this->_getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/presentation.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderProgramDetailsPage(object $twig, string $program) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programDetails', 'showcasePanels' => array_keys($this->_getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/program-details.html.twig', ['requestedPage' => 'programdetails', 'program' => $this->_getProgramDetails($program)]);
        echo $twig->render('components/footer.html.twig');
    }
    
    public function renderProgramsListPage(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programsList', 'showcasePanels' => array_keys($this->_getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/programs-list.html.twig', ['programs' => $this->_getProgramsList()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function render404Page(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'showcase404', 'showcasePanels' => array_keys($this->_getShowcasePanelsURLS())]);
        echo $twig->render('showcase_panels/404.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    private function _getProgramDetails(string $program) {
        $programManager = new ProgramManager;
        $programsList = $programManager->programs;
        return $programsList[$program];
    }

    private function _getProgramsList() {
        $programManager = new ProgramManager;
        return $programManager->programs;
    }

    private function _getShowcasePanelsStyles() {
        return $this->_showcasePanelsStyles;
    }

    private function _getShowcasePanelsURLS() {
        return $this->_showcasePanelsURLs;
    }
}