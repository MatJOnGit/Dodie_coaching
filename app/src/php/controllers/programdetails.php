<?php

require('app/src/php/model/model.php');

class ProgramDetailsController {
    public $stylePath = 'app/src/css/showcase.css';

    public function renderProgramDetails($twig) {
        $requestedProgram = $_GET['program'];
        $programDetails = getProgramDetails($requestedProgram);

        echo $twig->render('templates/head.twig', ['stylePath' => $this->stylePath]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'programdetails']);
        echo $twig->render(
            'showcase/programdetails.twig',
            [
                'requestedPage' => 'programdetails',
                'program' => $programDetails
            ]
        );
        echo $twig->render('templates/footer.twig');
    }
}