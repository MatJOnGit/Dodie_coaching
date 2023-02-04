<?php

namespace App\Entities;

use App\Domain\Models\Appliance as ApplianceModel;

final class Appliance {
    public function isApplianceIdValid(int $applicantId) {
        $appliance = new ApplianceModel;
        
        return $appliance->selectApplicantId($applicantId);
    }
    
    public function eraseAppliance(int $applicantId) {
        $appliance = new ApplianceModel;
        
        return $appliance->deleteAppliance($applicantId);
    }
    
    public function getApplicantData(int $applicantId) {
        $appliance = new ApplianceModel;
        
        return $appliance->selectApplicantData($applicantId);
    }
    
    public function getMessageType(): string {
        return empty($_POST['rejection-message']) ? 'default' : 'custom';
    }
    
    public function acceptAppliance(int $applianceId, string $newApplianceStatus) {
        $appliance = new ApplianceModel;
        
        return $appliance->updateApplianceStatus($applianceId, $newApplianceStatus);
    }
}