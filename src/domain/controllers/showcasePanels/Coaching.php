<?php

namespace App\Domain\Controllers\ShowCasePanels;

final class Coaching extends ShowcasePanel {
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
}