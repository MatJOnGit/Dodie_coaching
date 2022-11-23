<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Meetings;
use Dodie_Coaching\Models\Notes;

class SubscriberNotes extends AdminSubscribers {
    private $_notesScripts = [
        'classes/UserPanels.model',
        'classes/NotesHelper.model',
        'notesHelperApp'
    ];

    public function renderSubscriberNotesPage(object $twig, int $subscriberId) {
        echo $twig->render('admin_panels/subscriber-notes.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Notes",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-profile&id=' . $subscriberId, 'Profil abonnés'],
            'subscriberHeaders' => $this->_getSubscriberHeaders($subscriberId),
            'attendedMeetings' => $this->getAttendedMeetings($subscriberId),
            'subscriberNotes' => $this->_getSubscriberNotes($subscriberId),
            'pageScripts' => $this->_getNotesScripts()
        ]);
    }

    public function getAttendedMeetings(int $subscriberId) {
        $meetings = new Meetings;

        return $meetings->selectAttendedMeetings($subscriberId);
    }

    public function isNoteIdValid(int $noteId) {
        $notes = new Notes;

        return $notes->selectSubscriberNote($noteId);
    }

    public function buildNoteData(int $subscriberId) {
        $this->_setTimeZone();

        $noteMessage = htmlspecialchars($_POST['note-message']);
        $noteDateIndex = htmlspecialchars($_POST['attached-meeting-date']);
        $attendedMeetings = $this->getAttendedMeetings($subscriberId);

        if (is_numeric($noteDateIndex) && array_key_exists($noteDateIndex, $attendedMeetings)) {
            $noteData = [
                'message' => $noteMessage,
                'date' => $attendedMeetings[$noteDateIndex]['slot_date'],
                'subscriber_id' => $attendedMeetings[$noteDateIndex]['user_id'],
                'attached_to_meeting' => true
            ];
        }

        else {
            $noteData = [
                'message' => $noteMessage,
                'date' => date('Y-m-d H:i:s'),
                'subscriber_id' => $subscriberId,
                'attached_to_meeting' => false
            ];
        }

        return $noteData;
    }

    public function isSaveNoteActionRequested(string $action): bool {
        return $action === 'save-note';
    }

    public function isEditNoteActionRequested(string $action): bool {
        return $action === 'edit-note';
    }

    public function isDeleteNoteActionRequested(string $action): bool {
        return $action === 'delete-note';
    }

    public function editNote(array $noteData, int $noteId) {
        $notes = new Notes;

        return $notes->updateSubscriberNote($noteData['message'], $noteData['date'], $noteData['attached_to_meeting'], $noteId);
    }

    public function eraseNote(int $noteId) {
        $notes = new Notes;

        return $notes->deleteSubscriberNote($noteId);
    }

    public function logNote(array $noteData) {
        $notes = new Notes;

        return $notes->insertSubscriberNote($noteData['message'], $noteData['date'], $noteData['subscriber_id'], $noteData['attached_to_meeting']);
    }
    
    private function _getNotesScripts(): array {
        return $this->_notesScripts;
    }

    private function _getSubscriberNotes(int $subscriberId) {
        $notes = new Notes;

        return $notes->selectSubscriberNotes($subscriberId);
    }
}