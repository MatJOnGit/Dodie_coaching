<?php

class PresentationController {
    public $stylePath = 'app/src/css/showcase.css';
    
    public function renderPresentationPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePath' => $this->stylePath]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'presentation']);
        echo $twig->render('showcase/presentation.twig');
        echo $twig->render('templates/footer.twig');
    }
}