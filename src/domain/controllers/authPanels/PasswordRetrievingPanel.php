<?php

namespace App\Domain\Controllers\AuthPanels;

final class PasswordRetrievingPanel extends AuthPanel {
    private const PASSWORD_RETRIEVING_PANEL_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ConnectionHelper.model',
        'classes/PasswordRetriever.model',
        'passwordRecoveryApp'
    ];
    
    public function renderPasswordRetrievingPage(object $twig): void {
        echo $twig->render('connection_panels/password-retrieving.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => 'mot de passe perdu',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_getPasswordRetrievingPanelScripts('pwdRetrieving')
        ]);
    }
    
    private function _getPasswordRetrievingPanelScripts(): array {
        return self::PASSWORD_RETRIEVING_PANEL_SCRIPTS;
    }
}