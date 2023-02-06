<?php

namespace App\Entities;

use App\Domain\Models\Account;

final class Routing {
    public const URLS = [
        'pages' => [
            'showcase' => ['presentation', 'coaching', 'programs-list', 'program-details', 'showcase-404'],
            'authentification' => ['login', 'registering', 'password-retrieving', 'mail-notification', 'token-signing', 'password-editing', 'retrieved-password'],
            'userPanels' => ['dashboard', 'nutrition', 'progress', 'meetings-booking', 'subscription'],
            'adminPanels' => ['admin-dashboard', 'appliances-list', 'appliance-details', 'subscribers-list', 'subscriber-profile', 'subscriber-program', 'subscriber-notes', 'meetings-management', 'meal-editing']
        ],
        'actions' => [
            'authentification' => ['log-account', 'register-account', 'logout', 'send-token', 'verify-token', 'register-password'],
            'progress' => ['add-report', 'delete-report'],
            'meeting' => ['book-appointment', 'cancel-appointment', 'save-meeting', 'delete-meeting'],
            'appliance' => ['reject-appliance', 'approve-appliance'],
            'notes' => ['save-note', 'edit-note', 'delete-note'],
            'program-intakes' => ['generate-meals'],
            'program-file' => ['generate-program-file'],
        ]
    ];
    
    public function areParamsSet(array $params): bool {
        $areParamsSet = true;
        
        foreach ($params as $param) {
            if (!isset($_GET[$param])) {
                $areParamsSet = false;
            }
        }
        
        return $areParamsSet;
    }
    
    public function getParam(string $param) {
        return htmlspecialchars($_GET[$param]);
    }
    
    public function getRole() {
        $account = new Account;
        
        return $account->selectRole($_SESSION['email']);
    }
    
    public function getUserId() {
        $account = new Account;
        
        return $account->selectId($_SESSION['email']);
    }
    
    public function isRequestMatching(string $request, string $toMatch) {
        return $request === $toMatch;
    }
    
    public function isRoleMatching(array $userRole, array $toMatch): bool {
        $isRoleMatching = false;
        
        if ($userRole) {
            $isRoleMatching = in_array($userRole['status'], $toMatch);
        }
        
        return $isRoleMatching;
    }
}