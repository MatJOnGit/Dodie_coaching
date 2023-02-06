<?php

namespace App\Domain\Controllers\AuthPanels;

use App\Domain\Models\ResetToken;

final class TokenSigning extends AuthPanel {
    private const TOKEN_SIGNING_PANEL_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ConnectionHelper.model',
        'classes/TokenSigningHelper.model',
        'tokenSigningApp'
    ];
    
    public function renderTokenSigningPage(object $twig): void {
        echo $twig->render('connection_panels/token-signing.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => 'réinitialisation de mot de passe',
            'appSection' => 'connectionPanels',
            'remainingAttempts' => $this->_getTokenSigningRemainingAttempts(),
            'pageScripts' => $this->_getTokenSigningPanelScripts()
        ]);
    }
    
    private function _getTokenSigningPanelScripts(): array {
        return self::TOKEN_SIGNING_PANEL_SCRIPTS;
    }
    
    private function _getTokenSigningRemainingAttempts() {
        $resetToken = new ResetToken;
        
        return $resetToken->selectRemainingAttempts($_SESSION['email'])['remaining_atpt'];
    }
}