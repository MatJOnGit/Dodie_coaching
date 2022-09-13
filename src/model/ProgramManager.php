<?php

require_once ('./../src/model/Manager.php');

class ProgramManager extends Manager {
    public $programs = array(
        'monthly' => array(
            'name' => 'monthly',
            'frenchTitle' => 'Formule mois',
            'duration' => 30,
            'subscriptionPrice' => 219,
            'description' => "Vous avez un mariage de prévu et vous avez besoin d'un coup de main pour rentrer dans votre robe ou votre costume ?<br/><br/>Vous souhaitez peut-être simplement tester par vous-même mes services ?<br/><br/>Ce programme vous donnera des bases solides pour commencer à prendre soin de vous, et en toute sérénité."
        ),
        'quarterly' => array(
            'name' => 'quarterly',
            'frenchTitle' => 'Formule trimestre',
            'duration' => 90,
            'subscriptionPrice' => 649,
            'description' => ""
        ),
        'halfyearly' => array(
            'name' => 'halfyearly',
            'frenchTitle' => 'Formule 6 mois',
            'duration' => 180,
            'subscriptionPrice' => 1199,
            'description' => ""
        )
        // for tests with more than 3 programs, uncomment those following lines
        // ,'annual' => array(
        //     'name' => 'annual',
        //     'frenchTitle' => 'Formule un an',
        //     'duration' => 365,
        //     'subscriptionPrice' => 1999,
        //     'description' => ""
        // )
    );
}