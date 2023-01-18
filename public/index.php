<?php

declare(strict_types=1);

use Symfony\Component\Asset\UrlPackage;

session_start();

// session_destroy();
// echo $_SESSION['user-email'];

class DB_Exception extends Exception { }
class URL_Exception extends Exception { }
class Data_Exception extends Exception { }
class Mailer_Exception extends Exception { }
class PdfGenerator_Exception extends Exception { }

try {
    require_once ('./../vendor/autoload.php');
    
    $loader = new \Twig\Loader\FilesystemLoader('./../src/templates/');
    
    $twig = new Twig\Environment($loader, [
        'cache' => false,
        'debug' => true
    ]);
    
    $twig->addExtension(new \Twig\Extension\DebugExtension());
    
    $Urls = [
        'pages' => [
            'showcase' => ['presentation', 'coaching', 'programs-list', 'program-details', 'showcase-404'],
            'connection' => ['login', 'registering', 'password-retrieving', 'mail-notification', 'token-signing', 'password-editing', 'retrieved-password'],
            'userPanels' => ['dashboard', 'nutrition', 'progress', 'meetings-booking', 'subscription'],
            'adminPanels' => ['admin-dashboard', 'appliances-list', 'appliance-details', 'subscribers-list', 'subscriber-profile', 'subscriber-program', 'subscriber-notes', 'meetings-management']
        ],
        'actions' => [
            'connection' => ['log-account', 'register-account', 'logout', 'send-token', 'verify-token', 'register-password'],
            'progress' => ['add-report', 'delete-report'],
            'meeting' => ['book-appointment', 'cancel-appointment', 'save-meeting', 'delete-meeting'],
            'appliance' => ['reject-appliance', 'approve-appliance'],
            'notes' => ['save-note', 'edit-note', 'delete-note'],
            'program-intakes' => ['generate-meals'],
            'program-file' => ['generate-program-file']
        ]
    ];
    
    $user = new Dodie_Coaching\Controllers\User;
    if ($user->isGetParamSet('page')) {
        $page = $user->getParam('page');
        
        if (in_array($page, $Urls['pages']['showcase'])) {
            $showcase = new Dodie_Coaching\Controllers\Showcase;
            $isLogged = $user->isLogged();
            
            if ($showcase->isRequestMatching($page, 'presentation')) {
                $showcase->renderPresentationPage($twig, $isLogged);
            }
            
            elseif ($showcase->isRequestMatching($page, 'coaching')) {
                $showcase->renderCoachingPage($twig, $isLogged);
            }
            
            elseif ($showcase->isRequestMatching($page, 'programs-list')) {
                if ($showcase->isProgramsListAvailable()) {
                    $showcase->renderProgramsListPage($twig, $isLogged);
                }
                
                else {
                    throw new Data_Exception('MISSING PROGRAMS LIST IN DATA');
                    // $showcase->routeTo('404');
                }
            }
            
            elseif ($showcase->isRequestMatching($page, 'program-details')) {
                if ($showcase->isProgramsListAvailable()) {
                    if ($showcase->areParamsSet(['program'])) {
                        $requestedProgram = $showcase->getParam('program');
                        
                        if ($showcase->isProgramAvailable($requestedProgram)) {
                            $showcase->renderProgramDetailsPage($twig, $requestedProgram, $isLogged);
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
                    throw new Data_Exception('MISSING PROGRAMS LIST IN DATA');
                    // $showcase->routeTo('404');
                }
            }
            
            else {
                $showcase->renderShowcase404Page($twig, $isLogged);
            }
        }
        
        elseif (in_array($page, $Urls['pages']['connection'])) {
            if (!$user->isLogged()) {
                if ($user->isRequestMatching($page, 'login')) {
                    $user->renderLoginPage($twig);
                }
                
                elseif ($user->isRequestMatching($page, 'registering')) {
                    $user->renderRegisteringPage($twig);
                }
                
                elseif ($user->isRequestMatching($page, 'mail-notification')) {
                    $user->renderMailNotificationPage($twig);
                }
                
                elseif ($user->isRequestMatching($page, 'token-signing')) {
                    $user->renderTokenSigningPage($twig);
                }
                
                elseif ($user->isRequestMatching($page, 'password-retrieving')) {
                    $user->renderPasswordRetrievingPage($twig);
                }
                
                elseif ($user->isRequestMatching($page, 'password-editing')) {
                    $user->renderPasswordEditingPage($twig);
                }
            }
            
            elseif ($user->isRequestMatching($page, 'retrieved-password')) {
                $user->renderRetrievedPasswordPage($twig);
            }
            
            else {
                $user->routeTo('dashboard');
            }
        }
        
        elseif (in_array($page, $Urls['pages']['userPanels'])) {
            if ($user->isLogged()) {
                $userRole = $user->getRole();
                
                if ($userRole && $user->isRoleMatching($userRole, ['member', 'subscriber'])) {
                    $userPanels = new Dodie_Coaching\Controllers\UserPanels;
                    
                    if ($userPanels->isRequestMatching($page, 'dashboard')) {
                        $userDashboard = new Dodie_Coaching\Controllers\UserDashboard;
                        $userDashboard->renderUserDashboardPage($twig);
                    }
                    
                    elseif ($userPanels->isRequestMatching($page, 'nutrition')) {
                        $nutrition = new Dodie_Coaching\Controllers\Nutrition;
                        
                        if ($nutrition->isMenuRequested()) {
                            $subscriberId = $nutrition->getUserId()['id'];
                            $nutrition->renderNutritionMenu($twig, $subscriberId);
                        }
                        
                        elseif ($nutrition->isMealRequested()) {
                            $mealData = $nutrition->getMealData();
                            
                            if ($nutrition->areMealParamsValid($mealData)) {
                                $nutrition->renderMealDetails($twig, $mealData);
                            }
                            
                            else {
                                throw new URL_Exception('INVALID MEAL PARAMETER');
                                // $nutrition->routeTo('nutrition');
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
                    
                    elseif ($userPanels->isRequestMatching($page, 'progress')) {
                        $progress = new Dodie_Coaching\Controllers\Progress;
                        $progress->renderProgress($twig);
                    }
                    
                    elseif ($userPanels->isRequestMatching($page, 'meetings-booking')){
                        $meetingsBooking = new Dodie_Coaching\Controllers\MeetingsBooking;
                        $meetingsBooking->renderMeetingsBooking($twig);
                    }
                    
                    elseif ($userPanels->isRequestMatching($page, 'subscription')) {
                        $subscriptions = new Dodie_Coaching\Controllers\Subscriptions;
                        $subscriptions->renderSubscriptions($twig);
                    }
                    
                    else {
                        throw new Exception('UNKNOWN PAGE REQUESTED');
                    }
                }
                
                elseif ($userRole && $user->isRoleMatching($userRole, ['admin'])) {
                    $user->routeTo('admin-dashboard');
                }
                
                else {
                    throw new Data_Exception('INVALID USER ROLE');
                    // $user->logoutUser();
                }
            }
            
            else {
                $user->logoutUser();
            }
        }
        
        elseif (in_array($page, $Urls['pages']['adminPanels'])) {
            if ($user->isLogged()) {
                $userRole = $user->getRole();
                
                if ($userRole && $user->isRoleMatching($userRole, ['admin'])) {
                    $adminPanels = new Dodie_Coaching\Controllers\AdminPanels;
                    
                    if ($adminPanels->isRequestMatching($page, 'admin-dashboard')) {
                        $adminDashboard = new Dodie_Coaching\Controllers\AdminDashboard;
                        $adminDashboard->renderAdminDashboardPage($twig);
                    }
                    
                    elseif ($adminPanels->isRequestMatching($page, 'appliances-list')) {
                        $appliances = new Dodie_Coaching\Controllers\Appliances;
                        $appliances->renderAppliancesListPage($twig);
                    }
                    
                    elseif ($adminPanels->isRequestMatching($page, 'appliance-details') && $adminPanels->areParamsSet(['id'])) {
                        $appliance = new Dodie_Coaching\Controllers\Appliances;
                        $applicantId = $appliance->getParam('id');
                        
                        if ($appliance->isApplianceAvailable($applicantId)) {
                            $appliance->renderApplianceDetailsPage($twig, $applicantId);
                        }
                        
                        else {
                            $appliance->routeTo('appliancesList');
                        }
                    }
                    
                    elseif ($adminPanels->isRequestMatching($page, 'subscribers-list')) {
                        $subscribers = new Dodie_Coaching\Controllers\Subscribers;
                        $subscribers->renderSubscribersListPage($twig);
                    }
                    
                    elseif ($adminPanels->isRequestMatching($page, 'subscriber-profile')) {
                        $subscribers = new Dodie_Coaching\Controllers\Subscribers;
                        
                        if ($adminPanels->areParamsSet(['id'])) {
                            $subscriberId = intval($subscribers->getParam('id'));
                            
                            if ($subscribers->isSubscriberIdValid($subscriberId)) {
                                $subscribers->renderSubscriberProfilePage($twig, $subscriberId);
                            }
                            
                            else {
                                $subscribers->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $subscribers->routeTo('subscribersList');
                        }
                    }
                    
                    elseif ($adminPanels->isRequestMatching($page, 'subscriber-program')) {
                        $program = new Dodie_Coaching\Controllers\program;
                        
                        if ($adminPanels->areParamsSet(['id'])) {
                            $subscriberId = intval($program->getParam('id'));
                            
                            if ($program->isSubscriberIdValid($subscriberId)) {
                                $program->renderSubscriberProgramPage($twig, $subscriberId);
                            }
                            
                            else {
                                $program->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $programs->routeTo('subscribersList');
                        }
                    }
                    
                    elseif ($adminPanels->isRequestMatching($page, 'subscriber-notes')) {
                        $notes = new Dodie_Coaching\Controllers\Notes;
                        
                        if ($notes->areParamsSet(['id'])) {
                            $subscriberId = intval($notes->getParam('id'));
                            
                            if ($notes->isSubscriberIdValid($subscriberId)) {
                                $notes->renderSubscriberNotesPage($twig, $subscriberId);
                            }
                            
                            else {
                                $notes->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $notes->routeTo('subscribersList');
                        }
                    }
                    
                    else if ($adminPanels->isRequestMatching($page, 'meetings-management')) {
                        $meetingsManagement = new Dodie_Coaching\Controllers\MeetingsManagement;
                        
                        $meetingsManagement->renderMeetingsManagement($twig);
                    }
                }
                
                else {
                    throw new Data_Exception('INVALID USER ROLE');
                    // $user->logoutUser();
                }
            }
            
            else {
                $user->logoutUser();
            }
        }
        
        else {
            throw new URL_Exception('INVALID PAGE PARAMETER');
            // $user = new Dodie_Coaching\Controllers\User;
            // $user->logoutUser();
        }
    }
    
    elseif ($user->isGetParamSet('action')) {
        $user = new Dodie_Coaching\Controllers\User;
        $action = $user->getParam('action');
        
        if (in_array($action, $Urls['actions']['connection'])) {
            
            if ($user->isRequestMatching($action, 'log-account') && !$user->isLogged()) {
                if ($user->areDataPosted(['email', 'password'])) {
                    $userData = $user->getFormData(['email', 'password']);
                    
                    if ($user->areFormDataValid($userData)) {
                        if ($user->isAccountExisting($userData)) {
                            if ($user->updateLoginData($userData)) {
                                $user->logUser($userData);
                                $user->routeTo('dashboard');
                            }
                            
                            else {
                                throw new DB_Exception('FAILED TO UPDATE LOGGING DATE');
                                // $user->routeTo('login');
                            }
                        }
                        
                        else {
                            $user->routeTo('login');
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
            
            elseif ($user->isRequestMatching($action, 'register-account') && !$user->isLogged()) {
                if ($user->areDataPosted(['email', 'password', 'confirmation-password'])) {
                    $userData = $user->getFormData(['email', 'password', 'confirmation-password']);
                    
                    if ($user->areFormDataValid($userData)) {
                        if (!$user->isEmailExisting($userData['email'])) {            
                            if ($user->registerAccount($userData)) {
                                $user->createStaticData($userData);
                                $user->logUser($userData);
                                $user->routeTo('dashboard');
                            }
                            
                            else {
                                throw new DB_Exception('FAILED TO REGISTER ACCOUNT');
                                // $user->routeTo('registering');
                            }
                        }
                        
                        else {
                            $user->routeTo('registering');
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
            
            elseif ($user->isRequestMatching($action, 'send-token') && !$user->isLogged()) {
                if ($user->areDataPosted(['email'])) {
                    $userData = $user->getFormData(['email']);
                    
                    if ($user->areFormDataValid($userData)) {
                        if ($user->isEmailExisting($userData['email'])) {
                            $token = $user->getTokenDate($userData['email']);
                            
                            if ($token) {
                                if ($user->isLastTokenOld($token)) {
                                    if ($user->eraseToken($userData['email'])) {
                                        $user->logUser($userData);
                                        $user->routeTo('send-token');
                                    }
                                    
                                    else {
                                        throw new DB_Exception('FAILED TO DELETE PREVIOUS TOKEN');
                                        // $user->routeTo('mail-notification');
                                    }
                                }
                                
                                else {
                                    $user->routeTo('mail-notification');
                                }
                            }
                            
                            else {
                                $user->sessionize($userData, ['email']);
                                $user->routeTo('send-token');
                            }
                        }
                        
                        else {
                            $user->routeTo('mail-notification');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('INVALID FORM DATA');
                    }
                }
                
                elseif ($user->isDataSessionized('email')) {
                    $newToken = $user->generateToken();
                    
                    if ($user->registerToken($newToken)) {
                        $pwdRetriever = new Dodie_Coaching\Services\PasswordRetriever;
                        
                        if (!$pwdRetriever->sendToken($newToken)) {
                            throw new Mailer_Exception('FAILED TO SEND NEW TOKEN TO THE USER MAILBOX');
                        }
                        
                        else {
                            $user->routeTo('mail-notification');
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
            
            elseif ($user->isRequestMatching($action, 'verify-token') && !$user->isLogged()) {
                if ($user->isDataSessionized('email') && $user->areDataPosted(['token'])) {
                    $userData = $user->getFormData(['token']);
                    
                    if ($user->areFormDataValid($userData)) {
                        if ($user->isTokenMatching()) {
                            $user->sessionize($userData, ['token']);
                            $user->routeTo('edit-password');
                        }
                        
                        else {
                            if (!$user->subtractTokenAttempt()) {
                                throw new DB_Exception('FAILED TO SUBTRACT A TOKEN ATTEMPT');                                
                            }
                            
                            $user->routeTo('token-signing');
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
            
            elseif ($user->isRequestMatching($action, 'register-password') && !$user->isLogged()) {
                if ($user->areDataPosted(['password', 'confirmation-password'])) {
                    $userData = $user->getFormData(['password', 'confirmation-password']);
                    
                    if ($user->areFormDataValid($userData)) {
                        if ($user->isDataSessionized('email')) {
                            $userEmail = $user->getSessionizedParam('email');
                            
                            if ($user->registerPassword($userData)) {
                                if ($user->eraseToken($userEmail)) {
                                    $user->unsessionizeData(['token']);
                                    $user->sessionize($userData, ['password']);
                                    $user->routeTo('retrieved-password');
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
            
            elseif ($user->isRequestMatching($action, 'logout') && $user->isLogged()) {
                $user->logoutUser();
            }
        }
        
        elseif (in_array($action, $Urls['actions']['progress'])) {
            $progress = new Dodie_Coaching\Controllers\Progress;
            
            if ($user->isLogged()) {
                if ($progress->isRequestMatching($action, 'add-report')) {
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
                
                elseif ($progress->isRequestMatching($action, 'delete-report')) {
                    if ($progress->areParamsSet(['id'])) {
                        $reportId = $progress->getParam('id');
                        
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
                $user->logoutUser();
            }
        }
        
        elseif (in_array($action, $Urls['actions']['meeting'])) {
            $meetings = new Dodie_Coaching\Controllers\MeetingsBooking;
            
            if ($user->isLogged()) {
                if ($meetings->isRequestMatching($action, 'book-appointment')) {
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
                    
                    $meetings->routeTo('meetingsBooking');
                }
                
                elseif ($meetings->isRequestMatching($action, 'cancel-appointment')) {
                    if (!$meetings->cancelAppointment()) {
                        throw new DB_Exception('FAILED TO CANCEL APPOINTMENT');
                    }
                    $meetings->routeTo('meetingsBooking');
                }
                
                elseif ($meetings->isRequestMatching($action, 'save-meeting')) {
                    $userRole = $user->getRole();
                    
                    if ($user->isRoleMatching($userRole, ['admin'])) {
                        $meetingData = $user->getFormData(['meeting-day', 'meeting-time']);
                        
                        if ($meetingData) {
                            $meeting = new Dodie_Coaching\Controllers\MeetingsManagement;
                            
                            if ($meeting->areDateDataValid($meetingData)) {
                                if (!$meeting->addMeetingSlot($meetingData)) {
                                    throw new DB_Exception('FAILED TO INSERT A NEW MEETING');
                                }
                                
                                $meeting->routeTo('meetingsManagement');
                            }        
                        }
                    }
                    
                    else {
                        $user->logoutUser();
                    }
                }
                
                elseif ($meetings->isRequestMatching($action, 'delete-meeting')) {
                    $userRole = $user->getRole();
                    
                    if ($user->isRoleMatching($userRole, ['admin'])) {
                        if ($user->isGetParamSet('id')) {
                            $meeting = new Dodie_Coaching\Controllers\MeetingsManagement;
                            $meetingId = $meeting->getParam('id');
                            
                            if ($meeting->isMeetingIdValid($meetingId)) {
                                $attendeeData = $meeting->getAttendeeData($meetingId);
                                $isMeetingBooked = $meeting->isMeetingBooked($attendeeData);
                                
                                if ($meeting->eraseMeetingSlot($meetingId) && $isMeetingBooked) {
                                    $canceledMeetingAlerter = new Dodie_Coaching\Services\CanceledMeetingAlerter;
                                    
                                    if (!$canceledMeetingAlerter->sendCancelMeetingNotification($attendeeData[0])) {
                                        throw new Mailer_Exception('FAILED TO SEND MEETING DELETION NOTIFICATION');
                                    }
                                }
                                
                                else {
                                    throw new DB_Exception('FAILED TO DELETE MEETING');
                                }
                                
                                $meeting->routeTo('meetingsManagement');
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
                        $user->logoutUser();
                    }
                }
            }
            
            else {
                $user->logoutUser();
            }
        }
        
        elseif (in_array($action, $Urls['actions']['appliance'])) {
            $appliances = new Dodie_Coaching\Controllers\Appliances;
            
            if ($user->isLogged()) {
                if ($appliances->isRequestMatching($action, 'reject-appliance')) {
                    if ($appliances->areParamsSet(['id'])) {
                        $applicantId = $appliances->getParam('id');
                        
                        if ($appliances->isApplianceIdValid($applicantId)) {
                            $appResponder = new Dodie_Coaching\Services\ApplianceResponder;
                            
                            $applicantData = $appliances->getApplicantData($applicantId);
                            $messageType = $appliances->getMessageType();
                            
                            if ($appliances->eraseAppliance($applicantId)) {
                                if (!$appResponder->sendRejectionNotification($messageType, $applicantData)) {
                                    throw new Mailer_Exception('FAILED TO SEND APPLIANCE REJECTION EMAIL');
                                }

                                $appliances->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            $appliances->routeTo('appliancesList');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
                
                elseif ($appliances->isRequestMatching($action, 'approve-appliance')) {
                    if ($appliances->areParamsSet(['id'])) {
                        $applicantId = $appliances->getParam('id');
                        
                        if ($appliances->isApplianceIdValid($applicantId)) {
                            $applianceResponder = new Dodie_Coaching\Services\ApplianceResponder;
                            $applicantData = $appliances->getApplicantData($applicantId);
                            
                            if ($appliances->acceptAppliance($applicantId, 'payment_pending')) {
                                if (!$applianceResponder->sendApprovalNotification($applicantData)) {
                                    throw new Mailer_Exception('FAILED TO SEND APPLIANCE APPROVAL EMAIL');
                                }
                                
                                $appliances->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            $appliances->routeTo('appliancesList');
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
                $user->logoutUser();
            }
        }
        
        elseif (in_array($action, $Urls['actions']['notes'])) {
            $notes = new Dodie_Coaching\Controllers\Notes;
            
            if ($user->isLogged()) {
                if ($notes->isRequestMatching($action, 'save-note')) {
                    if ($notes->areParamsSet(['id'])) {
                        $subscriberId = intval($notes->getParam('id'));
                        
                        if ($notes->isSubscriberIdValid($subscriberId)) {
                            if ($notes->areDataPosted(['note-message', 'attached-meeting-date'])) {
                                $noteData = $notes->buildNoteData($subscriberId);
                                
                                if ($noteData) {
                                    $notes->logNote($noteData);
                                    
                                    header("location:index.php?page=subscriber-notes&id=" . $noteData['subscriber_id']);
                                }
                                
                                else {
                                    // header("location:index.php?page=subscriber-notes&id=" . $noteData['subscriber_id']);
                                    throw new Data_Exception('INVALID NOTES PARAMERS FROM NOTE FORM');
                                }
                            }
                            
                            else {
                                throw new Data_Exception('MISSING NOTE PARAMETERS IN NOTE FORM');
                                // $notes->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('INVALID ID PARAMETER IN URL');
                            // $notes->routeTo('appliancesList');
                        }
                    }
                    
                    else {
                        throw new Url_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
                
                elseif ($notes->isRequestMatching($action, 'edit-note')) {
                    if ($notes->areParamsSet(['note-id', 'id'])) {
                        $subscriberId = intval($notes->getParam('id'));
                        $noteId = intval($notes->getParam('note-id'));
                        
                        if ($notes->isSubscriberIdValid($subscriberId) && $notes->isNoteIdValid($noteId)) {
                            if ($notes->areDataPosted(['note-message', 'attached-meeting-date'])) {
                                $noteData = $notes->buildNoteData($subscriberId);

                                if ($noteData) {
                                    $notes->editNote($noteData, $noteId);

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
                
                elseif ($notes->isRequestMatching($action, 'delete-note')) {
                    if ($notes->areParamsSet(['note-id', 'id'])) {
                        $subscriberId = intval($notes->getParam('id'));
                        $noteId = intval($notes->getParam('note-id'));
                        
                        if ($notes->isNoteIdValid($noteId)) {
                            $notes->eraseNote($noteId);
                            
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
                $user->logoutUser();
            }
        }
        
        elseif (in_array($action, $Urls['actions']['program-intakes'])) {
            $program = new Dodie_Coaching\Controllers\program;
            
            if ($user->isLogged()) {
                if ($program->isRequestMatching($action, 'generate-meals')) {
                    if ($program->areParamsSet(['id'])) {
                        $subscriberId = intval($program->getParam('id'));
                        
                        if ($program->isSubscriberIdValid($subscriberId)) {
                            $mealsList = $program->getCheckedMeals();
                            
                            if ($mealsList) {
                                if (!$program->addProgramMeals($subscriberId, $mealsList)) {
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
                $user->logoutUser();
            }
        }
        
        elseif (in_array($action, $Urls['actions']['program-file'])) {
            $programFile = new Dodie_Coaching\Controllers\ProgramFile;
            
            if ($user->isLogged()) {
                if ($programFile->isRequestMatching($action, 'generate-program-file')) {
                    if ($programFile->areParamsSet(['id'])) {
                        $subscriberId = intval($programFile->getParam('id'));
                        
                        if ($programFile->isSubscriberIdValid($subscriberId)) {
                            $programFileStatus = $programFile->getProgramFileStatus($subscriberId);
                            
                            if ($programFile->isProgramFileUpdatable($subscriberId)) {
                                $programData = $programFile->getProgramData($subscriberId);

                                if ($programData) {
                                    $programFile->buildFile($twig, $subscriberId, $programData);

                                    header("location:index.php?page=subscriber-program&id=" . $subscriberId);
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
                $user->logoutUser();
            }
        }
        
        else {
            $user->logoutUser();
        }
    }
    
    else {
        header("location:index.php?page=presentation");
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