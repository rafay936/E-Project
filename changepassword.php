<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="vaccinerequest.php" download></a>
    <a href="addchild.php" download></a>
    <a href="bedrequest.php" download></a>
    <a href="logout.php" download></a>
    <a href="medicalhistory.php" download></a>
    <a href="profile.php" download></a>
    <a href="userpanel.php" download></a>
    <a href="userdashboard.php" download></a>
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .panel-container {
            width: 400px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .input-field {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .input-field:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .submit-btn:active {
            background-color: #388e3c;
        }
    </style>
</head>
<body>

    <div class="panel-container">
        <h2>Change Password</h2>

        <form action="submit_password_change.html" method="POST">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" class="input-field" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" class="input-field" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="input-field" required>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>

</body>
</html>
