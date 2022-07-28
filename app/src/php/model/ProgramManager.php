<?php

require_once('app/src/php/model/Manager.php');

class ProgramManager extends Manager {
    public function getProgramsList() {
        $db = $this->dbConnect();
    
        /* Réduire les datas pulled via la requête au minimum */
        $programListQuery = 'SELECT * FROM programs';
        $programsStatement = $db->prepare($programListQuery);
        $programsStatement->execute();
        $programs = $programsStatement->fetchAll();
    
        return $programs;
    }

    public function getProgramDetails($requestedProgram) {
        $db = $this->dbConnect();
    
        /* Réduire les datas pulled via la requête au minimum */
        $programDetailsQuery = 'SELECT * FROM programs WHERE name = ?';
        $programsStatement = $db->prepare($programDetailsQuery);
        $programsStatement->execute(array($requestedProgram));
        $programDetails = $programsStatement->fetch();
    
        return $programDetails;
    }
}