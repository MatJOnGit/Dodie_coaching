<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Subscribers as SubscriberModel;
use Dodie_Coaching\Models\ProgramFiles as ProgramFilesModel;
use Dodie_Coaching\Models\Nutrition as NutritionModel;

class Subscribers extends AdminPanels {
    public function getMessageType() {
        return empty($_POST['rejection-message']) ? 'default' : 'custom';
    }

    public function getProgramFileStatus($subscriberId) {
        $programFile = new ProgramFilesModel;

        $programFileStatus = $programFile->selectFileStatus($subscriberId);
        return $programFileStatus ? $programFileStatus['file_status'] : NULL;
    }
    
    public function getSubscriberData(int $subscriberId) {
        $subscriber = new SubscriberModel;

        return $subscriber->selectSubscriberData($subscriberId);
    }

    public function isProgramFileUpdatable($subscriberId) {
        $programFileStatus = $this->getProgramFileStatus($subscriberId);
        $updatableFileStatus = ['unhosted', 'depleted'];
        $isProgramFileUpdatable = in_array($programFileStatus, $updatableFileStatus);

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

        return ($isProgramFileUpdatable && $isProgramCompleted);
    }
    
    public function isSubscriberIdValid(int $subscriberId) {
        $subscriber = new SubscriberModel;

        return $subscriber->selectSubscriberId($subscriberId);
    }
    
    public function renderSubscriberProfilePage(object $twig, int $subscriberId) {
        echo $twig->render('admin_panels/subscriber-profile.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Profil abonné",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscribers-list', 'Liste des abonnés'],
            'subscriberDetails' => $this->_getSubscriberDetails($subscriberId)[0],
            'accountDetails' => $this->_getAccountDetails($subscriberId)
        ]);
    }
    
    public function renderSubscribersListPage(object $twig) {
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

    // Build an array out of a subscriber's program meals list. Return NULL if no meal is found. 
    protected function _getProgramMeals($subscriberId) {
        $subscribers = new SubscriberModel;
        
        $generatedMeals = $subscribers->selectProgramMeals($subscriberId);
        
        return strlen($generatedMeals['meals_list']) ? explode(', ', $generatedMeals['meals_list']) : NULL;
    }
    
    protected function _getSubscriberHeaders(int $subscriberId) {
        $subscriber = new SubscriberModel;

        return $subscriber->selectSubscriberHeader($subscriberId);
    }
    
    private function _getSubscribersHeaders() {
        $subscriber = new SubscriberModel;

        return $subscriber->selectSubscribersHeaders();
    }

    private function _isMealCompleted($subscriberId, $weekDay, $meal) {
        $nutrition = new NutritionModel;

        $isMealCompleted = true;

        $ingredientsCountPerMeal = $nutrition->selectIngredientsCount($subscriberId, $weekDay, $meal);
        
        if ($ingredientsCountPerMeal[0]['ingredientsCount'] === '0') {
            $isMealCompleted = false;
        }

        return $isMealCompleted;
    }
}