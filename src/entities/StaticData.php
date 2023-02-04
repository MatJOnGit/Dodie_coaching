<?php

namespace App\Entities;

use App\Domain\Models\StaticData as StaticDataModel;

final class StaticData {
    public function createStaticData(array $userData) {
        $staticData = new StaticDataModel;
        
        return $staticData->insertStaticData($userData['email']);
    }
}