<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Vaccine page</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
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

        <style>

        </style>

        <body>
            <div class="container">
                <h1>Vaccines</h1>

                <div class="button-container">
                    <div class="row-one">

                        <select class="btn" id="vaccineType">
                            <option value="">Select Vaccine Type</option>
                            <option value="covishield">Covishield</option>
                            <option value="covaxin">Covaxin</option>
                            <option value="sputnik">Sputnik V</option>
                        </select>
                        <button class="btn" id="injectBtn">Inject</button>
                    </div>

                </div>

                <!-- Modal -->
                <div class="modal-overlay" id="modal">
                    <div class="modal-content">
                        <form id="vaccineForm">
                            <div class="form-group">
                            <span class="close-btn" onclick="closeModal()"><i class="fas fa-close"></i></span>
                                <label>Date & Time:</label>
                                <input type="datetime-local" required>
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
                                    <option value="">Select Vaccine</option>
                                    <option value="covishield">Covishield</option>
                                    <option value="covaxin">Covaxin</option>
                                </select>
                            </div>

                            <button type="submit" class="btn" id="submitBtn">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table section -->

            <body>
                <table>
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>PAY</th>
                            <th>USER</th>
                            <th class="num">Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1001</td>
                            <td>15,00</td>
                            <td>User 1</td>
                            <td class="num">7829940</td>

                        </tr>
                        <tr>
                            <td>1002</td>
                            <td>4000</td>
                            <td>user 2</td>
                            <td class="num">785553940</td>
                        </tr>
                        <tr>
                            <td>1003</td>
                            <td>13,00</td>
                            <td>User 3</td>
                            <td class="num">7855540</td>

                        </tr>
                        <tr>
                            <td>1004</td>
                            <td>400</td>
                            <td>user 4</td>
                            <td class="num">7848940</td>
                        </tr>
                    </tbody>
                </table>
            </body>

            <!-- Footer -->
            <footer>
                <p>&copy;Copy |All Rights Reserved VMS</p>
            </footer>
    </main>

    <script>
                // Vaccine Page Inject button
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