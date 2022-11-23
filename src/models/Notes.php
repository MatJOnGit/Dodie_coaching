<?php

namespace Dodie_Coaching\Models;

use PDO;

class Notes extends Main {
    public function deleteSubscriberNote(int $noteId) {
        $db = $this->dbConnect();
        $deleteNoteQuery = "DELETE FROM followup_notes WHERE id = ?";
        $deleteNoteStatement = $db->prepare($deleteNoteQuery);

        return $deleteNoteStatement->execute([$noteId]);
    }

    public function insertSubscriberNote(string $message, string $date, int $subscriberId, int $attached_to_meeting) {
        $db = $this->dbConnect();
        $insertSubscriberNoteQuery = 'INSERT INTO followup_notes (date, user_id, note_entry, attached_to_meeting) VALUES (:date, :user_id, :message, :attached_to_meeting)';
        $insertSubscriberNoteStatement = $db->prepare($insertSubscriberNoteQuery);

        return $insertSubscriberNoteStatement->execute([
            'date' => $date,
            'user_id' => $subscriberId,
            'message' => $message,
            'attached_to_meeting' => $attached_to_meeting
        ]);
    }

    public function selectSubscriberNote(int $noteId) {
        $db = $this->dbConnect();
        $selectSubscriberNoteQuery = "SELECT id FROM followup_notes WHERE id = ?";
        $selectSubscriberNoteStatement = $db->prepare($selectSubscriberNoteQuery);
        $selectSubscriberNoteStatement->execute([$noteId]);

        return $selectSubscriberNoteStatement->fetch();
    }

    public function selectSubscriberNotes(int $subscriberId) {
        $db = $this->dbConnect();
        $selectSubscriberNotesQuery = "SELECT fun.id, fun.date, fun.note_entry, fun.attached_to_meeting FROM followup_notes fun INNER JOIN subscribers sub ON fun.user_id = sub.user_id WHERE sub.user_id = ? ORDER BY fun.date DESC";
        $selectSubscriberNotesStatement = $db->prepare($selectSubscriberNotesQuery);
        $selectSubscriberNotesStatement->execute([$subscriberId]);

        return $selectSubscriberNotesStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateSubscriberNote(string $message, string $date, int $attached_to_meeting, int $noteId) {
        $db = $this->dbConnect();
        $updateSubscriberNoteQuery = "UPDATE followup_notes SET note_entry = ?, date = ?,attached_to_meeting = ? WHERE id = ?";
        $updateSubscriberNoteStatement = $db->prepare($updateSubscriberNoteQuery);

        return $updateSubscriberNoteStatement->execute([$message, $date, $attached_to_meeting, $noteId]);
    }
}