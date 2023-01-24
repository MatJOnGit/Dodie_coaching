<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\Meeting;
use Dodie_Coaching\Models\Note as NoteModel;

class Note extends Subscriber {
    private $_noteScripts = [
        'classes/UserPanels.model',
        'classes/NotesHelper.model',
        'notesHelperApp'
    ];
    
    /*********************************************************************
    Builds an associative array containing the previous form message along
    with its associated data, depending on the form optionnal parameters
    *********************************************************************/
    public function buildNoteData(int $subscriberId): array {
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
        $note = new NoteModel;
        
        return $note->updateNote($noteData['message'], $noteData['date'], $noteData['attached_to_meeting'], $noteId);
    }
    
    public function eraseNote(int $noteId) {
        $note = new NoteModel;
        
        return $note->deleteNote($noteId);
    }
    
    public function getAttendedMeetings(int $subscriberId) {
        $meeting = new Meeting;
        
        return $meeting->selectAttendedMeetings($subscriberId);
    }
    
    public function isNoteIdValid(int $noteId) {
        $note = new NoteModel;
        
        return $note->selectNote($noteId);
    }
    
    public function logNote(array $noteData) {
        $note = new NoteModel;
        
        return $note->insertNote($noteData['message'], $noteData['date'], $noteData['subscriber_id'], $noteData['attached_to_meeting']);
    }
    
    public function renderSubscriberNotesPage(object $twig, int $subscriberId): void {
        echo $twig->render('admin_panels/subscriber-notes.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Notes de suivi",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-profile&id=' . $subscriberId, 'Profil abonnés'],
            'subscriberHeaders' => $this->getSubscriberHeaders($subscriberId),
            'attendedMeetings' => $this->getAttendedMeetings($subscriberId),
            'notes' => $this->_getSubscriberNotes($subscriberId),
            'pageScripts' => $this->_getNotesScripts()
        ]);
    }
    
    private function _getNotesScripts(): array {
        return $this->_noteScripts;
    }
    
    private function _getSubscriberNotes(int $subscriberId) {
        $note = new NoteModel;
        
        return $note->selectNotes($subscriberId);
    }
}