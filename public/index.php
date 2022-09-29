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

    // require_once('./../src/Autoloader.php');
    // Autoloader::register();

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
            $showcaseController = new \App\Controllers\ShowcaseController;

            if ($page === 'presentation') {
                $showcaseController->renderPresentationPage($twig);
            }

            elseif ($page === 'coaching') {
                $showcaseController->renderCoachingPage($twig);
            }

            elseif (($page === 'programslist') || ($page === 'programdetails')) {
                if ($showcaseController->isProgramsListAvailable()) {
                    if ($page === 'programslist') {
                        $showcaseController->renderProgramsListPage($twig);
                    }

                    elseif (($page === 'programdetails') && (isset($_GET['program']))) {
                        $program = htmlspecialchars($_GET['program']);

                        if ($showcaseController->areProgramDetailsAvailable($program)) {
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
            require ('./../src/controllers/UserController.php');
            $userController = new App\Controllers\UserController;

            if (!$userController->isUserLogged()) {
                if ($page === 'login') {
                    $userController->renderLoginPage($twig);
                }

                elseif ($page === 'registering') {
                    $userController->renderRegisteringPage($twig);
                }

                elseif ($page === 'password-retrieving') {
                    $userController->renderPasswordRetrievingPage($twig);
                }
            }

            else {
                header("location:{$userController->getConnectionPanelsURL('dashboard')}");
            }
        }

        elseif (in_array($page, $Urls['pages']['memberPanels'])) {
            require('./../src/controllers/UserController.php');
            $userController = new App\Controllers\UserController;

            if ($userController->isUserLogged()) {
                $areUserStaticDataCompleted = $userController->areMemberStaticDataCompleted();

                if ($page === 'dashboard' && $areUserStaticDataCompleted) {
                    require('./../src/controllers/MemberPanelsController.php');
                    $memberPanelController = new App\Controllers\MemberPanelsController;
                    $memberPanelController->renderMemberDashboard($twig);
                }

                elseif ($page === 'nutrition-program' && $areUserStaticDataCompleted) {
                    require('./../src/controllers/NutritionProgramController.php');
                    $nutritionProgramController = new App\Controllers\NutritionProgramController;

                    if ($nutritionProgramController->isMenuRequested()) {
                        $nutritionProgramController->renderNutritionProgramMenu($twig);
                    }

                    elseif ($nutritionProgramController->isMealRequested()) {
                        $mealData = $nutritionProgramController->getMealData();

                        if ($nutritionProgramController->areMealParamsValid($mealData)) {
                            $nutritionProgramController->renderMealComposition($twig, $mealData);
                        }

                        else {
                            header("location:{$nutritionProgramController->getMemberPanelURL('nutritionProgram')}");
                        }
                    }

                    elseif ($nutritionProgramController->isShoppingListRequested()) {
                        if ($_GET['request'] === 'shopping-list') {
                            $nutritionProgramController->renderShoppingList($twig);
                        }

                        else {
                            header("location:{$nutritionProgramController->getMemberPanelURL('nutritionProgram')}");
                        }
                    }

                    else {
                        header("location:{$nutritionProgramController->getMemberPanelURL('nutritionProgram')}");
                    }
                }

                elseif ($page === 'progress' && $areUserStaticDataCompleted) {
                    require('./../src/controllers/ProgressController.php');
                    $progressController = new App\Controllers\ProgressController;
                    $progressController->renderMemberProgress($twig);
                }

                elseif ($page === 'meetings' && $areUserStaticDataCompleted){
                    require('./../src/controllers/MeetingsController.php');
                    $meetingsController = new App\Controllers\MeetingsController;
                    $meetingsController->renderMeetings($twig);
                }

                elseif ($page === 'get-to-know-you') {
                    require('./../src/controllers/MemberPanelsController.php');
                    $memberPanelController = new App\Controllers\MemberPanelsController;
                    $memberPanelController->renderUserStaticDataForm($twig);
                }

                else {
                    header('location:index.php?page=get-to-know-you');
                }
            }

            else {
                $userController->destroySessionData();
                header("location:{$userController->getConnectionPanelsURL('login')}");
            }
        }
        
        else {
            header('Location: index.php?page=presentation');
        }
    }

    elseif (isset($_GET['action'])) {
        $action = htmlspecialchars($_GET['action']);
        require('./../src/controllers/UserController.php');
        $userController = new App\Controllers\UserController;

        if (in_array($action, $Urls['actions']['connection'])) {

            if (!$userController->isUserLogged()) {
                $userData = $userController->getLoginFormData();

                if ($_GET['action'] === 'log-account') {
                    if ($userController->isLoginFormValid($userData)) {
                        $isUserVerified = $userController->isAccountValid($userData['email'], $userData['password']);

                        if ($isUserVerified) {
                            $isLoginDateUpdated = $userController->logUserLoginDate($userData['email']);
    
                            if ($isLoginDateUpdated) {
                                $userController->logUser($userData);
                                header("location:{$userController->getConnectionPanelsURL('dashboard')}");
                            }
                        }
    
                        else {
                            $userController->destroySessionData();
                            header("location:{$userController->getConnectionPanelsURL('login')}");
                        }
                    }
    
                    else {
                        $userController->destroySessionData();
                        header("location:{$userController->getConnectionPanelsURL('login')}");
                    }
                }

                elseif ($action === 'register-account') {
                    $userData = $userController->getRegistrationFormAdditionalData($userData);

                    if ($userController->isRegisteringFormValid($userData)) {
                        $isUserVerified = $userController->isAccountValid($userData['email'], $userData['password']);

                        if (!$isUserVerified) {
                            $isUserRegistered = $userController->registerAccount($userData);
    
                            if ($isUserRegistered) {
                                $userController->logUser($userData);
                                header("location:{$userController->getConnectionPanelsURL('dashboard')}");
                            }
    
                            else {
                                $userController->destroySessionData();
                                header("location:{$userController->getConnectionPanelsURL('registering')}");
                            }
                        }
    
                        else {
                            header("location:{$userController->getConnectionPanelsURL('registering')}");
                        }
                    }
    
                    else {
                        $userController->destroySessionData();
                        header("Location:{$userController->getConnectionPanelsURL('registering')}");
                    }
                }
            }

            else {
                if ($action === 'logout') {
                    $userController->destroySessionData();
                    header("Location:index.php?page=presentation");
                }
    
                else {
                    header("location:{$userController->getConnectionPanelsURL('dashboard')}");
                }
            } 
        }

        elseif (in_array($action, $Urls['actions']['progress'])) {
            require('./../src/controllers/ProgressController.php');
            $progressController = new App\Controllers\ProgressController;

            if ($userController->isUserLogged()) {
                if ($action === 'add-weight-report') {
                    if ($progressController->areBaseFormDataSet()) {
                        $baseFormData = $progressController->getBaseFormData();

                        if ($progressController->areBaseFormDataValid($baseFormData)) {
                            if ($progressController->isCurrentWeightReport($baseFormData)) {
                                $formatedFormData = $progressController->formatBaseFormData($baseFormData);
                                $progressController->logWeightReport($formatedFormData);
                            }

                            elseif ($progressController->areExtendedFormDataSet()) {
                                $extendedFormData = $progressController->getExtendedFormData($baseFormData);

                                if ($progressController->areExtendedFormDataValid($extendedFormData)) {
                                    $formatedFormData = $progressController->formatExtendedFormData($extendedFormData);
                                    $progressController->logWeightReport($formatedFormData);
                                }

                                else {
                                    throw new Exception('Le paramètre day et/ou time ne correspond pas à ce qui est attendu.');
                                }
                            }

                            else {
                                throw new Exception('Il manque des paramètres au niveau des données complémentaires pour un reporting différé');
                            }
                        }

                        else {
                            throw new Exception('Erreur au niveau des données de base de reporting');
                        }
                    }

                    else {
                        throw new Exception('Il manque des paramètres au niveau des données de base.');
                    }

                    header("location:{$progressController->getMemberPanelURL('progress')}");
                }

                elseif ($action === 'delete-weight-report') {
                    if ($progressController->isReportIdSet()) {
                        $reportId = $progressController->getReportId();

                        if ($progressController->isReportIdParamValid($reportId)) {
                            $progressHistory = $progressController->getMemberProgressHistory();

                            if ($progressController->isReportIdParamExisting($progressHistory, $reportId)) {
                                if (!$progressController->deleteReport($progressHistory, $reportId)) {
                                    throw new Exception ("ERROR EXECUTING REPORT DELETION IN DATABASE");
                                }
                            }

                            else {
                                throw new Exception("REPORT NOT FOUND IN DATABASE");
                            }
                        }

                        else {
                            throw new Exception("INVALID REPORT ID PARAMETER");
                        }
                    }

                    else {
                        throw new Exception("MISSING REPORT ID PARAMETER");
                    }
                }

                header("location:{$progressController->getMemberPanelURL('progress')}");
            }

            else {
                $userController->destroySessionData();
                header("location:{$progressController->getMemberPanelURL('login')}");
            }
        }

        elseif (in_array($action, $Urls['actions']['meeting'])) {
            require('./../src/controllers/MeetingsController.php');
            $meetingsController = new \App\Controllers\MeetingsController;

            if ($userController->isUserLogged()) {
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
                $userController->destroySessionData();
                header("location:{$meetingsController->getMemberPanelURL('login')}");
            }
        }
        
        else {
            $userController->destroySessionData();
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