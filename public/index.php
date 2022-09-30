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
            'memberPanels' => ['get-to-know-you', 'dashboard', 'nutrition', 'progress', 'meetings', 'subscription']
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
            $showcaseController = new Dodie_Coaching\Controllers\ShowcaseController;

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
            $userController = new Dodie_Coaching\Controllers\UserController;

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
            $userController = new Dodie_Coaching\Controllers\UserController;

            if ($userController->isUserLogged()) {
                $areUserStaticDataCompleted = $userController->areMemberStaticDataCompleted();

                if ($page === 'dashboard' && $areUserStaticDataCompleted) {
                    $memberPanelController = new Dodie_Coaching\Controllers\MemberPanelsController;
                    $memberPanelController->renderMemberDashboard($twig);
                }

                elseif ($page === 'nutrition' && $areUserStaticDataCompleted) {
                    $nutritionController = new Dodie_Coaching\Controllers\NutritionController;

                    if ($nutritionController->isMenuRequested()) {
                        $nutritionController->renderNutritionMenu($twig);
                    }

                    elseif ($nutritionController->isMealRequested()) {
                        $mealData = $nutritionController->getMealData();

                        if ($nutritionController->areMealParamsValid($mealData)) {
                            $nutritionController->renderMealComposition($twig, $mealData);
                        }

                        else {
                            header("location:{$nutritionController->getMemberPanelURL('nutrition')}");
                        }
                    }

                    elseif ($nutritionController->isShoppingListRequested()) {
                        if ($_GET['request'] === 'shopping-list') {
                            $nutritionController->renderShoppingList($twig);
                        }

                        else {
                            header("location:{$nutritionController->getMemberPanelURL('nutrition')}");
                        }
                    }

                    else {
                        header("location:{$nutritionController->getMemberPanelURL('nutrition')}");
                    }
                }

                elseif ($page === 'progress' && $areUserStaticDataCompleted) {
                    $progressController = new Dodie_Coaching\Controllers\ProgressController;
                    $progressController->renderMemberProgress($twig);
                }

                elseif ($page === 'meetings' && $areUserStaticDataCompleted){
                    $meetingsController = new Dodie_Coaching\Controllers\MeetingsController;
                    $meetingsController->renderMeetings($twig);
                }

                elseif ($page === 'get-to-know-you') {
                    $memberPanelController = new Dodie_Coaching\Controllers\MemberPanelsController;
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
        $userController = new Dodie_Coaching\Controllers\UserController;

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
            $progressController = new Dodie_Coaching\Controllers\ProgressController;

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
            $meetingsController = new Dodie_Coaching\Controllers\MeetingsController;

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