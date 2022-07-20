<?php

require('app/src/php/model/ProgramsManager.php');

class ProgramsListController {
    public $stylePath = 'app/src/css/showcase.css';
    
    public function renderProgramsListPage($twig) {
        $programsManager = new ProgramsManager;
        $programsList = $programsManager->getProgramsList();
        echo $twig->render('templates/head.twig', ['stylePath' => $this->stylePath]);
        echo $twig->render('templates/header.twig', ['requestedPage' => 'programslist']);
        echo $twig->render('showcase/programslist.twig', ['programsList' => $programsList]);
        echo $twig->render('templates/footer.twig');
    }
}