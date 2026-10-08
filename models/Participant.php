<?php

class Participant
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Insert one participant. Returns true if added, false if duplicate.
    public function add($event_id, $full_name, $student_id, $email, $phone)
    {
        $sql = "INSERT IGNORE INTO participants
                (event_id, full_name, student_id, email, phone)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", $event_id, $full_name, $student_id, $email, $phone);
        $stmt->execute();

        return $stmt->affected_rows === 1;
    }

    // Get all participants of one event (newest first)
    public function getByEvent($event_id)
    {
        $sql = "SELECT * FROM participants WHERE event_id = ? ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();

        return $stmt->get_result();
    }
}

?>