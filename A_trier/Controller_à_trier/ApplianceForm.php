<?php

namespace Dodie_Coaching\Controllers;

final class StaticDataForm extends UserPanel {
    public function renderStaticDataForm(object $twig): void {
        echo $twig->render('user_panels/static-data-form.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'informations',
            'appSection' => 'privatePanels',
            'userPanelLandingPage' => 'vos informations',
        ]);
    }
}