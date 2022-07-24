<?php

require ('app/src/php/controllers/MainController.php');

class ConnectionController extends MainController {
    public function renderLoginPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePath' => $this->connectionStylePath]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'login']);
    }
}