<?php

namespace App\Domain\Controllers\AdminPanels;

final class SubscriberProgram extends AdminPanel {
    private const SUBSCRIBER_PROGRAM_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ProgramDisplayer.model',
        'classes/ProgramInitializer.model',
        'programManagementApp'
    ];
    
    public function renderSubscriberProgramPage(object $twig, object $subscriber, object $program, object $programFile, object $meal, string $fileStatus, int $subscriberId): void {
        echo $twig->render('admin_panels/subscriber-program.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => 'Programme',
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-profile&id=' . $subscriberId, 'Profil abonnés'],
            'subscriberHeaders' => $subscriber->getSubscriberHeaders($subscriberId),
            'programData' => $program->buildProgramData($subscriberId),
            'programMeals' => $program->getProgramMeals($subscriberId),
            'isProgramFileUpdatable' => $programFile->isProgramFileUpdatable($fileStatus, $subscriberId),
            'weekDaysTranslations' => $program->buildWeekDaysTranslations(),
            'mealsTranslations' => $meal->getMealsTranslations(),
            'pageScripts' => $this->_getSubscriberProgramScripts()
        ]);
    }
    
    private function _getSubscriberProgramScripts(): array {
        return self::SUBSCRIBER_PROGRAM_SCRIPTS;
    }
}