<?php

namespace App\Domain\Controllers\ShowCasePanels;

final class ProgramDetails extends ShowcasePanel {
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
}