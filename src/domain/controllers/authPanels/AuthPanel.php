<?php

namespace App\Domain\Controllers\AuthPanels;

use App\Domain\Interfaces\Dispatcher;

class AuthPanel implements Dispatcher {
    private const AUTH_PANELS_STYLES = [
        'pages/connection-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];
    
    private const ROUTING_URLS = [
        'dashboard' => 'index.php?page=dashboard',
        'login' => 'index.php?page=login',
        'presentation' => 'index.php?page=presentation',
        'registering' => 'index.php?page=registering',
        'mail-notification' => 'index.php?page=mail-notification',
        'edit-password' => 'index.php?page=password-editing',
        'retrieved-password' => 'index.php?page=retrieved-password',
        'admin-dashboard' => 'index.php?page=admin-dashboard',
        'send-token' => 'index.php?action=send-token',
        'token-signing' => 'index.php?page=token-signing'
    ];

    protected function _getAuthPanelsStyles(): array {
        return self::AUTH_PANELS_STYLES;
    }
    
    public function getRoutingURL(string $panel): string {
        return self::ROUTING_URLS[$panel];
    }

    public function routeTo(string $page): void {
        header("location:{$this->getRoutingURL($page)}");
    }
}