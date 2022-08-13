<?php

require_once('app/src/php/model/Manager.php');

class DashboardManager extends Manager {
    public $dashboardMenuItems = array(
        'nutritionProgram' => array(
            'frenchTitle' => 'Programme nutritionnel',
            'iconClass' => 'bowl-food',
            'link' => 'nutrition-program'
        ),
        'progression' => array(
            'frenchTitle' => 'Progression',
            'iconClass' => 'person-running',
            'link' => 'progression'
        ),
        'meetings' => array(
            'frenchTitle' => 'Rendez-vous',
            'iconClass' => 'calendar',
            'link' => 'meetings'
        ),
        'subscription' => array(
            'frenchTitle' => 'Abonnement',
            'iconClass' => 'star',
            'link' => 'subscription'
        )
    );
}