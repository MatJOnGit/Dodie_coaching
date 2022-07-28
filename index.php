<?php

try {
    require_once './app/vendor/autoload.php';
    $loader = new \Twig\Loader\FilesystemLoader('./app/src/php/views');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
        'debug' => true
    ]);
    $twig->addExtension(new \Twig\Extension\DebugExtension());
        
    if (isset($_GET['page'])) {
    
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

        elseif (in_array($_GET['page'], ['login', 'registering', 'password-retrieving'])) {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;
    
            if ($_GET['page'] === 'login') {
                if (!isset($_SESSION['user'])) {
                    $connectionController->renderLoginPage($twig);
                }
                else {
                    echo 'vous êtes déjà connecté, ' . ($_SESSION['user']);
                }
            }
            elseif ($_GET['page'] === 'registering') {
                if (!isset($_SESSION['user'])) {
                    $connectionController->renderRegisteringPage($twig);
                }
                else {
                    echo 'Vous êtes déjà enregistré, ' . ($_SESSION['user']);
                }
            }
            elseif ($_GET['page'] === 'password-retrieving') {
                $connectionController->renderPasswordRetrievingPage($twig);
            }
        }
    
        else {
            header('Location: index.php');
        }
    }

    elseif (isset($_GET['action'])) {
        if ($_GET['action'] === 'register-account') {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;
            $connectionController->registerAccount();
        }
        

        elseif ($_GET['action'] === 'log-account') {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;
            $connectionController->logAccount();
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