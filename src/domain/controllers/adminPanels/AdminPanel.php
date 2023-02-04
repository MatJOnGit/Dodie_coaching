<?php

namespace App\Domain\Controllers\AdminPanels;

use App\Domain\Interfaces\Dispatcher;

class AdminPanel implements Dispatcher {
    private const ADMIN_PANELS_STYLES = [
        'pages/admin-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];
    
    private const ROUTING_URLS = [
        'admin-dashboard' => 'index.php?page=admin-dashboard',
        'appliancesList' => 'index.php?page=appliances-list',
        'meetingsManagement' => 'index.php?page=meetings-management'
    ];
    
    public function getRoutingURL(string $panel): string {
        return self::ROUTING_URLS[$panel];
    }
    
    public function routeTo(string $page): void {
        header("location:{$this->getRoutingURL($page)}");
    }
    
    protected function _getAdminPanelsStyles(): array {
        return self::ADMIN_PANELS_STYLES;
    }
}