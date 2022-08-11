<?php

require_once('app/src/php/model/Manager.php');

class DashboardManager extends Manager {
    public $dashboardMenuItems = array(
        'nutritionProgram' => array(
            'frenchTitle' => 'programme nutritionnel',
            'iconClass' => 'bowl-food',
            'link' => 'nutrition-program'
        ),
        'progression' => array(
            'frenchTitle' => 'progression',
            'iconClass' => 'person-running',
            'link' => 'progression'
        ),
        'meetings' => array(
            'frenchTitle' => 'meetings',
            'iconClass' => 'calendar',
            'link' => 'meetings'
        ),
        'subscription' => array(
            'frenchTitle' => 'abonnement',
            'iconClass' => 'star',
            'link' => 'subscription'
        )
    );
}