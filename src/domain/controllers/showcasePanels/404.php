<?php

namespace App\Domain\Controllers\ShowCasePanels;

final class Showcase404 extends ShowcasePanel {
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
}