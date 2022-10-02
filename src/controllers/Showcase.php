<?php

namespace Dodie_Coaching\Controllers;

class Showcase extends Main {
    public $programsList = [
        'monthly' => [
            'name' => 'monthly',
            'frenchTitle' => 'Formule mois',
            'duration' => 30,
            'subscriptionPrice' => 219,
            'description' => "Vous avez un mariage de prévu et vous avez besoin d'un coup de main pour rentrer dans votre robe ou votre costume ?<br/><br/>Vous souhaitez peut-être simplement tester par vous-même mes services ?<br/><br/>Ce programme vous donnera des bases solides pour commencer à prendre soin de vous, et en toute sérénité."
        ],
        'quarterly' => [
            'name' => 'quarterly',
            'frenchTitle' => 'Formule trimestre',
            'duration' => 90,
            'subscriptionPrice' => 649,
            'description' => ""
        ],
        'halfyearly' => [
            'name' => 'halfyearly',
            'frenchTitle' => 'Formule 6 mois',
            'duration' => 180,
            'subscriptionPrice' => 1199,
            'description' => ""
        ]
        // for tests with more than 3 programs, uncomment those following lines
        // ,'annual' => [
        //     'name' => 'annual',
        //     'frenchTitle' => 'Formule annuelle',
        //     'duration' => 365,
        //     'subscriptionPrice' => 1999,
        //     'description' => ""
        // ]
    ];

    private $_showcasePanelsStyles = [
        'pages/showcase-panels',
        'components/header',
        'components/buttons',
        'components/footer'
    ];

    protected $_routingURLs = [
        'presentation' => 'index.php?page=presentation',
        'coaching' => 'index.php?page=coaching',
        'programsList' => 'index.php?page=programslist',
        'programDetails' => 'index.php?page=programdetails',
        'showcase404' => 'index.php?page=showcase-404'
    ];

    public function areProgramsPagesRequested(string $page): bool {
        return ($page === 'programslist') || ($page === 'programdetails');
    }

    public function getProgram(): string {
        return htmlspecialchars($_GET['program']);
    }

    public function isCoachingPageRequested(string $page): bool {
        return $page === 'coaching';
    }

    public function isPresentationPageRequested(string $page): bool {
        return $page === 'presentation';
    }
  
    public function isProgramAvailable(string $requestedProgram): bool {
        return in_array($requestedProgram, array_keys($this->programsList));
    }

    public function isProgramDetailsRequested(string $page): bool {
        return $page === 'programdetails';
    }

    public function isProgramsListAvailable(): bool {
        return count($this->_getProgramsList()) > 0;
    }

    public function isProgramsListRequested(string $page): bool {
        return $page === 'programslist';
    }

    public function isRequestedProgramSet() : bool {
        return isset($_GET['program']);
    }
    
    public function renderCoachingPage(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'coaching', 'showcasePanels' => array_keys($this->_getRoutingURLs())]);
        echo $twig->render('showcase_panels/coaching.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderPresentationPage(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage'=> 'presentation', 'showcasePanels' => array_keys($this->_getRoutingURLs())]);
        echo $twig->render('showcase_panels/presentation.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    public function renderProgramDetailsPage(object $twig, string $program) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programDetails', 'showcasePanels' => array_keys($this->_getRoutingURLs())]);
        echo $twig->render('showcase_panels/program-details.html.twig', ['requestedPage' => 'programdetails', 'program' => $this->_getProgramDetails($program)]);
        echo $twig->render('components/footer.html.twig');
    }
    
    public function renderProgramsListPage(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'programsList', 'showcasePanels' => array_keys($this->_getRoutingURLs())]);
        echo $twig->render('showcase_panels/programs-list.html.twig', ['programs' => $this->_getProgramsList()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderShowcase404Page(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'showcase404', 'showcasePanels' => array_keys($this->_getRoutingURLs())]);
        echo $twig->render('showcase_panels/404.html.twig');
        echo $twig->render('components/footer.html.twig');
    }

    private function _getProgramDetails(string $program): array {
        return $this->programsList[$program];
    }

    private function _getProgramsList(): array {
        return $this->programsList;
    }

    private function _getShowcasePanelsStyles(): array {
        return $this->_showcasePanelsStyles;
    }

    private function _getRoutingURLs(): array {
        return $this->_routingURLs;
    }
}