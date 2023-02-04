<?php

namespace App\Domain\Controllers\ShowCasePanels;

use App\Domain\Interfaces\Dispatcher;

class ShowcasePanel implements Dispatcher {
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
    
    public function getRoutingURL(string $panel): string {
        return self::ROUTING_URLS[$panel];
    }
    
    public function routeTo(string $page): void {
        header("location:{$this->getRoutingURL($page)}");
    }
    
    protected function _getRoutingURLs(): array {
        return self::ROUTING_URLS;
    }
    
    protected function _getShowcasePanelsStyles(): array {
        return self::SHOWCASE_PANELS_STYLES;
    }
    
    protected function _getShowcaseScripts(): array {
        return self::SHOWCASE_SCRIPTS;
    }
}