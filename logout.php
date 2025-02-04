<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="vaccinerequest.php" download></a>
    <a href="addchild.php" download></a>
    <a href="bedrequest.php" download></a>
    <a href="changepassword.php" download></a>
    <a href="medicalhistory.php" download></a>
    <a href="profile.php" download></a>
    <a href="userpanel.php" download></a>
    <a href="userdashboard.php" download></a>
    <title>Logout Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .logout-container {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        button {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        button:active {
            background-color: #388e3c;
        }
    </style>
</head>
<body>

    <div class="logout-container">
        <h2>You are logged in</h2>
        <p>Click the button below to log out:</p>
        <button onclick="logout()">Log Out</button>
    </div>

    <script>
        function logout() {
            
            sessionStorage.clear();
            localStorage.clear();

            
            alert("You have logged out successfully.");
            window.location.href = "login.html";
        }
    </script>

</body>
</html>
