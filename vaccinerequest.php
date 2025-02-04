<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="addchild.php" download></a>
    <a href="bedrequest.php" download></a>
    <a href="medicalhistory.php" download></a>
    <a href="changepassword.php" download></a>
    <a href="profile.php" download></a>
    <a href="userpanel.php" download></a>
    <a href="userdashboard.php" download></a>
    <a href="logout.php" download></a>
    <title>Vaccine Request</title>
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
        <h2>Vaccine Request</h2>

        <form action="submit_vaccine_request.html" method="POST">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" class="input-field" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" class="input-field" required>

            <label for="vaccine">Vaccine Type:</label>
            <select id="vaccine" name="vaccine" class="input-field" required>
                <option value="">Select a Vaccine</option>
                <option value="covid19">COVID-19</option>
                <option value="flu">Flu</option>
                <option value="hepatitis">Fever</option>
                <option value="others">Other</option>
            </select>

            <label for="request_date">Request Date:</label>
            <input type="date" id="request_date" name="request_date" class="input-field" required>

            <button type="submit" class="submit-btn">Submit Request</button>
        </form>
    </div>

</body>
</html>
