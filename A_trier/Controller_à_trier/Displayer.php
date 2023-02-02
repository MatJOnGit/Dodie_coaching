<?php

namespace App\AuthPanel;

class Displayer {
    private const AUTH_PANELS_SCRIPTS = [
        'pwdRetrieving' => [
            'classes/ElementFader.model',
            'classes/ConnectionHelper.model',
            'classes/PasswordRetriever.model',
            'passwordRecoveryApp'
        ]
    ];
    
    public function renderRetrievedPasswordPage(object $twig): void {
        echo $twig->render('connection_panels/retrieved-password.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => "Mot de passe modifié",
            'passSection' => 'connectionPanels'
        ]);
    }
}