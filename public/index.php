<?php

session_start();
// session_destroy();
// echo $_SESSION['user-email'];

try {
    require_once ('./../vendor/autoload.php');
    $loader = new \Twig\Loader\FilesystemLoader ('./../src/views');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
        'debug' => true
    ]);
    $twig->addExtension(new \Twig\Extension\DebugExtension());

    $Urls = [
        'pages' => [
            'showcase' => ['presentation', 'coaching', 'programslist', 'programdetails', 'showcase-404'],
            'connection' => ['login', 'registering', 'password-retrieving'],
            'memberPanels' => ['get-to-know-you', 'dashboard', 'nutrition-program', 'progress', 'meetings', 'subscription']
        ],
        'actions' => [
            'connection' => ['log-account', 'register-account', 'log-out'],
            'progress' => ['add-weight-report', 'delete-weight-report'],
            'meeting' => ['book-new-appointment', 'cancel-appointment']
        ]
    ];

    if (isset($_GET['page'])) {
        $page = htmlspecialchars($_GET['page']);

        if (in_array($page, $Urls['pages']['showcase'])) {
            require ('./../src/controllers/ShowcaseController.php');
            $showcaseController = new ShowcaseController;

            if ($page === 'presentation') {
                $showcaseController->renderPresentationPage($twig);
            }

            elseif ($page === 'coaching') {
                $showcaseController->renderCoachingPage($twig);
            }

            elseif (($page === 'programslist') || ($page === 'programdetails')) {
                if ($showcaseController->verifyProgramsListAvailability()) {
                    if ($page === 'programslist') {
                        $showcaseController->renderProgramsListPage($twig);
                    }

                    elseif (($page === 'programdetails') && (isset($_GET['program']))) {
                        $program = htmlspecialchars($_GET['program']);

                        if ($showcaseController->verifyProgramDetailsAvailability($program)) {
                            $showcaseController->renderProgramDetailsPage($twig, $program);
                        }

                        else {
                            header("Location:{$showcaseController->getShowcasePanelURL('programsList')}");
                        }
                    }

                    else {
                        header("Location:{$showcaseController->getShowcasePanelURL('showcase404')}");
                    }
                }

                else {
                    header("Location:{$showcaseController->getShowcasePanelURL('showcase404')}");
                }
            }

            else {
                if ($page === 'showcase-404') {
                    $showcaseController->render404Page($twig);
                }

                else {
                    header("Location:{$showcaseController->getShowcasePanelURL('showcase404')}");
                }
            }
        }

        elseif (in_array($page, $Urls['pages']['connection'])) {
            require ('./../src/controllers/AccountController.php');
            $accountController = new AccountController;
            $isUserLogged = $accountController->verifySessionDataValidity();

            if (!$isUserLogged) {
                if ($page === 'login') {
                    $accountController->renderLoginPage($twig);
                }

                elseif ($page === 'registering') {
                    $accountController->renderRegisteringPage($twig);
                }

                elseif ($page === 'password-retrieving') {
                    $accountController->renderPasswordRetrievingPage($twig);
                }
            }
            else {
                header("location:{$accountController->getConnectionPanelsURL('dashboard')}");
            }
        }

        elseif (in_array($page, $Urls['pages']['memberPanels'])) {
            require('./../src/controllers/AccountController.php');
            $accountController = new AccountController;

            if ($accountController->verifySessionDataValidity()) {
                $completeStaticDataAccount = $accountController->verifyMemberStaticDataCompletion();

                if ($page === 'dashboard' && $completeStaticDataAccount) {
                    require('./../src/controllers/MemberPanelsController.php');
                    $memberPanelController = new MemberPanelsController;
                    $memberPanelController->renderMemberDashboard($twig);
                }

                elseif ($page === 'nutrition-program' && $completeStaticDataAccount) {
                    require('./../src/controllers/NutritionProgramController.php');
                    $nutritionProgramController = new NutritionProgramController;

                    if (!isset($_GET['day']) && !isset($_GET['meal']) && !isset($_GET['request'])) {
                        $nutritionProgramController->renderMemberNutritionProgram($twig);
                    }

                    elseif (isset($_GET['day']) && isset($_GET['meal']) && !isset($_GET['request'])) {
                        echo "Affichage de la page précisant le détail du repas";
                    }

                    elseif (!isset($_GET['day']) && !isset($_GET['meal']) && isset($_GET['request'])) {
                        if ($_GET['request'] === 'shopping-list') {
                            echo "Affichage de la liste de courses";
                        }

                        elseif ($_GET['request'] === 'printable-program') {
                            echo "On télécharge le programme en version PDF";
                        }
                        
                        else {
                            header("location:{$nutritionProgramController->getMemberPanelURL('nutritionProgram')}");
                        }
                    }

                    else {
                        header("location:{$nutritionProgramController->getMemberPanelURL('nutritionProgram')}");
                    }
                }

                elseif ($page === 'progress' && $completeStaticDataAccount) {
                    require('./../src/controllers/ProgressController.php');
                    $progressController = new ProgressController;
                    $progressController->renderMemberProgress($twig);
                }

                elseif ($page === 'meetings' && $completeStaticDataAccount){
                    require('./../src/controllers/MeetingsController.php');
                    $meetingsController = new MeetingsController;
                    $meetingsController->renderMeetings($twig);
                }

                elseif ($page === 'get-to-know-you') {
                    require('./../src/controllers/MemberPanelsController.php');
                    $memberPanelController = new MemberPanelsController;
                    $memberPanelController->renderUserStaticDataForm($twig);
                }

                else {
                    header('location:index.php?page=get-to-know-you');
                }
            }

            else {
                $accountController->destroySessionData();
                header("location:{$accountController->getConnectionPanelsURL('login')}");
            }
        }
        
        else {
            header('Location: index.php?page=presentation');
        }
    }

    elseif (isset($_GET['action'])) {
        require('./../src/controllers/AccountController.php');
        $accountController = new AccountController;
        $action = htmlspecialchars($_GET['action']);

        if (in_array($action, $Urls['actions']['connection'])) {
            $isUserLogged = $accountController->verifySessionDataValidity();

            if (!$isUserLogged) {
                $userData = $accountController->getLoginFormData();

                if ($_GET['action'] === 'log-account') {
                    if ($accountController->verifyLoginFormDataValidity($userData)) {
                        $isAccountKnown = $accountController->verifyAccountValidity($userData['email'], $userData['password']);

                        if ($isAccountKnown) {
                            $isLoginDateUpdated = $accountController->updateLoginDate($userData['email']);
    
                            if ($isLoginDateUpdated) {
                                $accountController->setSessionData($userData);
                                header("location:{$accountController->getConnectionPanelsURL('dashboard')}");
                            }
                        }
    
                        else {
                            $accountController->destroySessionData();
                            header("location:{$accountController->getConnectionPanelsURL('login')}");
                        }
                    }
    
                    else {
                        $accountController->destroySessionData();
                        header("location:{$accountController->getConnectionPanelsURL('login')}");
                    }
                }

                elseif ($action === 'register-account') {
                    $userData = $accountController->getRegistrationFormAdditionalData($userData);

                    if ($accountController->verifyRegisteringFormValidity($userData)) {
                        $isAccountKnown = $accountController->verifyAccountValidity($userData['email'], $userData['password']);

                        if (!$isAccountKnown) {
                            $isAccountRegistered = $accountController->registerNewAccount($userData);
    
                            if ($isAccountRegistered) {
                                $accountController->setSessionData($userData);
                                header("location:{$accountController->getConnectionPanelsURL('dashboard')}");
                            }
    
                            else {
                                $accountController->destroySessionData();
                                header("location:{$accountController->getConnectionPanelsURL('registering')}");
                            }
                        }
    
                        else {
                            $accountController->destroySessionData();
                            header("location:{$accountController->getConnectionPanelsURL('registering')}");
                        }
                    }
    
                    else {
                        $accountController->destroySessionData();
                        header("Location:{$accountController->getConnectionPanelsURL('registering')}");
                    }
                }
            }

            elseif ($action === 'logout') {
                $accountController->destroySessionData();
                header("Location:index.php?page=presentation");
            }

            else {
                header("location:{$accountController->getConnectionPanelsURL('dashboard')}");
            }
        }

        elseif (in_array($action, $Urls['actions']['progress'])) {
            require('./../src/controllers/ProgressController.php');
            $progressController = new ProgressController;
            $isUserLogged = $accountController->verifySessionDataValidity();

            if ($isUserLogged) {
                if ($action === 'add-weight-report') {
                    if ($progressController->verifyAddWeightFormValidity()) {
                        $progressController->addWeightReport();
                    }
                }

                elseif ($action === 'delete-weight-report') {
                    if (isset($_GET['id'])) {
                        $weightReportId = $progressController->getDeleteWeightReportId();
                        $progressHistory = $progressController->getMemberProgressHistory($weightReportId);

                        if ($progressController->verifyWeightReportIdValidity($progressHistory, $weightReportId)) {
                            $progressController->deleteMemberReport($progressHistory, $weightReportId);
                        }
                    }
                }
                header("location:{$progressController->getMemberPanelURL('progress')}");
            }

            else {
                $accountController->destroySessionData();
                header("location:{$progressController->getMemberPanelURL('login')}");
            }
        }

        elseif (in_array($action, $Urls['actions']['meeting'])) {
            require('./../src/controllers/MeetingsController.php');
            $meetingsController = new MeetingsController;
            $isUserLogged = $accountController->verifySessionDataValidity();

            if ($isUserLogged) {
                if ($action === 'book-new-appointment') {
                    $requestedMeetingDate = $meetingsController->getMeetingDate();

                    if (!is_null($requestedMeetingDate)) {
                        if (in_array($requestedMeetingDate, $meetingsController->getMeetings())) {
                            $meetingsController->addAppointment($requestedMeetingDate);
                        }
                    }
                }

                elseif ($action === 'cancel-appointment') {
                    $meetingsController->cancelMemberNextMeeting();
                }
                header("location:{$meetingsController->getMemberPanelURL('meetings')}");
            }

            else {
                $accountController->destroySessionData();
                header("location:{$meetingsController->getMemberPanelURL('login')}");
            }
        }
        
        else {
            $accountController->destroySessionData();
            header("location:index.php?page=presentation");
        }
    }

    else {
        header("location:index.php?page=presentation");
    }
}

catch(Exception $e) {
    echo 'Erreur ! ' . $e->getMessage();
}