<?php

declare(strict_types=1);

session_start();

class DB_Exception extends Exception { }
class URL_Exception extends Exception { }
class Data_Exception extends Exception { }
class Mailer_Exception extends Exception { }
class PdfGenerator_Exception extends Exception { }

try {
    require_once ('./../vendor/autoload.php');
    
    $loader = new \Twig\Loader\FilesystemLoader('./../src/domain/templates/');
    $twig = new Twig\Environment($loader, [
        'cache' => false,
        'debug' => true
    ]);
    
    $twig->addExtension(new \Twig\Extension\DebugExtension());
    
    $routing = new App\Entities\Routing;
    $session = new App\Entities\Session;
    
    if ($routing->areParamsSet(['page'])) {
        $page = $routing->getParam('page');
        
        if (in_array($page, $routing::URLS['pages']['showcase'])) {
            $defaultDisplayer = new App\Domain\Controllers\ShowCasePanels\ShowcasePanel;
            $isLogged = $session->isUserLogged();
            
            if ($routing->isRequestMatching($page, 'presentation')) {
                $presentation = new App\Domain\Controllers\ShowCasePanels\Presentation;
                $presentation->renderPresentationPage($twig, $isLogged);
            }
            
            elseif ($routing->isRequestMatching($page, 'coaching')) {
                $coaching = new App\Domain\Controllers\ShowCasePanels\Coaching;
                $coaching->renderCoachingPage($twig, $isLogged);
            }
            
            elseif ($routing->isRequestMatching($page, 'programs-list')) {
                $programs = new App\Entities\ProgramsList;
                
                if ($programs->isProgramsListAvailable()) {
                    $programsList = new App\Domain\Controllers\ShowCasePanels\ProgramsList;
                    $programsList->renderProgramsListPage($twig, $programs, $isLogged);
                }
                
                else {
                    throw new Data_Exception('MISSING PROGRAMS LIST IN DATA');
                    // $defaultDisplayer->routeTo('404');
                }
            }
            
            elseif ($routing->isRequestMatching($page, 'program-details')) {
                $programs = new App\Entities\ProgramsList;
                
                if ($programs->isProgramsListAvailable()) {
                    if ($routing->areParamsSet(['program'])) {
                        $programDetails = new App\Domain\Controllers\ShowCasePanels\ProgramDetails;
                        
                        $requestedProgram = $routing->getParam('program');
                        
                        if ($programs->isProgramAvailable($requestedProgram)) {
                            $programDetails->renderProgramDetailsPage($twig, $programs, $requestedProgram, $isLogged);
                        }
                        
                        else {
                            throw new URL_Exception('INVALID PROGRAM PARAMETER');
                            // $programDetails->routeTo('404');
                        }
                    }
                    
                    else {
                        throw new URL_Exception('MISSING PROGRAM PARAMETER');
                        // $defaultDisplayer->routeTo('404');
                    }
                }
                
                else {
                    throw new Data_Exception('MISSING PROGRAMS LIST IN DATA');
                    // $defaultDisplayer->routeTo('404');
                }
            }
            
            else {
                $showcase404 = new App\Domain\Controllers\ShowCasePanels\Showcase404;
                $showcase404->renderShowcase404Page($twig, $isLogged);
            }
        }
        
        elseif (in_array($page, $routing::URLS['pages']['authentification'])) {
            if (!$session->isUserLogged()) {
                if ($routing->isRequestMatching($page, 'login')) {
                    $login = new App\Domain\Controllers\AuthPanels\Login;
                    $login->renderLoginPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'registering')) {
                    $registering = new App\Domain\Controllers\AuthPanels\Registering;
                    $registering->renderRegisteringPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'password-retrieving')) {
                    $passwordRetrieving = new App\Domain\Controllers\AuthPanels\PasswordRetrieving;
                    $passwordRetrieving->renderPasswordRetrievingPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'password-editing')) {
                    $passwordEditing = new App\Domain\Controllers\AuthPanels\PasswordEditing;
                    $passwordEditing->renderPasswordEditingPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'mail-notification')) {
                    $passwordNotification = new App\Domain\Controllers\AuthPanels\PasswordNotification;
                    $passwordNotification->renderMailNotificationPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'token-signing')) {
                    $tokenSigning = new App\Domain\Controllers\AuthPanels\TokenSigning;
                    $tokenSigning->renderTokenSigningPage($twig);
                }
            }
            
            else {
                $dashboard = new App\Domain\Controllers\CostumerPanels\Dashboard;
                $dashboard->routeTo('dashboard');
            }
        }
        
        elseif (in_array($page, $routing::URLS['pages']['userPanels'])) {
            if ($session->isUserLogged()) {
                $defaultDisplayer = new App\Domain\Controllers\CostumerPanels\CostumerPanel;
                $userRole = $routing->getRole();
                
                if ($userRole && $routing->isRoleMatching($userRole, ['member', 'subscriber'])) {
                    if ($routing->isRequestMatching($page, 'dashboard')) {
                        $dashboard = new App\Domain\Controllers\CostumerPanels\Dashboard;
                        $dashboard->renderCostumerDashboardPage($twig);
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'nutrition')) {
                        $program = new App\Entities\Program;
                        $meal = new App\Entities\Meal;
                        
                        if ($program->isMenuRequested()) {
                            $programMenu = new App\Domain\Controllers\CostumerPanels\ProgramMenu;
                            $programFile = new App\Entities\ProgramFile;
                            $program = new App\Entities\Program;
                            
                            $subscriberId = intval($routing->getUserId()['id']);
                            $programMenu->renderNutritionMenuPage($twig, $program, $meal, $programFile, $subscriberId);
                        }
                        
                        elseif ($program->isMealRequested()) {
                            $mealParams = $program->getMealParams();
                            
                            if ($program->areMealParamsValid($mealParams)) {
                                $mealDetails = new App\Domain\Controllers\CostumerPanels\MealDetails;
                                $subscriberId = intval($routing->getUserId()['id']);
                                $mealDetails->renderMealDetailsPage($twig, $program, $meal, $mealParams, $subscriberId);
                            }
                            
                            else {
                                throw new URL_Exception('INVALID MEAL PARAMETER');
                                // $defaultDisplayer->routeTo('nutrition');
                            }
                        }
                        
                        elseif ($program->isRequestSet()) {
                            $request = $program->getRequest();
                            
                            if ($program->isShoppingListRequested($request)) {
                                $shoppingList = new App\Domain\Controllers\CostumerPanels\ShoppingList;
                                
                                $subscriberId = intval($routing->getUserId()['id']);
                                $shoppingList->renderShoppingListPage($twig, $subscriberId);
                            }
                            
                            else {
                                $defaultDisplayer->routeTo('nutrition');
                            }
                        }
                        
                        else {
                            $defaultDisplayer->routeTo('nutrition');
                        }
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'progress')) {
                        $progress = new App\Domain\Controllers\CostumerPanels\Progress;
                        $progressReport = new App\Entities\ProgressReport;
                        $progress->renderProgressPage($twig, $progressReport);
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'meetings-booking')){
                        $meetingBooking = new App\Domain\Controllers\CostumerPanels\MeetingBooking;
                        $meetingBooking->renderMeetingsBookingPage($twig);
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'subscription')) {
                        $subscription = new \App\Domain\Controllers\CostumerPanels\Subscription;
                        $subscription->renderSubscriptionPage($twig);
                    }
                    
                    else {
                        throw new Exception('UNKNOWN PAGE REQUESTED');
                        // $defaultDisplayer->routeTo('nutrition');
                    }
                }
                
                elseif ($userRole && $routing->isRoleMatching($userRole, ['admin'])) {
                    $dashboard = new App\Domain\Controllers\AdminPanels\Dashboard;
                    $dashboard->routeTo('admin-dashboard');
                }
                
                else {
                    throw new Data_Exception('INVALID USER ROLE');
                    // $defaultDisplayer->routeTo('login');
                }
            }
            
            else {
                $session->logoutUser();
            }
        }
        
        elseif (in_array($page, $routing::URLS['pages']['adminPanels'])) {
            if ($session->isUserLogged()) {
                $defaultDisplayer = new App\Domain\Controllers\AdminPanels\AdminPanel;
                $userRole = $routing->getRole();
                
                if ($userRole && $routing->isRoleMatching($userRole, ['admin'])) {
                    $adminPanel = new App\Domain\Controllers\AdminPanels\AdminPanel;
                    
                    if ($routing->isRequestMatching($page, 'admin-dashboard')) {
                        $dashboard = new App\Domain\Controllers\AdminPanels\Dashboard;
                        $dashboard->renderAdminDashboardPage($twig);
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'appliances-list')) {
                        $appliancesList = new App\Domain\Controllers\AdminPanels\AppliancesList;
                        $appliancesList->renderAppliancesListPage($twig);
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'appliance-details') && $routing->areParamsSet(['id'])) {
                        $applianceDetails = new App\Domain\Controllers\AdminPanels\ApplianceDetails;
                        $appliance = new App\Entities\Appliance;
                        
                        $applicantId = intval($routing->getParam('id'));
                        
                        if ($appliance->isApplianceIdValid($applicantId)) {
                            $applianceDetails->renderApplianceDetailsPage($twig, $applicantId);
                        }
                        
                        else {
                            $applianceDetails->routeTo('appliancesList');
                        }
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'subscribers-list')) {
                        $subscribersList = new App\Domain\Controllers\AdminPanels\SubscribersList;
                        $subscribersList->renderSubscribersListPage($twig);
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'subscriber-profile')) {
                        $subscriberProfile = new App\Domain\Controllers\AdminPanels\SubscriberProfile;
                        
                        if ($routing->areParamsSet(['id'])) {
                            $subscriber = new App\Entities\Subscriber;
                            $subscriberId = intval($routing->getParam('id'));
                            
                            if ($subscriber->isSubscriberIdValid($subscriberId)) {
                                $subscriberProfile->renderSubscriberProfilePage($twig, $subscriberId);
                            }
                            
                            else {
                                $subscriberProfile->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $defaultDisplayer->routeTo('subscribersList');
                        }
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'subscriber-program')) {
                        $subscriberProgram = new App\Domain\Controllers\AdminPanels\SubscriberProgram;
                        $subscriber = new App\Entities\Subscriber;
                        
                        if ($routing->areParamsSet(['id'])) {
                            $program = new App\Entities\Program;
                            $subscriberId = intval($routing->getParam('id'));
                            
                            if ($subscriber->isSubscriberIdValid($subscriberId)) {
                                $subscriber = new App\Entities\Subscriber;
                                $program = new App\Entities\Program;
                                $meal = new App\Entities\Meal;
                                $programFile = new App\Entities\ProgramFile;
                                
                                $fileStatus = $programFile->getProgramFileStatus($subscriberId);
                                
                                $subscriberProgram->renderSubscriberProgramPage($twig, $subscriber, $program, $programFile, $meal, $fileStatus, $subscriberId);
                            }
                            
                            else {
                                $defaultDisplayer->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $defaultDisplayer->routeTo('subscribersList');
                        }
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'subscriber-notes')) {
                        if ($routing->areParamsSet(['id'])) {
                            $subscriberNote = new App\Domain\Controllers\AdminPanels\SubscriberNote;
                            $subscriber = new App\Entities\Subscriber;
                            
                            $subscriberId = intval($routing->getParam('id'));
                            
                            if ($subscriber->isSubscriberIdValid($subscriberId)) {
                                $subscriber = new App\Entities\Subscriber;
                                $meeting = new App\Entities\Meeting;
                                $subscriberNote->renderSubscriberNotesPage($twig, $subscriber, $meeting, $subscriberId);
                            }
                            
                            else {
                                $defaultDisplayer->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $note->routeTo('subscribersList');
                        }
                    }
                    
                    else if ($routing->isRequestMatching($page, 'meetings-management')) {
                        $meetingManagement = new App\Domain\Controllers\AdminPanels\MeetingManagement;
                        
                        $meetingManagement->renderMeetingsManagementPage($twig);
                    }
                    
                    else if ($routing->isRequestMatching($page, 'subscriber-meal')) {
                        $program = new App\Entities\Program;
                        
                        if ($routing->areParamsSet(['id', 'meal'])) {
                            $mealParsedParams = $program->getMealParams();
                            $subscriberId = intval($routing->getParam('id'));
                            
                            if ($program->areMealParamsValid($mealParsedParams)) {
                                $subscriberMeal = new App\Domain\Controllers\AdminPanels\SubscriberMeal;
                                $subscriber = new App\Entities\Subscriber;
                                $program = new App\Entities\Program;
                                $meal = new App\Entities\Meal;
                                
                                $latestMealStatus = $program->getLatestMealStatus($mealParsedParams['meal'], $mealParsedParams['day'], $subscriberId);
                                
                                if ($latestMealStatus === 'confirmed') {
                                    $subscriberMeal->renderSubscriberMeal($twig, $subscriber, $program, $meal, $subscriberId, $mealParsedParams, $latestMealStatus);
                                }
                                
                                else {
                                    header("location:index.php?page=subscriber-meal-editing&id=" . $subscriberId . "meal=" . $mealParam);
                                }
                            }
                            
                            else {
                                throw new URL_Exception('INVALID MEAL PARAMETER');
                            }
                        }
                        
                        else {
                            throw new URL_Exception('MISSING ID AND/OR MEAL PARAM IN URL');
                            // header("location:index.php?page=subscriber-program&id=" . $subscriberId);
                        }
                    }
                    
                    else if ($routing->isRequestMatching($page, 'subscriber-meal-editing')) {
                        $program = new App\Entities\Program;
                        
                        if ($routing->areParamsSet(['id', 'meal'])) {
                            $mealParsedParams = $program->getMealParams();
                            $subscriberId = intval($routing->getParam('id'));
                            
                            if ($program->areMealParamsValid($mealParsedParams)) {
                                $subscriberMealEditing = new App\Domain\Controllers\AdminPanels\SubscriberMealEditing;
                                $subscriber = new App\Entities\Subscriber;
                                $program = new App\Entities\Program;
                                $meal = new App\Entities\Meal;
                                
                                $mealParam = $routing->getParam('meal');
                                $subscriberMealEditing->renderSubscriberMealEditing($twig, $subscriber, $program, $meal, $subscriberId, $mealParam, $mealParsedParams);
                            }
                            
                            else {
                                throw new URL_Exception('INVALID MEAL PARAMETER');
                            }
                        }
                        
                        else {
                            throw new URL_Exception('MISSING ID AND/OR MEAL PARAM IN URL');
                            // header("location:index.php?page=subscriber-program&id=" . $subscriberId);
                        }
                    }
                    
                    else if ($routing->isRequestMatching($page, 'ingredients-management')) {
                        $ingredientsManagement = new App\Domain\Controllers\AdminPanels\IngredientsManagement;
                        
                        $ingredientsManagement->renderIngredientsManagementPage($twig);
                    }

                    else if ($routing->isRequestMatching($page, 'recipes-management')) {
                        $recipesManagement = new App\Domain\Controllers\AdminPanels\RecipesManagement;
                        
                        $recipesManagement->renderRecipesManagementPage($twig);
                    }
                }
                
                else {
                    throw new Data_Exception('INVALID USER ROLE');
                    // $session->logoutUser();
                }
            }
            
            else {
                $session->logoutUser();
            }
        }
        
        else {
            throw new URL_Exception('INVALID PAGE PARAMETER');
            // $session->logoutUser();
        }
    }
    
    elseif ($routing->areParamsSet(['action'])) {
        $action = $routing->getParam('action');
        
        if (in_array($action, $routing::URLS['actions']['authentification'])) {
            $authPanel = new App\Domain\Controllers\AuthPanels\AuthPanel;
            $authForm = new App\Entities\AuthForm;
            
            if ($routing->isRequestMatching($action, 'log-account') && !$session->isUserLogged()) {
                if ($authForm->areDataPosted(['email', 'password'])) {
                    $routingData = $authForm->getData(['email', 'password']);
                    
                    if ($authForm->areDataValid($routingData)) {
                        $account = new App\Entities\Account;
                        
                        if ($account->isAccountExisting($routingData)) {
                            if ($account->updateLoginData($routingData)) {
                                $apiKey = $account->getApiKey($routingData);
                                
                                if ($apiKey) {
                                    $session->logUser($routingData, $apiKey);
                                    $authPanel->routeTo('dashboard');
                                }
                                
                                else {
                                    throw new DB_Exception('COULD NOT GET API TOKEN');
                                }
                            }
                            
                            else {
                                throw new DB_Exception('FAILED TO UPDATE LOGGING DATE');
                                // $authPanel->routeTo('login');
                            }
                        }
                        
                        else {
                            $authPanel->routeTo('login');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('INVALID EMAIL AND/OR PASSWORD PARAMETERS');
                    }
                }
                
                else {
                    throw new Data_Exception('MISSING EMAIL AND/OR PASSWORD PARAMETERS');
                }
            }
            
            elseif ($routing->isRequestMatching($action, 'register-account') && !$session->isUserLogged()) {
                if ($authForm->areDataPosted(['email', 'password', 'confirmation-password'])) {
                    $routingData = $authForm->getData(['email', 'password', 'confirmation-password']);
                    
                    if ($authForm->areDataValid($routingData)) {
                        $account = new App\Entities\Account;
                        
                        if (!$account->isEmailExisting($routingData['email'])) {
                            if ($account->registerAccount($routingData)) {
                                $staticData = new App\Entities\StaticData;
                                
                                $staticData->createStaticData($routingData);
                                $apiKey = $account->getApiKey($routingData);
                                
                                if ($apiKey) {
                                    $session->logUser($routingData, $apiKey);
                                    $authPanel->routeTo('dashboard');
                                }
                                
                                else {
                                    throw new DB_Exception('COULD NOT GET API TOKEN');
                                }
                            }
                            
                            else {
                                throw new DB_Exception('FAILED TO REGISTER ACCOUNT');
                                // $authPanel->routeTo('registering');
                            }
                        }
                        
                        else {
                            $authPanel->routeTo('registering');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('INVALID EMAIL AND/OR PASSWORD PARAMETERS');
                    }
                }
                
                else {
                    throw new Data_Exception('MISSING EMAIL AND/OR PASSWORD AND OR CONFIRMATION-PASSWORD PARAMETERS');
                }
            }
            
            elseif ($routing->isRequestMatching($action, 'send-token') && !$session->isUserLogged()) {
                $token = new App\Entities\ResetToken;
                
                if ($authForm->areDataPosted(['email'])) {
                    $userData = $authForm->getData(['email']);
                    
                    if ($authForm->areDataValid($userData)) {
                        $account = new App\Entities\Account;
                        
                        if ($account->isEmailExisting($userData['email'])) {
                            $tokenData = $token->getTokenDate($userData['email']);
                            
                            if ($tokenData) {
                                if ($token->isLastTokenOld($tokenData)) {
                                    if ($token->eraseToken($userData['email'])) {
                                        $apiKey = $account->getApiKey($routingData);
                                
                                        if ($apiKey) {
                                            $session->logUser($routingData, $apiKey);
                                            $defaultDisplayer->routeTo('send-token');
                                        }
                                        
                                        else {
                                            throw new DB_Exception('COULD NOT GET API TOKEN');
                                        }
                                    }
                                    
                                    else {
                                        throw new DB_Exception('FAILED TO DELETE PREVIOUS TOKEN');
                                        $defaultDisplayer->routeTo('mail-notification');
                                    }
                                }
                                
                                else {
                                    $defaultDisplayer->routeTo('mail-notification');
                                }
                            }
                            
                            else {
                                $passwordRetrieving = new App\Domain\Controllers\AuthPanels\PasswordRetrieving;
                                
                                $session->sessionize($userData, ['email']);
                                $passwordRetrieving->routeTo('send-token');
                            }
                        }
                        
                        else {
                            $defaultDisplayer->routeTo('mail-notification');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('INVALID FORM DATA');
                    }
                }
                
                elseif ($session->isDataSessionized('email')) {
                    $tokenData = $token->generateToken();
                    
                    if ($token->registerToken($tokenData)) {
                        $passwordRetriever = new App\Services\PasswordRetriever;
                        
                        if (!$passwordRetriever->sendToken($tokenData)) {
                            throw new Mailer_Exception('FAILED TO SEND NEW TOKEN TO THE USER MAILBOX');
                        }
                        
                        else {
                            $passwordNotification = new App\Domain\Controllers\AuthPanels\PasswordNotification;
                            $passwordNotification->routeTo('mail-notification');
                        }
                    }
                    
                    else {
                        throw new DB_Exception('FAILED TO INSERT A NEW TOKEN');
                    }
                }
                
                else {
                    throw new Data_Exception('MISSING EMAIL PARAMETER');
                }
            }
            
            elseif ($routing->isRequestMatching($action, 'verify-token') && !$session->isUserLogged()) {
                $token = new App\Entities\ResetToken;
                $authForm = new App\Entities\Form;
                if ($session->isDataSessionized('email') && $authForm->areDataPosted(['token'])) {
                    $routingData = $authForm->getData(['token']);
                    
                    if ($authForm->areDataValid($routingData)) {
                        $authPanel = new App\Domain\Controllers\AuthPanels\AuthPanel;
                        
                        if ($token->isTokenMatching()) {
                            $session->sessionize($routingData, ['token']);
                            $authPanel->routeTo('edit-password');
                        }
                        
                        else {
                            if (!$token->subtractTokenAttempt()) {
                                throw new DB_Exception('FAILED TO SUBTRACT A TOKEN ATTEMPT');                                
                            }
                            
                            $authPanel->routeTo('token-signing');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('INVALID TOKEN DATA IN FORM');
                    }
                }
                
                else {
                    throw new Data_Exception('MISSING EMAIL IN SESSION AND/OR TOKEN IN POST');
                }
            }
            
            elseif ($routing->isRequestMatching($action, 'register-password') && !$session->isUserLogged()) {
                $authForm = new App\Entities\Form;
                
                if ($authForm->areDataPosted(['password', 'confirmation-password'])) {
                    $routingData = $authForm->getData(['password', 'confirmation-password']);
                    
                    if ($authForm->areDataValid($routingData)) {
                        $session = new App\Entities\Session;
                        
                        if ($session->isDataSessionized('email')) {
                            $account = new App\Entities\Account;
                            
                            $routingEmail = $session->getSessionizedParam('email');
                            
                            if ($account->registerPassword($routingData)) {
                                $token = new App\Entities\ResetToken;
                                
                                if ($token->eraseToken($routingEmail)) {
                                    $session->unsessionizeData(['token']);
                                    $session->sessionize($routingData, ['password']);
                                    $defaultDisplayer->routeTo('retrieved-password');
                                }
                                
                                else {
                                    throw new DB_Exception('FAILED TO REMOVE TOKEN');
                                }
                            }
                            
                            else {
                                throw new DB_Exception('FAILED TO REGISTER NEW PASSWORD');
                            }
                        }
                        
                        else {
                            throw new Data_Exception('MISSING EMAIL DATA IN SESSION');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('INVALID PASSWORD AND/OR CONFIRMATION PASSWORD PARAMETERS');
                    }
                }
                
                else {
                    throw new Data_Exception('MISSING PASSWORD AND/OR CONFIRMATION PASSWORD DATA');
                }
            }
            
            elseif ($routing->isRequestMatching($action, 'logout') && $session->isUserLogged()) {
                $showcase = new App\Domain\Controllers\ShowCasePanels\ShowcasePanel;
                $session->logoutUser();
            }
        }
        
        elseif (in_array($action, $routing::URLS['actions']['progress'])) {
            $progress = new App\Domain\Controllers\CostumerPanels\Progress;
            $progressReport = new App\Entities\ProgressReport;
            
            if ($session->isUserLogged()) {
                if ($routing->isRequestMatching($action, 'add-report')) {
                    $progressForm = new App\Entities\ProgressForm;
                    
                    if ($progressForm->areBaseFormDataSet()) {
                        $baseFormData = $progressForm->getBaseFormData();
                        
                        if ($progressForm->areBaseFormDataValid($baseFormData)) {
                            if ($progressReport->isCurrentWeight($baseFormData)) {
                                $formatedFormData = $progressForm->getFormatedBaseFormData($baseFormData);
                                
                                if (!$progressReport->logProgress($formatedFormData)) {
                                    throw new DB_Exception('FAILED TO LOG CURRENT WEIGHT REPORT');
                                }
                            }
                            
                            elseif ($progressForm->areExtendedFormDataSet()) {
                                $extendedFormData = $progressForm->getExtendedFormData($baseFormData);
                                
                                if ($progressForm->areExtendedFormDataValid($extendedFormData)) {
                                    $formatedFormData = $progressForm->getFormatedExtendedFormData($extendedFormData);
                                    
                                    if (!$progressReport->logProgress($formatedFormData)) {
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
                
                elseif ($routing->isRequestMatching($action, 'delete-report')) {
                    if ($routing->areParamsSet(['id'])) {
                        $reportId = $routing->getParam('id');
                        
                        if ($progressReport->isReportIdValid($reportId)) {
                            $progressHistory = $progressReport->getHistory();
                            
                            if ($progressReport->isReportIdExisting($progressHistory, $reportId)) {
                                if (!$progressReport->eraseProgressReport($progressHistory, $reportId)) {
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
                $session->logoutUser();
            }
        }
        
        elseif (in_array($action, $routing::URLS['actions']['meeting'])) {
            $meetingBooking = new App\Domain\Controllers\CostumerPanels\MeetingBooking;
            
            if ($session->isUserLogged()) {
                $meetingBookingForm = new App\Entities\MeetingBookingForm;
                
                if ($routing->isRequestMatching($action, 'book-appointment')) {
                    $dateData = $meetingBookingForm->getDateData();
                    
                    if ($meetingBookingForm->areDateDataValid($dateData)) {
                        $appointment = new App\Entities\Appointment;
                        $formatedDate = $meetingBookingForm->getFormatedDate($dateData);
                        
                        if ($appointment->isMeetingsSlotAvailable($formatedDate)) {
                            if (!$appointment->bookAppointment($formatedDate)) {
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
                    
                    $meetingBooking->routeTo('meetingsBooking');
                }
                
                elseif ($routing->isRequestMatching($action, 'cancel-appointment')) {
                    $appointment = new App\Entities\Appointment;
                    if (!$appointment->cancelAppointment()) {
                        throw new DB_Exception('FAILED TO CANCEL APPOINTMENT');
                    }
                    
                    $meetingBooking->routeTo('meetingsBooking');
                }
                
                elseif ($routing->isRequestMatching($action, 'save-meeting')) {
                    $userRole = $routing->getRole();
                    
                    if ($routing->isRoleMatching($userRole, ['admin'])) {
                        $meetingForm = new App\Entities\MeetingBookingForm;
                        $meetingData = $meetingForm->getData(['meeting-day', 'meeting-time']);
                        
                        if ($meetingData) {
                            $meetingManagement = new App\Domain\Controllers\AdminPanels\MeetingManagement;
                            $meeting = new App\Entities\Meeting;
                            
                            if ($meeting->areDateDataValid($meetingData)) {
                                if (!$meeting->addMeetingSlot($meetingData)) {
                                    throw new DB_Exception('FAILED TO INSERT A NEW MEETING');
                                }
                                
                                $meetingManagement->routeTo('meetingsManagement');
                            }
                        }
                    }
                    
                    else {
                        $session->logoutUser();
                    }
                }
                
                elseif ($routing->isRequestMatching($action, 'delete-meeting')) {
                    $userRole = $routing->getRole();
                    
                    if ($routing->isRoleMatching($userRole, ['admin'])) {
                        if ($routing->areParamsSet(['id'])) {
                            $meeting = new App\Entities\Meeting;
                            
                            $meetingId = $routing->getParam('id');
                            
                            if ($meeting->isMeetingIdValid($meetingId)) {
                                $meetingManagement = new App\Domain\Controllers\AdminPanels\MeetingManagement;
                                $attendeeData = $meeting->getAttendeeData($meetingId);
                                $isMeetingBooked = $meeting->isMeetingBooked($attendeeData);
                                
                                if ($meeting->eraseMeetingSlot($meetingId) && $isMeetingBooked) {
                                    $canceledMeetingAlerter = new App\Services\CanceledMeetingAlerter;
                                    
                                    if (!$canceledMeetingAlerter->sendCancelMeetingNotification($attendeeData[0])) {
                                        throw new Mailer_Exception('FAILED TO SEND MEETING DELETION NOTIFICATION');
                                    }
                                }
                                
                                $meetingManagement->routeTo('meetingsManagement');
                            }
                            
                            else {
                                throw new Data_Exception('INVALID MEETING ID PARAMETER IS URL');
                            }
                        }
                        
                        else {
                            throw new URL_Exception('MISSING MEETING ID PARAMETER IS URL');
                        }
                    }
                    
                    else {
                        $session->logoutUser();
                    }
                }
            }
            
            else {
                $session->logoutUser();
            }
        }
        
        elseif (in_array($action, $routing::URLS['actions']['appliance'])) {
            if ($session->isUserLogged()) {
                $adminPanel = new App\Domain\Controllers\AdminPanels\AdminPanel;
                $appliance = new App\Entities\Appliance;
                $appResponder = new App\Services\ApplianceResponder;
                
                if ($routing->isRequestMatching($action, 'reject-appliance')) {
                    if ($routing->areParamsSet(['id'])) {
                        $applicantId = intval($routing->getParam('id'));
                        
                        if ($appliance->isApplianceIdValid($applicantId)) {
                            $applicantData = $appliance->getApplicantData($applicantId);
                            $messageType = $appliance->getMessageType();
                            
                            if ($appliance->eraseAppliance($applicantId)) {
                                if (!$appResponder->sendRejectionNotification($messageType, $applicantData)) {
                                    throw new Mailer_Exception('FAILED TO SEND APPLIANCE REJECTION EMAIL');
                                }
                                
                                $adminPanel->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            $adminPanel->routeTo('appliancesList');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
                
                elseif ($routing->isRequestMatching($action, 'approve-appliance')) {
                    if ($routing->areParamsSet(['id'])) {
                        $applicantId = intval($routing->getParam('id'));
                        
                        if ($appliance->isApplianceIdValid($applicantId)) {
                            $applicantData = $appliance->getApplicantData($applicantId);
                            
                            if ($appliance->acceptAppliance($applicantId, 'payment_pending')) {
                                if (!$appResponder->sendApprovalNotification($applicantData)) {
                                    throw new Mailer_Exception('FAILED TO SEND APPLIANCE APPROVAL EMAIL');
                                }
                                
                                $adminPanel->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            $adminPanel->routeTo('appliancesList');
                        }
                    }
                    
                    else {
                        throw new Url_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
            }
            
            else {
                $session->logoutUser();
            }
        }
        
        elseif (in_array($action, $routing::URLS['actions']['notes'])) {            
            if ($session->isUserLogged()) {
                $note = new App\Entities\Note;
                
                if ($routing->isRequestMatching($action, 'save-note')) {
                    if ($routing->areParamsSet(['id'])) {
                        $subscriber = new App\Entities\Subscriber;
                        $subscriberId = intval($routing->getParam('id'));
                        
                        if ($subscriber->isSubscriberIdValid($subscriberId)) {
                            $noteForm = new App\Entities\NoteForm;
                            
                            if ($noteForm->areDataPosted(['note-message', 'attached-meeting-date'])) {
                                $noteData = $note->buildNoteData($subscriberId);
                                
                                if ($noteData) {
                                    $note->logNote($noteData);
                                    
                                    header("location:index.php?page=subscriber-notes&id=" . $noteData['subscriber_id']);
                                }
                                
                                else {
                                    // header("location:index.php?page=subscriber-notes&id=" . $noteData['subscriber_id']);
                                    throw new Data_Exception('INVALID NOTES PARAMERS FROM NOTE FORM');
                                }
                            }
                            
                            else {
                                throw new Data_Exception('MISSING NOTE PARAMETERS IN NOTE FORM');
                                // $note->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('INVALID ID PARAMETER IN URL');
                            // $note->routeTo('appliancesList');
                        }
                    }
                    
                    else {
                        throw new Url_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
                
                elseif ($routing->isRequestMatching($action, 'edit-note')) {
                    if ($routing->areParamsSet(['note-id', 'id'])) {
                        $subscriber = new App\Entities\Subscriber;
                        
                        $subscriberId = intval($routing->getParam('id'));
                        $noteId = intval($routing->getParam('note-id'));
                        
                        if ($subscriber->isSubscriberIdValid($subscriberId) && $note->isNoteIdValid($noteId)) {
                            $noteForm = new App\Entities\NoteForm;
                            
                            if ($noteForm->areDataPosted(['note-message', 'attached-meeting-date'])) {
                                $noteData = $note->buildNoteData($subscriberId);
                                
                                if ($noteData) {
                                    $note->editNote($noteData, $noteId);
                                    
                                    header("location:index.php?page=subscriber-notes&id=" . $subscriberId);
                                }
                                
                                else {
                                    throw new Data_Exception('INVALID NOTES PARAMERS FROM NOTE FORM');
                                }
                            }
                            
                            else {
                                throw new Data_Exception('MISSING NOTE OR DATE IN EDIT NOTE FORM');
                            }
                        }
                        
                        else {
                            throw new Data_Exception('INVALID SUBSCRIBER OR NOTE ID');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('MISSING ID PARAMETER IS URL');
                    }
                }
                
                elseif ($routing->isRequestMatching($action, 'delete-note')) {
                    if ($routing->areParamsSet(['note-id', 'id'])) {
                        $subscriberId = intval($routing->getParam('id'));
                        $noteId = intval($routing->getParam('note-id'));
                        
                        if ($note->isNoteIdValid($noteId)) {
                            $note->eraseNote($noteId);
                            
                            header("location:index.php?page=subscriber-notes&id=" . $subscriberId);
                        }
                        
                        else {
                            throw new Data_Exception('INVALID NOTE ID');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('MISSING ID PARAMETER IS URL');
                    }
                }
            }
            
            else {
                $session->logoutUser();
            }
        }
        
        elseif (in_array($action, $routing::URLS['actions']['program-intakes'])) {            
            if ($session->isUserLogged()) {
                if ($routing->isRequestMatching($action, 'generate-meals')) {
                    if ($routing->areParamsSet(['id'])) {
                        $subscriber = new App\Entities\Subscriber;
                        $subscriberId = intval($routing->getParam('id'));
                        
                        if ($subscriber->isSubscriberIdValid($subscriberId)) {
                            $meal = new App\Entities\Meal;
                            $mealsList = $meal->getCheckedMeals();
                            
                            if ($mealsList) {
                                if (!$meal->saveProgramMeals($subscriberId, $mealsList)) {
                                    throw new DB_Exception('FAILED TO UPDATE MEALS LIST');
                                }
                                
                                header("location:index.php?page=subscriber-program&id=" . $subscriberId);
                            }
                            
                            else {
                                throw new Data_Exception('MISSING CHECKED ELEMENT IN MEALS FORM');
                            }
                        }
                        
                        else {
                            throw new URL_Exception('INVALID ID PARAMETER IN URL');
                        }
                    }
                    
                    else {
                        throw new URL_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
            }
            
            else {
                $session->logoutUser();
            }
        }
        
        elseif (in_array($action, $routing::URLS['actions']['program-file'])) {
            if ($session->isUserLogged()) {
                if ($routing->isRequestMatching($action, 'generate-program-file')) {
                    if ($routing->areParamsSet(['id'])) {
                        $subscriberId = intval($routing->getParam('id'));
                        $subscriber = new App\Entities\Subscriber;
                        
                        if ($subscriber->isSubscriberIdValid($subscriberId)) {
                            $programFile = new App\Entities\ProgramFile;
                            $programFileStatus = $programFile->getProgramFileStatus($subscriberId);
                            
                            if ($programFile->isProgramFileUpdatable($programFileStatus, $subscriberId)) {
                                $program = new App\Entities\Program;
                                $programData = $program->buildProgramData($subscriberId);
                                $subscriberHeaders = $subscriber->getSubscriberHeaders($subscriberId);
                                
                                if ($programData && $subscriberHeaders) {
                                    $meal = new App\Entities\Meal;
                                    
                                    $fileContent = $programFile->renderFileContent($twig, $program, $meal, $programData, $subscriberHeaders, $subscriberId);
                                    
                                    if ($fileContent) {
                                        $pdfFileBuilder = new App\Services\PdfFileBuilder;
                                        $pdfFile = $pdfFileBuilder->generateFile($fileContent);
                                        $fileName = $programFile->getFileName($subscriberHeaders);
                                        
                                        if ($pdfFile & $fileName) {
                                            if ($programFile->savePdf($fileContent, $fileName, $subscriberHeaders)) {
                                                $programUpdateNotifier = new App\Services\ProgramUpdateNotifier;
                                                
                                                $programFile->setProgramFileData($subscriberId, $fileName, 'updated');
                                                
                                                $programUpdateNotifier->sendProgramFileNotification($subscriberHeaders);
                                                
                                                header("location:index.php?page=subscriber-program&id=" . $subscriberId);
                                            }
                                            
                                            else {
                                                throw new PdfGenerator_Exception('FAILED TO SAVE PDF FILE');
                                            }
                                        }
                                        
                                        else {
                                            throw new PdfGenerator_Exception('FAILED TO GENERATE PDF FILE');
                                        }
                                    }
                                    
                                    else {
                                        throw new Data_Exception('FAILED TO BUILD FILE DATA');
                                    }
                                }
                                
                                else {
                                    throw new Data_Exception('NO PROGRAM DATA FOUND FOR THIS USER');
                                }
                            }
                            
                            else {
                                throw new Data_Exception('PROGRAM FILE IS NOT UPDATABLE');
                            }
                        }
                        
                        else {
                            throw new URL_Exception('INVALID ID PARAMETER IN URL');
                        }
                    }
                    
                    else {
                        throw new URL_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
            }
            
            else {
                $session->logoutUser();
            }
        }
        
        else {
            $session->logoutUser();
        }
    }
    
    else {
        $showcase = new App\Domain\Controllers\ShowCasePanels\ShowcasePanel;
        $showcase->routeTo('presentation');
    }
}

catch(Mailer_Exception $e) {
    echo "New MAILER exception caught: '" . $e->getMessage() . "' in index file \non line " . $e->getLine();
}

catch(PdfGenerator_Exception $e) {
    echo "New PDF GENERATOR exception caught: '" . $e->getMessage() . "' in index file \non line " . $e->getLine();
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