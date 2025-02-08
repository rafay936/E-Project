<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital beds page</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .vaccinated {
            background-color: rgba(40, 167, 70, 0.84);
            color: white;
        }

        .pending {
            background-color: rgba(255, 234, 7, 0.8);
            color: black;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: rgba(88, 80, 207, 0.669);
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        @media screen (max-width: 600px) {
            .num {
                display: wrap;
            }

            ;

            table {
                width: 100%;
                padding: 0
            }

            ;
        }
    </style>
</head>

<body>
    <div class="menu-btn">☰</div>

    <!-- Side Navbar -->
    <nav class="sidenav">
        <div class="sidenav-header">
            <h2>🏥 Hospital Dashboard</h2>
            <div class="close-btn">×</div>
        </div>
        <ul class="nav-menu">
            <a href="dashboard.php">
                <li><i class="fas fa-home"></i>Dashboard</li>
            </a>
            <a href="vaccine.php">
                <li><i class="fas fa-syringe"></i>Vaccines</li>
            </a>
            <a href="beds.php">
                <li><i class="fas fa-bed"></i>Beds</li>
            </a>
            <a href="notification.php">
                <li><i class="fas fa-bell"></i>Notifications</li>
            </a>
            <a href="profile.php">
                <li><i class="fas fa-user"></i>Profile</li>
            </a>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main">
        <nav class="breadcrumb">
            <ol>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ol>
        </nav>
        <!-- Cards -->
        <div class="container">
            <h1>Bed Availability</h1>

            <div class="button-container">
                <div class="row-one">

                    <select class="btn" id="vaccineType">
                        <option value="">Select bed Type</option>
                        <option value="covishield">General Bed</option>
                        <option value="covaxin">Icu</option>
                        <option value="sputnik">Private</option>
                    </select>
                    <button class="btn" id="injectBtn">Inject</button>
                </div>

            </div>

            <!-- Modal -->
            <div class="modal-overlay" id="modal">
                <div class="modal-content">
                    <form id="vaccineForm">
                        <div class="form-group">
                            <label>Day</label>
                            <input type="date" required>
                        </div>

                        <div class="form-group">
                            <label>User:</label>
                            <select required>
                                <option value="">Select User</option>
                                <option value="user1">user 1</option>
                                <option value="user2">user 2</option>
                                <option value="user3">user 3</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Vaccine:</label>
                            <select required>
                                <option value="">Select Bad</option>
                                <option value="covishield">General Bed</option>
                                <option value="covaxin">Icu</option>
                            </select>
                        </div>

                        <button type="submit" class="btn" id="submitBtn">Submit</button>
                    </form>
                </div>
            </div>
        </div>



        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Hospital Name</th>
                        <th>Vaccine Type</th>
                        <th>Dose Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#P001</td>
                        <td>Dow University Hospital</td>
                        <td>Covishield</td>
                        <td>2</td>
                        <td data-label="Status"><span class="status vaccinated">Vaccinated</span></td>
                    </tr>
                    <tr>
                        <td>#P002</td>
                        <td>Ziauddin Hospital</td>
                        <td>Covaxin</td>
                        <td>1</tdpe>
                        <td data-label="Status"><span class="status pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td>#P003</td>
                        <td>Bahria Hospital Karachi</td>
                        <td>Sputnik V</td>
                        <td>2</td>
                        <td data-label="Status"><span class="status vaccinated">Vaccinated</span></td>
                    </tr>
                    <tr>
                        <td>#P003</td>
                        <td>Aga Khan University Hospital</td>
                        <td>Sputnik V</td>
                        <td>2</td>
                        <td data-label="Status"><span class="status vaccinated">Vaccinated</span></td>
                    </tr>
                    <tr>
                        <td>#P002</td>
                        <td>Jinnah Hospital Karachi</td>
                        <td>Covaxin</td>
                        <td>1</td>
                        <td data-label="Status"><span class="status pending">Pending</span></td>
                    </tr>
                </tbody>
            </table>

            <!-- Footer -->
            <footer>
                <p>&copy;Copy |All Rights Reserved VMS</p>
            </footer>
    </main>




    <script>
        // beds Page Inject button
        // Modal Handling
        const injectBtn = document.getElementById('injectBtn');
        const modal = document.getElementById('modal');

        injectBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Form Submission
        document.getElementById('vaccineForm').addEventListener('submit', (e) => {
            e.preventDefault();
            modal.style.display = 'none';
            alert('Vaccination details submitted successfully!');
            document.getElementById('vaccineForm').reset();
        });

        // Responsive Handling
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                document.querySelector('.button-container').style.flexDirection = 'row';
            } else {
                document.querySelector('.button-container').style.flexDirection = 'column';
            }
        });
    </script>

    <script src="script.js"></script>
</body>

</html>