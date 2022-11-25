<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Meetings;
use Dodie_Coaching\Models\Notes as NotesModel;

class Notes extends Subscribers {
    private $_notesScripts = [
        'classes/UserPanels.model',
        'classes/NotesHelper.model',
        'notesHelperApp'
    ];

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

    public function editNote(array $noteData, int $noteId) {
        $notes = new NotesModel;

        return $notes->updateNote($noteData['message'], $noteData['date'], $noteData['attached_to_meeting'], $noteId);
    }

    public function eraseNote(int $noteId) {
        $notes = new NotesModel;

        return $notes->deleteNote($noteId);
    }

    public function getAttendedMeetings(int $subscriberId) {
        $meetings = new Meetings;

        return $meetings->selectAttendedMeetings($subscriberId);
    }

    public function isNoteIdValid(int $noteId) {
        $notes = new NotesModel;

        return $notes->selectNote($noteId);
    }

    public function logNote(array $noteData) {
        $notes = new NotesModel;

        return $notes->insertNote($noteData['message'], $noteData['date'], $noteData['subscriber_id'], $noteData['attached_to_meeting']);
    }

    public function renderSubscriberNotesPage(object $twig, int $subscriberId) {
        echo $twig->render('admin_panels/subscriber-notes.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Notes de suivi",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-profile&id=' . $subscriberId, 'Profil abonnés'],
            'subscriberHeaders' => $this->_getSubscriberHeaders($subscriberId),
            'attendedMeetings' => $this->getAttendedMeetings($subscriberId),
            'subscriberNotes' => $this->_getSubscriberNotes($subscriberId),
            'pageScripts' => $this->_getNotesScripts()
        ]);
    }
    
    private function _getNotesScripts(): array {
        return $this->_notesScripts;
    }

    private function _getSubscriberNotes(int $subscriberId) {
        $notes = new NotesModel;

        return $notes->selectNotes($subscriberId);
    }
}