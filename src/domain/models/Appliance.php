<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

class Appliance {
    use Mixins\Database;

    public function selectAppliancesCount() {
        $db = $this->dbConnect();
        $selectAppliancesCountQuery = "SELECT COUNT(user_id) as appliancesCount FROM appliances WHERE staging = 'support_confirmation'";
        $selectAppliancesCountStatement = $db->prepare($selectAppliancesCountQuery);
        $selectAppliancesCountStatement->execute();
        
        return $selectAppliancesCountStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectAppliancesHeaders() {
        $db = $this->dbConnect();
        $selectAppliancesHeadersQuery =
            "SELECT 
                CONCAT(acc.first_name, ' ', UPPER(acc.last_name)) as 'name',
                app.user_id,
                DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), usd.birthdate)), '%Y') + 0 AS 'age',
                udd.job_style,
                usd.program_goal
            FROM appliances app
            INNER JOIN accounts acc ON app.user_id = acc.id
            INNER JOIN users_dynamic_data udd ON app.user_id = udd.user_id
            INNER JOIN users_static_data usd ON app.user_id = usd.user_id
            WHERE app.staging = 'support_confirmation'
            ORDER BY app.appliance_date ASC";
        $selectAppliancesHeadersStatement = $db->prepare($selectAppliancesHeadersQuery);
        $selectAppliancesHeadersStatement->execute();
        
        return $selectAppliancesHeadersStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteAppliance(int $applicantId) {
        $db = $this->dbConnect();
        $deleteApplianceQuery = "DELETE FROM appliances WHERE user_id = ?";
        $deleteApplianceStatement = $db->prepare($deleteApplianceQuery);
        
        return $deleteApplianceStatement->execute([$applicantId]);
    }
    
    public function selectApplicantId(string $applicantId) {
        $db = $this->dbConnect();
        $selectApplicantIdQuery = "SELECT user_id FROM appliances WHERE user_id = ?";
        $selectApplicantIdStatement = $db->prepare($selectApplicantIdQuery);
        $selectApplicantIdStatement->execute([$applicantId]);
        
        return $selectApplicantIdStatement->fetch();
    }
    
    public function selectApplianceDetails(string $applianceId) {
        $db = $this->dbConnect();
        $selectApplianceDetailsQuery =
            "SELECT
                app.user_id,
                CONCAT(acc.first_name, ' ', UPPER(acc.last_name)) as 'name',
                DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), usd.birthdate)), '%Y') + 0 AS 'age',
                udd.job_style,
                usd.height,
                usd.initial_weight,
                usd.weight_goal,
                usd.food_restrictions,
                usd.food_intolerances,
                usd.sport_habits,
                udd.objectives
            FROM appliances app
            INNER JOIN accounts acc ON app.user_id = acc.id
            INNER JOIN users_dynamic_data udd ON app.user_id = udd.user_id
            INNER JOIN users_static_data usd ON app.user_id = usd.user_id
            WHERE app.user_id = ?";
        $selectApplianceDetailsStatement = $db->prepare($selectApplianceDetailsQuery);
        $selectApplianceDetailsStatement->execute([$applianceId]);
        
        return $selectApplianceDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateApplianceStatus(string $applicantId, string $newApplianceStatus) {
        $db = $this->dbConnect();
        $updateApplianceStatusQuery = "UPDATE appliances SET staging = ? WHERE user_id = ?";
        $updateApplianceStatusStatement = $db->prepare($updateApplianceStatusQuery);
        
        return $updateApplianceStatusStatement->execute([$newApplianceStatus, $applicantId]);
    }
    
    public function selectApplicantData(int $applicantId) {
        $db = $this->dbConnect();
        $selectApplicantDataQuery = "SELECT email, first_name FROM accounts acc INNER JOIN appliances app ON acc.id = app.user_id WHERE app.user_id = ?";
        $selectApplicantDataStatement = $db->prepare($selectApplicantDataQuery);
        $selectApplicantDataStatement->execute([$applicantId]);
        
        return $selectApplicantDataStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function dbConnect() {
        return $this->connect();
    }
}