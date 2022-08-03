<?php

session_start();
// session_destroy();

try {
    require_once './app/vendor/autoload.php';
    $loader = new \Twig\Loader\FilesystemLoader('./app/src/php/views');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
        'debug' => true
    ]);
    $twig->addExtension(new \Twig\Extension\DebugExtension());

    if (isset($_GET['page'])) {

        $showcasePages = ['presentation', 'coaching', 'programslist', 'programdetails', 'showcase-404'];
        if (in_array($_GET['page'], $showcasePages)) {

            require('app/src/php/controllers/ShowcaseController.php');
            $showcaseController = new ShowcaseController;

            if ($_GET['page'] === 'presentation') {
                $showcaseController->renderPresentationPage($twig);
            }
            elseif ($_GET['page'] === 'coaching') {
                $showcaseController->renderCoachingPage($twig);
            }
            else {
                $programManager = new ProgramManager;
                $isProgramsDataAvailable = (count($programManager->programs) > 0) ? true : false;

                if (!$isProgramsDataAvailable) {
                    if ($_GET['page'] === 'showcase-404') {
                        $showcaseController->render404Page($twig);
                    }
                    else {
                        header('location:index.php?page=showcase-404');
                    }
                }
                else {
                    if ($_GET['page'] === 'showcase-404') {
                        header('Location:index.php?page=programslist');
                    }
                    elseif ($_GET['page'] === 'programslist') {
                        $showcaseController->renderProgramsListPage($twig);
                    }
                    else {
                        if ((!isset($_GET['program'])) || (!in_array($_GET['program'], array_keys($programManager->programs)))) {
                            header('Location:index.php?page=programslist');
                        }
                        else {
                            $showcaseController->renderProgramDetailsPage($twig);
                        }
                    }
                }
            }
        }

        elseif (in_array($_GET['page'], ['login', 'registering', 'password-retrieving'])) {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;
    
            if ($_GET['page'] === 'login') {
                $connectionController->renderLoginPage($twig);
            }
            elseif ($_GET['page'] === 'registering') {
                $connectionController->renderRegisteringPage($twig);
            }
            elseif ($_GET['page'] === 'password-retrieving') {
                $connectionController->renderPasswordRetrievingPage($twig);
            }
        }

        else if ($_GET['page'] === 'dashboard') {
            echo 'Bienvenue sur le dashboard';
        }
    
        else {
            header('Location: index.php');
        }
    }

    elseif (isset($_GET['action'])) {
        if ($_GET['action'] === 'register-account') {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;
            $connectionController->verifyRegistrationForm();
        }

        elseif ($_GET['action'] === 'log-account') {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;
            $connectionController->verifyLoginForm();
        }
    }

    else {
        require('./app/src/php/controllers/ShowcaseController.php');
        $showcaseController = new ShowcaseController;
        $showcaseController->renderPresentationPage($twig);
    }
}

catch(Exception $e) {
    echo 'Erreur ! ' . $e->getMessage();
}