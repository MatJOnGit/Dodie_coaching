<?php

namespace App\Domain\Controllers\AdminPanels;

use App\Domain\Models\Note;

final class SubscriberNote extends AdminPanel {
    private const NOTE_SCRIPTS = [
        'classes/ElementFader.model',
        'classes/NoteManager.model',
        'noteManagementApp'
    ];
    
    public function renderSubscriberNotesPage(object $twig, object $subscriber, object $meeting, int $subscriberId): void {
        echo $twig->render('admin_panels/subscriber-notes.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyles(),
            'frenchTitle' => "Notes de suivi",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-profile&id=' . $subscriberId, 'Profil abonnés'],
            'subscriberHeaders' => $subscriber->getSubscriberHeaders($subscriberId),
            'attendedMeetings' => $meeting->getAttendedMeetings($subscriberId),
            'notes' => $this->_getSubscriberNotes($subscriberId),
            'pageScripts' => $this->_getNotesScripts()
        ]);
    }
    
    private function _getNotesScripts(): array {
        return self::NOTE_SCRIPTS;
    }
    
    private function _getSubscriberNotes(int $subscriberId) {
        $note = new Note;
        
        return $note->selectNotes($subscriberId);
    }
}