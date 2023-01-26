<?php

declare(strict_types=1);

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
    if ($user->areParamsSet(['page'])) {
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
                    $userPanel = new Dodie_Coaching\Controllers\UserPanel;
                    
                    if ($userPanel->isRequestMatching($page, 'dashboard')) {
                        $userDashboard = new Dodie_Coaching\Controllers\UserDashboard;
                        $userDashboard->renderUserDashboardPage($twig);
                    }
                    
                    elseif ($userPanel->isRequestMatching($page, 'nutrition')) {
                        $nutrition = new Dodie_Coaching\Controllers\Nutrition;
                        
                        if ($nutrition->isMenuRequested()) {
                            $subscriberId = intval($nutrition->getUserId()['id']);
                            $nutrition->renderNutritionMenuPage($twig, $subscriberId);
                        }
                        
                        elseif ($nutrition->isMealRequested()) {
                            $mealData = $nutrition->getMealData();
                            
                            if ($nutrition->areMealParamsValid($mealData)) {
                                $nutrition->renderMealDetailsPage($twig, $mealData);
                            }
                            
                            else {
                                throw new URL_Exception('INVALID MEAL PARAMETER');
                                // $nutrition->routeTo('nutrition');
                            }
                        }
                        
                        elseif ($nutrition->isRequestSet()) {
                            $request = $nutrition->getRequest();
                            
                            if ($nutrition->isShoppingListRequested($request)) {
                                $nutrition->renderShoppingListPage($twig);
                            }
                            
                            else {
                                $userPanel->routeTo('nutrition');
                            }
                        }
                        
                        else {
                            $userPanel->routeTo('nutrition');
                        }
                    }
                    
                    elseif ($userPanel->isRequestMatching($page, 'progress')) {
                        $progress = new Dodie_Coaching\Controllers\Progress;
                        $progress->renderProgressPage($twig);
                    }
                    
                    elseif ($userPanel->isRequestMatching($page, 'meetings-booking')){
                        $meetingBooking = new Dodie_Coaching\Controllers\MeetingBooking;
                        $meetingBooking->renderMeetingsBookingPage($twig);
                    }
                    
                    elseif ($userPanel->isRequestMatching($page, 'subscription')) {
                        $subscription = new Dodie_Coaching\Controllers\Subscription;
                        $subscription->renderSubscriptionPage($twig);
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
                    $adminPanel = new Dodie_Coaching\Controllers\AdminPanel;
                    
                    if ($adminPanel->isRequestMatching($page, 'admin-dashboard')) {
                        $adminDashboard = new Dodie_Coaching\Controllers\AdminDashboard;
                        $adminDashboard->renderAdminDashboardPage($twig);
                    }
                    
                    elseif ($adminPanel->isRequestMatching($page, 'appliances-list')) {
                        $appliance = new Dodie_Coaching\Controllers\Appliance;
                        $appliance->renderAppliancesListPage($twig);
                    }
                    
                    elseif ($adminPanel->isRequestMatching($page, 'appliance-details') && $adminPanel->areParamsSet(['id'])) {
                        $appliance = new Dodie_Coaching\Controllers\Appliance;
                        $applicantId = intval($appliance->getParam('id'));
                        
                        if ($appliance->isApplianceIdValid($applicantId)) {
                            $appliance->renderApplianceDetailsPage($twig, $applicantId);
                        }
                        
                        else {
                            $appliance->routeTo('appliancesList');
                        }
                    }
                    
                    elseif ($adminPanel->isRequestMatching($page, 'subscribers-list')) {
                        $subscriber = new Dodie_Coaching\Controllers\Subscriber;
                        $subscriber->renderSubscribersListPage($twig);
                    }
                    
                    elseif ($adminPanel->isRequestMatching($page, 'subscriber-profile')) {
                        $subscriber = new Dodie_Coaching\Controllers\Subscriber;
                        
                        if ($adminPanel->areParamsSet(['id'])) {
                            $subscriberId = intval($subscriber->getParam('id'));
                            
                            if ($subscriber->isSubscriberIdValid($subscriberId)) {
                                $subscriber->renderSubscriberProfilePage($twig, $subscriberId);
                            }
                            
                            else {
                                $subscriber->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $subscriber->routeTo('subscribersList');
                        }
                    }
                    
                    elseif ($adminPanel->isRequestMatching($page, 'subscriber-program')) {
                        $program = new Dodie_Coaching\Controllers\Program;
                        
                        if ($adminPanel->areParamsSet(['id'])) {
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
                            // $program->routeTo('subscribersList');
                        }
                    }
                    
                    elseif ($adminPanel->isRequestMatching($page, 'subscriber-notes')) {
                        $note = new Dodie_Coaching\Controllers\Note;
                        
                        if ($note->areParamsSet(['id'])) {
                            $subscriberId = intval($note->getParam('id'));
                            
                            if ($note->isSubscriberIdValid($subscriberId)) {
                                $note->renderSubscriberNotesPage($twig, $subscriberId);
                            }
                            
                            else {
                                $note->routeTo('subscribersList');
                            }
                        }
                        
                        else {
                            throw new Url_Exception('MISSING ID PARAMETER IN URL');
                            // $note->routeTo('subscribersList');
                        }
                    }
                    
                    else if ($adminPanel->isRequestMatching($page, 'meetings-management')) {
                        $meetingManagement = new Dodie_Coaching\Controllers\MeetingManagement;
                        
                        $meetingManagement->renderMeetingsManagementPage($twig);
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
    
    elseif ($user->areParamsSet(['action'])) {
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
                            $tokenData = $user->getTokenDate($userData['email']);
                            
                            if ($tokenData) {
                                if ($user->isLastTokenOld($tokenData)) {
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
                                if (!$progress->eraseProgressItem($progressHistory, $reportId)) {
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
            $meeting = new Dodie_Coaching\Controllers\MeetingBooking;
            
            if ($user->isLogged()) {
                if ($meeting->isRequestMatching($action, 'book-appointment')) {
                    $dateData = $meeting->getDateData();
                    
                    if ($meeting->areDateDataValid($dateData)) {
                        $formatedDate = $meeting->getFormatedDate($dateData);
                        
                        if ($meeting->isMeetingsSlotAvailable($formatedDate)) {
                            if (!$meeting->bookAppointment($formatedDate)) {
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
                    
                    $meeting->routeTo('meetingsBooking');
                }
                
                elseif ($meeting->isRequestMatching($action, 'cancel-appointment')) {
                    if (!$meeting->cancelAppointment()) {
                        throw new DB_Exception('FAILED TO CANCEL APPOINTMENT');
                    }
                    
                    $meeting->routeTo('meetingsBooking');
                }
                
                elseif ($meeting->isRequestMatching($action, 'save-meeting')) {
                    $userRole = $user->getRole();
                    
                    if ($user->isRoleMatching($userRole, ['admin'])) {
                        $meetingData = $user->getFormData(['meeting-day', 'meeting-time']);
                        
                        if ($meetingData) {
                            $meeting = new Dodie_Coaching\Controllers\MeetingManagement;
                            
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
                
                elseif ($meeting->isRequestMatching($action, 'delete-meeting')) {
                    $userRole = $user->getRole();
                    
                    if ($user->isRoleMatching($userRole, ['admin'])) {
                        if ($user->areParamsSet(['id'])) {
                            $meeting = new Dodie_Coaching\Controllers\MeetingManagement;
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
            $appliance = new Dodie_Coaching\Controllers\Appliance;
            
            if ($user->isLogged()) {
                if ($appliance->isRequestMatching($action, 'reject-appliance')) {
                    if ($appliance->areParamsSet(['id'])) {
                        $applicantId = intval($appliances->getParam('id'));
                        
                        if ($appliance->isApplianceIdValid($applicantId)) {
                            $appResponder = new Dodie_Coaching\Services\ApplianceResponder;
                            
                            $applicantData = $appliances->getApplicantData($applicantId);
                            $messageType = $appliances->getMessageType();
                            
                            if ($appliance->eraseAppliance($applicantId)) {
                                if (!$appResponder->sendRejectionNotification($messageType, $applicantData)) {
                                    throw new Mailer_Exception('FAILED TO SEND APPLIANCE REJECTION EMAIL');
                                }
                                
                                $appliance->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            $appliance->routeTo('appliancesList');
                        }
                    }
                    
                    else {
                        throw new Data_Exception('MISSING ID PARAMETER IN URL');
                    }
                }
                
                elseif ($appliances->isRequestMatching($action, 'approve-appliance')) {
                    if ($appliance->areParamsSet(['id'])) {
                        $applicantId = intval($appliances->getParam('id'));
                        
                        if ($appliance->isApplianceIdValid($applicantId)) {
                            $applianceResponder = new Dodie_Coaching\Services\ApplianceResponder;
                            $applicantData = $appliance->getApplicantData($applicantId);
                            
                            if ($appliance->acceptAppliance($applicantId, 'payment_pending')) {
                                if (!$applianceResponder->sendApprovalNotification($applicantData)) {
                                    throw new Mailer_Exception('FAILED TO SEND APPLIANCE APPROVAL EMAIL');
                                }
                                
                                $appliance->routeTo('appliancesList');
                            }
                        }
                        
                        else {
                            $appliance->routeTo('appliancesList');
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
            $note = new Dodie_Coaching\Controllers\Note;
            
            if ($user->isLogged()) {
                if ($note->isRequestMatching($action, 'save-note')) {
                    if ($note->areParamsSet(['id'])) {
                        $subscriberId = intval($note->getParam('id'));
                        
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
                
                elseif ($note->isRequestMatching($action, 'edit-note')) {
                    if ($note->areParamsSet(['note-id', 'id'])) {
                        $subscriberId = intval($note->getParam('id'));
                        $noteId = intval($note->getParam('note-id'));
                        
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
                
                elseif ($note->isRequestMatching($action, 'delete-note')) {
                    if ($note->areParamsSet(['note-id', 'id'])) {
                        $subscriberId = intval($note->getParam('id'));
                        $noteId = intval($note->getParam('note-id'));
                        
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
                                $programData = $programFile->buildProgramData($subscriberId);
                                $subscriberHeaders = $programFile->getSubscriberHeaders($subscriberId);
                                
                                if ($programData && $subscriberHeaders) {
                                    $output = $programFile->buildFile($twig, $subscriberId, $programData, $subscriberHeaders);
                                    
                                    if ($output) {
                                        if ($programFile->saveDataToPdf($output, $subscriberHeaders)) {
                                            $programFileAlerter = new Dodie_Coaching\Services\ProgramFileAlerter;
                                            
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