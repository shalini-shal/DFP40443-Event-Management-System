<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Event.php';

$eventModel = new Event($conn);

if (!isset($_GET['id'])) {
    die("Event ID is missing.");
}

$id = $_GET['id'];

$event = $eventModel->getEventById($id);

if (!$event) {
    die("Event not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Event</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        .container {
            width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Edit Event</h2>

    <form action="../controllers/EventController.php" method="POST">

        <!-- Hidden ID -->

        <input 
            type="hidden"
            name="id"
            value="<?php echo htmlspecialchars($event['id']); ?>"
        >

        <label>Event Name</label>

        <input
            type="text"
            name="event_name"
            value="<?php echo htmlspecialchars($event['event_name']); ?>"
            maxlength="100"
            required
        >


        <label>Event Date</label>

        <input
            type="date"
            name="event_date"
            value="<?php echo htmlspecialchars($event['event_date']); ?>"
            required
        >


        <label>Venue</label>

        <input
            type="text"
            name="venue"
            value="<?php echo htmlspecialchars($event['venue']); ?>"
            maxlength="100"
            required
        >


        <label>Target Audience</label>

        <input
            type="text"
            name="target_audience"
            value="<?php echo htmlspecialchars($event['target_audience']); ?>"
            maxlength="150"
            required
        >


        <label>Budget (RM)</label>

        <input
            type="number"
            name="budget"
            value="<?php echo htmlspecialchars($event['budget']); ?>"
            step="0.01"
            min="0"
            required
        >


        <label>Status</label>

        <select name="status" required>

            <option value="">-- Select Status --</option>

            <option value="Pending"
                <?php if ($event['status'] == 'Pending') echo 'selected'; ?>>
                Pending
            </option>

            <option value="Approved"
                <?php if ($event['status'] == 'Approved') echo 'selected'; ?>>
                Approved
            </option>

            <option value="Rejected"
                <?php if ($event['status'] == 'Rejected') echo 'selected'; ?>>
                Rejected
            </option>

        </select>


        <button type="submit" name="update_event">
            Update Event
        </button>

    </form>

    <a href="event_list.php" class="back">
        ← Back to Event List
    </a>

</div>

</body>

</html>