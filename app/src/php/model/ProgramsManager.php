<?php

require_once('app/src/php/model/Manager.php');

class ProgramsManager extends Manager {
    public function getProgramsList() {
        $db = $this->dbConnect();
    
        $programQuery = 'SELECT * FROM programs';
        $programsStatement = $db->prepare($programQuery);
        $programsStatement->execute();
        $programs = $programsStatement->fetchAll();
    
        return $programs;
    }

    public function getProgramDetails($requestedProgram) {
        $db = $this->dbConnect();
    
        $programQuery = 'SELECT * FROM programs WHERE name = ?';
        $programsStatement = $db->prepare($programQuery);
        $programsStatement->execute(array($requestedProgram));
        $programDetails = $programsStatement->fetch();
    
        return $programDetails;
    }
}