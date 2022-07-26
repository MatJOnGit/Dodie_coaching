<?php

require ('app/src/php/controllers/MainController.php');

class ConnectionController extends MainController {
    public $connectionPageStyles = ['pages/connection', 'components/header', 'components/internal-links', 'components/form', 'components/footer', 'themes/member-section'];

    public function renderLoginPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPageStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'login']);
        echo $twig->render('member/login.twig');
        echo $twig->render('templates/footer.twig');
    }

    public function renderRegisteringPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPageStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'login']);
        echo $twig->render('member/registering.twig');
        echo $twig->render('templates/footer.twig');
    }
}