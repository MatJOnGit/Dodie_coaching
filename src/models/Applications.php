<?php

namespace Dodie_Coaching\Models;

use PDO;

class Applications extends Main {
    public function deleteApplication(int $applicantId) {
        $db = $this->dbConnect();
        $deleteApplicationQuery = "DELETE FROM applications WHERE user_id = ?";
        $deleteApplicationStatement = $db->prepare($deleteApplicationQuery);

        return $deleteApplicationStatement->execute([$applicantId]);
    }

    public function selectApplicantData(int $applicantId) {
        $db = $this->dbConnect();
        $selectApplicantDataQuery = "SELECT email, first_name FROM accounts a INNER JOIN applications app ON a.id = app.user_id WHERE app.user_id = ?";
        $selectApplicantDataStatement = $db->prepare($selectApplicantDataQuery);
        $selectApplicantDataStatement->execute([$applicantId]);

        return $selectApplicantDataStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectApplicantId(string $applicantId) {
        $db = $this->dbConnect();
        $selectApplicantIdQuery = "SELECT user_id FROM applications WHERE user_id = ?";
        $selectApplicantIdStatement = $db->prepare($selectApplicantIdQuery);
        $selectApplicantIdStatement->execute([$applicantId]);

        return $selectApplicantIdStatement->fetch();
    }

    public function selectApplicationDetails(string $applicationId) {
        $db = $this->dbConnect();
        $selectApplicationDetailsQuery = "SELECT app.user_id, CONCAT(a.first_name, ' ', UPPER(a.last_name)) as 'name', DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), usd.birthdate)), '%Y') + 0 AS 'age', udd.job_style, usd.height, usd.initial_weight, usd.weight_goal, usd.food_restrictions, usd.food_intolerances, usd.sport_habits, udd.objectives FROM applications app INNER JOIN accounts a ON app.user_id = a.id INNER JOIN users_dynamic_data udd ON app.user_id = udd.user_id INNER JOIN users_static_data usd
        ON app.user_id = usd.user_id WHERE app.user_id = ?";
        $selectApplicationDetailsStatement = $db->prepare($selectApplicationDetailsQuery);
        $selectApplicationDetailsStatement->execute([$applicationId]);

        return $selectApplicationDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectApplicationsCount() {
        $db = $this->dbConnect();
        $selectApplicationsCountQuery = "SELECT COUNT(user_id) as applicationsCount FROM applications WHERE staging = 'support_confirmation'";
        $selectApplicationsCountStatement = $db->prepare($selectApplicationsCountQuery);
        $selectApplicationsCountStatement->execute();

        return $selectApplicationsCountStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectApplicationsHeaders() {
        $db = $this->dbConnect();
        $selectApplicationsHeadersQuery = "SELECT CONCAT(a.first_name, ' ', UPPER(a.last_name)) as 'name', app.user_id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), usd.birthdate)), '%Y') + 0 AS 'age', udd.job_style, usd.program_goal FROM applications app INNER JOIN users_static_data usd ON app.user_id = usd.user_id INNER JOIN accounts a ON app.user_id = a.id INNER JOIN users_dynamic_data udd ON app.user_id = udd.user_id WHERE app.staging = 'support_confirmation' ORDER BY app.application_date ASC";
        $selectApplicationsHeadersStatement = $db->prepare($selectApplicationsHeadersQuery);
        $selectApplicationsHeadersStatement->execute();

        return $selectApplicationsHeadersStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateApplicationStatus(string $applicantId, string $newApplicationStatus) {
        $db = $this->dbConnect();
        $updateApplicationStatusQuery = "UPDATE applications SET staging = ? WHERE user_id = ?";
        $updateApplicationStatusStatement = $db->prepare($updateApplicationStatusQuery);

        return $updateApplicationStatusStatement->execute([$newApplicationStatus, $applicantId]);
    }
}