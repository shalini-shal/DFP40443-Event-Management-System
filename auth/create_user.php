
<?php

require_once __DIR__ . '/../config/database.php';

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check empty fields
    if (empty($username) || empty($password) || empty($confirm_password)) {

        $error = "Please fill in all fields.";

    // Check password match
    } elseif ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    // Check username length
    } elseif (strlen($username) < 3) {

        $error = "Username must be at least 3 characters.";

    // Check password length
    } elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    } else {

        // Check if username already exists
        $checkSql = "SELECT id FROM users WHERE username = ?";

        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();

        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {

            $error = "Username already exists. Please choose another username.";

        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {

                $success = "Registration successful! You can now login.";

                // Clear form values
                $username = "";

            } else {

                $error = "Registration failed. Please try again.";
            }

            $stmt->close();
        }

        $checkStmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create User - Event Management System</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        .container {
            width: 400px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        h3 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: normal;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Event Management System</h2>

    <h3>Create New User</h3>

    <?php if (!empty($error)) { ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php } ?>

    <?php if (!empty($success)) { ?>

        <div class="success">
            <?php echo htmlspecialchars($success); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <label>Username</label>

        <input
            type="text"
            name="username"
            maxlength="50"
            value="<?php echo htmlspecialchars($username ?? ''); ?>"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >

        <label>Confirm Password</label>

        <input
            type="password"
            name="confirm_password"
            required
        >

        <button type="submit" name="register">
            Register
        </button>

    </form>

    <div class="login-link">
        Already have an account?
        <a href="login.php">Login here</a>
    </div>

</div>

</body>

</html>

