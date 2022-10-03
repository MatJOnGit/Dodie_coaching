<?php

declare(strict_types=1);
session_start();

// session_destroy();
// echo $_SESSION['user-email'];

class DB_Exception extends Exception { }
class URL_Exception extends Exception { }
class Data_Exception extends Exception { }

try {
    require_once ('./../vendor/autoload.php');
    
    $loader = new \Twig\Loader\FilesystemLoader('./../src/views');
    
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
        'debug' => true
    ]);

    $twig->addExtension(new \Twig\Extension\DebugExtension());

    

    $Urls = [
        'pages' => [
            'showcase' => ['presentation', 'coaching', 'programslist', 'programdetails', 'showcase-404'],
            'connection' => ['login', 'registering', 'password-retrieving'],
            'userPanels' => ['get-to-know-you', 'dashboard', 'nutrition', 'progress', 'meetings', 'subscription']
        ],
        'actions' => [
            'connection' => ['log-account', 'register-account', 'log-out'],
            'progress' => ['add-report', 'delete-report'],
            'meeting' => ['book-appointment', 'cancel-appointment']
        ]
    ];

    if (isset($_GET['page'])) {
        $page = htmlspecialchars($_GET['page']);

        if (in_array($page, $Urls['pages']['showcase'])) {
            $showcase = new Dodie_Coaching\Controllers\Showcase;

            if ($showcase->isPresentationPageRequested($page)) {
                $showcase->renderShowcasePage($twig, 'presentation');
            }

            elseif ($showcase->isCoachingPageRequested($page)) {
                $showcase->renderShowcasePage($twig, 'coaching');
            }

            elseif ($showcase->areProgramsPagesRequested($page)) {
                if ($showcase->isProgramsListAvailable()) {
                    if ($showcase->isProgramsListRequested($page)) {
                        $showcase->renderShowcasePage($twig, 'programsList');
                    }

                    elseif ($showcase->isProgramDetailsRequested($page) && $showcase->isRequestedProgramSet()) {
                        $requestedProgram = $showcase->getProgram();

                        if ($showcase->isProgramAvailable($requestedProgram)) {
                            $showcase->renderShowcasePage($twig, 'programDetails');
                        }

                        else {
                            throw new URL_Exception('INVALID PROGRAM PARAMETER');
                            // $showcase->routeTo('programsList');
                        }
                    }

                    else {
                        throw new URL_Exception('MISSING PROGRAM PARAMETER');
                        // $showcase->routeTo('404');
                    }
                }

                else {
                    throw new Data_Exception('EMPTY PROGRAMS ARRAY IN SHOWCASE CONTROLLER');
                    // $showcase->routeTo('404');
                }
            }

            else {
                $showcase->renderShowcasePage($twig, '404');
            }
        }

        elseif (in_array($page, $Urls['pages']['connection'])) {
            $user = new Dodie_Coaching\Controllers\User;

            if (!$user->isLogged()) {
                if ($user->isLoginPageRequested($page)) {
                    $user->renderLoginPage($twig);
                }

                elseif ($user->isRegisteringPageRequested($page)) {
                    $user->renderRegisteringPage($twig);
                }

                else {
                    $user->renderPasswordRetrievingPage($twig);
                }
            }

            else {
                $user->routeTo('dashboard');
            }
        }

        elseif (in_array($page, $Urls['pages']['userPanels'])) {
            $user = new Dodie_Coaching\Controllers\User;

            if ($user->isLogged()) {
                $userPanels = new Dodie_Coaching\Controllers\UserPanels;
                $areDataCompleted = $user->areDataCompleted();

                if ($userPanels->isUserDashboardPageRequested($page) && $areDataCompleted) {
                    $userDashboard = new Dodie_Coaching\Controllers\UserDashboard;
                    $userDashboard->renderUserDashboardPage($twig);
                }

                elseif ($userPanels->isNutritionPageRequested($page) && $areDataCompleted) {
                    $nutrition = new Dodie_Coaching\Controllers\Nutrition;

                    if ($nutrition->isMenuRequested()) {
                        $nutrition->renderNutritionMenu($twig);
                    }

                    elseif ($nutrition->isMealRequested()) {
                        $mealData = $nutrition->getMealData();

                        if ($nutrition->areMealParamsValid($mealData)) {
                            $nutrition->renderMealComposition($twig, $mealData);
                        }

                        else {
                            throw new URL_Exception('INVALID MEAL PARAMETER');
                            // $userPanels->routeTo('nutrition');
                        }
                    }

                    elseif ($nutrition->isRequestSet()) {
                        $request = $nutrition->getRequest();

                        if ($nutrition->isShoppingListRequested($request)) {
                            $nutrition->renderShoppingList($twig);
                        }

                        else {
                            $userPanels->routeTo('nutrition');
                        }
                    }

                    else {
                        $userPanels->routeTo('nutrition');
                    }
                }

                elseif ($userPanels->isProgressPageRequested($page) && $areDataCompleted) {
                    $progress = new Dodie_Coaching\Controllers\Progress;
                    $progress->renderProgress($twig);
                }

                elseif ($userPanels->isMeetingsPageRequested($page) && $areDataCompleted){
                    $meetings = new Dodie_Coaching\Controllers\Meetings;
                    $meetings->renderMeetings($twig);
                }

                elseif ($userPanels->isSubscriptionPageRequested($page) && $areDataCompleted) {
                    $subscription = new Dodie_Coaching\Controllers\Subscription;
                    $subscription->renderSubscription($twig);
                }

                elseif ($userPanels->isStaticDataPageRequested($page)) {
                    $staticDataForm = new Dodie_Coaching\Controllers\StaticDataForm;
                    $staticDataForm->renderStaticDataForm($twig);
                }

                else {
                    $userPanels->routeTo('getToKnowYou');
                }
            }

            else {
                throw new URL_Exception('MISSING SESSION PARAMETERS');
                // $user->destroySessionData();
                // $user->routeTo('login');
            }
        }

        else {
            throw new URL_Exception('INVALID PAGE PARAMETER');
            // $user = new Dodie_Coaching\Controllers\User;
            // $user->destroySessionData();
            // $user->routeTo('login');
        }
    }

    elseif (isset($_GET['action'])) {
        $user = new Dodie_Coaching\Controllers\User;
        $action = $user->getRequestedAction();
        
        if (in_array($action, $Urls['actions']['connection'])) {

            if (!$user->isLogged()) {
                $userData = $user->getLoginFormData();

                if ($user->isLoginActionRequested($action)) {
                    if ($user->isLoginFormValid($userData)) {
                        if ($user->isAccountExisting($userData)) {
                            if ($user->updateLoginData($userData)) {
                                $user->logUser($userData);
                                $user->routeTo('dashboard');
                            }

                            else {
                                throw new DB_Exception('LOGGING FAILED');
                                // $user->routeTo('login');
                            }
                        }
    
                        else {
                            $user->destroySessionData();
                            $user->routeTo('login');
                        }
                    }
    
                    else {
                        $user->destroySessionData();
                        $user->routeTo('login');
                    }
                }

                elseif ($user->isRegisteringActionRequested($action)) {
                    $userData = $user->getRegistrationFormAdditionalData($userData);

                    if ($user->isRegisteringFormValid($userData)) {
                        if (!$user->isAccountExisting($userData)) {
                            $isUserRegistered = $user->registerAccount($userData);
    
                            if ($isUserRegistered) {
                                $user->createStaticData($userData);
                                $user->logUser($userData);
                                $user->routeTo('dashboard');
                            }
    
                            else {
                                throw new DB_Exception('REGISTERING FAILED');
                                // $user->destroySessionData();
                                // $user->routeTo('registering');
                            }
                        }
    
                        else {
                            $user->routeTo('registering');
                        }
                    }
    
                    else {
                        $user->destroySessionData();
                        $user->routeTo('registering');
                    }
                }
            }

            else {
                if ($user->isLogoutActionRequested($action)) {
                    $user->destroySessionData();
                    $user->routeTo('presentation');
                }
            } 
        }

        elseif (in_array($action, $Urls['actions']['progress'])) {
            $progress = new Dodie_Coaching\Controllers\Progress;

            if ($user->isLogged()) {
                if ($progress->isReportAdditionRequested($action)) {
                    if ($progress->areBaseFormDataSet()) {
                        $baseFormData = $progress->getBaseFormData();

                        if ($progress->areBaseFormDataValid($baseFormData)) {
                            if ($progress->isCurrentWeightReport($baseFormData)) {
                                $formatedFormData = $progress->getFormatedBaseFormData($baseFormData);
                                
                                if (!$progress->logProgress($formatedFormData)) {
                                    throw new DB_Exception('FAILED TO LOG CURRENT WEIGHT REPORT');
                                }
                            }

                            elseif ($progress->areExtendedFormDataSet()) {
                                $extendedFormData = $progress->getExtendedFormData($baseFormData);

                                if ($progress->areExtendedFormDataValid($extendedFormData)) {
                                    $formatedFormData = $progress->getFormatedExtendedFormData($extendedFormData);
                                    
                                    if (!$progress->logProgress($formatedFormData)) {
                                        throw new DB_Exception('FAILED TO LOG OLD WEIGHT REPORT');
                                    }
                                }

                                else {
                                    throw new URL_Exception('INVALID "DAY" OR "TIME" PARAMETERS FOR OLD WEIGHT REPORT');
                                }
                            }

                            else {
                                throw new URL_Exception('MISSING "DAY" OR "TIME" PARAMETER FOR OLD WEIGHT REPORT');
                            }
                        }

                        else {
                            throw new URL_Exception('INVALID "USER-WEIGHT" OR "DATE-TYPE" PARAMETERS FOR CURRENT WEIGHT REPORT');
                        }
                    }

                    else {
                        throw new URL_Exception('MISSING "USER-WEIGHT" OR "DATE-TYPE" PARAMETER FOR CURRENT WEIGHT REPORT');
                    }

                    $progress->routeTo('progress');
                }

                elseif ($progress->isReportDeletionRequested($action)) {
                    if ($progress->isReportIdSet()) {
                        $reportId = $progress->getReportId();

                        if ($progress->isReportIdValid($reportId)) {
                            $progressHistory = $progress->getHistory();

                            if ($progress->isReportIdExisting($progressHistory, $reportId)) {
                                if (!$progress->eraseProgress($progressHistory, $reportId)) {
                                    throw new DB_Exception ("FAILED TO DELETE REPORT");
                                }
                            }

                            else {
                                throw new Data_Exception("NO REPORT FOUND TO DELETE");
                            }
                        }

                        else {
                            throw new URL_Exception("INVALID REPORT ID PARAMETER");
                        }
                    }

                    else {
                        throw new URL_Exception("MISSING REPORT ID PARAMETER");
                    }
                }

                $progress->routeTo('progress');
            }

            else {
                $user->destroySessionData();
                $user->routeTo('login');
            }
        }

        elseif (in_array($action, $Urls['actions']['meeting'])) {
            $meetings = new Dodie_Coaching\Controllers\Meetings;

            if ($user->isLogged()) {
                if ($meetings->isBookingRequested($action)) {
                    $dateData = $meetings->getDateData();
                    
                    if ($meetings->areDateDataValid($dateData)) {
                        $formatedDate = $meetings->getFormatedDate($dateData);

                        if ($meetings->isMeetingsSlotAvailable($formatedDate)) {
                            if (!$meetings->bookAppointment($formatedDate)) {
                                throw new DB_Exception('FAILED TO BOOK APPOINTMENT');
                            }
                        }

                        else {
                            throw new Data_Exception('UNAVAILABLE MEETING SLOT');
                        }
                    }

                    else {
                        throw new URL_Exception('INVALID "DATE DATA" PARAMETER');
                    }
                }

                elseif ($meetings->isCancellationRequested($action)) {
                    if (!$meetings->cancelAppointment()) {
                        throw new DB_Exception('FAILED TO CANCEL APPOINTMENT');
                    }
                }

                $meetings->routeTo('meetings');
            }

            else {
                $user->destroySessionData();
                $user->routeTo('login');
            }
        }
        
        else {
            $user->destroySessionData();
            $user->routeTo('presentation');
        }
    }

    else {
        header("location:index.php?page=presentation");
    }
}

catch(DB_Exception $e) {
    echo "New DATABASE exception caught: '" . $e->getMessage() . "' in index file \non line " . $e->getLine();
}

catch(URL_Exception $e) {
    echo "New URL exception caught: '" . $e->getMessage() . "' in index file \non line " . $e->getLine();
}

catch(Data_Exception $e) {
    echo "New DATA exception caught: '" . $e->getMessage() . "' in index file \non line " . $e->getLine();
}

catch(Exception $e) {
    echo "New exception caught: '" . $e->getMessage() . "' in index file \non line " . $e->getLine();
}