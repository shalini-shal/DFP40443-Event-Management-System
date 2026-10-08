<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Event.php';

$eventModel = new Event($conn);
$events = $eventModel->getAllEvents();

// Get notification
$notification = $_SESSION['notification'] ?? '';
$notification_type = $_SESSION['notification_type'] ?? '';

// Remove notification from session after getting it
unset($_SESSION['notification']);
unset($_SESSION['notification_type']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Event Management System</title>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        .container {
            width: 95%;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
        }

        .add-btn {
            display: inline-block;
            background-color: #333;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        .edit {
            color: blue;
            text-decoration: none;
        }

        .delete {
            color: red;
            text-decoration: none;
        }
        .logout-btn {
            display: inline-block;
            background-color: #c62828;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            margin-left: 10px;
        }

.logout-btn:hover {
    background-color: #a61f1f;
}

    </style>

</head>

<body>

<div class="container">

    <h2>Event Management System</h2>

    <a href="event_add.php" class="add-btn">
    + Add New Event
</a>

<a href="../auth/logout.php" class="logout-btn">
    Logout
</a>

    <table>

        <tr>
            <th>ID</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Target Audience</th>
            <th>Budget (RM)</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($event = $events->fetch_assoc()) { ?>

            <tr>

                <td>
                    <?php echo $event['id']; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($event['event_name']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($event['event_date']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($event['venue']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($event['target_audience']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($event['budget']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($event['status']); ?>
                </td>

                <td>

                    <a href="event_edit.php?id=<?php echo $event['id']; ?>"
                       class="edit">
                        Edit
                    </a>

                    |
                    <a href="event_participants.php?id=<?php echo $event['id']; ?>"
                    >Participants</a>

                    <a href="../controllers/EventController.php?delete=<?php echo $event['id']; ?>"
                       class="delete">
                        Delete
                    </a>

                </td>

            </tr>

        <?php } ?>

    </table>

</div>


<!-- SUCCESS / ERROR MESSAGE -->

<?php if (!empty($notification)) { ?>

<script>

Swal.fire({

    icon: '<?php echo htmlspecialchars($notification_type); ?>',

    title: 'Success!',

    text: '<?php echo htmlspecialchars($notification); ?>',

    confirmButtonText: 'Close',

    confirmButtonColor: '#3085d6'

});

</script>

<?php } ?>


</body>

</html>