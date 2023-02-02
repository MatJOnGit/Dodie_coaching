<?php

namespace App\Entities;

use Dodie_Coaching\Models\StaticData;

class StaticDataForm extends Form {
    public function createStaticData(array $userData) {
        $staticData = new StaticData;
        
        return $staticData->insertStaticData($userData['email']);
    }
}