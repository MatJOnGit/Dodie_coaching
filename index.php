<?php

require_once './app/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./app/src/php/views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

if (!isset($_GET['page'])) {
    require('./app/src/php/controllers/ShowcaseController.php');
    $showcaseController = new ShowcaseController;
    $showcaseController->renderPresentationPage($twig);
}

elseif (isset($_GET['page'])) {

    /* Showcase page requests */
    if (in_array($_GET['page'], ['presentation', 'coaching', 'programslist', 'programdetails'])) {
        require('app/src/php/controllers/ShowcaseController.php');
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
    }

    /* Connection pages requests */

    elseif (in_array($_GET['page'], ['login', 'registering', 'passwordretrieving'])) {
        require('app/src/php/controllers/ConnectionController.php');
        $connectionController = new ConnectionController;
        $connectionController->renderLoginPage($twig);
    }


    else {
        header('Location: index.php');
    }
}