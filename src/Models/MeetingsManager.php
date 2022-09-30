<?php

namespace Dodie_Coaching\Models;

use PDO;

class MeetingsManager extends Manager {
    public function bookMemberMeeting(string $memberEmail, string $meetingDate) {
        $db = $this->dbConnect();
        $meetingBookingQuery = "UPDATE scheduled_slots sl SET sl.user_id = (SELECT id FROM accounts WHERE email = ?), sl.slot_status = 'booked' WHERE sl.slot_date = ?";
        $meetingBookingQueryStatement = $db->prepare($meetingBookingQuery);
        $meetingBookingQueryStatement->execute([$memberEmail, $meetingDate]);

        return $meetingBookingQueryStatement->fetchAll();
    }

    public function getAvailableMeetingsSlots(int $appointmentDelay) {
        $db = $this->dbConnect();
        $availableMeetingSlotsGetterQuery = "SELECT slot_date FROM scheduled_slots WHERE slot_date >= (CURRENT_TIMESTAMP + interval ? DAY_HOUR) AND slot_status = 'available' AND user_id = 0 ORDER BY slot_date";
        $availableMeetingSlotsGetterStatement = $db->prepare($availableMeetingSlotsGetterQuery);
        $availableMeetingSlotsGetterStatement->execute([$appointmentDelay]);

        return $availableMeetingSlotsGetterStatement->fetchAll();
    }

    public function getMemberNextMeetingSlots(string $userEmail) {
        $db = $this->dbConnect();
        $memberIncomingMeetingsGetterQuery = "SELECT sl.slot_date FROM scheduled_slots sl INNER JOIN accounts a ON sl.user_id = a.id WHERE a.email= ? AND sl.slot_date > (CURRENT_TIMESTAMP) ORDER BY sl.slot_date DESC LIMIT 1";
        $memberIncomingMeetingsGetterStatement = $db->prepare($memberIncomingMeetingsGetterQuery);
        $memberIncomingMeetingsGetterStatement->execute([$userEmail]);

        return  $memberIncomingMeetingsGetterStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function releaseMemberAppointmentMeetingSlot(string $email) {
        $db = $this->dbConnect();
        $meetingSlotReleasingQuery = "UPDATE scheduled_slots SET user_id = 0, slot_status = 'available' WHERE user_id = (SELECT id FROM accounts WHERE email = ?)";
        $meetingSlotReleasingQueryStatement = $db->prepare($meetingSlotReleasingQuery);
        $meetingSlotReleasingQueryStatement->execute([$email]);
    }
}