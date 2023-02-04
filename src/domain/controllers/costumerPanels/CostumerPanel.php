<?php

namespace App\Domain\Controllers\CostumerPanels;

use App\Domain\Interfaces\Dispatcher;

class CostumerPanel implements Dispatcher {
    private const ROUTING_URLS = [
        'dashboard' => 'index.php?page=dashboard',
        'meetingsBooking' => 'index.php?page=meetings-booking',
        'nutrition' => 'index.php?page=nutrition',
        'progress' => 'index.php?page=progress',
        'subscription' => 'index.php?page=subscription',
    ];

    private const COSTUMER_PANELS_STYLES = [
        'pages/user-panels',
        'components/header',
        'components/form',
        'components/footer'
    ];

    private array $_USERPANELS = ['dashboard', 'nutrition', 'progress', 'meetings', 'subscription'];

    protected function _getUserPanels(): array {
        return $this->_USERPANELS;
    }
    
    protected function _getCostumerPanelsStyles(): array {
        return self::COSTUMER_PANELS_STYLES;
    }
    
    public function getRoutingURL(string $panel): string {
        return self::ROUTING_URLS[$panel];
    }

    public function routeTo(string $page): void {
        header("location:{$this->getRoutingURL($page)}");
    }
}