<?php

namespace App\Entities;

use App\Domain\Models\MeetingSlot;

final class Meeting {
    public function areDateDataValid(array $meetingData): bool {
        $meetingDate = $meetingData['meeting-day'] . ' ' . $meetingData['meeting-time'];
        
        return date('Y-m-d H:i', strtotime($meetingDate)) === $meetingDate;
    }
    
    public function addMeetingSlot(array $meetingData) {
        $meetingSlot = new MeetingSlot;
        
        $meetingDate = $meetingData['meeting-day'] . ' ' . $meetingData['meeting-time'] . ':00';
        
        return $meetingSlot->insertMeeting($meetingDate);
    }
    
    public function isMeetingIdValid(string $meetingId): bool {
        $meetingManagement = new MeetingSlot;
        
        $isMeetingIdValid = false;
        
        foreach ($meetingManagement->selectNextMeetings() as $meetingManagement) {
            if ($meetingManagement['slot_id'] === $meetingId) {
                $isMeetingIdValid = true;
            }
        }
        
        return $isMeetingIdValid;
    }
    
    public function getAttendeeData(string $meetingId) {
        $meetingSlot = new MeetingSlot;
        
        return $meetingSlot->selectAttendeeData($meetingId);
    }
    
    public function isMeetingBooked(array $attendeeData): bool {
        return !empty($attendeeData) ? $attendeeData[0]['user_id'] > 0 : false;
    }
    
    public function eraseMeetingSlot(string $meetingId) {
        $meetingSlot = new MeetingSlot;
        
        return $meetingSlot->deleteMeeting($meetingId);
    }
    
    public function getAttendedMeetings(int $subscriberId) {
        $meetingSlot = new MeetingSlot;
        
        return $meetingSlot->selectAttendedMeetings($subscriberId);
    }
}