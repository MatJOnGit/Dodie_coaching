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
            'memberPanel' => ['get-to-know-you', 'dashboard', 'nutrition-program', 'progress', 'meetings', 'subscription']
        ],
        'actions' => [
            'connection' => ['log-account', 'register-account'],
            'progression' => ['add-weight-report', 'delete-weight-report'],
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

            else {
                if ($showcaseController->verifyProgramsListAvailability()) {
                    
                    if ($page === 'programslist') {
                        $showcaseController->renderProgramsListPage($twig);
                    }

                    elseif (isset($_GET['program'])) {
                        $program = htmlspecialchars($_GET['program']);

                        if (!is_null($program) && ($showcaseController->verifyProgramDetails($program))) {
                            $showcaseController->renderProgramDetailsPage($twig);
                        }

                        else {
                            header("Location:{$connectionController->showcasePanelURL['programsList']}");
                        }
                    }

                    elseif ($page === 'showcase-404') {
                        header("Location:{$connectionController->showcasePanelURL['showcase404']}");
                    }
                }

                else {
                    if ($page === 'showcase-404') {
                        $showcaseController->render404Page($twig);
                    }

                    else {
                        header("Location:{$connectionController->showcasePanelURL['showcase404']}");
                    }
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

        elseif (in_array($page, $Urls['pages']['memberPanel'])) {
            require('app/src/php/controllers/MemberPanelController.php');
            $memberPanelController = new MemberPanelController;

            if (!$memberPanelController->verifyAccount()) {
                header('location:index.php?page=login');
            }

            else {
                $userStaticData = $memberPanelController->verifyUserStaticData();

                if (($page !== 'get-to-know-you') && (!$userStaticData)) {
                    header('location:index.php?page=get-to-know-you');
                }

                elseif (($page === 'get-to-know-you') && ($userStaticData)) {
                    header('location:index.php?page=dashboard');
                }

                elseif ($page === 'dashboard') {
                    $memberPanelController->storeMemberIdentity();
                    $memberPanelController->renderMemberDashboard($twig);
                }

                elseif ($page === 'nutrition-program') {
                    $memberPanelController->storeMemberIdentity();
                    $memberPanelController->renderMemberNutritionProgram($twig);
                }

                elseif ($page === 'progress') {
                    $memberPanelController->storeMemberIdentity();
                    $memberPanelController->renderMemberProgress($twig);
                }

                elseif ($page === 'meetings'){
                    $memberPanelController->storeMemberIdentity();
                    $memberPanelController->renderMeetings($twig);
                }

                elseif ($page === 'get-to-know-you'){
                    $memberPanelController->renderMemberDataForm($twig);
                }
            }
        }
        
        else {
            header('Location: index.php');
        }
    }

    elseif (isset($_GET['action'])) {
        $action = htmlspecialchars($_GET['action']);

        if (in_array($action, $Urls['actions']['connection'])) {
            require('app/src/php/controllers/ConnectionController.php');
            $connectionController = new ConnectionController;

            if ($_GET['action'] === 'log-account') {
                if ($connectionController->verifyLoginFormData()) {
                    $dbUserPassword = $connectionController->verifyUserInDatabase($connectionController->getUserEmail());

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
                    $dbUserPassword = $connectionController->verifyUserInDatabase($this->getUserEmail());

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

        elseif (in_array($action, $Urls['actions']['progression'])) {
            require('app/src/php/controllers/MemberPanelController.php');
            $memberPanelController = new MemberPanelController;
            $dbUserPassword = $memberPanelController->verifyUserInDatabase($memberPanelController->getUserEmail());

            if (!empty($dbUserPassword)) {
                if ($action === 'add-weight-report') {
                    if ($memberPanelController->verifyAddWeightFormData()) {
                        $memberPanelController->addWeightReport();
                    }
                }

                elseif ($action === 'delete-weight-report') {
                    $progressHistory = $memberPanelController->getProgressHistory();

                    if (isset($_GET['id']) && $memberPanelController->verifyWeightReportId($progressHistory)) {
                        $reportId = htmlspecialchars($_GET['id']);
                        $memberPanelController->deleteMemberReport($reportId, $progressHistory);
                    }
                }
                header("location:{$memberPanelController->memberPanelsURL['progress']}");
            }
            else {
                header("location:{$memberPanelController->memberPanelsURL['login']}");
            }
        }

        elseif (in_array($action, $Urls['actions']['meeting'])) {
            require('app/src/php/controllers/MemberPanelController.php');
            $memberPanelController = new MemberPanelController;
            $dbUserPassword = $memberPanelController->verifyUserInDatabase($memberPanelController->getUserEmail());

            if (!empty($dbUserPassword)) {
                if ($action === 'book-new-appointment') {
                    $requestedMeetingDate = $memberPanelController->verifyMeetingFormData();

                    if (!is_null($requestedMeetingDate)) {
                        if (in_array($requestedMeetingDate, $memberPanelController->getMeetings())){
                            $memberPanelController->addAppointment($requestedMeetingDate);
                        }
                    }
                }

                elseif ($action === 'cancel-appointment') {
                    $memberPanelController->cancelMemberNextMeeting();
                }
                header("location:{$memberPanelController->memberPanelsURL['meetings']}");
            }

            else {
                header("location:{$memberPanelController->memberPanelsURL['login']}");
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