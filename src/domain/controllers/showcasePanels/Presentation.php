<?php

namespace App\Domain\Controllers\ShowCasePanels;

final class Presentation extends ShowcasePanel {
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
}