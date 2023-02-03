<?php

namespace App\Domain\Controllers\ShowCasePanels;

use App\Domain\Interfaces\Dispatcher;

class Showcase implements Dispatcher {
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
            'pageScripts' => self::_getShowcaseScripts()
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
    
    public function renderProgramDetailsPage(object $twig, object $programList, string $program, bool $isLogged): void {
        echo $twig->render('showcase_panels/program-details.html.twig', [
            'frenchTitle' => 'programmes',
            'appSection' => 'showcasePanels',
            'showcasePanels' => $this->_getRoutingURLs(),
            'program' => $programList->getProgramDetails($program),
            'stylePaths' => $this->_getShowcasePanelsStyles(),
            'isUserLogged' => $isLogged,
            'pageScripts' => $this->_getShowcaseScripts()
        ]);
    }
    
    public function renderProgramsListPage(object $twig, object $programList, bool $isLogged): void {
        echo $twig->render('showcase_panels/programs-list.html.twig', [
            'frenchTitle' => 'programmes',
            'appSection' => 'showcasePanels',
            'showcasePanels' => $this->_getRoutingURLs(),
            'programs' => $programList->getProgramsList(),
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
    
    private function _getRoutingURLs(): array {
        return self::ROUTING_URLS;
    }
    
    private function _getShowcasePanelsStyles(): array {
        return self::SHOWCASE_PANELS_STYLES;
    }
    
    private function _getShowcaseScripts(): array {
        return self::SHOWCASE_SCRIPTS;
    }
    
    public function getRoutingURL(string $panel): string {
        return self::ROUTING_URLS[$panel];
    }

    public function routeTo(string $page): void {
        header("location:{$this->getRoutingURL($page)}");
    }
}