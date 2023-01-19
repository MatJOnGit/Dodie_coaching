<?php

namespace Dodie_Coaching\Models;

use PDO;

class Subscribers extends Main {
    public function selectAccountDetails(int $subscriberId) {
        $db = $this->dbConnect();
        $selectAccountDetailsQuery =
            "SELECT
                sub.first_subscription_date,
                sl.program_type,
                sl.date
            FROM subscribers_data sub
            LEFT JOIN subscription_logs sl ON sub.user_id = sl.user_id
            WHERE sub.user_id = ?
            ORDER BY sl.date DESC";
        $selectAccountDetailsStatement = $db->prepare($selectAccountDetailsQuery);
        $selectAccountDetailsStatement ->execute([$subscriberId]);
        
        return $selectAccountDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectSubscriberDetails(int $subscriberId) {
        $db = $this->dbConnect();
        $selectSubscriberDetailsQuery =
            "SELECT
                CONCAT(acc.first_name, ' ', UPPER(acc.last_name)) as 'name',
                sub.user_id,
                DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), usd.birthdate)), '%Y') + 0 AS 'age',
                usd.program_goal,
                udd.job_style,
                sub.program_status,
                sub.meals_list,
                usd.height,
                usd.initial_weight,
                usd.food_restrictions,
                usd.food_intolerances,
                usd.sport_habits,
                uwr.date,
                uwr.weight AS 'current_weight',
                usd.weight_goal
            FROM subscribers_data sub
            INNER JOIN accounts acc ON sub.user_id = acc.id
            INNER JOIN appliances app ON sub.user_id = app.user_id
            INNER JOIN users_static_data usd ON app.user_id = usd.user_id
            INNER JOIN users_dynamic_data udd ON sub.user_id = udd.user_id
            LEFT JOIN users_weight_reports uwr ON sub.user_id = uwr.user_id
            WHERE sub.user_id = ?
            ORDER BY uwr.date DESC";
        $selectSubscriberDetailsStatement = $db->prepare($selectSubscriberDetailsQuery);
        $selectSubscriberDetailsStatement->execute([$subscriberId]);
        
        return $selectSubscriberDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectSubscriberData (int $subscriberId) {
        $db = $this->dbConnect();
        $selectSubscriberDataQuery = "SELECT email, first_name FROM accounts acc INNER JOIN subscribers_data sub ON acc.id = sub.user_id WHERE sub.user_id = ?";
        $selectSubscriberDataStatement = $db->prepare($selectSubscriberDataQuery);
        $selectSubscriberDataStatement->execute([$subscriberId]);
        
        return $selectSubscriberDataStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectSubscriberHeader(int $subscriberId) {
        $db = $this->dbConnect();
        $selectSubscriberHeaderQuery =
            "SELECT
                CONCAT(acc.first_name, ' ', UPPER(acc.last_name)) as 'name',
                acc.first_name,
                acc.email,
                usd.user_id
            FROM subscribers_data sub
            INNER JOIN accounts acc ON sub.user_id = acc.id
            INNER JOIN users_static_data usd ON sub.user_id = usd.user_id
            WHERE sub.user_id = ?";
        $selectSubscriberHeaderStatement = $db->prepare($selectSubscriberHeaderQuery);
        $selectSubscriberHeaderStatement->execute([$subscriberId]);
        
        return $selectSubscriberHeaderStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectSubscriberId(int $subscriberId) {
        $db = $this->dbConnect();
        $selectSubscriberIdQuery = "SELECT user_id FROM subscribers_data WHERE user_id = ?";
        $selectSubscriberIdStatement = $db->prepare($selectSubscriberIdQuery);
        $selectSubscriberIdStatement->execute([$subscriberId]);
        
        return $selectSubscriberIdStatement->fetch();
    }
    
    public function selectSubscribersCount() {
        $db = $this->dbConnect();
        $selectSubscribersCountQuery = "SELECT COUNT(id) as subscribersCount FROM accounts WHERE status = 'subscriber'";
        $selectSubscribersCountStatement = $db->prepare($selectSubscribersCountQuery);
        $selectSubscribersCountStatement->execute();
        
        return $selectSubscribersCountStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectSubscribersHeaders() {
        $db = $this->dbConnect();
        $selectSubscribersHeadersQuery =
            "SELECT
                CONCAT(acc.first_name, ' ', UPPER(acc.last_name)) as 'name',
                sub.user_id,
                DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), usd.birthdate)), '%Y') + 0 AS 'age',
                usd.program_goal,
                udd.job_style,
                sub.program_status
            FROM subscribers_data sub
            INNER JOIN users_static_data usd ON sub.user_id = usd.user_id
            INNER JOIN accounts acc ON sub.user_id = acc.id
            INNER JOIN users_dynamic_data udd ON sub.user_id = udd.user_id
            INNER JOIN appliances app ON sub.user_id = app.user_id
            WHERE app.staging = 'confirmed'";
        $selectSubscribersHeadersStatement = $db->prepare($selectSubscribersHeadersQuery);
        $selectSubscribersHeadersStatement->execute();
        
        return $selectSubscribersHeadersStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectProgramMeals($subscriberId) {
        $db = $this->dbConnect();
        $selectProgramMealsQuery = "SELECT meals_list FROM subscribers_data WHERE user_id = ?";
        $selectProgramMealsStatement = $db->prepare($selectProgramMealsQuery);
        $selectProgramMealsStatement->execute([$subscriberId]);

        return $selectProgramMealsStatement->fetch(PDO::FETCH_ASSOC);
    }
}