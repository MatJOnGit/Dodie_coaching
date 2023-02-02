<?php

namespace App\Domain\Controllers\CostumerPanels;

use App\Domain\Models\Progress as ProgressModel;

class Progress extends CostumerPanel {
    private const PROGRESS_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/ProgressManager.model',
        'progressManagementApp'
    ];

    public function renderProgressPage(object $twig): void {
        echo $twig->render('user_panels/progress.html.twig', [
            'stylePaths' => $this->_getCostumerPanelsStyles(),
            'frenchTitle' => 'progression',
            'appSection' => 'userPanels',
            'prevPanel' => ['dashboard', 'Tableau de bord'],
            'progressHistory' => $this->getHistory(),
            'pageScripts' => $this->_getProgressScripts()
        ]);
    }
    
    public function getHistory(): array {
        $progress = new ProgressModel;
        
        return $progress->selectReports($_SESSION['email']);
    }
    
    private function _getProgressScripts(): array {
        return self::PROGRESS_SCRIPTS;
    }
}