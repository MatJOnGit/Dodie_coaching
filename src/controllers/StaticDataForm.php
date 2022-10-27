<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\StaticDataForm as StaticDataFormModel;

class StaticDataForm extends UserPanels {
    public function renderStaticDataForm(object $twig) {
        echo $twig->render('user_panels/static-data-form.html.twig', [
            'stylePaths' => $this->_getUserPanelsStyles(),
            'frenchTitle' => 'informations',
            'appSection' => 'userPanels',
            'userPanelLandingPage' => 'vos informations',
        ]);
    }
    
}