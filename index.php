<?php

require_once './app/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./app/src/php/views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$stylePath = 'app/src/css/showcase.css';

if (!isset($_GET['page'])) {
    require('./app/src/php/controllers/presentation.php');
    $presentationController = new PresentationController;
    $presentationController->renderPresentationPage($twig);
}

elseif (isset($_GET['page'])) {
    if ($_GET['page'] === 'presentation') {
        require('./app/src/php/controllers/presentation.php');
        $presentationController = new PresentationController;
        $presentationController->renderPresentationPage($twig);
        
    }
    elseif ($_GET['page'] === 'coaching') {
        require('./app/src/php/controllers/coaching.php');
        $coachingController = new CoachingController;
        $coachingController->renderCoachingPage($twig);
    }
    elseif ($_GET['page'] === 'programslist') {
        require('./app/src/php/controllers/programslist.php');
        $programsListController = new ProgramsListController;
        $programsListController->renderProgramsListPage($twig);
    }
    elseif ($_GET['page'] === 'programdetails') {
        if (isset($_GET['program'])) {
            if (in_array($_GET['program'], ['monthly', 'quarterly', 'halfyearly'])){
                require('./app/src/php/controllers/programdetails.php');
                $programDetailsController = new ProgramDetailsController;
                $programDetailsController->renderProgramDetails($twig);
            }
            else {
                header('Location: index.php?page=programslist');
            }
        }
        else {
            header('Location: index.php?page=programslist');
        }
    }
    else {
        header('Location: index.php');
    }
}