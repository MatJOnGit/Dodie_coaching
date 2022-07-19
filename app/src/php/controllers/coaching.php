<?php

class CoachingController {
    public $stylePath = 'app/src/css/showcase.css';

    public function renderCoachingPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePath' => $this->stylePath]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> $_GET['page']]);
        echo $twig->render('showcase/coaching.twig');
        echo $twig->render('templates/footer.twig');
    }
}