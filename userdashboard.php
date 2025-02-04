<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circle Diagram</title>
    <style>
        body {
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .container {
            position: relative;
            width: 300px;
            height: 400px;
        }
        .circle {
            width: 120px;
            height: 120px;
            border: 2px solid black;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: bold;
            background-color: white;
            position: absolute;
        }
        .circle.vaccine {
            top: 50px;
            left: 50px;
        }
        .circle.bed {
            top: 220px;
            left: 50px;
        }
        .arrow {
            position: absolute;
            font-size: 16px;
            font-weight: bold;
        }
        .arrow.vaccine {
            top: 90px;
            left: 190px;
        }
        .arrow.bed {
            top: 260px;
            left: 190px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="circle vaccine">Vaccine<br>Requested</div>
        <div class="arrow vaccine">→ 4%</div>
        
        <div class="circle bed">Bed<br>Requested</div>
        <div class="arrow bed">→ 10%</div>
    </div>
</body>
</html>
