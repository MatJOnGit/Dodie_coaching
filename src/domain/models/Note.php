<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

final class Note {
    use Mixins\Database;
    
    public function dbConnect() {
        return $this->connect();
    }
    
    public function deleteNote(int $noteId) {
        $db = $this->dbConnect();
        $deleteNoteQuery = "DELETE FROM followup_notes WHERE id = ?";
        $deleteNoteStatement = $db->prepare($deleteNoteQuery);
        
        return $deleteNoteStatement->execute([$noteId]);
    }
    
    public function insertNote(string $message, string $date, int $subscriberId, int $attached_to_meeting) {
        $db = $this->dbConnect();
        $insertNoteQuery = "INSERT INTO followup_notes (date, user_id, note_entry, attached_to_meeting) VALUES (?, ?, ?, ?)";
        $insertNoteStatement = $db->prepare($insertNoteQuery);
        
        return $insertNoteStatement->execute([$date, $subscriberId, $message, $attached_to_meeting]);
    }
    
    public function selectNote(int $noteId) {
        $db = $this->dbConnect();
        $selectNoteQuery = "SELECT id FROM followup_notes WHERE id = ?";
        $selectNoteStatement = $db->prepare($selectNoteQuery);
        $selectNoteStatement->execute([$noteId]);
        
        return $selectNoteStatement->fetch();
    }
    
    public function selectNotes(int $subscriberId) {
        $db = $this->dbConnect();
        $selectNotesQuery =
            "SELECT
                fun.id,
                fun.date,
                fun.note_entry,
                fun.attached_to_meeting 
            FROM followup_notes fun
            INNER JOIN subscribers_data sub ON fun.user_id = sub.user_id
            WHERE sub.user_id = ?
            ORDER BY fun.date DESC";
        $selectNotesStatement = $db->prepare($selectNotesQuery);
        $selectNotesStatement->execute([$subscriberId]);
        
        return $selectNotesStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateNote(string $message, string $date, int $attached_to_meeting, int $noteId) {
        $db = $this->dbConnect();
        $updateNoteQuery = "UPDATE followup_notes SET note_entry = ?, date = ?, attached_to_meeting = ? WHERE id = ?";
        $updateNoteStatement = $db->prepare($updateNoteQuery);
        
        return $updateNoteStatement->execute([$message, $date, $attached_to_meeting, $noteId]);
    }
}