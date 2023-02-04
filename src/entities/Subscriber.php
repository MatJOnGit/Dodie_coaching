<?php

namespace App\Entities;

use App\Domain\Models\Subscriber as SubscriberModel;

final class Subscriber {
    public function getSubscriberHeaders(int $subscriberId) {
        $subscriber = new SubscriberModel;
        
        return $subscriber->selectSubscriberHeader($subscriberId);
    }
    
    public function isSubscriberIdValid(int $subscriberId) {
        $subscriber = new SubscriberModel;
        
        return $subscriber->selectSubscriberId($subscriberId);
    }
}