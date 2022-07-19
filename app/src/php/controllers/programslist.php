<?php

require('app/src/php/model/model.php');

class ProgramsListController {
    public $stylePath = 'app/src/css/showcase.css';
    
    public function renderProgramsListPage($twig) {
        $programs = getProgramsList();
        echo $twig->render('templates/head.twig', ['stylePath' => $this->stylePath]);
        echo $twig->render('templates/header.twig', ['requestedPage' => 'programslist']);
        echo $twig->render('showcase/programslist.twig', ['programs' => $programs]);
        echo $twig->render('templates/footer.twig');
    }
}