<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="addchild.php" download></a>
    <a href="medicalhistory.php" download></a>
    <a href="bedrequest.php" download></a>
    <a href="vaccine.php" download></a>
    <a href="logout.php" download></a>
    <a href="changepassword.php" download></a>
    <a href="userpanel.php" download></a>
    <a href="userdashboard.php" download></a>
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
            flex-direction: column;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            width: 100%;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .profile-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
            margin-top: 100px; /* To avoid overlap with the header */
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
            object-fit: cover;
        }

        .profile-container h2 {
            margin: 10px 0;
            color: #333;
        }

        .profile-container p {
            color: #777;
            margin: 5px 0;
        }

        .profile-container input {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .update-button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .update-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Profile Page</h1>
    </div>

    <div class="profile-container">
        <img src="download.png" alt="Profile Picture">
        
        <h2>Profile</h2>
        
        <form>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" placeholder="Enter username"><br><br>

            <label for="email">Email:</label><br>
            <input type="text" id="email" name="email" placeholder="Enter email"><br><br>

            <label for="address">Phone number:</label><br>
            <input type="text" id="phone number" name="phone number" placeholder="Enter phone number"><br><br>

            <label for="password">Password:</label><br>
            <input type="text" id="password" name="password" placeholder="Enter password"><br><br>

            <button type="button" class="update-button">Log Out</button>
        </form>
    </div>

</body>
</html>
