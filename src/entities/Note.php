<?php

namespace App\Entities;

use App\Domain\Models\MeetingSlot;
use App\Domain\Models\Note as NoteModel;

final class Note {
    /*********************************************************************
    Builds an associative array containing the previous form message along
    with its associated data, depending on the form optionnal parameters
    *********************************************************************/
    public function buildNoteData(int $subscriberId): array {
        $timezone = new Timezone;
        $timezone->setTimeZone();

        $meeting = new Meeting;

        $noteMessage = htmlspecialchars($_POST['note-message']);
        $noteDateIndex = htmlspecialchars($_POST['attached-meeting-date']);
        $attendedMeetings = $meeting->getAttendedMeetings($subscriberId);
        
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
    
    public function logNote(array $noteData) {
        $note = new NoteModel;
        
        return $note->insertNote($noteData['message'], $noteData['date'], $noteData['subscriber_id'], $noteData['attached_to_meeting']);
    }
    
    public function isNoteIdValid(int $noteId) {
        $note = new NoteModel;
        
        return $note->selectNote($noteId);
    }

    public function editNote(array $noteData, int $noteId) {
        $note = new NoteModel;
        
        return $note->updateNote($noteData['message'], $noteData['date'], $noteData['attached_to_meeting'], $noteId);
    }

    public function eraseNote(int $noteId) {
        $note = new NoteModel;
        
        return $note->deleteNote($noteId);
    }
}