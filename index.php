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

    $Urls = [
        'pages' => [
            'showcase' => ['presentation', 'coaching', 'programslist', 'programdetails', 'showcase-404'],
            'connection' => ['login', 'registering', 'password-retrieving'],
            'memberPanels' => ['get-to-know-you', 'dashboard', 'nutrition-program', 'progress', 'meetings', 'subscription']
        ],
        'actions' => [
            'connection' => ['log-account', 'register-account'],
            'progress' => ['add-weight-report', 'delete-weight-report'],
            'meeting' => ['book-new-appointment', 'cancel-appointment']
        ]
    ];

    if (isset($_GET['page'])) {
        $page = htmlspecialchars($_GET['page']);

        if (in_array($page, $Urls['pages']['showcase'])) {
            require('app/src/php/controllers/ShowcaseController.php');
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

                        if (!is_null($program) && ($showcaseController->verifyProgramDetailsAvailability($program))) {
                            $showcaseController->renderProgramDetailsPage($twig, $program);
                        }

                        else {
                            header("Location:{$showcaseController->getShowcasePanelsURL('programsList')}");
                        }
                    }

                    else {
                        header("Location:{$showcaseController->getShowcasePanelsURL('showcase404')}");
                    }
                }
            }

            else {
                if ($page === 'showcase-404') {
                    $showcaseController->render404Page($twig);
                }

                else {
                    header("Location:{$showcaseController->getShowcasePanelsURL('showcase404')}");
                }
            }
        }

        elseif (in_array($page, $Urls['pages']['connection'])) {
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

        elseif (in_array($page, $Urls['pages']['memberPanels'])) {
            require('app/src/php/controllers/AccountController.php');
            $accountController = new AccountController;

            if ($accountController->verifyAccountPasswordValidity()) {
                $completeStaticDataAccount = $accountController->verifyMemberStaticDataCompletion();

                if ($page === 'dashboard' && $completeStaticDataAccount) {
                    require('app/src/php/controllers/MemberPanelsController.php');
                    $memberPanelController = new MemberPanelsController;
                    $memberPanelController->renderMemberDashboard($twig);
                }

                elseif ($page === 'nutrition-program' && $completeStaticDataAccount) {
                    require('app/src/php/controllers/NutritionProgramController.php');
                    $nutritionProgramController = new NutritionProgramController;
                    $nutritionProgramController->renderMemberNutritionProgram($twig);
                }

                elseif ($page === 'progress' && $completeStaticDataAccount) {
                    require('app/src/php/controllers/ProgressController.php');
                    $progressController = new ProgressController;
                    $progressController->renderMemberProgress($twig);
                }

                elseif ($page === 'meetings' && $completeStaticDataAccount){
                    require('app/src/php/controllers/MeetingsController.php');
                    $meetingsController = new MeetingsController;
                    $meetingsController->renderMeetings($twig);
                }

                elseif ($page === 'get-to-know-you') {
                    require('app/src/php/controllers/MemberPanelsController.php');
                    $memberPanelController = new MemberPanelsController;
                    $memberPanelController->renderMemberDataForm($twig);
                }

                else {
                    header('location:index.php?page=get-to-know-you');
                }
            }

            else {
                header('location:index.php?page=login');
            }
        }
        
        else {
            header('Location: index.php?page=presentation');
        }
    }

    elseif (isset($_GET['action'])) {
        $action = htmlspecialchars($_GET['action']);

        if (in_array($action, $Urls['actions']['connection'])) {
            require('app/src/php/controllers/ConnectionController.php');
            require('app/src/php/controllers/AccountController.php');
            $connectionController = new ConnectionController;
            $accountController = new AccountController;

            if ($_GET['action'] === 'log-account') {
                if ($connectionController->verifyLoginFormData()) {
                    $dbUserPassword = $accountController->verifyAccountPasswordValidity();

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

            elseif ($action === 'register-account') {
                if ($connectionController->verifyRegisteringFormData()) {
                    $dbUserPassword = $accountController->verifyAccountPasswordValidity();

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

        elseif (in_array($action, $Urls['actions']['progress'])) {
            require('app/src/php/controllers/ProgressController.php');
            require('app/src/php/controllers/AccountController.php');
            $progressController = new ProgressController;
            $accountController = new AccountController;
            $dbUserPassword = $accountController->verifyAccountPasswordValidity();

            if (!empty($dbUserPassword)) {
                if ($action === 'add-weight-report') {
                    if ($progressController->verifyAddWeightFormValidity()) {
                        $progressController->addWeightReport();
                    }
                }

                elseif ($action === 'delete-weight-report') {
                    if (isset($_GET['id'])) {
                        $weightReportId = htmlspecialchars($_GET['id']);
                        $progressHistory = $progressController->getMemberProgressHistory($weightReportId);

                        if ($progressController->verifyWeightReportIdValidity($progressHistory, $weightReportId)) {
                            $progressController->deleteMemberReport($progressHistory, $weightReportId);
                        }
                    }
                }
                header("location:{$progressController->getMemberPanelsURLs('progress')}");
            }
            else {
                header("location:{$progressController->getMemberPanelsURLs['login']}");
            }
        }

        elseif (in_array($action, $Urls['actions']['meeting'])) {
            require('app/src/php/controllers/MeetingsController.php');
            require('app/src/php/controllers/AccountController.php');
            $meetingsController = new MeetingsController;
            $accountController = new AccountController;
            $dbUserPassword = $accountController->verifyAccountPasswordValidity();

            if (!empty($dbUserPassword)) {
                if ($action === 'book-new-appointment') {
                    $requestedMeetingDate = $meetingsController->getMeetingDate();

                    if (!is_null($requestedMeetingDate)) {
                        if (in_array($requestedMeetingDate, $meetingsController->getMeetings())){
                            $meetingsController->addAppointment($requestedMeetingDate);
                        }
                    }
                }

                elseif ($action === 'cancel-appointment') {
                    $meetingsController->cancelMemberNextMeeting();
                }
                header("location:{$meetingsController->getMemberPanelsURLs('meetings')}");
            }

            else {
                header("location:{$meetingsController->getMemberPanelsURLs('login')}");
            }
        }
    }

    else {
        header("location:index.php?page=presentation");
    }
}

catch(Exception $e) {
    echo 'Erreur ! ' . $e->getMessage();
}