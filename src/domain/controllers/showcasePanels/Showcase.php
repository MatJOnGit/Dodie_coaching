<?php

namespace App\Domain\Controllers\ShowCasePanels;

use App\Domain\Interfaces\Dispatcher;

class Showcase implements Dispatcher {
    private const PROGRAMS_LIST = [
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

    private const ROUTING_URLS = [
        'presentation' => 'index.php?page=presentation',
        'coaching' => 'index.php?page=coaching',
        'programs-list' => 'index.php?page=programs-list',
        'program-details' => 'index.php?page=program-details',
        '404' => 'index.php?page=showcase-404'
    ];
    
    private const SHOWCASE_PANELS_STYLES = [
        'pages/showcase-panels',
        'components/header',
        'components/footer'
    ];
    
    private const SHOWCASE_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/DynamicMenuDisplayer.model',
        'dynamicMenuApp'
    ];
    
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
    
    static function renderPresentationPage(object $twig, bool $isLogged): void {
        echo $twig->render('showcase_panels/presentation.html.twig', [
            'frenchTitle' => 'présentation',
            'appSection' => 'showcasePanels',
            'showcasePanels' => self::_getRoutingURLs(),
            'stylePaths' => self::_getShowcasePanelsStyles(),
            'isUserLogged' => $isLogged,
            'pageScripts' => self::_getShowcaseScripts()
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
    
    static function _getRoutingURLs(): array {
        return self::ROUTING_URLS;
    }
    
    static function _getShowcasePanelsStyles(): array {
        return self::SHOWCASE_PANELS_STYLES;
    }
    
    static function _getShowcaseScripts(): array {
        return self::SHOWCASE_SCRIPTS;
    }
    
    protected function _getProgramsList(): array {
        return self::PROGRAMS_LIST;
    }
    
    protected function _getProgramDetails(string $program): array {
        return self::PROGRAMS_LIST[$program];
    }

    public function isProgramAvailable(string $requestedProgram): bool {
        return in_array($requestedProgram, array_keys(self::PROGRAMS_LIST));
    }
    
    public function isProgramsListAvailable(): bool {
        return count($this->_getProgramsList()) > 0;
    }
    
    public function getRoutingURL(string $panel): string {
        return self::ROUTING_URLS[$panel];
    }

    public function routeTo(string $page): void {
        header("location:{$this->getRoutingURL($page)}");
    }
}