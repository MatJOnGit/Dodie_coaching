<?php

namespace App\Domain\Controllers\ConnectionPanels;

final class Login extends ConnectionPanel {
    private const LOGIN_PANEL_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ConnectionHelper.model',
        'classes/LoginHelper.model',
        'loginApp'
    ];
    
    public function renderLoginPage(object $twig): void {
        echo $twig->render('connection_panels/login.html.twig', [
            'stylePaths' => $this->_getConnectionPanelsStyles(),
            'frenchTitle' => 'connection',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_loginPanelsScripts()
        ]);
    }
    
    private function _loginPanelsScripts(): array {
        return self::LOGIN_PANEL_SCRIPTS;
    }
}