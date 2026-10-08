<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Participant.php';

if (!isset($_GET['id'])) {
    die("Event ID is missing.");
}

$eventModel = new Event($conn);
$participantModel = new Participant($conn);

$event = $eventModel->getEventById((int) $_GET['id']);

if (!$event) {
    die("Event not found.");
}

$participants = $participantModel->getByEvent($event['id']);

// Notification (used after upload)
$notification = $_SESSION['notification'] ?? '';
$notification_type = $_SESSION['notification_type'] ?? 'info';
unset($_SESSION['notification'], $_SESSION['notification_type']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants - <?php echo htmlspecialchars($event['event_name']); ?></title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px; }
        .container { width: 95%; margin: auto; background: white; padding: 30px; border-radius: 10px; }
        h2 { text-align: center; }
        .info { background: #f0f0f0; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .upload-box { border: 1px dashed #999; padding: 15px; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #333; color: white; }
        button { background-color: #333; color: white; border: none; padding: 10px 15px; cursor: pointer; }
        button:hover { background-color: #555; }
    </style>
</head>

<body>
<div class="container">

    <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>

    <div class="info">
        <b>Date:</b> <?php echo htmlspecialchars($event['event_date']); ?> |
        <b>Venue:</b> <?php echo htmlspecialchars($event['venue']); ?> |
        <b>Target Audience:</b> <?php echo htmlspecialchars($event['target_audience']); ?> |
        <b>Status:</b> <?php echo htmlspecialchars($event['status']); ?>
    </div>

        <h3>Bulk Registration (CSV Upload)</h3>

    <div class="upload-box">
        <form action="../controllers/ParticipantController.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">

            <input type="file" name="csv_file" accept=".csv" required>

            <button type="submit" name="upload_csv">Upload CSV</button>

        </form>
        <small>Format: full_name, student_id, email, phone (first row is the header). Max 2MB, .csv only.</small>
    </div>

    <h3>Registered Participants</h3>

    <table>
        <tr>
            <th>No.</th>
            <th>Full Name</th>
            <th>Student ID</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Registered At</th>
        </tr>

        <?php $no = 1; while ($p = $participants->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($p['full_name']); ?></td>
            <td><?php echo htmlspecialchars($p['student_id']); ?></td>
            <td><?php echo htmlspecialchars($p['email']); ?></td>
            <td><?php echo htmlspecialchars($p['phone'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($p['created_at']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <p><a href="event_list.php">← Back to Event List</a></p>

</div>

<?php if (!empty($notification)) { ?>
<script>
Swal.fire({
    icon: '<?php echo htmlspecialchars($notification_type); ?>',
    text: '<?php echo htmlspecialchars($notification); ?>'
});
</script>
<?php } ?>

</body>
</html>