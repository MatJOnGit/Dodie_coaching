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
    require('./app/src/php/controllers/ShowcaseController.php');
    $showcaseController = new ShowcaseController;
    $showcaseController->renderPresentationPage($twig);
}

elseif (isset($_GET['page'])) {
    require('./app/src/php/controllers/ShowcaseController.php');
    $showcaseController = new ShowcaseController;

    if ($_GET['page'] === 'presentation') {
        $showcaseController->renderPresentationPage($twig);
        
    }
    elseif ($_GET['page'] === 'coaching') {
        $showcaseController->renderCoachingPage($twig);
    }
    elseif ($_GET['page'] === 'programslist') {
        $showcaseController->renderProgramsListPage($twig);
    }
    elseif ($_GET['page'] === 'programdetails') {
        if (isset($_GET['program'])) {
            if (in_array($_GET['program'], ['monthly', 'quarterly', 'halfyearly'])){
                $showcaseController->renderProgramDetailsPage($twig);
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