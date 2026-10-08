<?php

require_once __DIR__ . '/../config/database.php';

class Event
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // CREATE - Add new event
    public function addEvent($event_name, $event_date, $venue, $target_audience, $budget, $status)
    {
        $sql = "INSERT INTO events 
                (event_name, event_date, venue, target_audience, budget, status)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssssds",
            $event_name,
            $event_date,
            $venue,
            $target_audience,
            $budget,
            $status
        );

        return $stmt->execute();
    }

    // READ - Get all events
    public function getAllEvents()
    {
        $sql = "SELECT * FROM events ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->get_result();
    }

    // READ - Get one event
    public function getEventById($id)
    {
        $sql = "SELECT * FROM events WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // UPDATE - Update event
    public function updateEvent($id, $event_name, $event_date, $venue, $target_audience, $budget, $status)
    {
        $sql = "UPDATE events 
                SET event_name = ?,
                    event_date = ?,
                    venue = ?,
                    target_audience = ?,
                    budget = ?,
                    status = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "sssssdi",
            $event_name,
            $event_date,
            $venue,
            $target_audience,
            $budget,
            $status,
            $id
        );

        return $stmt->execute();
    }

    // DELETE - Delete event
    public function deleteEvent($id)
    {
        $sql = "DELETE FROM events WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}

?>