<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Subscriber as SubscriberModel;
use Dodie_Coaching\Models\Nutrition;

class Subscriber extends AdminPanel {    
    public function getSubscriberData(int $subscriberId) {
        $subscriber = new SubscriberModel;
        
        return $subscriber->selectSubscriberData($subscriberId);
    }
    
    public function getSubscriberHeaders(int $subscriberId) {
        $subscriber = new SubscriberModel;
        
        return $subscriber->selectSubscriberHeader($subscriberId);
    }
    
    /***********************************************************************************
    Return the value of the possibility to update the subscriber's program file based on
    its status and meals completion
    ***********************************************************************************/
    public function isProgramFileUpdatable(int $subscriberId): bool {
        $programFileStatus = $this->getProgramFileStatus($subscriberId);
        $updatableFileStatus = ['unhosted', 'depleted'];
        $isFileStatusFlawed = in_array($programFileStatus, $updatableFileStatus);
        
        $weekDays = $this->_getWeekDays();
        $meals = $this->_getProgramMeals($subscriberId);
        $isProgramCompleted = true;
        
        foreach($weekDays as $weekDay) {
            $englishWeekDay = $weekDay['english'];
            
            foreach($meals as $meal) {
                if (!$this->_isMealCompleted($subscriberId, $englishWeekDay, $meal)) {
                    $isProgramCompleted = false;
                }
            }
        }
        
        return ($isFileStatusFlawed && $isProgramCompleted);
    }
    
    public function isSubscriberIdValid(int $subscriberId) {
        $subscriber = new SubscriberModel;
        
        return $subscriber->selectSubscriberId($subscriberId);
    }
    
    public function renderSubscriberProfilePage(object $twig, int $subscriberId): void {
        echo $twig->render('admin_panels/subscriber-profile.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Profil abonné",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscribers-list', 'Liste des abonnés'],
            'subscriberDetails' => $this->_getSubscriberDetails($subscriberId)[0],
            'accountDetails' => $this->_getAccountDetails($subscriberId)
        ]);
    }
    
    public function renderSubscribersListPage(object $twig): void {
        echo $twig->render('admin_panels/subscribers-list.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => 'Liste des abonnés',
            'appSection' => 'userPanels',
            'prevPanel' => ['admin-dashboard', 'Tableau de bord'],
            'subscribersHeaders' => $this->_getSubscribersHeaders()
        ]);
    }
    
    private function _getAccountDetails(int $subscriberId) {
        $subscriber = new SubscriberModel;
        
        return $subscriber->selectAccountDetails($subscriberId);
    }
    
    private function _getSubscriberDetails(int $subscriberId) {
        $subscriber = new SubscriberModel;
        
        return $subscriber->selectSubscriberDetails($subscriberId);
    }
    
    private function _getSubscribersHeaders() {
        $subscriber = new SubscriberModel;
        
        return $subscriber->selectSubscribersHeaders();
    }
    
    private function _isMealCompleted(int $subscriberId, string $weekDay, string $meal): bool {
        $nutrition = new Nutrition;
        
        $isMealCompleted = true;
        
        $ingredientsCountPerMeal = $nutrition->selectIngredientsCount($subscriberId, $weekDay, $meal);
        
        if ($ingredientsCountPerMeal[0]['ingredientsCount'] === '0') {
            $isMealCompleted = false;
        }
        
        return $isMealCompleted;
    }
}