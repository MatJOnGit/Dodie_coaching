<?php

namespace App\Domain\Controllers\AuthPanels;

final class LoginPanel extends AuthPanel {
    private const LOGIN_PANEL_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ConnectionHelper.model',
        'classes/LoginHelper.model',
        'loginApp'
    ];
    
    public function renderLoginPage(object $twig): void {
        echo $twig->render('connection_panels/login.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => 'connection',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_loginPanelsScripts()
        ]);
    }
    
    private function _loginPanelsScripts(): array {
        return self::LOGIN_PANEL_SCRIPTS;
    }
}