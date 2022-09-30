<?php

namespace Dodie_Coaching\Models;

use Dodie_Coaching\Models\Manager as Manager, PDO;

class MemberPanelsManager extends Manager {
    public $dashboardMenuItems = [
        'nutrition' => [
            'frenchTitle' => 'Programme nutritionnel',
            'iconClass' => 'bowl-food',
            'link' => 'nutrition'
        ],
        'progress' => [
            'frenchTitle' => 'Progression',
            'iconClass' => 'person-running',
            'link' => 'progress'
        ],
        'meetings' => [
            'frenchTitle' => 'Rendez-vous',
            'iconClass' => 'calendar',
            'link' => 'meetings'
        ],
        'subscription' => [
            'frenchTitle' => 'Abonnement',
            'iconClass' => 'star',
            'link' => 'subscription'
            ]
    ];

    public function getDashboardMenuItems() {
        return $this->dashboardMenuItems;
    }

    public function getMemberId (string $userEmail) {
        $db = $this->dbConnect();
        $userIdGetterQuery = 'SELECT id from accounts WHERE email = ?';
        $userIdGetterStatement = $db->prepare($userIdGetterQuery);
        $userIdGetterStatement->execute([$userEmail]);
        $userId = $userIdGetterStatement->fetch(PDO::FETCH_ASSOC);

        return $userId['id'];
    }
}