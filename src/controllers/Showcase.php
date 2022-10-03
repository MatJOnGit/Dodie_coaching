<?php

namespace Dodie_Coaching\Controllers;

class Showcase extends Main {
    public $programsList = [
        'monthly' => [
            'name' => 'monthly',
            'frenchTitle' => 'Formule mois',
            'duration' => 30,
            'subscriptionPrice' => 219,
            'description' => "Vous avez un mariage de prévu et vous avez besoin d'un coup de main pour rentrer dans votre robe ou votre costume ?\n\nVous souhaitez peut-être simplement tester par vous-même mes services ?\n\nCe programme vous donnera des bases solides pour commencer à prendre soin de vous, et en toute sérénité."
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

    protected $_routingURLs = [
        'presentation' => 'index.php?page=presentation',
        'coaching' => 'index.php?page=coaching',
        'programsList' => 'index.php?page=programslist',
        'programDetails' => 'index.php?page=programdetails',
        '404' => 'index.php?page=showcase-404'
    ];

    private $_showcasePanelsStyles = [
        'pages/showcase-panels',
        'components/header',
        'components/buttons',
        'components/footer'
    ];

    private $_templateFilesRoute = 'showcase_panels/';

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

    public function renderShowcasePage(object $twig, string $page) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getShowcasePanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => $page, 'showcasePanels' => array_keys($this->_getRoutingURLs())]);
        echo $twig->render($this->_getTemplateFileRoute($page), $this->_getTemplateData($page));
        echo $twig->render('components/footer.html.twig');
    }
    
    private function _getTemplateData(string $page): array {
        switch ($page) {
            case 'programDetails' :
                $templateData = [
                    'requestedPage' => $page,
                    'program' => $this->_getProgramDetails($this->getProgram())
                ];
                break;

            case 'programsList':
                $templateData = [
                    'programs' => $this->_getProgramsList()
                ];
                break;
            
            default:
                $templateData = [];
        }

        return $templateData; 
    }

    private function _getProgramDetails(string $program): array {
        return $this->programsList[$program];
    }

    private function _getProgramsList(): array {
        return $this->programsList;
    }

    private function _getRoutingURLs(): array {
        return $this->_routingURLs;
    }

    private function _getShowcasePanelsStyles(): array {
        return $this->_showcasePanelsStyles;
    }

    private function _getTemplateFileRoute(string $page): string {
        return $this->_getTemplateFilesRoute() . $page . '.html.twig';
    }

    private function _getTemplateFilesRoute(): string {
        return $this->_templateFilesRoute;
    }
}