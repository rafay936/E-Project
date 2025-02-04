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
        }

        .profile-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
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

    <div class="profile-container">
        
        <h2>Username</h2>
        
        <form>
            <label for="address">Address:</label><br>
            <input type="text" id="address" name="address" placeholder="Enter address"><br><br>

            <label for="age">Age:</label><br>
            <input type="number" id="age" name="age" placeholder="Enter age"><br><br>

            <button type="button" class="update-button">Update</button>
        </form>
    </div>

</body>
</html>
