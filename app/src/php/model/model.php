<?php

function getProgramsList() {
    try {
        $db = new PDO(
            'mysql:host=localhost;port=3308;dbname=dodie;charset=utf8',
            'root',
            'root'
        );
    }
    
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $programQuery = 'SELECT * FROM programs';
    $programsStatement = $db->prepare($programQuery);
    $programsStatement->execute();
    $programs = $programsStatement->fetchAll();

    return $programs;
}

function getProgramDetails($requestedProgram) {
    try {
        $db = new PDO(
            'mysql:host=localhost;port=3308;dbname=dodie;charset=utf8',
            'root',
            'root'
        );
    }
    
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $programQuery = 'SELECT * FROM programs WHERE name = ?';
    $programsStatement = $db->prepare($programQuery);
    $programsStatement->execute(array($requestedProgram));
    $programDetails = $programsStatement->fetch();

    return($programDetails);
}