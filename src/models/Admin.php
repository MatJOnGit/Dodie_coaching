<?php

namespace Dodie_Coaching\Models;

use PDO;

class Admin extends Main {
    public function deleteApplication(int $applicationId) {
        $db = $this->dbConnect();
        $deleteApplicationQuery = "DELETE FROM costumer_applications WHERE id = ?";
        $deleteApplicationStatement = $db->prepare($deleteApplicationQuery);

        return $deleteApplicationStatement->execute([$applicationId]);
    }

    public function selectApplicantData(int $applicationId) {
        $db = $this->dbConnect();
        $selectApplicantEmailQuery = "SELECT email, first_name FROM accounts a INNER JOIN costumer_applications ca ON a.id = ca.user_id WHERE ca.id = ?";
        $selectApplicantEmailStatement = $db->prepare($selectApplicantEmailQuery);
        $selectApplicantEmailStatement->execute([$applicationId]);

        return $selectApplicantEmailStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectApplicationDate(string $applicationId) {
        $db = $this->dbConnect();
        $selectApplicationQuery = "SELECT application_date FROM costumer_applications WHERE id = ?";
        $selectApplicationStatement = $db->prepare($selectApplicationQuery);
        $selectApplicationStatement->execute([$applicationId]);

        return $selectApplicationStatement->fetch();
    }
    
    public function selectApplicationsCount() {
        $db = $this->dbConnect();
        $selectApplicationsCountQuery = "SELECT COUNT(user_id) as applicationsCount FROM costumer_applications WHERE staging = 'support_confirmation'";
        $selectApplicationsCountStatement = $db->prepare($selectApplicationsCountQuery);
        $selectApplicationsCountStatement->execute();

        return $selectApplicationsCountStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectSubscribersCount() {
        $db = $this->dbConnect();
        $selectSubscribersCountQuery = "SELECT COUNT(id) as subscribersCount FROM accounts WHERE status = 'subscriber'";
        $selectSubscribersCountStatement = $db->prepare($selectSubscribersCountQuery);
        $selectSubscribersCountStatement->execute();

        return $selectSubscribersCountStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function selectIncomingMeetings() {
        $db = $this->dbConnect();
        $selectIncomingMeetingsQuery = "SELECT sl.slot_date, a.first_name, a.last_name FROM scheduled_slots sl INNER JOIN accounts a ON sl.user_id = a.id WHERE sl.slot_date > CURRENT_TIMESTAMP AND sl.slot_status = 'booked' AND sl.user_id > 0";
        $selectIncomingMeetingsStatement = $db->prepare($selectIncomingMeetingsQuery);
        $selectIncomingMeetingsStatement->execute();

        return $selectIncomingMeetingsStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectApplicationsHeaders() {
        $db = $this->dbConnect();
        $selectApplicationsHeadersQuery = "SELECT CONCAT(a.first_name, ' ', UPPER(a.last_name)) as 'name', ca.id, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), usd.birthdate)), '%Y') + 0 AS 'age', udd.job_style, usd.program_goal FROM costumer_applications ca INNER JOIN users_static_data usd ON ca.user_id = usd.user_id INNER JOIN accounts a ON ca.user_id = a.id INNER JOIN users_dynamic_data udd ON ca.user_id = udd.user_id WHERE ca.staging = 'support_confirmation' ORDER BY ca.application_date ASC";
        $selectApplicationsHeadersStatement = $db->prepare($selectApplicationsHeadersQuery);
        $selectApplicationsHeadersStatement->execute();

        return $selectApplicationsHeadersStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectApplicationDetails(string $applicationId) {
        $db = $this->dbConnect();
        $selectApplicationDetailsQuery = "SELECT ca.id, CONCAT(a.first_name, ' ', UPPER(a.last_name)) as 'name', DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), usd.birthdate)), '%Y') + 0 AS 'age', udd.job_style, usd.height, usd.initial_weight, usd.weight_goal, usd.food_restrictions, usd.food_intolerances, usd.sport_habits, udd.objectives FROM costumer_applications ca INNER JOIN accounts a ON ca.user_id = a.id INNER JOIN users_dynamic_data udd ON ca.user_id = udd.user_id INNER JOIN users_static_data usd
        ON ca.user_id = usd.user_id WHERE ca.id = ?";
        $selectApplicationDetailsStatement = $db->prepare($selectApplicationDetailsQuery);
        $selectApplicationDetailsStatement->execute([$applicationId]);

        return $selectApplicationDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateApplicationStatus(string $applicationId, string $newApplicationStatus) {
        $db = $this->dbConnect();
        $updateApplicationStatusQuery = "UPDATE costumer_applications SET staging = ? WHERE id = ?";
        $updateApplicationStatusStatement = $db->prepare($updateApplicationStatusQuery);

        return $updateApplicationStatusStatement->execute([$newApplicationStatus, $applicationId]);
    }
}