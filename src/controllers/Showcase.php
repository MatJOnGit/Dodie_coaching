<?php

namespace Dodie_Coaching\Controllers;

class Showcase extends Main {
    private $_programsList = [
        'monthly' => [
            'name' => 'monthly',
            'frenchTitle' => 'Formule mois',
            'duration' => 30,
            'subscriptionPrice' => 219,
            'description' => "Vous avez un mariage de prévu et vous avez besoin d'un coup de main pour rentrer dans votre robe ou votre costume ?<br><br>Vous souhaitez peut-être simplement tester par vous-même nos services ?<br><br>Ce programme vous donnera des bases solides pour commencer à prendre soin de vous, et en toute sérénité."
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
    
    private $_routingURLs = [
        'presentation' => 'index.php?page=presentation',
        'coaching' => 'index.php?page=coaching',
        'programs-list' => 'index.php?page=programs-list',
        'program-details' => 'index.php?page=program-details',
        '404' => 'index.php?page=showcase-404'
    ];
    
    private $_showcasePanelsStyles = [
        'pages/showcase-panels',
        'components/header',
        'components/footer'
    ];
    
    private $_showcaseScripts = [
        'classes/Fader.model',
        'classes/DynamicMenuDisplayer.model',
        'dynamicMenuApp'
    ];
    
    public function isProgramAvailable(string $requestedProgram): bool {
        return in_array($requestedProgram, array_keys($this->_programsList));
    }
    
    public function isProgramsListAvailable(): bool {
        return count($this->_getProgramsList()) > 0;
    }
    
    public function renderCoachingPage(object $twig, bool $isLogged): void {
        echo $twig->render('showcase_panels/coaching.html.twig', [
            'frenchTitle' => 'coaching',
            'appSection' => 'showcasePanels',
            'showcasePanels' => $this->_getRoutingURLs(),
            'stylePaths' => $this->_getShowcasePanelsStyles(),
            'isUserLogged' => $isLogged,
            'pageScripts' => $this->_getShowcaseScripts()
        ]);
    }
    
    public function renderPresentationPage(object $twig, bool $isLogged): void {
        echo $twig->render('showcase_panels/presentation.html.twig', [
            'frenchTitle' => 'présentation',
            'appSection' => 'showcasePanels',
            'showcasePanels' => $this->_getRoutingURLs(),
            'stylePaths' => $this->_getShowcasePanelsStyles(),
            'isUserLogged' => $isLogged,
            'pageScripts' => $this->_getShowcaseScripts()
        ]);
    }
    
    public function renderProgramDetailsPage(object $twig, string $program, bool $isLogged): void {
        echo $twig->render('showcase_panels/program-details.html.twig', [
            'frenchTitle' => 'programmes',
            'appSection' => 'showcasePanels',
            'showcasePanels' => $this->_getRoutingURLs(),
            'program' => $this->_getProgramDetails($program),
            'stylePaths' => $this->_getShowcasePanelsStyles(),
            'isUserLogged' => $isLogged,
            'pageScripts' => $this->_getShowcaseScripts()
        ]);
    }
    
    public function renderProgramsListPage(object $twig, bool $isLogged): void {
        echo $twig->render('showcase_panels/programs-list.html.twig', [
            'frenchTitle' => 'programmes',
            'appSection' => 'showcasePanels',
            'showcasePanels' => $this->_getRoutingURLs(),
            'programs' => $this->_getProgramsList(),
            'stylePaths' => $this->_getShowcasePanelsStyles(),
            'isUserLogged' => $isLogged,
            'pageScripts' => $this->_getShowcaseScripts()
        ]);
    }

    public function renderShowcase404Page(object $twig, bool $isLogged): void {
        echo $twig->render('showcase_panels/404.html.twig', [
            'frenchTitle' => '404',
            'appSection' => 'showcasePanels',
            'showcasePanels' => $this->_getRoutingURLs(),
            'stylePaths' => $this->_getShowcasePanelsStyles(),
            'isUserLogged' => $isLogged,
            'pageScripts' => $this->_getShowcaseScripts()
        ]);
    }
    
    private function _getProgramDetails(string $program): array {
        return $this->_programsList[$program];
    }
    
    private function _getProgramsList(): array {
        return $this->_programsList;
    }
    
    private function _getRoutingURLs(): array {
        return $this->_routingURLs;
    }
    
    private function _getShowcasePanelsStyles(): array {
        return $this->_showcasePanelsStyles;
    }
    
    private function _getShowcaseScripts(): array {
        return $this->_showcaseScripts;
    }
}