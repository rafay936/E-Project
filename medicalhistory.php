<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="addchild.php" download></a>
    <a href="profile.php" download></a>
    <a href="bedrequest.php" download></a>
    <a href="vaccinerequest.php" download></a>
    <a href="logout.php" download></a>
    <a href="changepassword.php" download></a>
    <a href="userpanel.php" download></a>
    <a href="userdashboard.php" download></a>
    <title>Medical History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h1>Medical History</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Beds</th>
                <th>Vaccine</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Alia</td>
                <td>2</td>
                <td>COVID-19</td>
                <td>29-12-2024</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Ahmad</td>
                <td>3</td>
                <td>Flu</td>
                <td>15-01-2025</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Ali</td>
                <td>1</td>
                <td>Fever</td>
                <td>30-01-2025</td>
            </tr>
            <tr>
            <td>2</td>
                <td>Sara</td>
                <td>5</td>
                <td>Malaria</td>
                <td>4-02-2025</td>
    </tr>
        </tbody>
    </table>

    <button onclick="generateReport()">Generate Report</button>

    <script>
        function generateReport() {
            alert("Report generated successfully!");
        }
    </script>

</body>
</html>
