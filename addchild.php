<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="medicalhistory.php" download></a>
    <a href="profile.php" download></a>
    <a href="bedrequest.php" download></a>
    <a href="vaccinerequest.php" download></a>
    <a href="changepassword.php" download></a>
    <a href="logout.php" download></a>
    <a href="userpanel.php" download></a>
    <a href="userdashboard.php" download></a>
    <title>Add Child</title>
</head>
<body>

    <h2>Add Child</h2>

    <form action="/submit_form" method="post">
        <label for="childName">Child's Name:</label>
        <input type="text" id="childName" name="childName" required><br><br>

        <label for="fatherName">Father's Name:</label>
        <input type="text" id="fatherName" name="fatherName" required><br><br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required min="0"><br><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>

</body>
</html>
