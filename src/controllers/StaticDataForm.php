<?php

namespace Dodie_Coaching\Controllers;

class StaticDataForm extends UserPanels {
    public function renderStaticDataForm(object $twig) {
        echo $twig->render('user_panels/static-data-form.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'informations',
            'appSection' => 'privatePanels',
            'userPanelLandingPage' => 'vos informations',
        ]);
    }
    
}