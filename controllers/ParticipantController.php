<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Participant.php';

$eventModel = new Event($conn);
$participantModel = new Participant($conn);

// Helper: save a message and go back to the participants page
function redirectWith($msg, $type, $url)
{
    $_SESSION['notification'] = $msg;
    $_SESSION['notification_type'] = $type;
    header("Location: $url");
    exit();
}

if (isset($_POST['upload_csv'])) {

    $event_id = (int) $_POST['event_id'];
    $back = "../views/event_participants.php?id=" . $event_id;

    // 1. The event must exist
    if (!$eventModel->getEventById($event_id)) {
        die("Event not found.");
    }

    // 2. A file must have been sent
    if (!isset($_FILES['csv_file'])) {
        redirectWith("No file received.", "error", $back);
    }

    $file = $_FILES['csv_file'];

    // 3. PHP-level size errors (php.ini limit)
    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        redirectWith("File is too large. Maximum size is 2MB.", "error", $back);
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        redirectWith("Upload failed. Please choose a file and try again.", "error", $back);
    }

    // 4. Our own 2MB limit
    if ($file['size'] > 2 * 1024 * 1024) {
        redirectWith("File is too large. Maximum size is 2MB.", "error", $back);
    }

    // 5. Extension must be .csv
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'csv') {
        redirectWith("Only .csv files are allowed.", "error", $back);
    }

    // 6. Check the real content type, not just the file name
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['text/plain', 'text/csv', 'application/csv', 'application/vnd.ms-excel'];

    if (!in_array($mime, $allowed)) {
        redirectWith("Invalid file content. Please upload a real CSV file.", "error", $back);
    }

    // 7. Read the CSV row by row
    $handle = fopen($file['tmp_name'], 'r');

    if ($handle === false) {
        redirectWith("Could not read the file.", "error", $back);
    }

    fgetcsv($handle); // skip the header row

    $added = 0;
    $skipped = 0;

    while (($row = fgetcsv($handle)) !== false) {

        // Not enough columns (blank line or bad row)
        if (count($row) < 3) {
            $skipped++;
            continue;
        }

        $full_name  = trim($row[0]);
        $student_id = trim($row[1]);
        $email      = trim($row[2]);
        $phone      = isset($row[3]) ? trim($row[3]) : null;

        // Validate the data
        if ($full_name === '' || $student_id === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($full_name) > 100 || strlen($student_id) > 30) {
            $skipped++;
            continue;
        }

        // Insert into the database
        if ($participantModel->add($event_id, $full_name, $student_id, $email, $phone)) {
            $added++;
        } else {
            $skipped++; // duplicate
        }
    }

    fclose($handle);

    redirectWith("$added participant(s) registered, $skipped row(s) skipped.", "success", $back);
}

?>