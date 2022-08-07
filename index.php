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
        $page = htmlspecialchars($_GET['page']);
        $showcasePages = ['presentation', 'coaching', 'programslist', 'programdetails', 'showcase-404'];
        $connectionPages = ['login', 'registering', 'password-retrieving'];
        $memberPanelPages = ['dashboard', 'letsstart', 'meetings'];

        if (in_array($page, $showcasePages)) {
            require('app/src/php/controllers/ShowcaseController.php');
            $showcaseController = new ShowcaseController;

            if ($page === 'presentation') {
                $showcaseController->renderPresentationPage($twig);
            }
            elseif ($page === 'coaching') {
                $showcaseController->renderCoachingPage($twig);
            }
            else {
                if (!$showcaseController->verifyProgramsList()) {
                    if ($page === 'showcase-404') {
                        $showcaseController->render404Page($twig);
                    }
                    else {
                        header('location:index.php?page=showcase-404');
                    }
                }
                else {
                    if ($page === 'showcase-404') {
                        header('Location:index.php?page=programslist');
                    }
                    elseif ($page === 'programslist') {
                        $showcaseController->renderProgramsListPage($twig);
                    }
                    elseif (isset($_GET['program'])) {
                        $program = htmlspecialchars($_GET['program']);

                        if (is_null($program) || (!$showcaseController->verifyProgramDetails($program))) {
                            header('Location:index.php?page=programslist');
                        }
                        else {
                            $showcaseController->renderProgramDetailsPage($twig);
                        }
                    }
                }
            }
        }

        elseif (in_array($page, $connectionPages)) {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;
    
            if ($page === 'login') {
                $connectionController->renderLoginPage($twig);
            }
            elseif ($page === 'registering') {
                $connectionController->renderRegisteringPage($twig);
            }
            elseif ($page === 'password-retrieving') {
                $connectionController->renderPasswordRetrievingPage($twig);
            }
        }

        elseif (in_array($page, $memberPanelPages)) {
            require('app/src/php/controllers/MemberPanelController.php');
            $memberPanelController = new MemberPanelController;

            if (!$memberPanelController->verifyAccount()) {
                header('location:index.php?page=login');
            }
            else {
                if ($page === 'dashboard') {
                    $userStaticData = $memberPanelController->verifyUserStaticData();

                    if (!$userStaticData) {
                        // On créé une entrée dans la table user_Static_Data pour l'utilisateur, avec des valeurs nulle.
                        echo "L'utilisateur dont l'email est " . $_SESSION['user-email'] . " n'a pas encore entré de données statiques pour être coaché.<br>On lui affiche le formulaire.";
                    }
                    else {
                        $missingUserStaticData = $memberPanelController->getMissingUserStaticDataKey($userStaticData);

                        if (empty($missingUserStaticData)) {
                            $memberPanelController->renderMemberDashboard($twig);
                        }
                        else {
                            echo "Il manque quelques éléments à remplir par l'utilisateur dont l'email est " . $_SESSION['user-email'] . " pour être coaché.<br>On lui affiche le formulaire.";
                        }
                    }
                }

                else {
                    echo "Le compte est bien vérifié et des données existent, mais cette page du member panel n'existe pas encore ...";
                }
            }
        }
        
        else {
            header('Location: index.php');
        }
    }

    elseif (isset($_GET['action'])) {
        if ($_GET['action'] === 'log-account') {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;

            if ($connectionController->verifyLoginFormData()) {
                $dbUserPassword = $connectionController->verifyUserInDatabase();
                if (empty($dbUserPassword)) {
                    $connectionController->setFormErrorMessage('unknownEmail');
                    header("location:{$connectionController->connectionPagesURL['login']}");
                }
                elseif ($dbUserPassword[0] !== $connectionController->getUserPassword()) {
                    $connectionController->setFormErrorMessage('wrongPassword');
                    header("location:{$connectionController->connectionPagesURL['login']}");
                }
                else {
                    $isLoginDateUpdated = $connectionController->updateLoginDate();
                    if ($isLoginDateUpdated) {
                        header("location:{$connectionController->connectionPagesURL['dashboard']}");
                    }
                    else {
                        $connectionController->setFormErrorMessage('dbError');
                        header("location:{$connectionController->connectionPagesURL['login']}");
                    }
                }
            }
            else {
                $connectionController->setFormErrorMessage('invalidFormData');
                header("location:{$connectionController->connectionPagesURL['login']}");
            }
        }

        elseif ($_GET['action'] === 'register-account') {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;
            if ($connectionController->verifyRegisteringFormData()) {
                $dbUserPassword = $connectionController->verifyUserInDatabase();

                if (!empty($dbUserPassword)) {
                    $connectionController->setFormErrorMessage('usedEmail');
                    header("location:{$connectionController->connectionPagesURL['registering']}");
                }
                else {
                    $isAccountRegistered = $connectionController->setNewAccount();

                    if ($isAccountRegistered) {
                        header("location:{$connectionController->connectionPagesURL['dashboard']}");
                    }
                    else {
                        $connectionController->setFormErrorMessage('dbError');
                        header("location:{$connectionController->connectionPagesURL['registering']}");
                    }
                }
            }
            else {
                $connectionController->setFormErrorMessage('invalidFormData');
                header("Location:{$connectionController->connectionPagesURL['registering']}");
            }
            
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