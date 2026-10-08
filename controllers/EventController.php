<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Event.php';

$eventModel = new Event($conn);



// CREATE EVENT

if (isset($_POST['add_event'])) {

    $event_name = trim($_POST['event_name']);
    $event_date = $_POST['event_date'];
    $venue = trim($_POST['venue']);
    $target_audience = trim($_POST['target_audience']);
    $budget = $_POST['budget'];
    $status = $_POST['status'];

    if (
        empty($event_name) ||
        empty($event_date) ||
        empty($venue) ||
        empty($target_audience) ||
        empty($budget) ||
        empty($status)
    ) {

        die("Please fill in all fields.");

    }


    if ($eventModel->addEvent(
        $event_name,
        $event_date,
        $venue,
        $target_audience,
        $budget,
        $status
    )) {

        // Notification
        $_SESSION['notification'] = "Event added successfully!";
        $_SESSION['notification_type'] = "success";

        header("Location: ../views/event_list.php");
        exit();

    } else {

        die("Failed to add event.");

    }
}


// UPDATE EVENT

if (isset($_POST['update_event'])) {

    $id = $_POST['id'];

    $event_name = trim($_POST['event_name']);
    $event_date = $_POST['event_date'];
    $venue = trim($_POST['venue']);
    $target_audience = trim($_POST['target_audience']);
    $budget = $_POST['budget'];
    $status = $_POST['status'];


    if (
        empty($event_name) ||
        empty($event_date) ||
        empty($venue) ||
        empty($target_audience) ||
        empty($budget) ||
        empty($status)
    ) {

        die("Please fill in all fields.");

    }


    if ($eventModel->updateEvent(
        $id,
        $event_name,
        $event_date,
        $venue,
        $target_audience,
        $budget,
        $status
    )) {

        // Notification
        $_SESSION['notification'] = "Event updated successfully!";
        $_SESSION['notification_type'] = "success";

        header("Location: ../views/event_participants.php?id=" . (int) $id);
        exit();

    } else {

        die("Failed to update event.");

    }
}

// DELETE EVENT

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];


    if ($eventModel->deleteEvent($id)) {

        // Notification
        $_SESSION['notification'] = "Event deleted successfully!";
        $_SESSION['notification_type'] = "success";

        header("Location: ../views/event_list.php");
        exit();

    } else {

        die("Failed to delete event.");

    }
}

?>