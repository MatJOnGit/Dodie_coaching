<?php

declare(strict_types=1);

session_start();
// session_destroy();

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
            $showcase = new App\Domain\Controllers\ShowcasePanels\Showcase;
            $isLogged = $session->isUserLogged();
            
            if ($routing->isRequestMatching($page, 'presentation')) {
                $showcase->renderPresentationPage($twig, $isLogged);
            }
            
            elseif ($routing->isRequestMatching($page, 'coaching')) {
                $showcase->renderCoachingPage($twig, $isLogged);
            }
            
            elseif ($routing->isRequestMatching($page, 'programs-list')) {
                $programsList = new App\Entities\ProgramsList;

                if ($programsList->isProgramsListAvailable()) {
                    $showcase->renderProgramsListPage($twig, $programsList, $isLogged);
                }
                
                else {
                    throw new Data_Exception('MISSING PROGRAMS LIST IN DATA');
                    // $showcase->routeTo('404');
                }
            }
            
            elseif ($routing->isRequestMatching($page, 'program-details')) {
                $programsList = new App\Entities\ProgramsList;

                if ($programsList->isProgramsListAvailable()) {
                    if ($routing->areParamsSet(['program'])) {
                        $requestedProgram = $routing->getParam('program');
                        
                        if ($programsList->isProgramAvailable($requestedProgram)) {
                            $showcase->renderProgramDetailsPage($twig, $programsList, $requestedProgram, $isLogged);
                        }
                        
                        else {
                            throw new URL_Exception('INVALID PROGRAM PARAMETER');
                            // $showcase->renderShowcase404Page($twig, $isLogged);
                        }
                    }
                    
                    else {
                        throw new URL_Exception('MISSING PROGRAM PARAMETER');
                        // $showcase->routeTo('404');
                    }
                }
                
                else {
                    throw new Data_Exception('MISSING PROGRAMS LIST IN DATA');
                    // $showcase->routeTo('404');
                }
            }
            
            else {
                $showcase->renderShowcase404Page($twig, $isLogged);
            }
        }
        
        elseif (in_array($page, $routing::URLS['pages']['authentification'])) {
            if (!$session->isUserLogged()) {
                if ($routing->isRequestMatching($page, 'login')) {
                    $loginPanel = new App\Domain\Controllers\AuthPanels\LoginPanel;
                    $loginPanel->renderLoginPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'registering')) {
                    $registeringPanel = new App\Domain\Controllers\AuthPanels\RegisteringPanel;
                    $registeringPanel->renderRegisteringPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'password-retrieving')) {
                    $passwordRetrievingPanel = new App\Domain\Controllers\AuthPanels\PasswordRetrievingPanel;
                    $passwordRetrievingPanel->renderPasswordRetrievingPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'password-editing')) {
                    $passwordEditingPanel = new App\Domain\Controllers\AuthPanels\PasswordEditingPanel;
                    $passwordEditingPanel->renderPasswordEditingPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'mail-notification')) {
                    $passwordNotificationPanel = new App\Domain\Controllers\AuthPanels\PasswordNotificationPanel;
                    $passwordNotificationPanel->renderMailNotificationPage($twig);
                }
                
                elseif ($routing->isRequestMatching($page, 'token-signing')) {
                    $tokenSigningPanel = new App\Domain\Controllers\AuthPanels\TokenSigningPanel;
                    $tokenSigningPanel->renderTokenSigningPage($twig);
                }
            }
            
            else {
                $dashboard = new App\Domain\Controllers\CostumerPanels\Dashboard;
                $dashboard->routeTo('dashboard');
            }
        }
        
        elseif (in_array($page, $routing::URLS['pages']['userPanels'])) {
            if ($session->isUserLogged()) {
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
                            
                            $subscriberId = intval($routing->getUserId()['id']);
                            $programMenu->renderNutritionMenuPage($twig, $meal, $programFile, $subscriberId);
                        }
                        
                        elseif ($program->isMealRequested()) {
                            $mealData = $program->getMealData();
                            
                            if ($program->areMealParamsValid($mealData)) {
                                $mealDetails = new App\Domain\Controllers\CostumerPanels\MealDetails;
                                $mealDetails->renderMealDetailsPage($twig, $meal, $mealData);
                            }
                            
                            else {
                                throw new URL_Exception('INVALID MEAL PARAMETER');
                                // $nutrition->routeTo('nutrition');
                            }
                        }
                        
                        elseif ($program->isRequestSet()) {
                            $request = $program->getRequest();
                            
                            if ($program->isShoppingListRequested($request)) {
                                $shoppingList = new App\Domain\Controllers\CostumerPanels\ShoppingList;
                                $shoppingList->renderShoppingListPage($twig);
                            }
                            
                            else {
                                $routeDispatcher->routeTo('nutrition');
                            }
                        }
                        
                        else {
                            $routeDispatcher->routeTo('nutrition');
                        }
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'progress')) {
                        $progress = new \App\Domain\Controllers\CostumerPanels\Progress;
                        $progress->renderProgressPage($twig);
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
                    }
                }
                
                elseif ($userRole && $routing->isRoleMatching($userRole, ['admin'])) {
                    $dashboard = new App\Domain\Controllers\AdminPanels\Dashboard;
                    $dashboard->routeTo('admin-dashboard');
                }
                
                else {
                    throw new Data_Exception('INVALID USER ROLE');
                    // $routing->logoutUser();
                }
            }
            
            else {
                $session->logoutUser();
            }
        }
        
        elseif (in_array($page, $routing::URLS['pages']['adminPanels'])) {
            if ($session->isUserLogged()) {
                $userRole = $routing->getRole();
                
                if ($userRole && $routing->isRoleMatching($userRole, ['admin'])) {
                    $adminPanel = new App\Domain\Controllers\AdminPanels\AdminPanel;
                    
                    if ($routing->isRequestMatching($page, 'admin-dashboard')) {
                        $dashboard = new App\Domain\Controllers\AdminPanels\Dashboard;
                        $dashboard->renderAdminDashboardPage($twig);
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'appliances-list')) {
                        $appliance = new App\Controllers\Appliance;
                        $appliance->renderAppliancesListPage($twig);
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'appliance-details') && $routing->areParamsSet(['id'])) {
                        $appliance = new App\Controllers\Appliance;
                        $applicantId = intval($routing->getParam('id'));
                        
                        if ($appliance->isApplianceIdValid($applicantId)) {
                            $appliance->renderApplianceDetailsPage($twig, $applicantId);
                        }
                        
                        else {
                            $routeDispatcher->routeTo('appliancesList');
                        }
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'subscribers-list')) {
                        $subscriber = new App\Controllers\Subscriber;
                        $subscriber->renderSubscribersListPage($twig);
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'subscriber-profile')) {
                        $subscriber = new App\Controllers\Subscriber;
                        
                        if ($routing->areParamsSet(['id'])) {
                            $subscriberId = intval($routing->getParam('id'));
                            
                            if ($subscriber->isSubscriberIdValid($subscriberId)) {
                                $subscriber->renderSubscriberProfilePage($twig, $subscriberId);
                            }
                            
                            else {
                                $routeDispatcher->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $subscriber->routeTo('subscribersList');
                        }
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'subscriber-program')) {
                        $program = new App\Controllers\Program;
                        
                        if ($routing->areParamsSet(['id'])) {
                            $subscriberId = intval($routing->getParam('id'));
                            
                            if ($program->isSubscriberIdValid($subscriberId)) {
                                $program->renderSubscriberProgramPage($twig, $subscriberId);
                            }
                            
                            else {
                                $routeDispatcher->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $program->routeTo('subscribersList');
                        }
                    }
                    
                    elseif ($routing->isRequestMatching($page, 'subscriber-notes')) {
                        $note = new App\Controllers\Note;
                        
                        if ($routing->areParamsSet(['id'])) {
                            $subscriberId = intval($routing->getParam('id'));
                            
                            if ($note->isSubscriberIdValid($subscriberId)) {
                                $note->renderSubscriberNotesPage($twig, $subscriberId);
                            }
                            
                            else {
                                $routeDispatcher->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $note->routeTo('subscribersList');
                        }
                    }
                    
                    else if ($routing->isRequestMatching($page, 'meetings-management')) {
                        $meetingManagement = new App\Controllers\MeetingManagement;
                        
                        $meetingManagement->renderMeetingsManagementPage($twig);
                    }
                }
                
                else {
                    throw new Data_Exception('INVALID USER ROLE');
                    // $routing->logoutUser();
                }
            }
            
            else {
                $routing->logoutUser();
            }
        }
        
        else {
            throw new URL_Exception('INVALID PAGE PARAMETER');
            // $routing = new App\Controllers\User;
            // $routing->logoutUser();
        }
    }
    
    elseif ($routing->areParamsSet(['action'])) {
        $session = new App\Entities\Session;

        $action = $routing->getParam('action');
        
        if (in_array($action, $routing::URLS['actions']['authentification'])) {
            $authPanel = new App\Domain\Controllers\AuthPanels\AuthPanel;
            $form = new App\Entities\Form;
            
            if ($routing->isRequestMatching($action, 'log-account') && !$session->isUserLogged()) {
                if ($form->areDataPosted(['email', 'password'])) {
                    $routingData = $form->getData(['email', 'password']);
                    
                    if ($form->areDataValid($routingData)) {
                        $account = new App\Entities\Account;

                        if ($account->isAccountExisting($routingData)) {
                            if ($account->updateLoginData($routingData)) {
                                $session->logUser($routingData);
                                $authPanel->routeTo('dashboard');
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
                if ($form->areDataPosted(['email', 'password', 'confirmation-password'])) {
                    $routingData = $form->getData(['email', 'password', 'confirmation-password']);
                    
                    if ($form->areDataValid($routingData)) {
                        $account = new App\Entities\Account;

                        if (!$account->isEmailExisting($routingData['email'])) {
                            if ($account->registerAccount($routingData)) {
                                $routing->createStaticData($routingData);
                                $session->logUser($routingData);
                                $authPanel->routeTo('dashboard');
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
                $form = new App\Entities\Form;
                if ($form->areDataPosted(['email'])) {
                    $userData = $form->getData(['email']);
                    
                    if ($form->areDataValid($userData)) {
                        $account = new App\Entities\Account;

                        if ($account->isEmailExisting($userData['email'])) {
                            $tokenData = $token->getTokenDate($userData['email']);
                            
                            if ($tokenData) {
                                $authPanel = new App\Domain\Controllers\AuthPanels\AuthPanel;
                                if ($token->isLastTokenOld($tokenData)) {
                                    if ($token->eraseToken($userData['email'])) {
                                        $session->logUser($userData);
                                        $authPanel->routeTo('send-token');
                                    }
                                    
                                    else {
                                        throw new DB_Exception('FAILED TO DELETE PREVIOUS TOKEN');
                                        // $routeDispatcher->routeTo('mail-notification');
                                    }
                                }
                                
                                else {

                                    $authPanel->routeTo('mail-notification');
                                }
                            }
                            
                            else {
                                $passwordRetrievingPanel = new App\Domain\Controllers\AuthPanels\PasswordRetrievingPanel;

                                $session->sessionize($userData, ['email']);
                                $passwordRetrievingPanel->routeTo('send-token');
                            }
                        }
                        
                        else {
                            $routeDispatcher->routeTo('mail-notification');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('INVALID FORM DATA');
                    }
                }
                
                elseif ($session->isDataSessionized('email')) {
                    $tokenData = $token->generateToken();
                    
                    if ($token->registerToken($tokenData)) {
                        $passwordRetriever = new App\Domain\Services\PasswordRetriever;
                        
                        if (!$passwordRetriever->sendToken($tokenData)) {
                            throw new Mailer_Exception('FAILED TO SEND NEW TOKEN TO THE USER MAILBOX');
                        }
                        
                        else {
                            $passwordNotificationPanel = new App\Domain\Controllers\AuthPanels\PasswordNotificationPanel;
                            $passwordNotificationPanel->routeTo('mail-notification');
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
                $form = new App\Entities\Form;
                if ($session->isDataSessionized('email') && $form->areDataPosted(['token'])) {
                    $routingData = $form->getData(['token']);
                    
                    if ($form->areDataValid($routingData)) {
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
                $form = new App\Entities\Form;

                if ($form->areDataPosted(['password', 'confirmation-password'])) {
                    $routingData = $form->getData(['password', 'confirmation-password']);
                    
                    if ($form->areDataValid($routingData)) {
                        $session = new App\Entities\Session;

                        if ($session->isDataSessionized('email')) {
                            $account = new App\Entities\Account;

                            $routingEmail = $session->getSessionizedParam('email');
                            
                            if ($account->registerPassword($routingData)) {
                                $token = new App\Entities\ResetToken;

                                if ($token->eraseToken($routingEmail)) {
                                    $session->unsessionizeData(['token']);
                                    $session->sessionize($routingData, ['password']);
                                    $routeDispatcher->routeTo('retrieved-password');
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
                $showcase = new App\Domain\Controllers\ShowCasePanels\Showcase;
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
                            $progressHistory = $progress->getHistory();
                            
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
                        $meetingData = $routing->getData(['meeting-day', 'meeting-time']);
                        
                        if ($meetingData) {
                            $meeting = new App\Controllers\MeetingManagement;
                            
                            if ($meeting->areDateDataValid($meetingData)) {
                                if (!$meeting->addMeetingSlot($meetingData)) {
                                    throw new DB_Exception('FAILED TO INSERT A NEW MEETING');
                                }
                                
                                $routeDispatcher->routeTo('meetingsManagement');
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
                            $meeting = new App\Controllers\MeetingManagement;
                            $meetingId = $routing->getParam('id');
                            
                            if ($meeting->isMeetingIdValid($meetingId)) {
                                $attendeeData = $meeting->getAttendeeData($meetingId);
                                $isMeetingBooked = $meeting->isMeetingBooked($attendeeData);
                                
                                if ($meeting->eraseMeetingSlot($meetingId) && $isMeetingBooked) {
                                    $canceledMeetingAlerter = new App\Services\CanceledMeetingAlerter;
                                    
                                    if (!$canceledMeetingAlerter->sendCancelMeetingNotification($attendeeData[0])) {
                                        throw new Mailer_Exception('FAILED TO SEND MEETING DELETION NOTIFICATION');
                                    }
                                }
                                
                                $routeDispatcher->routeTo('meetingsManagement');
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
                        $routing->logoutUser();
                    }
                }
            }
            
            else {
                $routing->logoutUser();
            }
        }
        
        elseif (in_array($action, $routing::URLS['actions']['appliance'])) {
            $appliance = new App\Controllers\Appliance;
            
            if ($session->isUserLogged()) {
                if ($routing->isRequestMatching($action, 'reject-appliance')) {
                    if ($routing->areParamsSet(['id'])) {
                        $applicantId = intval($appliances->getParam('id'));
                        
                        if ($appliance->isApplianceIdValid($applicantId)) {
                            $appResponder = new App\Services\ApplianceResponder;
                            
                            $applicantData = $appliances->getApplicantData($applicantId);
                            $messageType = $appliances->getMessageType();
                            
                            if ($appliance->eraseAppliance($applicantId)) {
                                if (!$appResponder->sendRejectionNotification($messageType, $applicantData)) {
                                    throw new Mailer_Exception('FAILED TO SEND APPLIANCE REJECTION EMAIL');
                                }
                                
                                $routeDispatcher->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            $routeDispatcher->routeTo('appliancesList');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
                
                elseif ($appliances->isRequestMatching($action, 'approve-appliance')) {
                    if ($routing->areParamsSet(['id'])) {
                        $applicantId = intval($appliances->getParam('id'));
                        
                        if ($appliance->isApplianceIdValid($applicantId)) {
                            $applianceResponder = new App\Services\ApplianceResponder;
                            $applicantData = $appliance->getApplicantData($applicantId);
                            
                            if ($appliance->acceptAppliance($applicantId, 'payment_pending')) {
                                if (!$applianceResponder->sendApprovalNotification($applicantData)) {
                                    throw new Mailer_Exception('FAILED TO SEND APPLIANCE APPROVAL EMAIL');
                                }
                                
                                $routeDispatcher->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            $routeDispatcher->routeTo('appliancesList');
                        }
                    }
                    
                    else {
                        throw new Url_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
                
                else {
                    echo "Page d'action inconnue";
                }
            }
            
            else {
                $routing->logoutUser();
            }
        }
        
        elseif (in_array($action, $routing::URLS['actions']['notes'])) {
            $note = new App\Controllers\Note;
            
            if ($session->isUserLogged()) {
                if ($routing->isRequestMatching($action, 'save-note')) {
                    if ($routing->areParamsSet(['id'])) {
                        $subscriberId = intval($routing->getParam('id'));
                        
                        if ($note->isSubscriberIdValid($subscriberId)) {
                            if ($note->areDataPosted(['note-message', 'attached-meeting-date'])) {
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
                        $subscriberId = intval($routing->getParam('id'));
                        $noteId = intval($routing->getParam('note-id'));
                        
                        if ($note->isSubscriberIdValid($subscriberId) && $note->isNoteIdValid($noteId)) {
                            if ($note->areDataPosted(['note-message', 'attached-meeting-date'])) {
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
            $program = new App\Controllers\program;
            
            if ($session->isUserLogged()) {
                if ($routing->isRequestMatching($action, 'generate-meals')) {
                    if ($routing->areParamsSet(['id'])) {
                        $subscriberId = intval($routing->getParam('id'));
                        
                        if ($program->isSubscriberIdValid($subscriberId)) {
                            $mealsList = $program->getCheckedMeals();
                            
                            if ($mealsList) {
                                if (!$program->saveProgramMeals($subscriberId, $mealsList)) {
                                    throw new DB_Exception('échec de la mise à jour des repas du programme');
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
                $routing->logoutUser();
            }
        }
        
        elseif (in_array($action, $routing::URLS['actions']['program-file'])) {
            $programFile = new App\Controllers\ProgramFile;
            
            if ($session->isUserLogged()) {
                if ($routing->isRequestMatching($action, 'generate-program-file')) {
                    if ($routing->areParamsSet(['id'])) {
                        $subscriberId = intval($routing->getParam('id'));
                        
                        if ($programFile->isSubscriberIdValid($subscriberId)) {
                            $programFileStatus = $programFile->getProgramFileStatus($subscriberId);
                            
                            if ($programFile->isProgramFileUpdatable($subscriberId)) {
                                $programData = $programFile->buildProgramData($subscriberId);
                                $subscriberHeaders = $programFile->getSubscriberHeaders($subscriberId);
                                
                                if ($programData && $subscriberHeaders) {
                                    $output = $programFile->buildFile($twig, $subscriberId, $programData, $subscriberHeaders);
                                    
                                    if ($output) {
                                        if ($programFile->saveDataToPdf($output, $subscriberHeaders)) {
                                            $programFileAlerter = new App\Services\ProgramFileAlerter;
                                            
                                            $programFile->setProgramFileStatus($subscriberId, 'updated');
                                            $programFileAlerter->sendProgramFileNotification($subscriberHeaders);
                                            
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
                $routing->logoutUser();
            }
        }
        
        else {
            $routing->logoutUser();
        }
    }
    
    else {
        $showcase = new App\Domain\Controllers\ShowCasePanels\Showcase;
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