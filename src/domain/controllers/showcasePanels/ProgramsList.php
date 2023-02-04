<?php

namespace App\Domain\Controllers\ShowCasePanels;

final class ProgramsList extends ShowcasePanel {
    public function renderProgramsListPage(object $twig, object $programs, bool $isLogged): void {
        echo $twig->render('showcase_panels/programs-list.html.twig', [
            'frenchTitle' => 'programmes',
            'appSection' => 'showcasePanels',
            'showcasePanels' => $this->_getRoutingURLs(),
            'programs' => $programs->getProgramsList(),
            'stylePaths' => $this->_getShowcasePanelsStyles(),
            'isUserLogged' => $isLogged,
            'pageScripts' => $this->_getShowcaseScripts()
        ]);
    }
}