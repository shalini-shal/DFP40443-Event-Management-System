<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Event</title>

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
    </style>
</head>

<body>

<div class="container">

    <h2>Add New Event</h2>

    <form action="../controllers/EventController.php" method="POST">

        <label>Event Name</label>
        <input 
            type="text" 
            name="event_name" 
            required
            maxlength="100"
        >

        <label>Event Date</label>
        <input 
            type="date" 
            name="event_date" 
            required
        >

        <label>Venue</label>
        <input 
            type="text" 
            name="venue" 
            required
            maxlength="100"
        >

        <label>Target Audience</label>
        <input 
            type="text" 
            name="target_audience" 
            required
            maxlength="150"
        >

        <label>Budget (RM)</label>
        <input 
            type="number" 
            name="budget" 
            step="0.01"
            min="0"
            required
        >

        <label>Status</label>
        <select name="status" required>

            <option value="">-- Select Status --</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>

        </select>

        <button type="submit" name="add_event">
            Add Event
        </button>

    </form>

</div>

</body>
</html>