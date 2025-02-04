<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="addchild.php" download></a>
    <a href="bedrequest.php" download></a>
    <a href="changepassword.php" download></a>
    <a href="medicalhistory.php" download></a>
    <a href="profile.php" download></a>
    <a href="vaccinerequest.php" download></a>
    <a href="userdashboard.php" download></a>
    <a href="logout.php" download></a>
    <title>User Panel</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        select, input {
            width: 100%;
            padding: 5px;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td>Dashboard</td>
            <td>
                <label for="hospitals">Hospitals:</label>
                <select id="hospitals">
                    <option value="">Select</option>
                    <option value="hospital1">Ziauddin Hospital</option>
                    <option value="hospital2">Indus Hospital</option>
                    <option value="hospital3">Civil Hospital</option>
                    <option value="hospital4">Liaquat National Hospital</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Vaccine</td>
            <td>
                <label for="date">Date:</label>
                <input type="date" id="date">
            </td>
        </tr>
        <tr>
            <td>Add Child</td>
            <td>
                <label for="vaccine">Vaccine:</label>
                <select id="vaccine">
                    <option value="">Select</option>
                    <option value="covid19">COVID-19</option>
                    <option value="fever">Fever</option>
                    <option value="flu">Flu</option>
                    <option value="malaria">Malaria</option>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <label for="child">Child:</label>
                <select id="child">
                    <option value="">Select</option>
                    <option value="child1">Ali</option>
                    <option value="child2">Alia</option>
                    <option value="child3">Ahmad</option>
                    <option value="child4">Sara</option>
                </select>
            </td>
        </tr>
    </table>

</body>
</html>
