<?php

namespace App\Domain\Controllers\ConnectionPanels;

final class Registering extends ConnectionPanel {
    private const REGISTERING_PANEL_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ConnectionHelper.model',
        'classes/LoginHelper.model',
        'loginApp'
    ];
    
    public function renderRegisteringPage(object $twig): void {
        echo $twig->render('connection_panels/registering.html.twig', [
            'stylePaths' => $this->_getConnectionPanelsStyles(),
            'frenchTitle' => 'création de compte',
            'appSection' => 'connectionPanels',
            'pageScripts' => $this->_getRegisteringPanelScripts()
        ]);
    }
    
    private function _getRegisteringPanelScripts(): array {
        return self::REGISTERING_PANEL_SCRIPTS;
    }
}