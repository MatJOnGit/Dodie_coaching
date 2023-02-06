<?php

namespace App\Domain\Controllers\AuthPanels;

final class PasswordEditing extends AuthPanel {
    private const PASSWORD_EDITING_PANEL_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ConnectionHelper.model',
        'classes/PasswordEditor.model',
        'passwordEditionApp'
    ];
    
    public function renderPasswordEditingPage(object $twig): void {
        echo $twig->render('connection_panels/password-edition.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => 'Edition de votre mot de passe',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_getPasswordEditingPanelScripts('pwdEditing')
        ]);
    }
    
    private function _getPasswordEditingPanelScripts(): array {
        return self::PASSWORD_EDITING_PANEL_SCRIPTS;
    }
}

