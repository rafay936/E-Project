<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Profile page</title>
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
        <!-- Font Awesome for eye icon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        </head>

        <body>

            <!-- Profile Container -->
            <div class="container">
                <h1>Profile</h1>
                <div class="profile-container">
                    <div class="profile-pic">
                        <img src="p-img.jpg" alt="Profile Picture">
                    </div>

                    <div class="profile-info">
                        <div class="info-item">
                            <div class="info-label">Name</div>
                            <div class="info-value">City General Hospital</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">contact@cityhospital.com</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Address</div>
                            <div class="info-value">123 Medical Street, Health City, HC 456</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Username</div>
                            <div class="info-value">hospital_admin</div>
                        </div>

                        <div class="info-item password-field">
                            <div class="info-label">Password</div>
                            <div class="info-value">••••••••</div>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer>
                <p>&copy;Copy |All Rights Reserved VMS</p>
            </footer>
    </main>
    <script>
        //password show and hide code
        const togglePassword = document.querySelector('.toggle-password');
        const passwordField = document.querySelector('.password-field .info-value');

        togglePassword.addEventListener('click', () => {
            const isPassword = passwordField.textContent === '••••••••';
            passwordField.textContent = isPassword ? 'hospital@123' : '••••••••';
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>


    <script src="script.js"></script>
</body>

</html>