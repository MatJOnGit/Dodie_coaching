<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\StaticDataForm as StaticDataFormModel;

class StaticDataForm extends UserPanels {
    // Définition de userPanels à revoir
    public function renderStaticDataForm(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getUserPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'userPanelPages' => $this->_getUserPanels()]);
        echo $twig->render('user_panels/personal-data-form.html.twig');
        echo $twig->render('components/footer.html.twig');
    }
    
}