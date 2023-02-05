<?php

namespace App\Entities;

use App\Domain\Models\SubscriberData;

final class Subscriber {
    public function getSubscriberHeaders(int $subscriberId) {
        $subscriberData = new SubscriberData;
        
        return $subscriberData->selectSubscriberHeader($subscriberId);
    }
    
    public function isSubscriberIdValid(int $subscriberId) {
        $subscriberData = new SubscriberData;
        
        return $subscriberData->selectSubscriberId($subscriberId);
    }
}