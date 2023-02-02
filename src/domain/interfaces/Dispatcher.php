<?php

namespace App\Domain\Interfaces;

interface Dispatcher {
    public function routeTo(string $page): void;
    
    public function getRoutingURL(string $panel): string;
}